@extends('layouts.app')

@section('title', 'Connexion vendeur — MaPlace')

@section('content')
    <section class="auth-section">
        <div class="auth-card">
            <p class="eyebrow">Votre boutique vous attend</p>
            <h1>Espace vendeur</h1>
            <p class="muted auth-intro">Gérez vos annonces et présentez vos produits à la communauté.</p>
            @if ($errors->any())<div class="form-errors" role="alert">{{ $errors->first() }}</div>@endif
            <form method="POST" action="{{ route('seller.login.store') }}" class="stack-form">
                @csrf
                <label>Adresse e-mail<input type="email" name="email" value="{{ old('email') }}" autocomplete="email" required autofocus></label>
                <label>Mot de passe<input type="password" name="password" autocomplete="current-password" required></label>
                <label class="checkbox-label"><input type="checkbox" name="remember" value="1"> Se souvenir de moi</label>
                <button class="button button-full" type="submit">Se connecter</button>
            </form>
            <p class="auth-switch">Vous débutez ? <a href="{{ route('seller.register') }}">Créer un compte vendeur</a></p>
            <p class="auth-switch seller-switch">Vous cherchez un produit ? <a href="{{ route('client.login') }}">Connexion client</a></p>
        </div>
    </section>
@endsection
