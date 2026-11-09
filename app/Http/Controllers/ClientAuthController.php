<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ClientAuthController extends Controller
{
    public function create(): View
    {
        return view('auth.client-login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Ces identifiants ne correspondent pas à un compte client.'])->onlyInput('email');
        }

        Auth::guard('seller')->logout();
        $request->session()->regenerate();

        return redirect()->intended(route('marketplace.index'));
    }

    public function register(Request $request): RedirectResponse
    {
        $attributes = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create($attributes);
        Auth::guard('web')->login($user);
        Auth::guard('seller')->logout();
        $request->session()->regenerate();

        return redirect()->route('marketplace.index')->with('status', 'Bienvenue sur MaPlace !');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('marketplace.index');
    }
}
