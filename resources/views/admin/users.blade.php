@extends('admin.layout')

@section('title', 'Utilisateurs — Administration MaPlace')
@section('breadcrumb', 'Utilisateurs')

@section('content')
    <div class="admin-page-heading">
        <div><p class="eyebrow">Comptes de la plateforme</p><h1>Utilisateurs</h1><p class="muted">Gérez les comptes clients et administrateurs.</p></div>
        <span class="admin-result-count">{{ $users->total() }} compte(s)</span>
    </div>
    <section class="admin-panel">
        <form class="admin-search-form" method="GET" action="{{ route('admin.users.index') }}">
            <label><span class="sr-only">Rechercher un utilisateur</span><input type="search" name="q" value="{{ request('q') }}" placeholder="Rechercher par nom ou e-mail"></label>
            <button class="button button-small" type="submit">Rechercher</button>
            @if (request()->filled('q'))<a class="button button-small button-outline" href="{{ route('admin.users.index') }}">Effacer</a>@endif
        </form>
        <div class="admin-table-wrap"><table class="admin-table">
            <thead><tr><th>Nom</th><th>Adresse e-mail</th><th>Rôle</th><th>Inscription</th><th>Action</th></tr></thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td><strong>{{ $user->name }}</strong></td><td>{{ $user->email }}</td>
                        <td><span @class(['status-pill', 'status-admin' => $user->isAdmin(), 'status-client' => ! $user->isAdmin()])>{{ $user->isAdmin() ? 'Administrateur' : 'Client' }}</span></td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            @if ($user->is(auth('web')->user()) || ($user->isAdmin() && \App\Models\User::where('role', 'admin')->count() <= 1))
                                <span class="muted table-note">{{ $user->is(auth('web')->user()) ? 'Compte actuel' : 'Dernier administrateur' }}</span>
                            @else
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Supprimer définitivement ce compte ?')">@csrf @method('DELETE')<button class="table-delete" type="submit">Supprimer</button></form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td class="table-empty" colspan="5">Aucun utilisateur trouvé.</td></tr>
                @endforelse
            </tbody>
        </table></div>
        @if ($users->hasPages())<div class="admin-pagination">{{ $users->links() }}</div>@endif
    </section>
@endsection
