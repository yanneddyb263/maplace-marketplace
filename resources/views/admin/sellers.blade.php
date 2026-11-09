@extends('admin.layout')

@section('title', 'Vendeurs — Administration MaPlace')
@section('breadcrumb', 'Vendeurs')

@section('content')
    <div class="admin-page-heading">
        <div><p class="eyebrow">Comptes professionnels</p><h1>Vendeurs</h1><p class="muted">Consultez les boutiques et modérez leurs comptes.</p></div>
        <span class="admin-result-count">{{ $sellers->total() }} vendeur(s)</span>
    </div>
    <section class="admin-panel">
        <form class="admin-search-form" method="GET" action="{{ route('admin.sellers.index') }}">
            <label><span class="sr-only">Rechercher un vendeur</span><input type="search" name="q" value="{{ request('q') }}" placeholder="Nom, e-mail ou téléphone"></label>
            <button class="button button-small" type="submit">Rechercher</button>
            @if (request()->filled('q'))<a class="button button-small button-outline" href="{{ route('admin.sellers.index') }}">Effacer</a>@endif
        </form>
        <div class="admin-table-wrap"><table class="admin-table">
            <thead><tr><th>Boutique</th><th>Adresse e-mail</th><th>Téléphone</th><th>Annonces</th><th>Inscription</th><th>Action</th></tr></thead>
            <tbody>
                @forelse ($sellers as $seller)
                    <tr>
                        <td><div class="admin-product-cell"><span class="admin-product-icon seller-icon">⌂</span><strong>{{ $seller->name }}</strong></div></td>
                        <td>{{ $seller->email }}</td><td>{{ $seller->phone }}</td><td>{{ $seller->products_count }}</td><td>{{ $seller->created_at->format('d/m/Y') }}</td>
                        <td><form method="POST" action="{{ route('admin.sellers.destroy', $seller) }}" onsubmit="return confirm('Supprimer définitivement ce vendeur et toutes ses annonces ?')">@csrf @method('DELETE')<button class="table-delete" type="submit">Supprimer</button></form></td>
                    </tr>
                @empty
                    <tr><td class="table-empty" colspan="6">Aucun vendeur trouvé.</td></tr>
                @endforelse
            </tbody>
        </table></div>
        @if ($sellers->hasPages())<div class="admin-pagination">{{ $sellers->links() }}</div>@endif
    </section>
@endsection
