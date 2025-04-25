<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Spotify Search</title>

        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
        <!-- Custom CSS -->
        <style>
            body {
                background-color: #121212;
                color: #ffffff;
            }
            .search-card {
                background-color: #181818;
                box-shadow: none;
                border: none;
            }
            .search-button {
                background-color: #1DB954;
                border-color: #1DB954;
            }
            .search-button:hover {
                background-color: #1aa34a;
                border-color: #1aa34a;
            }
            .track-card {
                background-color: transparent;
                border: none;
                border-radius: 4px;
                transition: background-color 0.3s ease;
            }
            .track-card:hover {
                background-color: #282828;
                transform: none;
            }
            .loading-spinner {
                width: 3rem;
                height: 3rem;
                color: #1DB954;
            }
            .show-more-button {
                background-color: transparent;
                border: 2px solid #1DB954;
                color: #1DB954;
                transition: all 0.3s ease;
                margin-top: 1rem;
                padding: 0.5rem 1.5rem;
                display: inline-block !important;
            }
            .show-more-button:hover {
                background-color: #1DB954;
                color: white;
            }
            .track-card.hidden-track {
                display: none;
            }
            .playlist-header {
                background: linear-gradient(to bottom, #1DB954, #121212);
                padding: 2rem 0;
                margin-bottom: 2rem;
            }
            .playlist-info {
                display: flex;
                align-items: flex-end;
                gap: 1.5rem;
                padding: 0 2rem;
            }
            .playlist-image {
                width: 200px;
                height: 200px;
                box-shadow: 0 4px 60px rgba(0,0,0,.5);
            }
            .playlist-details {
                flex: 1;
            }
            .playlist-type {
                text-transform: uppercase;
                font-size: 0.75rem;
                margin-bottom: 0.5rem;
            }
            .playlist-title {
                font-size: 3rem;
                font-weight: bold;
                margin-bottom: 0.5rem;
                line-height: 1;
            }
            .playlist-description {
                color: #b3b3b3;
                margin-bottom: 1rem;
            }
            .playlist-stats {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                color: #b3b3b3;
                font-size: 0.875rem;
            }
            .track-list {
                padding: 0 1rem;
            }
            .track-row {
                display: grid;
                grid-template-columns: 40px 1fr 60px 40px;
                gap: 0.75rem;
                padding: 0.5rem;
                border-radius: 4px;
                transition: background-color 0.3s ease;
                align-items: center;
            }
            @media (min-width: 768px) {
                .track-row {
                    grid-template-columns: 50px 4fr 3fr 60px 50px;
                    gap: 1rem;
                }
            }
            .track-number {
                color: #b3b3b3;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.875rem;
            }
            .track-title {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                min-width: 0;
                padding-right: 0.5rem;
            }
            .track-title-content {
                min-width: 0;
                flex: 1;
            }
            .track-name {
                font-weight: 500;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                margin-bottom: 0.25rem;
                font-size: 0.875rem;
            }
            .track-artist {
                color: #b3b3b3;
                font-size: 0.75rem;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .track-album {
                color: #b3b3b3;
                display: none;
                align-items: center;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                font-size: 0.875rem;
                padding-right: 1rem;
            }
            @media (min-width: 768px) {
                .track-album {
                    display: flex;
                }
            }
            .track-duration {
                color: #b3b3b3;
                display: flex;
                align-items: center;
                justify-content: flex-end;
                font-size: 0.75rem;
                font-variant-numeric: tabular-nums;
                width: 60px;
                padding-right: 0.5rem;
            }
            .track-row-header .track-duration {
                padding-right: 0.5rem;
            }
            .track-image {
                width: 40px;
                height: 40px;
                flex-shrink: 0;
            }
            .track-row-header {
                color: #b3b3b3;
                border-bottom: 1px solid #282828;
                padding-bottom: 0.75rem;
                margin-bottom: 0.5rem;
                font-size: 0.75rem;
                text-transform: uppercase;
                letter-spacing: 0.1em;
            }
            @media (min-width: 768px) {
                .track-row-header {
                    font-size: 0.875rem;
                }
            }
            .track-actions {
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .search-container {
                background-color: #181818;
                padding: 1rem 2rem;
                margin-bottom: 2rem;
            }
            .search-input {
                background-color: #2a2a2a;
                border: none;
                color: white;
                padding: 0.75rem 1rem;
                border-radius: 4px;
            }
            .search-input:focus {
                background-color: #2a2a2a;
                color: white;
                box-shadow: none;
            }
            .modal-content {
                background-color: #181818;
                border: none;
                border-radius: 8px;
            }
            .modal-header {
                border-bottom: 1px solid #282828;
                padding: 1.5rem;
            }
            .modal-title {
                color: #ffffff;
                font-weight: 600;
            }
            .btn-close {
                filter: invert(1) grayscale(100%) brightness(200%);
            }
            .modal-body {
                padding: 1.5rem;
            }
            .modal-footer {
                border-top: 1px solid #282828;
                padding: 1.5rem;
            }
            .form-label {
                color: #b3b3b3;
                font-size: 0.875rem;
                font-weight: 500;
                margin-bottom: 0.5rem;
            }
            .form-control {
                background-color: #2a2a2a;
                border: 1px solid #404040;
                color: #ffffff;
                padding: 0.75rem 1rem;
                border-radius: 4px;
            }
            .form-control:focus {
                background-color: #2a2a2a;
                border-color: #1DB954;
                color: #ffffff;
                box-shadow: 0 0 0 0.25rem rgba(29, 185, 84, 0.25);
            }
            .form-control::placeholder {
                color: #6c757d;
            }
            .btn-secondary {
                background-color: transparent;
                border: 1px solid #404040;
                color: #ffffff;
            }
            .btn-secondary:hover {
                background-color: #2a2a2a;
                border-color: #404040;
                color: #ffffff;
            }
            .btn-primary {
                background-color: #1DB954;
                border-color: #1DB954;
                color: #ffffff;
                font-weight: 500;
            }
            .btn-primary:hover {
                background-color: #1aa34a;
                border-color: #1aa34a;
                color: #ffffff;
            }
            .btn-primary:disabled {
                background-color: #1DB954;
                border-color: #1DB954;
                opacity: 0.65;
            }
        </style>
        
        <!-- Midtrans Snap.js SDK -->
        <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    </head>
    <body>
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="text-center mb-4">
                        <h1 class="display-5 fw-bold">
                        <a href="https://saweria.co/youthband" target="_blank">
                        Donasi Sekarang
                        </a>

                            <i class="bi bi-spotify me-2"></i>Spotify Search
                        </h1>
                    </div>
                    
                    @php
                        $activeCredentials = app(App\Services\SpotifyService::class)->getActiveCredentials();
                    @endphp
                    
                    @if($activeCredentials)
                        <div class="playlist-header">
                            <div class="playlist-info">
                                <img src="https://cdn0-production-images-kly.akamaized.net/AyCrq0kgaeCj5amdLGAsanQgFOU=/1200x675/smart/filters:quality(75):strip_icc():format(webp)/kly-media-production/medias/929622/original/f08ccf109ec89dfa0c68f02b0a8e5fce15.jpeg" alt="Playlist" class="playlist-image">
                                <div class="playlist-details">
                                    <div class="playlist-type">Playlist</div>
                                    <h1 class="playlist-title">Request Songs</h1>
                                    <p class="playlist-description">Request your favorite songs to be added to the playlist</p>
                                    <div class="playlist-stats">
                                        <span>0 songs</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="search-container">
                            <form id="searchForm" class="mb-4">
                                <div class="input-group">
                                    <input 
                                        type="text" 
                                        id="searchInput" 
                                        name="query" 
                                        class="form-control search-input"
                                        placeholder="Search for songs, artists, albums..." 
                                        required
                                    >
                                    <button 
                                        type="submit" 
                                        class="btn btn-lg search-button text-white"
                                    >
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </form>
                            
                            <div class="text-center mb-3">
                                <button id="showAllSongsButton" class="btn btn-outline-success">
                                    <i class="bi bi-music-note-list me-1"></i>Show All Songs
                                </button>
                                <button id="requestSongButton" class="btn btn-outline-primary ms-2" data-bs-toggle="modal" data-bs-target="#songRequestModal">
                                    <i class="bi bi-plus-circle me-1"></i>Request a Song
                                </button>
                            </div>
                        </div>

                        <div class="track-list">
                            <div class="track-row track-row-header">
                                <div class="track-number">#</div>
                                <div class="track-title">Title</div>
                                <div class="track-album">Album</div>
                                <div class="track-duration">Duration</div>
                                <div class="track-actions"></div>
                            </div>
                            
                            <div id="searchResults" class="d-flex flex-column">
                                <!-- Results will be displayed here -->
                            </div>
                            
                            <div id="loadingIndicator" class="text-center py-4 d-none">
                                <div class="spinner-border loading-spinner" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-3 text-muted">Searching...</p>
                            </div>
                            
                            <div id="noResults" class="text-center py-4 text-muted d-none">
                                <i class="bi bi-emoji-frown fs-1"></i>
                                <p class="mt-2">No results found. Try a different search term.</p>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning mb-4">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <div>
                                    <strong>No active credentials found.</strong>
                                    Please contact the administrator to set up the application credentials.
                                </div>
                            </div>
                        </div>
                        
                        <div class="card search-card">
                            <div class="card-body p-4 text-center">
                                <i class="bi bi-lock-fill text-muted" style="font-size: 3rem;"></i>
                                <h4 class="mt-3">Search Feature Unavailable</h4>
                                <p class="text-muted">The search feature is currently unavailable. Please try again later.</p>
                            </div>
                        </div>
                    @endif
                </div>
                

            </div>
        </div>
        
        <!-- Song Request Modal -->
        <div class="modal fade" id="songRequestModal" tabindex="-1" aria-labelledby="songRequestModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="songRequestModalLabel">Request a Song</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="songRequestForm">
                            <div class="mb-4">
                                <label for="requestName" class="form-label">Your Name</label>
                                <input type="text" class="form-control" id="requestName" name="name" required>
                            </div>
                            <div class="mb-4">
                                <label for="songName" class="form-label">Song Name</label>
                                <input type="text" class="form-control" id="songName" name="song_name" required>
                            </div>
                            <div class="mb-4">
                                <label for="artistName" class="form-label">Artist (Optional)</label>
                                <input type="text" class="form-control" id="artistName" name="artist">
                            </div>
                            <div class="mb-4">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-4">
                                <label for="notes" class="form-label">Pesan</label>
                                <input type="text" class="form-control" id="notes" name="notes" required>
                            </div>
                            <div class="mb-4">
                                <label for="amount" class="form-label">Payment Amount (Minimum Rp 10,000)</label>
                                <input type="text" class="form-control" id="amount" name="amount" required placeholder="Rp 10.000,00" oninput="this.value = formatCurrency(this.value)">
                                <script>
                                    function formatCurrency(value) {
                                        const numberString = value.replace(/[^,\d]/g, '').toString();
                                        const split = numberString.split(',');
                                        let rupiah = split[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                                        if (split[1] !== undefined) {
                                            rupiah += ',' + split[1];
                                        }
                                        return 'Rp ' + rupiah;
                                    }
                                </script>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="submitSongRequest">Submit Request</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Request Success Toast -->
        <div class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 1060">
            <div id="requestSuccessToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body fw-bold">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        Song request submitted successfully!
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>

        <!-- Payment Success Toast -->
        <div class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 1060">
            <div id="paymentSuccessToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body fw-bold">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        Payment successful! Your song request will be processed.
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>

        <!-- Payment Pending Toast -->
        <div class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 1060">
            <div id="paymentPendingToast" class="toast align-items-center text-white bg-warning border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body fw-bold">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>
                        Payment is pending. Please complete the payment to process your song request.
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>

        <!-- Payment Error Toast -->
        <div class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 1060">
            <div id="paymentErrorToast" class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body fw-bold">
                        <i class="bi bi-x-circle-fill me-2"></i>
                        Payment failed. Please try again.
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>

        <!-- Payment Cancelled Toast -->
        <div class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 1060">
            <div id="paymentCancelledToast" class="toast align-items-center text-white bg-info border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body fw-bold">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        Payment was cancelled. Please try again if you want to complete your song request.
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>

        <!-- Error Toast -->
        <div class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 1060">
            <div id="errorToast" class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body fw-bold">
                        <i class="bi bi-x-circle-fill me-2"></i>
                        <span id="errorMessage"></span>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>

        <!-- Bootstrap 5 JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Check if Snap.js is loaded
                if (typeof window.snap === 'undefined') {
                    console.error('Snap.js is not loaded');
                    // Try to reload Snap.js
                    const script = document.createElement('script');
                    script.src = 'https://app.sandbox.midtrans.com/snap/snap.js';
                    script.setAttribute('data-client-key', '{{ config('services.midtrans.client_key') }}');
                    script.onload = function() {
                        if (typeof window.snap !== 'undefined') {
                            console.log('Snap.js loaded successfully');
                        } else {
                            console.error('Failed to load Snap.js after retry');
                        }
                    };
                    script.onerror = function() {
                        console.error('Failed to load Snap.js script');
                    };
                    document.body.appendChild(script);
                } else {
                    console.log('Snap.js already loaded');
                }

                // Initialize Bootstrap modals
                const songRequestModal = new bootstrap.Modal(document.getElementById('songRequestModal'), {
                    backdrop: 'static',
                    keyboard: false
                });
                
                const searchForm = document.getElementById('searchForm');
                const searchInput = document.getElementById('searchInput');
                const searchResults = document.getElementById('searchResults');
                const loadingIndicator = document.getElementById('loadingIndicator');
                const noResults = document.getElementById('noResults');
                const showMoreContainer = document.getElementById('showMoreContainer');
                const showMoreButton = document.getElementById('showMoreButton');
                const requestSongButton = document.getElementById('requestSongButton');
                const submitSongRequest = document.getElementById('submitSongRequest');
                const activePlaylistsContainer = document.getElementById('activePlaylists');
                
                // Only initialize event listeners if the elements exist (user has active credentials)
                if (searchForm && searchInput && searchResults) {
                    const itemsToShow = 10; // Number of items to show initially
                    let currentlyShown = itemsToShow;
                    let allTracks = [];
                    
                    // Store the active playlist ID
                    let activeplaylistId = @json($activeplaylistId);
                    
                    // Function to display tracks
                    function displayTracks(tracks, showAll = false) {
                        if (!tracks || tracks.length === 0) {
                            if (noResults) {
                                noResults.classList.remove('d-none');
                            }
                            if (showMoreContainer) {
                                showMoreContainer.style.display = 'none';
                            }
                            return;
                        }
                        
                        allTracks = tracks;
                        currentlyShown = itemsToShow;
                        
                        // Clear existing results
                        if (searchResults) {
                            searchResults.innerHTML = '';
                        }
                        
                        // Update playlist stats
                        const playlistStats = document.querySelector('.playlist-stats');
                        if (playlistStats) {
                            playlistStats.innerHTML = `<span>${tracks.length} songs</span>`;
                        }
                        
                        // Generate all results HTML
                        const resultsHTML = tracks.map((track, index) => `
                            <div class="track-row" data-index="${index}">
                                <div class="track-number">${index + 1}</div>
                                <div class="track-title">
                                    <img 
                                        src="${track.album?.images?.[0]?.url || 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIGZpbGw9IiMyODI4MjgiLz48cGF0aCBkPSJNMjAgMTBDMTUuNTg1OCAxMCAxMiAxMy41ODU4IDEyIDE4QzEyIDIyLjQxNDIgMTUuNTg1OCAyNiAyMCAyNkMyNC40MTQyIDI2IDI4IDIyLjQxNDIgMjggMThDMjggMTMuNTg1OCAyNC40MTQyIDEwIDIwIDEwWiIgZmlsbD0iIzRBNEE0QSIvPjwvc3ZnPg=='}" 
                                        alt="${track.album?.name || 'Album'}" 
                                        class="track-image"
                                    >
                                    <div class="track-title-content">
                                        <div class="track-name">${track.name || 'Unknown Track'}</div>
                                        <div class="track-artist">
                                            ${track.artists ? track.artists.map(artist => artist.name).join(', ') : 'Unknown Artist'}
                                        </div>
                                    </div>
                                </div>
                                <div class="track-album">${track.album?.name || 'Unknown Album'}</div>
                                <div class="track-duration">${formatDuration(track.duration_ms)}</div>
                                <div class="track-actions">
                                    <button class="btn btn-sm btn-outline-primary request-song-btn" 
                                        data-song-name="${track.name || 'Unknown Track'}"
                                        data-artist="${track.artists ? track.artists.map(artist => artist.name).join(', ') : 'Unknown Artist'}"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#songRequestModal">
                                        <i class="bi bi-plus-circle"></i>
                                    </button>
                                </div>
                            </div>
                        `).join('');
                        
                        // Add the results
                        if (searchResults) {
                            searchResults.innerHTML = resultsHTML;
                        }
                        
                        // Show/hide "Show More" button based on remaining tracks
                        if (showMoreContainer && showMoreButton) {
                            const remainingTracks = tracks.length - currentlyShown;
                            if (remainingTracks > 0) {
                                showMoreContainer.style.display = 'block';
                                showMoreButton.textContent = `Show More (${remainingTracks} more)`;
                            } else {
                                showMoreContainer.style.display = 'none';
                            }
                        }
                    }
                    
                    // Function to show more tracks
                    function showMoreTracks() {
                        const hiddenTracks = document.querySelectorAll('.track-card.hidden-track');
                        const tracksToShow = Math.min(hiddenTracks.length, itemsToShow);
                        
                        console.log('Hidden tracks:', hiddenTracks.length);
                        console.log('Tracks to show:', tracksToShow);
                        
                        for (let i = 0; i < tracksToShow; i++) {
                            hiddenTracks[i].classList.remove('hidden-track');
                        }
                        
                        currentlyShown += tracksToShow;
                        
                        // Update "Show More" button text and visibility
                        const remainingTracks = allTracks.length - currentlyShown;
                        if (remainingTracks > 0) {
                            showMoreButton.textContent = `Show More (${remainingTracks} more)`;
                        } else {
                            showMoreContainer.style.display = 'none';
                        }
                    }
                    
                    // Load playlist tracks when page loads
                    function loadPlaylistTracks(playlistids, showAll = false) {
                        loadingIndicator.classList.remove('d-none');
                        noResults.classList.add('d-none');
                        
                        // Check if playlistids is provided and has at least one element
                        if (!playlistids || !playlistids.length) {
                            // If no playlist IDs provided, use the active playlist ID or default
                            const playlistId = activeplaylistId;
                            fetchPlaylistTracks(playlistId, showAll);
                            return;
                        }
                        
                        var playlist_ids = playlistids[0];
                        console.log(playlist_ids);
                        
                        // Store the active playlist ID for later use
                        activeplaylistId = playlist_ids;
                        
                        // Fetch tracks from the specific playlist
                        fetchPlaylistTracks(playlist_ids, showAll);
                    }
                    
                    // Helper function to fetch tracks from a playlist
                    function fetchPlaylistTracks(playlistId, showAll = false) {
                        fetch(`/api/request/playlist/${playlistId}/tracks`)
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Playlist endpoint not available');
                                }
                                return response.json();
                            })
                            .then(data => {
                                loadingIndicator.classList.add('d-none');
                                console.log(data)
                                // Handle Spotify API response structure
                                let tracks = [];
                                
                                if (data && data.items && Array.isArray(data.items)) {
                                    // Extract track objects from the Spotify API response
                                    tracks = data.items.map(item => item.track).filter(track => track);
                                }
                                
                                displayTracks(tracks, showAll);
                            })
                            .catch(error => {
                                console.error('Error fetching playlist tracks:', error);
                                loadingIndicator.classList.add('d-none');
                                searchResults.innerHTML = `
                                    <div class="alert alert-danger text-center" role="alert">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                        An error occurred while loading tracks. Please try again.
                                    </div>
                                `;
                            });
                    }
                    
                    // Function to fetch active playlists
                    function fetchActivePlaylists() {
                        if (activePlaylistsContainer) {
                            displayActivePlaylists([activeplaylistId]);
                        }
                        loadPlaylistTracks([activeplaylistId]);
                    }
                    
                    // Call loadPlaylistTracks when page loads
                    // fetchActivePlaylists();
                    fetchActivePlaylists();
                    
                    // Add event listener for the "Show All Songs" button
                    const showAllSongsButton = document.getElementById('showAllSongsButton');
                    if (showAllSongsButton) {
                        showAllSongsButton.addEventListener('click', function() {
                            // Clear any search results
                            searchResults.innerHTML = '';
                            loadingIndicator.classList.remove('d-none');
                            noResults.classList.add('d-none');
                            
                            // Load all songs from the playlist with showAll=true
                            loadPlaylistTracks(null, true);
                        });
                    }
                    
                    // Show modal when request song button is clicked
                    if (requestSongButton) {
                        requestSongButton.addEventListener('click', function() {
                            songRequestModal.show();
                        });
                    }
                    
                    // Handle song request form submission
                    if (submitSongRequest) {
                        submitSongRequest.addEventListener('click', function() {
                            const form = document.getElementById('songRequestForm');
                            const formData = new FormData(form);
                            const data = {};
                            
                            // Convert FormData to JSON object
                            for (const [key, value] of formData.entries()) {
                                if (key === 'amount') {
                                    // Remove 'Rp ' prefix and convert comma to dot
                                    const numericValue = value.replace('Rp ', '').replace(/\./g, '').replace(',', '.');
                                    data[key] = parseFloat(numericValue);
                                } else {
                                    data[key] = value;
                                }
                            }
                            
                            // Show loading state
                            this.disabled = true;
                            this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...';
                            
                            // Submit the request
                            fetch('/api/song-request', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify(data)
                            })
                            .then(response => {
                                if (!response.ok) {
                                    return response.json().then(err => Promise.reject(err));
                                }
                                return response.json();
                            })
                            .then(result => {
                                if (result.success) {
                                    // Reset button state
                                    this.disabled = false;
                                    this.innerHTML = 'Submit Request';
                                    
                                    // Close the modal
                                    songRequestModal.hide();
                                    
                                    // Reset the form
                                    form.reset();
                                    
                                    // Show success toast
                                    const toast = new bootstrap.Toast(document.getElementById('requestSuccessToast'));
                                    toast.show();

                                    // Check if we have a valid song request ID in the data object
                                    if (!result.data || !result.data.id) {
                                        console.error('No song request ID received:', result);
                                        throw new Error('Failed to get song request ID');
                                    }

                                    // Create payment and get snap token
                                    fetch(`/api/payment/${result.data.id}/create`, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                        },
                                        body: JSON.stringify({
                                            amount: data.amount,
                                            email: data.email
                                        })
                                    })
                                    .then(response => {
                                        if (!response.ok) {
                                            return response.json().then(err => Promise.reject(err));
                                        }
                                        return response.json();
                                    })
                                    .then(paymentResult => {
                                        if (paymentResult.success && paymentResult.snap_token) {
                                            // Open Midtrans payment popup
                                            if (typeof window.snap === 'undefined') {
                                                console.error('Snap.js is not loaded');
                                                // Try to reload Snap.js
                                                const script = document.createElement('script');
                                                script.src = 'https://app.sandbox.midtrans.com/snap/snap.js';
                                                script.setAttribute('data-client-key', '{{ config('services.midtrans.client_key') }}');
                                                script.onload = function() {
                                                    if (typeof window.snap !== 'undefined' && paymentResult.snap_token) {
                                                        // Retry opening the payment popup
                                                        openPaymentPopup(paymentResult.snap_token);
                                                    } else {
                                                        showToast('errorToast', 'Payment system is not ready. Please try again later.');
                                                    }
                                                };
                                                script.onerror = function() {
                                                    showToast('errorToast', 'Failed to load payment system. Please try again later.');
                                                };
                                                document.body.appendChild(script);
                                                return;
                                            }

                                            openPaymentPopup(paymentResult.snap_token);
                                        } else {
                                            throw new Error(paymentResult.message || 'Failed to create payment');
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Payment creation error:', error);
                                        showToast('errorToast', 'Failed to create payment: ' + (error.message || 'Unknown error'));
                                    });

                                    function openPaymentPopup(snapToken) {
                                        if (!snapToken) {
                                            console.error('No snap token provided');
                                            showToast('errorToast', 'Failed to initialize payment. Please try again.');
                                            return;
                                        }

                                        try {
                                            window.snap.pay(snapToken, {
                                                onSuccess: function(result) {
                                                    console.log('Payment success', result);
                                                    
                                                    // Extract song request ID from order_id (format: SR-{id}-{timestamp})
                                                    const orderId = result.order_id;
                                                    const songRequestId = orderId.split('-')[1];
                                                    
                                                    // Update payment status
                                                    fetch(`/api/payment/${songRequestId}/update-status`, {
                                                        method: 'POST',
                                                        headers: {
                                                            'Content-Type': 'application/json',
                                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                                        },
                                                        body: JSON.stringify({
                                                            payment_status: 'success',
                                                            status: 'approved',
                                                            payment_method: result.payment_type,
                                                            paid_at: new Date().toISOString()
                                                        })
                                                    })
                                                    .then(response => {
                                                        if (!response.ok) {
                                                            throw new Error('Failed to update payment status');
                                                        }
                                                        return response.json();
                                                    })
                                                    .then(data => {
                                                        if (data.success) {
                                                            showToast('paymentSuccessToast');
                                                        } else {
                                                            showToast('errorToast', data.message || 'Failed to update payment status');
                                                        }
                                                    })
                                                    .catch(error => {
                                                        console.error('Error updating payment status:', error);
                                                        showToast('errorToast', 'Payment was successful but there was an error updating the status. Please contact support.');
                                                    });
                                                },
                                                onPending: function(result) {
                                                    console.log('Payment pending', result);
                                                    showToast('paymentPendingToast');
                                                },
                                                onError: function(result) {
                                                    console.log('Payment error', result);
                                                    showToast('paymentErrorToast');
                                                },
                                                onClose: function() {
                                                    console.log('Payment popup closed');
                                                    showToast('paymentCancelledToast');
                                                }
                                            });
                                        } catch (error) {
                                            console.error('Error opening payment popup:', error);
                                            showToast('errorToast', 'Failed to open payment popup. Please try again.');
                                        }
                                    }
                                } else {
                                    throw new Error(result.message || 'Failed to submit song request');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                
                                // Reset button state
                                this.disabled = false;
                                this.innerHTML = 'Submit Request';
                                
                                // Show error message
                                showToast('errorToast', error.message || 'Unknown error');
                            });
                        });
                    }
                    
                    // Handle request buttons in track cards
                    document.addEventListener('click', function(e) {
                        if (e.target.closest('.request-song-btn')) {
                            const button = e.target.closest('.request-song-btn');
                            const songName = button.getAttribute('data-song-name');
                            const artist = button.getAttribute('data-artist');
                            
                            // Pre-fill the form
                            document.getElementById('songName').value = songName;
                            document.getElementById('artistName').value = artist;
                            
                            // Show the modal
                            songRequestModal.show();
                        }
                    });
                    
                    searchForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        
                        const query = searchInput.value.trim();
                        if (!query) return;
                        
                        searchResults.innerHTML = '';
                        loadingIndicator.classList.remove('d-none');
                        noResults.classList.add('d-none');
                        
                        // Use the active playlist ID if available, otherwise use a default
                        const playlistId = activeplaylistId;
                        
                        // Use the playlist search endpoint with the active playlist ID
                        fetch(`/api/request/playlist/${playlistId}/search?query=${encodeURIComponent(query)}`)
                            .then(response => response.json())
                            .then(data => {
                                loadingIndicator.classList.add('d-none');
                                
                                // Handle different possible response structures
                                let tracks = [];
                                let total = 0;
                                
                                if (Array.isArray(data)) {
                                    tracks = data;
                                } else if (data && typeof data === 'object') {
                                    if (data.tracks && Array.isArray(data.tracks)) {
                                        tracks = data.tracks;
                                    } else if (data.tracks && data.tracks.items && Array.isArray(data.tracks.items)) {
                                        tracks = data.tracks.items;
                                        total = data.tracks.total || 0;
                                    } else if (data.items && Array.isArray(data.items)) {
                                        tracks = data.items;
                                        total = data.total || 0;
                                    }
                                }
                                displayTracks(tracks);
                            })
                            .catch(error => {
                                console.error('Error fetching search results:', error);
                                loadingIndicator.classList.add('d-none');
                                searchResults.innerHTML = `
                                    <div class="alert alert-danger text-center" role="alert">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                        An error occurred while searching. Please try again.
                                    </div>
                                `;
                            });
                    });

                    // Function to display active playlists
                    function displayActivePlaylists(playlistIds) {
                        if (!activePlaylistsContainer) return;
                        
                        if (playlistIds.length === 0) {
                            activePlaylistsContainer.innerHTML = '<p class="text-muted">No active playlists found.</p>';
                            return;
                        }

                        const playlistsHTML = playlistIds.map(id => `
                            <div class="card track-card">
                                <div class="card-body">
                                    <h5 class="card-title">Playlist ID: ${id}</h5>
                                </div>
                            </div>
                        `).join('');

                        activePlaylistsContainer.innerHTML = playlistsHTML;
                    }

                    // Add event listener for the "Show More" button
                    if (showMoreButton) {
                        showMoreButton.addEventListener('click', showMoreTracks);
                    }

                    // Function to format duration in mm:ss
                    function formatDuration(ms) {
                        const minutes = Math.floor(ms / 60000);
                        const seconds = Math.floor((ms % 60000) / 1000);
                        return `${minutes}:${seconds.toString().padStart(2, '0')}`;
                    }

                    // Function to show toast
                    function showToast(toastId, message = null) {
                        const toastElement = document.getElementById(toastId);
                        if (message && toastId === 'errorToast') {
                            document.getElementById('errorMessage').textContent = message;
                        }
                        const toast = new bootstrap.Toast(toastElement);
                        toast.show();
                    }
                }
            });
        </script>
    </body>
</html> 