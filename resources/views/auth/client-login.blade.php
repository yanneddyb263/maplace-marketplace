@extends('layouts.app')

@section('title', 'Connexion client — MaPlace')

@section('content')
    <section class="auth-section">
        <div class="auth-card">
            <p class="eyebrow">Ravi de vous revoir</p>
            <h1>Connexion client</h1>
            <p class="muted auth-intro">Connectez-vous pour échanger avec les vendeurs.</p>
            @if ($errors->any())<div class="form-errors" role="alert">{{ $errors->first() }}</div>@endif
            <form method="POST" action="{{ route('client.login.store') }}" class="stack-form">
                @csrf
                <label>Adresse e-mail<input type="email" name="email" value="{{ old('email') }}" autocomplete="email" required autofocus></label>
                <label>Mot de passe<input type="password" name="password" autocomplete="current-password" required></label>
                <label class="checkbox-label"><input type="checkbox" name="remember" value="1"> Se souvenir de moi</label>
                <button class="button button-full" type="submit">Se connecter</button>
            </form>
            <p class="auth-switch">Pas encore de compte ? <a href="{{ route('client.register') }}">Créer un compte client</a></p>
            <p class="auth-switch seller-switch">Vous vendez ? <a href="{{ route('seller.login') }}">Accéder à l'espace vendeur</a></p>
        </div>
    </section>
@endsection
