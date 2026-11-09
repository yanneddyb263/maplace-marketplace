@extends('layouts.app')

@section('title', 'Mes annonces — MaPlace')

@section('content')
    <section class="page-section">
        <div class="container">
            <div class="section-heading seller-heading">
                <div><p class="eyebrow">Espace vendeur</p><h1>Mes annonces</h1><p class="muted">Gérez les produits visibles sur votre vitrine.</p></div>
                <a class="button" href="{{ route('seller.products.create') }}">+ Déposer une annonce</a>
            </div>

            @if ($products->isEmpty())
                <div class="empty-state seller-empty"><span class="empty-icon">✦</span><h3>Votre vitrine est encore vide</h3><p>Ajoutez votre premier produit pour commencer à rencontrer vos clients.</p><a class="button" href="{{ route('seller.products.create') }}">Créer ma première annonce</a></div>
            @else
                <div class="seller-list">
                    @foreach ($products as $product)
                        <article class="seller-product-row">
                            <div class="seller-product-thumb">
                                @if ($product->image_url)<img src="{{ $product->image_url }}" alt="" loading="lazy">@else<span aria-hidden="true">✦</span>@endif
                            </div>
                            <div class="seller-product-info"><strong>{{ $product->name }}</strong><span>{{ $product->category }} · {{ number_format((float) $product->price, 0, ',', ' ') }} FCFA</span><small>{{ $product->is_active ? 'En ligne' : 'Masquée' }}</small></div>
                            <div class="seller-product-actions">
                                <a class="button button-outline button-small" href="{{ route('seller.products.edit', $product) }}">Modifier</a>
                                <form method="POST" action="{{ route('seller.products.destroy', $product) }}" onsubmit="return confirm('Supprimer cette annonce ?')">
                                    @csrf @method('DELETE')
                                    <button class="button button-danger button-small" type="submit">Supprimer</button>
                                </form>
                            </div>
                        </article>
                    @endforeach
                </div>
                @if ($products->hasPages())<div class="pagination-wrap">{{ $products->links() }}</div>@endif
            @endif
        </div>
    </section>
@endsection
