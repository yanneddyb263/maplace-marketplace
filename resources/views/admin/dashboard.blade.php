@extends('admin.layout')

@section('title', 'Tableau de bord — Administration MaPlace')
@section('breadcrumb', 'Vue d’ensemble')

@section('content')
    <div class="admin-page-heading">
        <div><p class="eyebrow">Vue d’ensemble</p><h1>Tableau de bord</h1><p class="muted">Suivez l’activité de votre marketplace.</p></div>
        <span class="admin-date">{{ now()->translatedFormat('l j F Y') }}</span>
    </div>

    <section class="kpi-grid" aria-label="Indicateurs clés">
        <article class="kpi-card"><div class="kpi-icon kpi-orange">⌂</div><span class="kpi-label">Vendeurs inscrits</span><strong>{{ number_format($sellerCount, 0, ',', ' ') }}</strong><span class="kpi-caption">Comptes vendeurs</span></article>
        <article class="kpi-card"><div class="kpi-icon kpi-blue">♙</div><span class="kpi-label">Clients inscrits</span><strong>{{ number_format($clientCount, 0, ',', ' ') }}</strong><span class="kpi-caption">Comptes clients</span></article>
        <article class="kpi-card"><div class="kpi-icon kpi-green">▧</div><span class="kpi-label">Produits publiés</span><strong>{{ number_format($productCount, 0, ',', ' ') }}</strong><span class="kpi-caption">Annonces au catalogue</span></article>
    </section>

    <section class="admin-panel">
        <div class="admin-panel-heading">
            <div><h2>Supervision du catalogue</h2><p class="muted">Recherchez et modérez les annonces publiées.</p></div>
            <span class="admin-result-count">{{ $products->total() }} annonce(s)</span>
        </div>
        <form class="admin-filter-form" method="GET" action="{{ route('admin.dashboard') }}">
            <label><span>Nom du produit</span><input type="search" name="name" value="{{ request('name') }}" placeholder="Ex. lampe"></label>
            <label><span>Catégorie</span><input type="search" name="category" value="{{ request('category') }}" placeholder="Ex. maison"></label>
            <label><span>Vendeur</span><input type="search" name="seller" value="{{ request('seller') }}" placeholder="Nom ou e-mail"></label>
            <div class="filter-actions"><button class="button button-small" type="submit">Filtrer</button><a class="button button-small button-outline" href="{{ route('admin.dashboard') }}">Effacer</a></div>
        </form>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>Produit</th><th>Catégorie</th><th>Vendeur</th><th>Prix</th><th>Statut</th><th>Action</th></tr></thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td><div class="admin-product-cell"><span class="admin-product-icon">✦</span><div><strong>{{ $product->name }}</strong><small>Ajouté le {{ $product->created_at->format('d/m/Y') }}</small></div></div></td>
                            <td>{{ $product->category }}</td>
                            <td><strong>{{ $product->seller->name }}</strong><small class="table-subline">{{ $product->seller->email }}</small></td>
                            <td class="price-cell">{{ number_format((float) $product->price, 0, ',', ' ') }} <small>FCFA</small></td>
                            <td><span @class(['status-pill', 'status-live' => $product->is_active, 'status-hidden' => ! $product->is_active])>{{ $product->is_active ? 'En ligne' : 'Masquée' }}</span></td>
                            <td><form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Supprimer définitivement cette annonce ?')">@csrf @method('DELETE')<button class="table-delete" type="submit" aria-label="Supprimer {{ $product->name }}">Supprimer</button></form></td>
                        </tr>
                    @empty
                        <tr><td class="table-empty" colspan="6">Aucune annonce ne correspond à ces filtres.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($products->hasPages())<div class="admin-pagination">{{ $products->links() }}</div>@endif
    </section>
@endsection
