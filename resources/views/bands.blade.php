@extends('layouts.client')

@section('title', 'Our Bands - PLAYCROWD')

@section('styles')
<style>
    .bands-section {
        padding: 5rem 0;
    }
    .bands-title {
        font-family: 'Bebas Neue', 'Montserrat', Arial, sans-serif;
        font-size: 3.2rem;
        font-weight: 700;
        letter-spacing: 4px;
        text-transform: uppercase;
        color: var(--retro-black);
        margin-bottom: 2rem;
        text-shadow: 2px 2px 0 var(--retro-orange);
        text-align: center;
    }
    .band-card {
        border: 3px solid var(--retro-red);
        border-radius: 18px 18px 40px 18px;
        padding: 2rem 1.5rem;
        background: var(--retro-cream);
        box-shadow: var(--retro-shadow);
        transition: var(--transition);
        height: 100%;
        text-align: center;
    }
    .band-card:hover {
        transform: translateY(-6px) scale(1.02);
        border-color: var(--retro-blue);
        box-shadow: 0 12px 0 var(--retro-orange);
    }
    .band-image {
        width: 140px;
        height: 140px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid var(--retro-blue);
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .band-name {
        font-family: 'Bebas Neue', 'Montserrat', Arial, sans-serif;
        font-size: 1.8rem;
        color: var(--retro-red);
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }
    .band-email {
        color: var(--retro-black);
        font-size: 0.95rem;
        font-weight: 500;
    }
    .band-actions {
        margin-top: 1.5rem;
    }
    .band-btn {
        font-size: 0.9rem;
        font-weight: 700;
        padding: 0.5rem 1.2rem;
        border-radius: 30px;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: var(--transition);
        border: 2px solid var(--retro-black);
        box-shadow: 0 4px 0 var(--retro-black);
    }
    .band-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 0 var(--retro-black);
    }
    .btn-request {
        background: var(--retro-red);
        color: var(--retro-cream);
    }
    .btn-request:hover {
        background: var(--retro-yellow);
        color: var(--retro-black);
    }
    .btn-profile {
        background: var(--retro-blue);
        color: var(--retro-black);
    }
    .btn-profile:hover {
        background: var(--retro-orange);
        color: var(--retro-black);
    }
</style>
@endsection

@section('content')
<section class="bands-section">
    <div class="container">
        <h1 class="bands-title">Our Bands</h1>
        <div class="row g-4 justify-content-center">
            @foreach ($bands as $band)
                @if($band && $band->name)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="band-card">
                        <img src="https://placehold.co/200x200/3DB6E3/FFF?text={{ urlencode($band->name) }}" 
                             alt="{{ $band->name }}" 
                             class="band-image">
                        <h3 class="band-name">{{ $band->name }}</h3>
                        <p class="band-email">{{ $band->email ?? 'No email provided' }}</p>
                        <div class="band-actions">
                            <a href="{{ route('searchRequestCommerce', ['bandname' => $band->name]) }}" 
                               class="btn band-btn btn-request me-2">
                                Request Song
                            </a>
                            <a href="#" class="btn band-btn btn-profile">
                                View Profile
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </div>
</section>
@endsection 