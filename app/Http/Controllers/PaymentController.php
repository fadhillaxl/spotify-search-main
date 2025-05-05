<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SongRequest;
use Midtrans\Config;
use Midtrans\Snap;
use App\Events\SongRequestUpdated;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
        
        // Verify configuration
        if (empty(Config::$serverKey)) {
            throw new \RuntimeException('Midtrans server key is not configured');
        }
    }

    public function createPayment(Request $request, $songRequestId)
    {
        try {
            $songRequest = SongRequest::findOrFail($songRequestId);
            
            // Validate amount
            $request->validate([
                'amount' => 'required|numeric|min:100',
                'email' => 'required|email'
            ]);

            $amount = $request->amount;

            // Create transaction details
            $transaction_details = [
                'order_id' => 'SR-' . $songRequest->id . '-' . time(),
                'gross_amount' => $amount
            ];

            // Create customer details
            $customer_details = [
                'first_name' => $songRequest->name,
                'email' => $request->email,
                'phone' => $request->phone ?? '08123456789'
            ];

            // Create item details
            $item_details = [
                [
                    'id' => 'SR-' . $songRequest->id,
                    'price' => $amount,
                    'quantity' => 1,
                    'name' => 'Song Request: ' . $songRequest->song_name
                ]
            ];

            // Create Snap transaction
            $params = [
                'transaction_details' => $transaction_details,
                'customer_details' => $customer_details,
                'item_details' => $item_details,
                'enabled_payments' => ['credit_card', 'bank_transfer', 'gopay', 'shopeepay']
            ];

            // Generate snap token
            $snapToken = Snap::getSnapToken($params);

            if (!$snapToken) {
                throw new \Exception('Failed to generate payment token');
            }

            // Update song request with payment details
            $songRequest->update([
                'amount' => $amount,
                'payment_status' => 'pending',
                'payment_id' => $transaction_details['order_id']
            ]);

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to create payment', [
                'error' => $e->getMessage(),
                'song_request_id' => $songRequestId,
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment: ' . $e->getMessage()
            ], 500);
        }
    }

    public function handleNotification(Request $request)
    {
        try {
            // Log the raw request data
            \Log::info('Raw payment notification request', [
                'request_data' => $request->all(),
                'headers' => $request->headers->all()
            ]);

            $notif = new \Midtrans\Notification();
            
            // Log the parsed notification data
            \Log::info('Parsed payment notification', [
                'transaction_status' => $notif->transaction_status,
                'payment_type' => $notif->payment_type,
                'order_id' => $notif->order_id,
                'fraud_status' => $notif->fraud_status,
                'status_code' => $notif->status_code,
                'signature_key' => $notif->signature_key,
                'gross_amount' => $notif->gross_amount
            ]);

            $transaction = $notif->transaction_status;
            $type = $notif->payment_type;
            $orderId = $notif->order_id;
            $fraud = $notif->fraud_status;

            // Extract song request ID from order ID (format: SR-{id}-{timestamp})
            $songRequestId = explode('-', $orderId)[1];
            \Log::info('Processing payment for song request', [
                'song_request_id' => $songRequestId,
                'order_id' => $orderId
            ]);

            $songRequest = SongRequest::findOrFail($songRequestId);
            
            // Log current status before update
            \Log::info('Current song request status', [
                'song_request_id' => $songRequestId,
                'current_status' => $songRequest->status,
                'current_payment_status' => $songRequest->payment_status
            ]);

            // Update payment status based on transaction status
            switch ($transaction) {
                case 'capture':
                    if ($type == 'credit_card') {
                        if ($fraud == 'challenge') {
                            $updates = [
                                'payment_status' => 'challenge',
                                'status' => 'pending'
                            ];
                        } else {
                            $updates = [
                                'payment_status' => 'success',
                                'status' => 'approved',
                                'paid_at' => now(),
                                'payment_method' => $type
                            ];
                        }
                    }
                    break;
                case 'settlement':
                    $updates = [
                        'payment_status' => 'success',
                        'status' => 'approved',
                        'paid_at' => now(),
                        'payment_method' => $type
                    ];
                    break;
                case 'pending':
                    $updates = [
                        'payment_status' => 'pending',
                        'status' => 'pending'
                    ];
                    break;
                case 'deny':
                    $updates = [
                        'payment_status' => 'failed',
                        'status' => 'rejected'
                    ];
                    break;
                case 'expire':
                    $updates = [
                        'payment_status' => 'expired',
                        'status' => 'rejected'
                    ];
                    break;
                case 'cancel':
                    $updates = [
                        'payment_status' => 'cancelled',
                        'status' => 'rejected'
                    ];
                    break;
                default:
                    $updates = [
                        'payment_status' => $transaction,
                        'status' => 'pending'
                    ];
            }

            // Log the updates that will be applied
            \Log::info('Applying status updates', [
                'song_request_id' => $songRequestId,
                'updates' => $updates
            ]);

            // Apply the updates
            $songRequest->update($updates);

            // Log the final status after update
            \Log::info('Updated song request status', [
                'song_request_id' => $songRequestId,
                'new_status' => $songRequest->fresh()->status,
                'new_payment_status' => $songRequest->fresh()->payment_status,
                'transaction_status' => $transaction
            ]);

            // Broadcast the status update event
            event(new SongRequestUpdated($songRequest));

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \Log::error('Failed to handle payment notification', [
                'error' => $e->getMessage(),
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to handle payment notification: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updatePaymentStatus(Request $request, $songRequestId)
    {
        try {
            $songRequest = SongRequest::findOrFail($songRequestId);
            
            $validated = $request->validate([
                'payment_status' => 'required|string',
                'status' => 'required|string',
                'payment_method' => 'required|string',
                'paid_at' => 'required|date'
            ]);

            $songRequest->update($validated);

            // Broadcast the status update event
            event(new SongRequestUpdated($songRequest));

            return response()->json([
                'success' => true,
                'message' => 'Payment status updated successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to update payment status', [
                'error' => $e->getMessage(),
                'song_request_id' => $songRequestId,
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update payment status: ' . $e->getMessage()
            ], 500);
        }
    }
} 