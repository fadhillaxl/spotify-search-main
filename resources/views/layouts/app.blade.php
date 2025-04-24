<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Content -->
            <main class="py-4">
                <div class="container">
                    @yield('content')
                </div>
            </main>
        </div>

        <!-- Bootstrap 5 JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- Midtrans Snap.js SDK -->
        <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
        <script>
            // Wait for the SDK to load
            window.addEventListener('load', function() {
                if (typeof window.snap === 'undefined') {
                    console.error('Failed to load Midtrans Snap.js SDK');
                    // Try to reload the SDK
                    const script = document.createElement('script');
                    script.src = 'https://app.sandbox.midtrans.com/snap/snap.js';
                    script.setAttribute('data-client-key', '{{ config('services.midtrans.client_key') }}');
                    script.onload = function() {
                        if (typeof window.snap !== 'undefined') {
                            console.log('Midtrans Snap.js SDK loaded successfully');
                        } else {
                            console.error('Failed to load Midtrans Snap.js SDK after retry');
                        }
                    };
                    script.onerror = function() {
                        console.error('Failed to load Midtrans Snap.js SDK script');
                    };
                    document.body.appendChild(script);
                } else {
                    console.log('Midtrans Snap.js SDK loaded successfully');
                }
            });
        </script>
        
        <!-- Custom JS -->
        <script>
            // Initialize tooltips
            document.addEventListener('DOMContentLoaded', function() {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                })
            })
        </script>
        @stack('scripts')
    </body>
</html>
