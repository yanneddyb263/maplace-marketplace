@extends('layouts.app')

@section('title', ($product->exists ? 'Modifier une annonce' : 'Déposer une annonce').' — MaPlace')

@section('content')
    <section class="auth-section product-form-section">
        <div class="auth-card product-form-card">
            <a class="back-link" href="{{ route('seller.products.index') }}">← Retour à mes annonces</a>
            <p class="eyebrow">{{ $product->exists ? 'Votre vitrine' : 'Nouveau produit' }}</p>
            <h1>{{ $product->exists ? 'Modifier l’annonce' : 'Déposer une annonce' }}</h1>
            <p class="muted auth-intro">Présentez clairement votre produit aux clients.</p>
            @if ($errors->any())<div class="form-errors" role="alert">{{ $errors->first() }}</div>@endif
            <form method="POST" action="{{ $product->exists ? route('seller.products.update', $product) : route('seller.products.store') }}" class="stack-form">
                @csrf
                @if ($product->exists) @method('PUT') @endif
                <label>Nom du produit<input type="text" name="name" value="{{ old('name', $product->name) }}" maxlength="160" required autofocus></label>
                <label>Catégorie<input type="text" name="category" value="{{ old('category', $product->category) }}" placeholder="Ex. Mode, Maison, Électronique" maxlength="80" required></label>
                <label>Prix (FCFA)<input type="number" name="price" value="{{ old('price', $product->price) }}" min="0" step="1" required></label>
                <label>Lien de la photo <span class="optional">(facultatif)</span><input type="url" name="image_url" value="{{ old('image_url', $product->image_url) }}" placeholder="https://..."></label>
                <label>Description<textarea name="description" rows="6" maxlength="5000" required>{{ old('description', $product->description) }}</textarea></label>
                <button class="button button-full" type="submit">{{ $product->exists ? 'Enregistrer les modifications' : 'Publier mon annonce' }}</button>
            </form>
        </div>
    </section>
@endsection
