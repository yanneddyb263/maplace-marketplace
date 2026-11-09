<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientAuthController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\SellerAuthController;
use App\Http\Controllers\SellerProductController;
use App\Http\Controllers\WhatsAppContactController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MarketplaceController::class, 'index'])->name('marketplace.index');
Route::get('/produits/{product}', [MarketplaceController::class, 'show'])->name('products.show');
Route::get('/produits/{product}/contacter', WhatsAppContactController::class)
    ->middleware('auth:web')
    ->name('products.contact');

Route::get('/connexion', [ClientAuthController::class, 'create'])->name('client.login');
Route::post('/connexion', [ClientAuthController::class, 'store'])->name('client.login.store');
Route::get('/inscription', fn () => view('auth.client-register'))->name('client.register');
Route::post('/inscription', [ClientAuthController::class, 'register'])->name('client.register.store');
Route::post('/deconnexion', [ClientAuthController::class, 'destroy'])
    ->middleware('auth:web')
    ->name('client.logout');

Route::prefix('vendeur')->name('seller.')->group(function (): void {
    Route::get('/connexion', [SellerAuthController::class, 'create'])->name('login');
    Route::post('/connexion', [SellerAuthController::class, 'store'])->name('login.store');
    Route::get('/inscription', fn () => view('auth.seller-register'))->name('register');
    Route::post('/inscription', [SellerAuthController::class, 'register'])->name('register.store');

    Route::middleware('auth:seller')->group(function (): void {
        Route::post('/deconnexion', [SellerAuthController::class, 'destroy'])->name('logout');
        Route::get('/annonces', [SellerProductController::class, 'index'])->name('products.index');
        Route::get('/annonces/creer', [SellerProductController::class, 'create'])->name('products.create');
        Route::post('/annonces', [SellerProductController::class, 'store'])->name('products.store');
        Route::get('/annonces/{product}/modifier', [SellerProductController::class, 'edit'])->name('products.edit');
        Route::put('/annonces/{product}', [SellerProductController::class, 'update'])->name('products.update');
        Route::delete('/annonces/{product}', [SellerProductController::class, 'destroy'])->name('products.destroy');
    });
});

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/connexion', [AdminAuthController::class, 'create'])->name('login');
    Route::post('/connexion', [AdminAuthController::class, 'store'])->name('login.store');

    Route::middleware(['auth:web', 'admin'])->group(function (): void {
        Route::post('/deconnexion', [AdminAuthController::class, 'destroy'])->name('logout');
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/utilisateurs', [AdminController::class, 'users'])->name('users.index');
        Route::delete('/utilisateurs/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
        Route::get('/vendeurs', [AdminController::class, 'sellers'])->name('sellers.index');
        Route::delete('/vendeurs/{seller}', [AdminController::class, 'destroySeller'])->name('sellers.destroy');
        Route::delete('/produits/{product}', [AdminController::class, 'destroyProduct'])->name('products.destroy');
    });
});
