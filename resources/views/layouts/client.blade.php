<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'PLAYCROWD - Connect with Your Favorite Bands')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Google Fonts for Retro Headings -->
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@700&display=swap" rel="stylesheet">

        <!-- Custom CSS -->
        <style>
            :root {
                --retro-red: #E94F2E;
                --retro-yellow: #F9D65C;
                --retro-blue: #3DB6E3;
                --retro-cream: #F7E6C4;
                --retro-black: #181818;
                --retro-orange: #F9A13B;
                --retro-shadow: 0 6px 0 var(--retro-black);
                --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            body {
                background: var(--retro-cream);
                color: var(--retro-black);
                font-family: 'Inter', sans-serif;
                line-height: 1.6;
            }

            .navbar {
                background: var(--retro-black) !important;
                padding: 1.2rem 0 1.2rem 0;
                box-shadow: var(--retro-shadow);
            }
            .navbar-brand {
                display: flex;
                align-items: center;
                font-family: 'Bebas Neue', 'Montserrat', Arial, sans-serif;
                font-size: 2.2rem;
                color: var(--retro-yellow) !important;
                letter-spacing: 3px;
                text-transform: uppercase;
            }
            .navbar-brand img {
                height: 54px;
                margin-right: 16px;
            }
            .nav-link {
                color: var(--retro-cream) !important;
                font-weight: 700;
                font-size: 1.1rem;
                letter-spacing: 1px;
                text-transform: uppercase;
                transition: var(--transition);
            }
            .nav-link:hover {
                color: var(--retro-yellow) !important;
            }

            .footer {
                background: var(--retro-black);
                color: var(--retro-cream);
                padding: 2.5rem 0 1rem;
                border-top: 8px solid var(--retro-yellow);
                box-shadow: 0 -6px 0 var(--retro-blue);
            }
            .footer-logo {
                width: 120px;
                margin-bottom: 1rem;
            }
            .footer h5 {
                color: var(--retro-yellow);
                font-family: 'Bebas Neue', 'Montserrat', Arial, sans-serif;
                font-weight: 900;
                font-size: 1.1rem;
                letter-spacing: 1px;
                text-transform: uppercase;
            }
            .footer a {
                color: var(--retro-yellow);
                text-decoration: none;
                font-weight: 700;
            }
            .footer a:hover {
                color: var(--retro-blue);
            }
            .footer-text {
                color: var(--retro-yellow);
                font-size: 0.95rem;
                font-weight: 700;
            }
            @media (max-width: 768px) {
                .hero-title { font-size: 1.7rem; }
                .hero-logo { width: 110px; }
                .feature-card, .how-step { padding: 1.1rem 0.5rem; }
                .main-content { padding: 2rem 0.5rem 0 0.5rem; }
            }
        </style>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        @yield('styles')
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <img src="/images/logo2.png" alt="PLAYCROWD Logo">
                    PLAYCROWD
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a href="{{ route('bands') }}" class="nav-link">Bands</a>
                        </li>
                        @if (Route::has('login'))
                            @auth
                                <li class="nav-item">
                                    <a href="{{ url('/dashboard') }}" class="nav-link">Dashboard</a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a href="{{ route('login') }}" class="nav-link">Login</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('register') }}" class="nav-link">Register</a>
                                </li>
                            @endauth
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')

        <footer class="footer">
            <div class="container">
                <div class="row align-items-center mb-4">
                    <div class="col-md-4 text-center text-md-start mb-3 mb-md-0">
                        <img src="/images/logo2.png" alt="PLAYCROWD Logo" class="footer-logo">
                    </div>
                    <div class="col-md-4 text-center mb-3 mb-md-0">
                        <h5>About PLAYCROWD</h5>
                        <p>Connecting live bands with their audience in cafes through song requests and donations.</p>
                    </div>
                    <div class="col-md-4 text-center text-md-end">
                        <h5>Contact Us</h5>
                        <p><a href="mailto:support@playcrowd.com">support@playcrowd.com</a><br><a href="tel:+6281234567890">+62 812 3456 7890</a></p>
                    </div>
                </div>
                <hr class="my-4 border-secondary">
                <div class="text-center">
                    <p class="footer-text mb-0">&copy; {{ date('Y') }} PLAYCROWD. All rights reserved.</p>
                </div>
            </div>
        </footer>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- Animation Script -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Navbar scroll effect
                window.addEventListener('scroll', function() {
                    const navbar = document.querySelector('.navbar');
                    if (window.scrollY > 50) {
                        navbar.classList.add('scrolled');
                    } else {
                        navbar.classList.remove('scrolled');
                    }
                });

                // Fade up animation
                const fadeElements = document.querySelectorAll('.fade-up');
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('active');
                        }
                    });
                }, {
                    threshold: 0.1
                });

                fadeElements.forEach(element => {
                    observer.observe(element);
                });
            });
        </script>

        @yield('scripts')
    </body>
</html> 