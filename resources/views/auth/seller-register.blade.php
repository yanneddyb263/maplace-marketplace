@extends('layouts.app')

@section('title', 'Rejoindre MaPlace comme vendeur')

@section('content')
    <section class="auth-section">
        <div class="auth-card">
            <p class="eyebrow">Ouvrez votre vitrine</p>
            <h1>Devenez vendeur</h1>
            <p class="muted auth-intro">Créez votre espace et publiez vos produits en quelques minutes.</p>
            @if ($errors->any())<div class="form-errors" role="alert">{{ $errors->first() }}</div>@endif
            <form method="POST" action="{{ route('seller.register.store') }}" class="stack-form">
                @csrf
                <label>Nom de votre boutique<input type="text" name="name" value="{{ old('name') }}" autocomplete="organization" required autofocus></label>
                <label>Adresse e-mail<input type="email" name="email" value="{{ old('email') }}" autocomplete="email" required></label>
                <label>Numéro WhatsApp<input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+225 07 00 00 00 00" autocomplete="tel" required></label>
                <p class="field-help">Les clients connectés utiliseront ce numéro pour vous contacter.</p>
                <label>Mot de passe<input type="password" name="password" autocomplete="new-password" minlength="8" required></label>
                <label>Confirmer le mot de passe<input type="password" name="password_confirmation" autocomplete="new-password" minlength="8" required></label>
                <button class="button button-full" type="submit">Créer mon espace vendeur</button>
            </form>
            <p class="auth-switch">Déjà vendeur ? <a href="{{ route('seller.login') }}">Se connecter</a></p>
        </div>
    </section>
@endsection
