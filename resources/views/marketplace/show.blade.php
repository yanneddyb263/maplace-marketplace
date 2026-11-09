@extends('layouts.app')

@section('title', $product->name.' — MaPlace')

@section('content')
    <section class="page-section">
        <div class="container">
            <a class="back-link" href="{{ route('marketplace.index') }}">← Retour aux annonces</a>
            <div class="detail-grid">
                <div class="detail-image">
                    @if ($product->image_url)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                    @else
                        <span class="image-placeholder image-placeholder-large" aria-hidden="true"><span>✦</span></span>
                    @endif
                </div>
                <div class="detail-copy">
                    <span class="product-category detail-category">{{ $product->category }}</span>
                    <h1>{{ $product->name }}</h1>
                    <p class="detail-price">{{ number_format((float) $product->price, 0, ',', ' ') }} <small>FCFA</small></p>
                    <div class="detail-divider"></div>
                    <h2>À propos de l'article</h2>
                    <p class="detail-description">{{ $product->description }}</p>
                    <div class="seller-card">
                        <span class="seller-avatar">{{ mb_strtoupper(mb_substr($product->seller->name, 0, 1)) }}</span>
                        <div><span class="muted">Proposé par</span><strong>{{ $product->seller->name }}</strong></div>
                    </div>
                    @if (auth('web')->check())
                        <a class="button whatsapp-button" href="{{ route('products.contact', $product) }}"><span aria-hidden="true">◉</span> Contacter le vendeur sur WhatsApp</a>
                        <p class="contact-note">Vous échangerez directement avec le vendeur.</p>
                    @elseif (auth('seller')->check())
                        <p class="login-hint">Connectez-vous avec un compte client pour contacter un vendeur.</p>
                        <a class="button" href="{{ route('client.login') }}">Connexion client</a>
                    @else
                        <a class="button whatsapp-button" href="{{ route('products.contact', $product) }}"><span aria-hidden="true">◉</span> Connectez-vous pour contacter</a>
                        <p class="contact-note">La mise en relation est réservée aux clients connectés.</p>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
