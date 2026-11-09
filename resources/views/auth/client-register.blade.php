@extends('layouts.app')

@section('title', 'Créer un compte client — MaPlace')

@section('content')
    <section class="auth-section">
        <div class="auth-card">
            <p class="eyebrow">Bienvenue au marché</p>
            <h1>Créer un compte client</h1>
            <p class="muted auth-intro">Inscrivez-vous pour contacter directement les vendeurs.</p>
            @if ($errors->any())<div class="form-errors" role="alert">{{ $errors->first() }}</div>@endif
            <form method="POST" action="{{ route('client.register.store') }}" class="stack-form">
                @csrf
                <label>Votre nom<input type="text" name="name" value="{{ old('name') }}" autocomplete="name" required autofocus></label>
                <label>Adresse e-mail<input type="email" name="email" value="{{ old('email') }}" autocomplete="email" required></label>
                <label>Mot de passe<input type="password" name="password" autocomplete="new-password" minlength="8" required></label>
                <label>Confirmer le mot de passe<input type="password" name="password_confirmation" autocomplete="new-password" minlength="8" required></label>
                <button class="button button-full" type="submit">Créer mon compte</button>
            </form>
            <p class="auth-switch">Déjà inscrit ? <a href="{{ route('client.login') }}">Se connecter</a></p>
        </div>
    </section>
@endsection
