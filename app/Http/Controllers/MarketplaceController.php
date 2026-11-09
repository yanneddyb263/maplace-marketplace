<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MarketplaceController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::query()
            ->with('seller')
            ->where('is_active', true)
            ->when($request->filled('q'), function ($query) use ($request): void {
                $search = $request->string('q')->trim()->toString();

                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->string('category')->toString()))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Product::query()
            ->where('is_active', true)
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('marketplace.index', compact('products', 'categories'));
    }

    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);

        $product->load('seller');

        return view('marketplace.show', compact('product'));
    }
}
