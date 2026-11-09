@extends('layouts.app')

@section('title', 'Connexion administrateur — MaPlace')

@section('content')
    <section class="auth-section">
        <div class="auth-card">
            <p class="eyebrow">Accès sécurisé</p>
            <h1>Administration</h1>
            <p class="muted auth-intro">Connectez-vous avec votre compte administrateur MaPlace.</p>
            @if ($errors->any())<div class="form-errors" role="alert">{{ $errors->first() }}</div>@endif
            <form method="POST" action="{{ route('admin.login.store') }}" class="stack-form">
                @csrf
                <label>Adresse e-mail<input type="email" name="email" value="{{ old('email') }}" autocomplete="username" required autofocus></label>
                <label>Mot de passe<input type="password" name="password" autocomplete="current-password" required></label>
                <label class="checkbox-label"><input type="checkbox" name="remember" value="1"> Se souvenir de moi</label>
                <button class="button button-full" type="submit">Se connecter</button>
            </form>
            <p class="auth-switch"><a href="{{ route('marketplace.index') }}">← Retour à la boutique</a></p>
        </div>
    </section>
@endsection
