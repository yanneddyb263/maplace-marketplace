<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;

class WhatsAppContactController extends Controller
{
    public function __invoke(Product $product): RedirectResponse
    {
        abort_unless($product->is_active, 404);

        $phone = preg_replace('/\D+/', '', $product->seller->phone);
        abort_if($phone === '', 404, 'Le vendeur n’a pas renseigné de numéro WhatsApp valide.');

        $message = rawurlencode("Bonjour, je suis intéressé(e) par votre annonce « {$product->name} ».");

        return redirect()->away("https://wa.me/{$phone}?text={$message}");
    }
}
