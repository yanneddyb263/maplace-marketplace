<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Administration — MaPlace')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-body">
    <aside class="admin-sidebar">
        <a class="brand admin-brand" href="{{ route('admin.dashboard') }}"><span class="brand-mark">M</span><span>ma<span class="brand-accent">place</span></span></a>
        <p class="admin-side-label">ADMINISTRATION</p>
        <nav class="admin-nav" aria-label="Navigation administrateur">
            <a @class(['is-current' => request()->routeIs('admin.dashboard')]) href="{{ route('admin.dashboard') }}"><span>◫</span> Tableau de bord</a>
            <a @class(['is-current' => request()->routeIs('admin.users.*')]) href="{{ route('admin.users.index') }}"><span>♙</span> Utilisateurs</a>
            <a @class(['is-current' => request()->routeIs('admin.sellers.*')]) href="{{ route('admin.sellers.index') }}"><span>⌂</span> Vendeurs</a>
        </nav>
        <div class="admin-side-bottom">
            <a href="{{ route('marketplace.index') }}">← Voir la boutique</a>
            <form method="POST" action="{{ route('admin.logout') }}">@csrf<button type="submit">↪ Déconnexion</button></form>
        </div>
    </aside>
    <div class="admin-main">
        <header class="admin-topbar">
            <span>Administration <span aria-hidden="true">/</span> @yield('breadcrumb', 'Vue d’ensemble')</span>
            <span class="admin-identity"><span class="admin-avatar">{{ mb_strtoupper(mb_substr(auth('web')->user()->name, 0, 1)) }}</span>{{ auth('web')->user()->name }}</span>
        </header>
        <main class="admin-content">
            @if (session('status'))<div class="flash-message" role="status">{{ session('status') }}</div>@endif
            @yield('content')
        </main>
    </div>
</body>
</html>
