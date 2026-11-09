<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->isProduction()) {
            throw new RuntimeException('Les données de démonstration ne doivent pas être chargées en production.');
        }

        $this->seedClients();
        $this->seedSellersAndProducts();
    }

    private function seedClients(): void
    {
        foreach ([
            ['name' => 'Aminata Koné', 'email' => 'aminata@example.test'],
            ['name' => 'Yao Kouassi', 'email' => 'yao@example.test'],
            ['name' => 'Fatou Diabaté', 'email' => 'fatou@example.test'],
        ] as $attributes) {
            if (User::where('email', $attributes['email'])->exists()) {
                continue;
            }

            $client = User::factory()->make($attributes);
            $client->role = 'client';
            $client->save();
        }
    }

    private function seedSellersAndProducts(): void
    {
        $catalog = [
            [
                'name' => 'Atelier Naya',
                'email' => 'atelier-naya@example.test',
                'phone' => '+225 07 00 00 00 01',
                'products' => [
                    ['name' => 'Sac en pagne tissé', 'category' => 'Mode', 'price' => 18000],
                    ['name' => 'Pochette artisanale', 'category' => 'Mode', 'price' => 8500],
                    ['name' => 'Étui à lunettes en tissu', 'category' => 'Artisanat', 'price' => 6000],
                ],
            ],
            [
                'name' => 'Maison Baobab',
                'email' => 'maison-baobab@example.test',
                'phone' => '+225 07 00 00 00 02',
                'products' => [
                    ['name' => 'Lampe en bois local', 'category' => 'Maison', 'price' => 32000],
                    ['name' => 'Plateau sculpté', 'category' => 'Maison', 'price' => 14500],
                    ['name' => 'Panier tressé', 'category' => 'Artisanat', 'price' => 12000],
                ],
            ],
            [
                'name' => 'Tech Ivoire',
                'email' => 'tech-ivoire@example.test',
                'phone' => '+225 07 00 00 00 03',
                'products' => [
                    ['name' => 'Casque audio sans fil', 'category' => 'Électronique', 'price' => 27500],
                    ['name' => 'Enceinte Bluetooth compacte', 'category' => 'Électronique', 'price' => 22000],
                    ['name' => 'Chargeur USB-C rapide', 'category' => 'Électronique', 'price' => 9500],
                ],
            ],
            [
                'name' => 'Éclat Nature',
                'email' => 'eclat-nature@example.test',
                'phone' => '+225 07 00 00 00 04',
                'products' => [
                    ['name' => 'Coffret de soins au karité', 'category' => 'Beauté', 'price' => 16500],
                    ['name' => 'Huile de coco artisanale', 'category' => 'Beauté', 'price' => 7000],
                    ['name' => 'Savons naturels assortis', 'category' => 'Beauté', 'price' => 5000],
                ],
            ],
        ];

        foreach ($catalog as $sellerData) {
            $seller = Seller::where('email', $sellerData['email'])->first();

            if (! $seller) {
                $seller = Seller::factory()->create([
                    'name' => $sellerData['name'],
                    'email' => $sellerData['email'],
                    'phone' => $sellerData['phone'],
                ]);
            }

            foreach ($sellerData['products'] as $productData) {
                $exists = Product::where('seller_id', $seller->id)
                    ->where('name', $productData['name'])
                    ->exists();

                if (! $exists) {
                    Product::factory()->for($seller)->create($productData);
                }
            }
        }
    }
}
