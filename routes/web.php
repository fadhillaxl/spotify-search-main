<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpotifyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SpotifyApiCredentialController;
use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\PaymentController;


Route::get('/request/{bandname}', [SpotifyController::class, 'searchRequest'])->name('searchRequest');
Route::get('/requestcommerce/{bandname}', [SpotifyController::class, 'searchRequest'])->name('searchRequestCommerce');
Route::get('/api/request/playlist/{playlistId}/tracks', [SpotifyController::class, 'playlistTracks'])->name('playlist.tracks');
Route::get('/api/request/playlist/{playlistId}/search', [SpotifyController::class, 'searchPlaylist'])->name('playlist.search');
Route::get('/api/request/active-playlists', [SpotifyController::class, 'getActivePlaylistIds']);
Route::post('/api/song-request', [SpotifyController::class, 'storeSongRequest'])->name('song.request.store');
Route::patch('/api/song-request/{id}/status', [DashboardController::class, 'updateStatus'])->name('song.request.update.status');

Route::middleware(['web'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    // Documentation route
    Route::get('/documentation', [DocumentationController::class, 'index'])->name('documentation');

    // Display the search page
    Route::get('/search', [SpotifyController::class, 'index'])->name('search.index');

    // API endpoint for searching
    Route::get('/api/search', [SpotifyController::class, 'search'])->name('search.api');

    // API endpoint for song requests
    Route::post('/api/song-request', [SpotifyController::class, 'storeSongRequest'])->name('song.request.store');

    // Payment routes
    Route::post('/api/payment/{songRequest}/create', [PaymentController::class, 'createPayment'])->name('payment.create');
    Route::post('/api/payment/notification', [PaymentController::class, 'handleNotification'])->name('payment.notification');
    Route::post('/api/payment/{songRequest}/update-status', [PaymentController::class, 'updatePaymentStatus'])->name('payment.update.status');

    // Dashboard routes
    Route::patch('/api/song-request/{id}/status', [DashboardController::class, 'updateStatus'])->name('song.request.update.status');

    // Playlist routes with authentication
    Route::middleware(['auth'])->group(function () {
        Route::get('/documentation', [DocumentationController::class, 'index'])->name('documentation');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/overlay', [DashboardController::class, 'overlay'])->name('overlay');

        Route::get('/playlists', [PlaylistController::class, 'index'])->name('playlists.index');
        Route::get('/playlists/create', [PlaylistController::class, 'create'])->name('playlists.create');
        Route::post('/playlists', [PlaylistController::class, 'store'])->name('playlists.store');
        Route::get('/playlists/{playlist}/edit', [PlaylistController::class, 'edit'])->name('playlists.edit');
        Route::patch('/playlists/{playlist}', [PlaylistController::class, 'update'])->name('playlists.update');
        Route::delete('/playlists/{playlist}', [PlaylistController::class, 'destroy'])->name('playlists.destroy');
        
        // Spotify API Credentials routes
        Route::resource('spotify/credentials', SpotifyApiCredentialController::class)->names([
            'index' => 'spotify.credentials.index',
            'create' => 'spotify.credentials.create',
            'store' => 'spotify.credentials.store',
            'show' => 'spotify.credentials.show',
            'edit' => 'spotify.credentials.edit',
            'update' => 'spotify.credentials.update',
            'destroy' => 'spotify.credentials.destroy',
        ]);
    });

    require __DIR__.'/auth.php';
});
