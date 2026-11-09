<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SellerAuthController extends Controller
{
    public function create(): View
    {
        return view('auth.seller-login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('seller')->attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Ces identifiants ne correspondent pas à un compte vendeur.'])->onlyInput('email');
        }

        Auth::guard('web')->logout();
        $request->session()->regenerate();

        return redirect()->intended(route('seller.products.index'));
    }

    public function register(Request $request): RedirectResponse
    {
        $attributes = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', 'unique:sellers,email'],
            'phone' => ['required', 'string', 'max:30', 'regex:/^\+[1-9][0-9\s().-]{7,20}$/'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $phoneDigits = preg_replace('/\D+/', '', $attributes['phone']);

        if (strlen($phoneDigits) < 8 || strlen($phoneDigits) > 15) {
            return back()
                ->withErrors(['phone' => 'Saisissez un numéro WhatsApp au format international valide.'])
                ->withInput();
        }

        $seller = Seller::create($attributes);
        Auth::guard('seller')->login($seller);
        Auth::guard('web')->logout();
        $request->session()->regenerate();

        return redirect()->route('seller.products.index')->with('status', 'Votre espace vendeur est prêt.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('seller')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('marketplace.index');
    }
}
