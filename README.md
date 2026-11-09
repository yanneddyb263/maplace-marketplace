# MaPlace

MaPlace est une place de marché Laravel où les vendeurs publient leurs produits et où les clients les contactent directement sur WhatsApp.

## Fonctionnalités

- Catalogue public avec recherche par mot-clé, filtre par catégorie et fiches produit.
- Comptes clients sous le guard Laravel `web` : inscription, connexion et déconnexion.
- Comptes vendeurs isolés dans leur propre table et authentifiés avec le guard `seller`.
- Espace vendeur pour créer, modifier et supprimer ses annonces.
- Contact WhatsApp accessible uniquement aux utilisateurs authentifiés par le guard `web`.
- Vérification de propriété côté serveur pour chaque opération sur une annonce.
- Back-office protégé par le guard `web` et le middleware `admin`, avec indicateurs, supervision du catalogue et modération des comptes.

## Installation

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
php artisan serve
```

Configurez la base de données dans `.env` avant de lancer les migrations. Les prix sont saisis en FCFA. Le numéro WhatsApp d'un vendeur doit être fourni au format international, par exemple `+225 07 00 00 00 00`.

## Données de démonstration

Pour peupler la base locale après les migrations, lancez :

```bash
php artisan db:seed
```

Le seeder ajoute trois clients, quatre vendeurs et douze produits avec des données d'exemple. Il est réexécutable sans dupliquer ces exemples, ne supprime pas les données existantes et ne modifie pas les rôles de comptes déjà présents. Les comptes de démonstration utilisent le mot de passe `password`; ne réutilisez jamais ces identifiants en production. Le seeder refuse de s'exécuter dans l'environnement `production` et ne crée pas de compte administrateur.

## Authentification

Les clients et les administrateurs utilisent le guard `web` et le fournisseur Eloquent `users`. Les vendeurs utilisent le guard de session `seller` et le fournisseur distinct `sellers`. Un compte vendeur n'est donc pas authentifié comme client, et inversement.

Les inscriptions publiques créent uniquement des comptes avec le rôle `client`. Pour attribuer les droits administrateur à un compte de confiance, créez-le d'abord puis changez son rôle depuis Tinker :

```bash
php artisan tinker
```

```php
$user = App\Models\User::where('email', 'admin@example.com')->firstOrFail();
$user->role = 'admin';
$user->save();
```

Le compte administrateur se connecte sur `/admin/connexion`. Les pages du back-office sont préfixées par `/admin` et protégées par `auth:web` et le middleware `admin`, qui vérifie le rôle. Le dernier administrateur et le compte connecté ne peuvent pas être supprimés. La suppression d'un vendeur entraîne aussi celle de ses annonces.

Les routes vendeurs sont préfixées par `/vendeur`. Les routes de gestion d'annonces exigent le guard `seller`; le point de contact WhatsApp exige le guard `web`.

## Vérification

```bash
php artisan test
```
