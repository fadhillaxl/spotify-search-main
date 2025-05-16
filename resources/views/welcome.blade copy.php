<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PLAYCROWD - Connect with Your Favorite Bands</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <style>
            :root {
                --primary: #1DB954;
                --primary-dark: #1aa34a;
                --dark: #121212;
                --dark-lighter: #181818;
                --dark-hover: #282828;
                --text-primary: #ffffff;
                --text-secondary: #b3b3b3;
                --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            body {
                background-color: var(--dark);
                color: var(--text-primary);
                font-family: 'Inter', sans-serif;
                line-height: 1.6;
            }

            .navbar {
                background-color: rgba(18, 18, 18, 0.8) !important;
                backdrop-filter: blur(10px);
                padding: 1rem 0;
                position: fixed;
                width: 100%;
                top: 0;
                z-index: 1000;
                transition: var(--transition);
            }

            .navbar.scrolled {
                box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            }

            .navbar-brand {
                font-weight: 700;
                font-size: 1.5rem;
                color: var(--text-primary) !important;
            }

            .nav-link {
                color: var(--text-secondary) !important;
                font-weight: 500;
                padding: 0.5rem 1rem !important;
                transition: var(--transition);
            }

            .nav-link:hover {
                color: var(--text-primary) !important;
            }

            .hero-section {
                background: linear-gradient(135deg, var(--primary) 0%, #191414 100%);
                padding: 8rem 0 6rem;
                position: relative;
                overflow: hidden;
            }

            .hero-section::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
                opacity: 0.5;
            }

            .hero-title {
                font-size: 4rem;
                font-weight: 800;
                margin-bottom: 1.5rem;
                line-height: 1.2;
                background: linear-gradient(to right, #ffffff, #b3b3b3);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }

            .hero-subtitle {
                font-size: 1.5rem;
                color: var(--text-secondary);
                margin-bottom: 2.5rem;
                max-width: 600px;
                margin-left: auto;
                margin-right: auto;
            }

            .btn {
                padding: 1rem 2rem;
                font-weight: 600;
                border-radius: 50px;
                transition: var(--transition);
                text-transform: uppercase;
                letter-spacing: 0.5px;
                font-size: 0.9rem;
            }

            .btn-primary {
                background-color: var(--primary);
                border-color: var(--primary);
                box-shadow: 0 4px 15px rgba(29, 185, 84, 0.3);
            }

            .btn-primary:hover {
                background-color: var(--primary-dark);
                border-color: var(--primary-dark);
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(29, 185, 84, 0.4);
            }

            .btn-outline {
                background-color: transparent;
                border: 2px solid var(--primary);
                color: var(--primary);
            }

            .btn-outline:hover {
                background-color: var(--primary);
                color: white;
                transform: translateY(-2px);
            }

            .feature-card {
                background-color: var(--dark-lighter);
                border-radius: 16px;
                padding: 2.5rem;
                margin-bottom: 2rem;
                transition: var(--transition);
                border: 1px solid rgba(255, 255, 255, 0.1);
                height: 100%;
            }

            .feature-card:hover {
                transform: translateY(-10px);
                background-color: var(--dark-hover);
                border-color: var(--primary);
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            }

            .feature-icon {
                font-size: 3rem;
                color: var(--primary);
                margin-bottom: 1.5rem;
                transition: var(--transition);
            }

            .feature-card:hover .feature-icon {
                transform: scale(1.1);
            }

            .feature-card h3 {
                font-size: 1.5rem;
                font-weight: 600;
                margin-bottom: 1rem;
            }

            .feature-card p {
                color: var(--text-secondary);
                font-size: 1.1rem;
                margin-bottom: 0;
            }

            .footer {
                background-color: var(--dark-lighter);
                padding: 4rem 0;
                margin-top: 6rem;
                border-top: 1px solid rgba(255, 255, 255, 0.1);
            }

            .footer-text {
                color: var(--text-secondary);
                font-size: 0.9rem;
            }

            @media (max-width: 768px) {
                .hero-title {
                    font-size: 2.5rem;
                }
                
                .hero-subtitle {
                    font-size: 1.2rem;
                }
                
                .btn {
                    padding: 0.8rem 1.5rem;
                }
                
                .feature-card {
                    padding: 2rem;
                }
            }

            /* Animation classes */
            .fade-up {
                opacity: 0;
                transform: translateY(20px);
                transition: var(--transition);
            }

            .fade-up.active {
                opacity: 1;
                transform: translateY(0);
            }

            .delay-1 { transition-delay: 0.1s; }
            .delay-2 { transition-delay: 0.2s; }
            .delay-3 { transition-delay: 0.3s; }
        </style>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <i class="bi bi-spotify me-2"></i>PLAYCROWD
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
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

        <section class="hero-section">
            <div class="container text-center">
                <h1 class="hero-title fade-up">Request Songs, Support Bands</h1>
                <p class="hero-subtitle fade-up delay-1">Connect with live bands at your favorite cafes. Request songs, send messages, and show your support through donations.</p>
                <div class="fade-up delay-2">
                    <a href="{{ route('searchRequestCommerce', ['bandname' => 'default']) }}" class="btn btn-primary me-3">Request a Song</a>
                    <a href="#features" class="btn btn-outline">How It Works</a>
                </div>
            </div>
        </section>

        <section id="features" class="container py-5">
            <div class="row">
                <div class="col-md-4 fade-up">
                    <div class="feature-card">
                        <i class="bi bi-music-note-beamed feature-icon"></i>
                        <h3>Request Songs</h3>
                        <p>Choose from the band's playlist and request your favorite songs during their live performance</p>
                    </div>
                </div>
                <div class="col-md-4 fade-up delay-1">
                    <div class="feature-card">
                        <i class="bi bi-chat-heart feature-icon"></i>
                        <h3>Send Messages</h3>
                        <p>Share your thoughts and appreciation with the band through personalized messages</p>
                    </div>
                </div>
                <div class="col-md-4 fade-up delay-2">
                    <div class="feature-card">
                        <i class="bi bi-gift feature-icon"></i>
                        <h3>Support Artists</h3>
                        <p>Show your support by making donations and helping bands continue their musical journey</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="container py-5">
            <div class="row align-items-center">
                <div class="col-md-6 fade-up">
                    <h2 class="mb-4">How It Works</h2>
                    <div class="d-flex mb-4">
                        <div class="me-3">
                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <span class="text-white fw-bold">1</span>
                            </div>
                        </div>
                        <div>
                            <h5>Browse the Playlist</h5>
                            <p class="text-secondary">Explore the band's available songs and find your favorites</p>
                        </div>
                    </div>
                    <div class="d-flex mb-4">
                        <div class="me-3">
                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <span class="text-white fw-bold">2</span>
                            </div>
                        </div>
                        <div>
                            <h5>Make Your Request</h5>
                            <p class="text-secondary">Select a song and add your personal message to the band</p>
                        </div>
                    </div>
                    <div class="d-flex mb-4">
                        <div class="me-3">
                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <span class="text-white fw-bold">3</span>
                            </div>
                        </div>
                        <div>
                            <h5>Support the Band</h5>
                            <p class="text-secondary">Add a donation amount and complete your request</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 fade-up delay-1">
                    <div class="position-relative">
                        <img src="https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Live Band Performance" class="img-fluid rounded-4 shadow-lg">
                        <div class="position-absolute bottom-0 start-0 end-0 p-4 bg-dark bg-opacity-75 rounded-bottom-4">
                            <h5 class="text-white mb-0">Experience Live Music</h5>
                            <p class="text-secondary mb-0">Connect with bands in your favorite cafes</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center fade-up">
                    <h2 class="mb-4">Why Use PLAYCROWD?</h2>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="feature-card h-100">
                                <i class="bi bi-people feature-icon"></i>
                                <h4>For Audience</h4>
                                <p>Request your favorite songs, send messages to the band, and show your support through donations</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="feature-card h-100">
                                <i class="bi bi-music-note-list feature-icon"></i>
                                <h4>For Bands</h4>
                                <p>Connect with your audience, receive song requests, and get support from your fans</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 mb-4 mb-md-0">
                        <h5 class="text-white mb-3">About PLAYCROWD</h5>
                        <p class="text-secondary">Connecting live bands with their audience in cafes through song requests and donations.</p>
                    </div>
                    <div class="col-md-4 mb-4 mb-md-0">
                        <h5 class="text-white mb-3">Quick Links</h5>
                        <ul class="list-unstyled">
                            <li><a href="#" class="text-secondary text-decoration-none">How It Works</a></li>
                            <li><a href="#" class="text-secondary text-decoration-none">For Bands</a></li>
                            <li><a href="#" class="text-secondary text-decoration-none">For Venues</a></li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h5 class="text-white mb-3">Contact Us</h5>
                        <ul class="list-unstyled">
                            <li class="text-secondary"><i class="bi bi-envelope me-2"></i> support@playcrowd.com</li>
                            <li class="text-secondary"><i class="bi bi-telephone me-2"></i> +62 812 3456 7890</li>
                        </ul>
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
    </body>
</html>
