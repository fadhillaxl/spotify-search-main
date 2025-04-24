@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="display-5 fw-bold">
                    <i class="bi bi-music-note-list me-2"></i>Song Requests Dashboard
                </h1>
                <a href="/search" class="btn btn-outline-primary">
                    <i class="bi bi-search me-1"></i>Back to Search
                </a>
            </div>
            
            <div class="card dashboard-card mb-4">
                <div class="card-body p-4">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Requests</h5>
                                    <p class="card-text display-6" id="totalRequests">{{ count($songRequests) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-dark">
                                <div class="card-body">
                                    <h5 class="card-title">Pending</h5>
                                    <p class="card-text display-6" id="pendingRequests">{{ $songRequests->where('status', 'pending')->count() }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Paid</h5>
                                    <p class="card-text display-6" id="approvedRequests">{{ $songRequests->where('status', 'approved')->count() }}</p>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="h4 mb-0">All Song Requests</h2>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-secondary active filter-btn" data-filter="all">All</button>
                            <button type="button" class="btn btn-outline-warning filter-btn" data-filter="pending">Pending</button>
                            <button type="button" class="btn btn-outline-success filter-btn" data-filter="approved">Paid</button>
                            <button type="button" class="btn btn-outline-danger filter-btn" data-filter="rejected">Rejected</button>
                        </div>
                    </div>
                    
                    <div id="loadingIndicator" class="text-center py-4 d-none">
                        <div class="spinner-border loading-spinner" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 text-muted">Loading...</p>
                    </div>
                    
                    <div id="requestsList" class="d-flex flex-column gap-3">
                        @if(count($songRequests) > 0)
                            @foreach($songRequests as $request)
                                <div class="card request-card" data-status="{{ $request->status }}" data-id="{{ $request->id }}">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h5 class="card-title mb-1">{{ $request->song_name }}</h5>
                                                <p class="card-text text-muted mb-1">
                                                    <i class="bi bi-person-fill me-1"></i>
                                                    {{ $request->artist ?: 'Unknown Artist' }}
                                                </p>
                                                <p class="card-text text-muted small mb-0">
                                                    <i class="bi bi-person me-1"></i>
                                                    Requested by: {{ $request->name }}
                                                </p>
                                                <p class="card-text text-muted small mb-0">
                                                    <i class="bi bi-clock me-1"></i>
                                                    {{ $request->created_at->format('M d, Y h:i A') }}
                                                </p>
                                                <p class="card-text text-muted small mb-0 amount-display">
                                                    <i class="bi bi-currency-dollar me-1"></i>
                                                    Amount: Rp {{ number_format($request->amount, 0, ',', '.') }}
                                                </p>
                                            </div>
                                            <div class="d-flex flex-column align-items-end">
                                                <div class="d-flex gap-2 mb-2">
                                                    <span class="badge status-badge status-{{ $request->status }}">
                                                        {{ $request->status === 'approved' ? 'Unpaid' : ucfirst($request->status) }}
                                                    </span>
                                                    <span class="badge payment-badge payment-{{ $request->payment_status }}">
                                                        {{ ucfirst($request->payment_status ?? 'unpaid') }}
                                                    </span>
                                                </div>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-outline-success update-status-btn" 
                                                        data-id="{{ $request->id }}" 
                                                        data-status="approved"
                                                        {{ $request->status === 'approved' ? 'disabled' : '' }}>
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger update-status-btn" 
                                                        data-id="{{ $request->id }}" 
                                                        data-status="rejected"
                                                        {{ $request->status === 'rejected' ? 'disabled' : '' }}>
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="alert alert-info text-center" role="alert">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                No song requests found.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Toast -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="statusUpdateToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-check-circle-fill me-2"></i>
                <span id="toastMessage">Status updated successfully!</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .dashboard-card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: none;
    }
    .status-badge {
        font-size: 0.8rem;
        padding: 0.35em 0.65em;
    }
    .status-pending {
        background-color: #ffc107;
        color: #212529;
    }
    .status-approved {
        background-color: #198754;
        color: #fff;
    }
    .status-rejected {
        background-color: #dc3545;
        color: #fff;
    }
    .request-card {
        transition: all 0.3s ease;
    }
    .request-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    .loading-spinner {
        width: 3rem;
        height: 3rem;
        color: #1DB954;
    }
    .request-card.updating {
        opacity: 0.7;
        pointer-events: none;
    }
    .payment-badge {
        font-size: 0.8rem;
        padding: 0.35em 0.65em;
    }
    .payment-success {
        background-color: #198754;
        color: #fff;
    }
    .payment-pending {
        background-color: #ffc107;
        color: #212529;
    }
    .payment-failed {
        background-color: #dc3545;
        color: #fff;
    }
    .payment-expired {
        background-color: #6c757d;
        color: #fff;
    }
    .payment-cancelled {
        background-color: #6c757d;
        color: #fff;
    }
    .payment-unpaid {
        background-color: #6c757d;
        color: #fff;
    }
</style>
@endpush

@push('scripts')
<!-- Pusher JS -->
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loadingIndicator = document.getElementById('loadingIndicator');
        const requestsList = document.getElementById('requestsList');
        const filterButtons = document.querySelectorAll('.filter-btn');
        const updateStatusButtons = document.querySelectorAll('.update-status-btn');
        
        // Initialize Pusher with debug logging
        const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
            cluster: 'ap1',
            encrypted: true,
            enabledTransports: ['ws', 'wss']
        });

        // Enable Pusher debug logging
        Pusher.logToConsole = true;

        // Subscribe to the song-requests channel
        const channel = pusher.subscribe('song-requests');
        
        // Log when subscription is successful
        channel.bind('pusher:subscription_succeeded', function() {
            console.log('Successfully subscribed to song-requests channel');
        });

        // Listen for request.updated events
        channel.bind('request.updated', function(data) {
            console.log('Received update:', data);
            updateRequestCard(data);
            updateStatistics();
        });

        // Listen for request.created events
        channel.bind('request.created', function(data) {
            console.log('Received new request:', data);
            addNewRequestCard(data);
            updateStatistics();
        });

        // Listen for payment.success events
        channel.bind('payment.success', function(data) {
            console.log('Payment successful:', data);
            // Update the request card with new status
            const updates = {
                ...data,
                status: 'approved',
                payment_status: 'success',
                payment_method: data.payment_method,
                paid_at: data.paid_at
            };
            updateRequestCard(updates);
            updateStatistics();
        });
        
        // Function to add new request card
        function addNewRequestCard(data) {
            // Remove the "No song requests found" message if it exists
            const noRequestsMessage = requestsList.querySelector('.alert-info');
            if (noRequestsMessage) {
                noRequestsMessage.remove();
            }

            const cardHTML = `
                <div class="card request-card" data-status="${data.status}" data-id="${data.id}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title mb-1">${data.song_name}</h5>
                                <p class="card-text text-muted mb-1">
                                    <i class="bi bi-person-fill me-1"></i>
                                    ${data.artist || 'Unknown Artist'}
                                </p>
                                <p class="card-text text-muted small mb-0">
                                    <i class="bi bi-person me-1"></i>
                                    Requested by: ${data.name}
                                </p>
                                <p class="card-text text-muted small mb-0">
                                    <i class="bi bi-clock me-1"></i>
                                    ${data.created_at}
                                </p>
                                <p class="card-text text-muted small mb-0 amount-display">
                                    <i class="bi bi-currency-dollar me-1"></i>
                                    Amount: Rp ${data.amount ? new Intl.NumberFormat('id-ID').format(data.amount) : '0'}
                                </p>
                            </div>
                            <div class="d-flex flex-column align-items-end">
                                <div class="d-flex gap-2 mb-2">
                                    <span class="badge status-badge status-${data.status}">
                                        ${data.status === 'approved' ? 'Unpaid' : data.status.charAt(0).toUpperCase() + data.status.slice(1)}
                                    </span>
                                    <span class="badge payment-badge payment-${data.payment_status || 'unpaid'}">
                                        ${data.payment_status ? data.payment_status.charAt(0).toUpperCase() + data.payment_status.slice(1) : 'Unpaid'}
                                    </span>
                                </div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-success update-status-btn" 
                                        data-id="${data.id}" 
                                        data-status="approved"
                                        ${data.status === 'approved' ? 'disabled' : ''}>
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger update-status-btn" 
                                        data-id="${data.id}" 
                                        data-status="rejected"
                                        ${data.status === 'rejected' ? 'disabled' : ''}>
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Add the new card at the top of the list
            requestsList.insertAdjacentHTML('afterbegin', cardHTML);

            // Add event listeners to the new buttons
            const newCard = requestsList.firstElementChild;
            const newButtons = newCard.querySelectorAll('.update-status-btn');
            newButtons.forEach(button => {
                button.addEventListener('click', handleStatusUpdate);
            });

            // Show success toast
            const toast = new bootstrap.Toast(document.getElementById('statusUpdateToast'));
            document.getElementById('toastMessage').textContent = 'New song request received!';
            toast.show();
        }
        
        // Function to update request card
        function updateRequestCard(data) {
            const card = document.querySelector(`.request-card[data-id="${data.id}"]`);
            if (!card) {
                console.log('Card not found:', data.id);
                return;
            }
            
            // Update status badge
            const statusBadge = card.querySelector('.status-badge');
            statusBadge.className = `badge status-badge status-${data.status}`;
            statusBadge.textContent = data.status === 'approved' ? 'Unpaid' : data.status.charAt(0).toUpperCase() + data.status.slice(1);
            
            // Update payment badge
            const paymentBadge = card.querySelector('.payment-badge');
            if (data.payment_status) {
                paymentBadge.className = `badge payment-badge payment-${data.payment_status}`;
                paymentBadge.textContent = data.payment_status.charAt(0).toUpperCase() + data.payment_status.slice(1);
            } else {
                paymentBadge.className = 'badge payment-badge payment-unpaid';
                paymentBadge.textContent = 'Unpaid';
            }

            // Update amount display
            const amountElement = card.querySelector('.amount-display');
            if (amountElement && data.amount) {
                amountElement.textContent = `Amount: Rp ${new Intl.NumberFormat('id-ID').format(data.amount)}`;
            }
            
            // Update data attribute
            card.setAttribute('data-status', data.status);
            
            // Update buttons
            const buttons = card.querySelectorAll('.update-status-btn');
            buttons.forEach(btn => {
                if (btn.getAttribute('data-status') === data.status) {
                    btn.disabled = true;
                } else {
                    btn.disabled = false;
                }
            });
            
            // Show success toast
            const toast = new bootstrap.Toast(document.getElementById('statusUpdateToast'));
            document.getElementById('toastMessage').textContent = `Request ${data.status} successfully!`;
            toast.show();
        }
        
        // Function to update statistics
        function updateStatistics() {
            const cards = document.querySelectorAll('.request-card');
            const total = cards.length;
            const pending = Array.from(cards).filter(card => card.getAttribute('data-status') === 'pending').length;
            const approved = Array.from(cards).filter(card => card.getAttribute('data-status') === 'approved').length;
            
            document.getElementById('totalRequests').textContent = total;
            document.getElementById('pendingRequests').textContent = pending;
            document.getElementById('approvedRequests').textContent = approved;
        }

        // Function to handle status updates
        function handleStatusUpdate() {
            const requestId = this.getAttribute('data-id');
            const newStatus = this.getAttribute('data-status');
            const card = this.closest('.request-card');
            
            // Show loading state
            card.classList.add('updating');
            loadingIndicator.classList.remove('d-none');
            
            // Send request to update status
            fetch(`/api/song-request/${requestId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(response => response.json())
            .then(result => {
                loadingIndicator.classList.add('d-none');
                card.classList.remove('updating');
                
                if (result.success) {
                    // Update the card immediately for better UX
                    updateRequestCard(result.data);
                    updateStatistics();
                }
            })
            .catch(error => {
                console.error('Error updating status:', error);
                loadingIndicator.classList.add('d-none');
                card.classList.remove('updating');
                
                // Show error message
                alert('An error occurred while updating the status. Please try again.');
            });
        }
        
        // Filter requests by status
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Update active button
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                const filter = this.getAttribute('data-filter');
                const requestCards = document.querySelectorAll('.request-card');
                
                requestCards.forEach(card => {
                    if (filter === 'all' || card.getAttribute('data-status') === filter) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
        
        // Add event listeners to all update status buttons
        updateStatusButtons.forEach(button => {
            button.addEventListener('click', handleStatusUpdate);
        });
    });
</script>
@endpush 