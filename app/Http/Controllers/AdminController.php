<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(Request $request): View
    {
        $filters = $request->validate([
            'name' => ['nullable', 'string', 'max:160'],
            'category' => ['nullable', 'string', 'max:80'],
            'seller' => ['nullable', 'string', 'max:160'],
        ]);

        $products = Product::query()
            ->with('seller')
            ->filterName($filters['name'] ?? null)
            ->filterCategory($filters['category'] ?? null)
            ->filterSeller($filters['seller'] ?? null)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $sellerCount = Seller::count();
        $clientCount = User::where('role', 'client')->count();
        $productCount = Product::count();

        return view('admin.dashboard', compact('products', 'sellerCount', 'clientCount', 'productCount'));
    }

    public function users(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:160'],
        ]);

        $users = User::query()
            ->when($filters['q'] ?? null, fn ($query, $search) => $query->where(
                fn ($query) => $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%"),
            ))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users', compact('users'));
    }

    public function sellers(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:160'],
        ]);

        $sellers = Seller::query()
            ->when($filters['q'] ?? null, fn ($query, $search) => $query->where(
                fn ($query) => $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%"),
            ))
            ->withCount('products')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.sellers', compact('sellers'));
    }

    public function destroyUser(User $user): RedirectResponse
    {
        abort_if($user->is(auth('web')->user()), 403, 'Vous ne pouvez pas supprimer votre propre compte administrateur.');
        abort_if($user->isAdmin() && User::where('role', 'admin')->count() <= 1, 403, 'Le dernier administrateur ne peut pas être supprimé.');

        $user->delete();

        return back()->with('status', 'Le compte utilisateur a été supprimé.');
    }

    public function destroySeller(Seller $seller): RedirectResponse
    {
        $seller->delete();

        return back()->with('status', 'Le compte vendeur et ses annonces ont été supprimés.');
    }

    public function destroyProduct(Product $product): RedirectResponse
    {
        $product->delete();

        return back()->with('status', 'L’annonce a été supprimée du catalogue.');
    }
}
