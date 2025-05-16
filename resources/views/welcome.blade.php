@extends('layouts.client')

@section('title', 'PLAYCROWD - Connect with Your Favorite Bands')

@section('styles')
<style>
    .hero-section {
        background: linear-gradient(120deg, var(--retro-yellow) 0 40%, var(--retro-blue) 40% 100%);
        color: var(--retro-black);
        padding: 7rem 0 5rem;
        text-align: center;
        position: relative;
        box-shadow: var(--retro-shadow);
    }
    .hero-logo {
        width: 260px;
        max-width: 90vw;
        margin-bottom: 2.2rem;
        filter: drop-shadow(0 8px 32px rgba(0,0,0,0.18));
    }
    .hero-title {
        font-family: 'Bebas Neue', 'Montserrat', Arial, sans-serif;
        font-size: 3.2rem;
        font-weight: 700;
        letter-spacing: 4px;
        text-transform: uppercase;
        color: var(--retro-black);
        margin-bottom: 1.2rem;
        text-shadow: 2px 2px 0 var(--retro-orange);
    }
    .hero-subtitle {
        font-size: 1.3rem;
        color: var(--retro-black);
        margin-bottom: 2.5rem;
        font-weight: 500;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }
    .hero-btns .btn {
        margin: 0 0.5rem 1rem 0.5rem;
        font-size: 1.1rem;
        font-weight: 800;
        border-radius: 40px;
        padding: 1rem 2.5rem;
        border: 3px solid var(--retro-black);
        box-shadow: var(--retro-shadow);
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: var(--transition);
    }
    .btn-retro-red {
        background: var(--retro-red);
        color: var(--retro-cream);
    }
    .btn-retro-red:hover {
        background: var(--retro-yellow);
        color: var(--retro-black);
    }
    .btn-retro-blue {
        background: var(--retro-blue);
        color: var(--retro-black);
    }
    .btn-retro-blue:hover {
        background: var(--retro-orange);
        color: var(--retro-black);
    }

    .main-content {
        max-width: 1100px;
        margin: 0 auto;
        padding: 3rem 1rem 0 1rem;
    }

    .feature-section {
        background: transparent;
        padding: 0 0 2rem;
    }
    .feature-card {
        border-radius: 18px 18px 40px 18px;
        padding: 2.2rem 1.2rem 1.7rem 1.2rem;
        margin-bottom: 2rem;
        background: var(--retro-cream);
        color: var(--retro-black);
        font-weight: 700;
        font-size: 1.1rem;
        box-shadow: 0 6px 0 var(--retro-black);
        border: 3px solid var(--retro-blue);
        text-align: center;
        position: relative;
        transition: var(--transition);
    }
    .feature-card .feature-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        display: inline-block;
    }
    .feature-card.red { border-color: var(--retro-red); }
    .feature-card.yellow { border-color: var(--retro-yellow); }
    .feature-card.blue { border-color: var(--retro-blue); }
    .feature-card.orange { border-color: var(--retro-orange); }
    .feature-card h3 {
        font-family: 'Bebas Neue', 'Montserrat', Arial, sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }
    .feature-card p {
        font-size: 1rem;
        font-weight: 400;
        color: var(--retro-black);
    }
    .feature-card:hover {
        box-shadow: 0 12px 0 var(--retro-orange);
        border-color: var(--retro-red);
        transform: translateY(-6px) scale(1.04) rotate(-2deg);
    }

    .how-section {
        background: transparent;
        color: var(--retro-black);
        padding: 0 0 2rem;
    }
    .how-step {
        background: var(--retro-yellow);
        color: var(--retro-black);
        border-radius: 16px 40px 16px 16px;
        padding: 1.7rem 1.2rem;
        margin-bottom: 2rem;
        font-weight: 700;
        box-shadow: 0 4px 0 var(--retro-black);
        border: 3px solid var(--retro-blue);
        position: relative;
        text-align: center;
    }
    .how-step .step-number {
        background: var(--retro-red);
        color: var(--retro-cream);
        border-radius: 50%;
        width: 38px; height: 38px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
        font-weight: 900;
        position: absolute;
        left: 50%;
        top: -19px;
        transform: translateX(-50%);
        box-shadow: 0 2px 8px rgba(0,0,0,0.10);
        border: 2px solid var(--retro-black);
    }
    .how-step h5 {
        font-family: 'Bebas Neue', 'Montserrat', Arial, sans-serif;
        font-size: 1.1rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        margin-top: 1.2rem;
        letter-spacing: 1px;
        text-transform: uppercase;
    }
    .how-step p {
        font-size: 0.97rem;
        font-weight: 400;
        color: var(--retro-black);
    }
</style>
@endsection

@section('content')
<section class="hero-section">
    <div class="container justify-content-center">
        <div class="row justify-content-center">
            <img src="/images/logo2.png" alt="PLAYCROWD Logo" class="hero-logo">
            <h1 class="hero-title">Request Songs, Support Bands</h1>
            <p class="hero-subtitle">Connect with live bands at your favorite cafes. Request songs, send messages, and show your support through donations.</p>
            <div class="hero-btns">
                <a href="{{ route('bands') }}" class="btn btn-retro-red me-2">Request a Song</a>
                <a href="#features" class="btn btn-retro-blue">How It Works</a>
            </div>
        </div>
    </div>
</section>

<div class="main-content">
    <section id="features" class="feature-section">
        <div class="row g-4">
            <div class="col-md-3 col-12">
                <div class="feature-card red">
                    <span class="feature-icon"><i class="bi bi-music-note-beamed"></i></span>
                    <h3>Request Songs</h3>
                    <p>Choose from the band's playlist and request your favorite songs during their live performance.</p>
                </div>
            </div>
            <div class="col-md-3 col-12">
                <div class="feature-card blue">
                    <span class="feature-icon"><i class="bi bi-chat-heart"></i></span>
                    <h3>Send Messages</h3>
                    <p>Share your thoughts and appreciation with the band through personalized messages.</p>
                </div>
            </div>
            <div class="col-md-3 col-12">
                <div class="feature-card yellow">
                    <span class="feature-icon"><i class="bi bi-gift"></i></span>
                    <h3>Support Artists</h3>
                    <p>Show your support by making donations and helping bands continue their musical journey.</p>
                </div>
            </div>
            <div class="col-md-3 col-12">
                <div class="feature-card orange">
                    <span class="feature-icon"><i class="bi bi-star-fill"></i></span>
                    <h3>Give Ratings</h3>
                    <p>Rate your experience and help your favorite bands shine!</p>
                </div>
            </div>
        </div>
    </section>

    <section class="how-section">
        <div class="row g-4 align-items-center">
            <div class="col-md-4 col-12">
                <div class="how-step">
                    <div class="step-number">1</div>
                    <h5>Browse the Playlist</h5>
                    <p>Explore the band's available songs and find your favorites.</p>
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="how-step">
                    <div class="step-number">2</div>
                    <h5>Make Your Request</h5>
                    <p>Select a song and add your personal message to the band.</p>
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="how-step">
                    <div class="step-number">3</div>
                    <h5>Support the Band</h5>
                    <p>Add a donation amount and complete your request.</p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
