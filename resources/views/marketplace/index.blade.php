@extends('layouts.app')

@section('title', 'MaPlace — Trouvez votre bonheur près de chez vous')

@section('content')
    <section class="hero">
        <div class="container hero-grid">
            <div class="hero-copy">
                <p class="eyebrow"><span class="eyebrow-dot"></span> Le marché local, autrement</p>
                <h1>De belles trouvailles.<br><span>Tout près de vous.</span></h1>
                <p class="hero-description">Découvrez les produits proposés par des vendeurs de votre communauté. Un coup de cœur ? Échangez directement avec eux.</p>
                <a class="button" href="#annonces">Explorer les annonces <span aria-hidden="true">↓</span></a>
                <div class="hero-trust"><span class="trust-icon">✓</span> Des vendeurs accessibles, un contact direct</div>
            </div>
            <div class="hero-art" aria-hidden="true">
                <div class="art-sun"></div><div class="art-card art-card-back">✦</div>
                <div class="art-card art-card-front"><span class="art-bag">⌑</span><span class="art-card-label">Une bonne affaire<br>vous attend</span></div>
                <span class="art-spark art-spark-one">✳</span><span class="art-spark art-spark-two">✦</span>
                <span class="art-note">Le local a du bon.</span>
            </div>
        </div>
    </section>

    <section class="catalog-section" id="annonces">
        <div class="container">
            <div class="section-heading">
                <div><p class="eyebrow">À découvrir</p><h2>Les annonces du moment</h2><p class="muted">Trouvez votre prochain coup de cœur.</p></div>
                <a class="text-link" href="{{ route('seller.register') }}">Vendre sur MaPlace <span aria-hidden="true">→</span></a>
            </div>

            <form class="search-bar" action="{{ route('marketplace.index') }}" method="GET" role="search">
                <label class="search-input"><span aria-hidden="true">⌕</span><input type="search" name="q" value="{{ request('q') }}" placeholder="Que recherchez-vous ?" aria-label="Rechercher un produit"></label>
                <label class="category-select"><span class="sr-only">Catégorie</span>
                    <select name="category" aria-label="Filtrer par catégorie">
                        <option value="">Toutes les catégories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>
                        @endforeach
                    </select>
                </label>
                <button class="button search-button" type="submit">Rechercher</button>
            </form>

            <div class="product-grid">
                @forelse ($products as $product)
                    <article class="product-card">
                        <a class="product-image" href="{{ route('products.show', $product) }}">
                            @if ($product->image_url)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy">
                            @else
                                <span class="image-placeholder" aria-hidden="true"><span>✦</span></span>
                            @endif
                            <span class="product-category">{{ $product->category }}</span>
                        </a>
                        <div class="product-info">
                            <a class="product-title" href="{{ route('products.show', $product) }}">{{ $product->name }}</a>
                            <p class="product-seller">Vendu par {{ $product->seller->name }}</p>
                            <div class="product-card-bottom"><strong>{{ number_format((float) $product->price, 0, ',', ' ') }} <small>FCFA</small></strong><a class="round-link" href="{{ route('products.show', $product) }}" aria-label="Voir {{ $product->name }}">↗</a></div>
                        </div>
                    </article>
                @empty
                    <div class="empty-state">
                        <span class="empty-icon">⌕</span>
                        <h3>{{ request()->filled('q') || request()->filled('category') ? 'Aucune annonce trouvée' : 'Le marché se prépare' }}</h3>
                        <p>{{ request()->filled('q') || request()->filled('category') ? 'Essayez un autre mot-clé ou une autre catégorie.' : 'Soyez parmi les premiers à découvrir les annonces de nos vendeurs.' }}</p>
                        @if (request()->filled('q') || request()->filled('category'))
                            <a class="text-link" href="{{ route('marketplace.index') }}">Effacer les filtres →</a>
                        @else
                            <a class="button button-outline" href="{{ route('seller.register') }}">Déposer une annonce</a>
                        @endif
                    </div>
                @endforelse
            </div>
            @if ($products->hasPages())
                <div class="pagination-wrap">{{ $products->links() }}</div>
            @endif
        </div>
    </section>
@endsection
