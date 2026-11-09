<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SellerProductController extends Controller
{
    public function index(): View
    {
        $products = auth('seller')->user()
            ->products()
            ->latest()
            ->paginate(10);

        return view('seller.products.index', compact('products'));
    }

    public function create(): View
    {
        return view('seller.products.form', ['product' => new Product]);
    }

    public function store(Request $request): RedirectResponse
    {
        auth('seller')->user()->products()->create($this->validatedAttributes($request));

        return redirect()->route('seller.products.index')->with('status', 'Votre annonce a été publiée.');
    }

    public function edit(Product $product): View
    {
        $this->authorizeOwnership($product);

        return view('seller.products.form', compact('product'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $this->authorizeOwnership($product);
        $product->update($this->validatedAttributes($request));

        return redirect()->route('seller.products.index')->with('status', 'Votre annonce a été mise à jour.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->authorizeOwnership($product);
        $product->delete();

        return redirect()->route('seller.products.index')->with('status', 'Votre annonce a été supprimée.');
    }

    private function validatedAttributes(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'description' => ['required', 'string', 'max:5000'],
            'category' => ['required', 'string', 'max:80'],
            'price' => ['required', 'integer', 'min:0', 'max:999999999'],
            'image_url' => ['nullable', 'url', 'max:2048'],
        ]);
    }

    private function authorizeOwnership(Product $product): void
    {
        abort_unless($product->seller_id === auth('seller')->id(), 403);
    }
}
