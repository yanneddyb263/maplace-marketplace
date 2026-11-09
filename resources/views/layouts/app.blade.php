<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MaPlace — Le marché près de chez vous')</title>
    <meta name="description" content="Découvrez des produits près de chez vous et contactez directement les vendeurs sur WhatsApp.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="site-header">
        <div class="container header-inner">
            <a class="brand" href="{{ route('marketplace.index') }}" aria-label="MaPlace, accueil">
                <span class="brand-mark">M</span><span>ma<span class="brand-accent">place</span></span>
            </a>
            <nav class="main-nav" aria-label="Navigation principale">
                <a href="{{ route('marketplace.index') }}">Explorer</a>
                @if (auth('seller')->check())
                    <a href="{{ route('seller.products.index') }}">Mes annonces</a>
                    <a class="button button-small" href="{{ route('seller.products.create') }}">+ Déposer une annonce</a>
                    <form method="POST" action="{{ route('seller.logout') }}">@csrf<button class="nav-button" type="submit">Déconnexion</button></form>
                @elseif (auth('web')->check())
                    <span class="welcome-user">Bonjour, {{ auth('web')->user()->name }}</span>
                    <form method="POST" action="{{ route('client.logout') }}">@csrf<button class="nav-button" type="submit">Déconnexion</button></form>
                @else
                    <a href="{{ route('seller.login') }}">Espace vendeur</a>
                    <a href="{{ route('client.login') }}">Se connecter</a>
                    <a class="button button-small" href="{{ route('client.register') }}">Créer un compte</a>
                @endif
            </nav>
            <button class="menu-toggle" type="button" aria-label="Ouvrir le menu" aria-expanded="false">☰</button>
        </div>
    </header>

    <main>
        @if (session('status'))
            <div class="container"><div class="flash-message" role="status">{{ session('status') }}</div></div>
        @endif
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container footer-inner">
            <a class="brand footer-brand" href="{{ route('marketplace.index') }}"><span class="brand-mark">M</span><span>ma<span class="brand-accent">place</span></span></a>
            <p>Les bonnes trouvailles commencent près de chez vous.</p>
            <a href="{{ route('seller.register') }}">Vous êtes vendeur ? Rejoignez-nous</a>
        </div>
    </footer>
    @stack('scripts')
</body>
</html>
