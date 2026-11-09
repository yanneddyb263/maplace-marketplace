<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarketplaceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_marketplace_only_shows_active_products(): void
    {
        $seller = $this->createSeller();
        Product::create([
            'seller_id' => $seller->id,
            'name' => 'Lampe artisanale',
            'description' => 'Lampe en bois',
            'category' => 'Maison',
            'price' => 15000,
        ]);
        Product::create([
            'seller_id' => $seller->id,
            'name' => 'Annonce masquée',
            'description' => 'Invisible publiquement',
            'category' => 'Maison',
            'price' => 20000,
            'is_active' => false,
        ]);

        $this->get(route('marketplace.index'))
            ->assertOk()
            ->assertSee('Lampe artisanale')
            ->assertDontSee('Annonce masquée');
    }

    public function test_authentication_and_seller_product_pages_render(): void
    {
        $this->get(route('client.login'))->assertOk();
        $this->get(route('client.register'))->assertOk();
        $this->get(route('seller.login'))->assertOk();
        $this->get(route('seller.register'))->assertOk();

        $seller = $this->createSeller();
        $this->actingAs($seller, 'seller')
            ->get(route('seller.products.index'))
            ->assertOk();

        $this->get(route('seller.products.create'))->assertOk();
        $product = $this->createProduct($seller);
        $this->get(route('seller.products.edit', $product))->assertOk();
        $this->get(route('products.show', $product))->assertOk();
    }

    public function test_clients_and_sellers_register_separately_and_publish_products(): void
    {
        $email = 'shared@example.com';

        $this->post(route('client.register.store'), [
            'name' => 'Client',
            'email' => $email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('marketplace.index'));

        $this->assertDatabaseHas('users', ['email' => $email]);

        $this->post(route('seller.register.store'), [
            'name' => 'Boutique',
            'email' => $email,
            'phone' => '+225 07 00 00 00 00',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('seller.products.index'));

        $seller = Seller::where('email', $email)->firstOrFail();
        $this->assertAuthenticatedAs($seller, 'seller');
        $this->assertGuest('web');

        $this->post(route('seller.products.store'), [
            'name' => 'Sac en tissu',
            'description' => 'Un sac confectionné localement.',
            'category' => 'Mode',
            'price' => 12000,
        ])->assertRedirect(route('seller.products.index'));

        $this->assertDatabaseHas('products', [
            'seller_id' => $seller->id,
            'name' => 'Sac en tissu',
        ]);
    }

    public function test_guest_must_sign_in_before_contacting_a_seller(): void
    {
        $product = $this->createProduct($this->createSeller());

        $this->get(route('products.contact', $product))
            ->assertRedirect(route('client.login'));
    }

    public function test_seller_session_cannot_contact_a_seller_as_a_client(): void
    {
        $seller = $this->createSeller();
        $product = $this->createProduct($seller);

        $this->actingAs($seller, 'seller')
            ->get(route('products.contact', $product))
            ->assertRedirect(route('client.login'));
    }

    public function test_client_session_cannot_access_the_seller_dashboard(): void
    {
        $client = User::create([
            'name' => 'Client',
            'email' => 'client@example.com',
            'password' => 'password123',
        ]);

        $this->actingAs($client, 'web')
            ->get(route('seller.products.index'))
            ->assertRedirect(route('seller.login'));
    }

    public function test_client_contact_redirects_to_the_sellers_whatsapp(): void
    {
        $product = $this->createProduct($this->createSeller());
        $client = User::create([
            'name' => 'Client',
            'email' => 'client@example.com',
            'password' => 'password123',
        ]);

        $this->actingAs($client, 'web')
            ->get(route('products.contact', $product))
            ->assertRedirect('https://wa.me/2250700000000?text=Bonjour%2C%20je%20suis%20int%C3%A9ress%C3%A9%28e%29%20par%20votre%20annonce%20%C2%AB%20Chaise%20en%20bois%20%C2%BB.');
    }

    public function test_seller_credentials_cannot_authenticate_through_the_client_guard(): void
    {
        $seller = $this->createSeller();

        $this->post(route('client.login.store'), [
            'email' => $seller->email,
            'password' => 'password123',
        ])->assertSessionHasErrors('email');
    }

    public function test_logging_in_with_one_guard_clears_the_other_guard(): void
    {
        $seller = $this->createSeller();
        $client = User::create([
            'name' => 'Client',
            'email' => 'client@example.com',
            'password' => 'password123',
        ]);

        $this->actingAs($seller, 'seller')
            ->post(route('client.login.store'), [
                'email' => $client->email,
                'password' => 'password123',
            ])
            ->assertRedirect(route('marketplace.index'));

        $this->assertAuthenticatedAs($client, 'web');
        $this->assertGuest('seller');
    }

    public function test_seller_cannot_edit_another_sellers_product(): void
    {
        $owner = $this->createSeller('owner@example.com');
        $otherSeller = $this->createSeller('other@example.com');
        $product = $this->createProduct($owner);

        $this->actingAs($otherSeller, 'seller')
            ->put(route('seller.products.update', $product), [
                'name' => 'Produit détourné',
                'description' => 'Tentative de modification',
                'category' => 'Maison',
                'price' => 1,
            ])
            ->assertForbidden();
    }

    private function createSeller(string $email = 'seller@example.com'): Seller
    {
        return Seller::create([
            'name' => 'Boutique locale',
            'email' => $email,
            'phone' => '+225 07 00 00 00 00',
            'password' => 'password123',
        ]);
    }

    private function createProduct(Seller $seller): Product
    {
        return Product::create([
            'seller_id' => $seller->id,
            'name' => 'Chaise en bois',
            'description' => 'Une chaise solide',
            'category' => 'Maison',
            'price' => 25000,
        ]);
    }
}
