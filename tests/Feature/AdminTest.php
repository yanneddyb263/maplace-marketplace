<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_admin_can_sign_in_and_view_the_dashboard_metrics(): void
    {
        $admin = $this->createUser('admin@example.com', 'admin');
        $this->createUser('client@example.com', 'client');
        $seller = $this->createSeller();
        $this->createProduct($seller);

        $this->post(route('admin.login.store'), [
            'email' => $admin->email,
            'password' => 'password123',
        ])->assertRedirect(route('admin.dashboard'));

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Vendeurs inscrits')
            ->assertSee('Clients inscrits')
            ->assertSee('Produits publiés')
            ->assertSee('Lampe de bureau');

        $this->get(route('admin.users.index'))->assertOk()->assertSee('client@example.com');
        $this->get(route('admin.sellers.index'))->assertOk()->assertSee($seller->email);
    }

    public function test_client_cannot_access_admin_and_cannot_sign_in_as_admin(): void
    {
        $client = $this->createUser('client@example.com', 'client');

        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));

        $this->post(route('admin.login.store'), [
            'email' => $client->email,
            'password' => 'password123',
        ])->assertSessionHasErrors('email');

        $this->assertGuest('web');
    }

    public function test_seller_guard_does_not_grant_admin_access(): void
    {
        $seller = $this->createSeller();

        $this->actingAs($seller, 'seller')
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_catalog_filters_use_product_query_scopes(): void
    {
        $seller = $this->createSeller('Atelier Soleil', 'seller@example.com');
        $otherSeller = $this->createSeller('Maison du Lac', 'other@example.com');
        $this->createProduct($seller, 'Lampe artisanale', 'Maison');
        $this->createProduct($otherSeller, 'Lampe de lecture', 'Bureau');

        $this->actingAs($this->createUser('admin@example.com', 'admin'), 'web')
            ->get(route('admin.dashboard', [
                'name' => 'Lampe',
                'category' => 'Bureau',
                'seller' => 'Lac',
            ]))
            ->assertOk()
            ->assertSee('Lampe de lecture')
            ->assertDontSee('Lampe artisanale');
    }

    public function test_admin_can_delete_users_products_and_sellers(): void
    {
        $admin = $this->createUser('admin@example.com', 'admin');
        $client = $this->createUser('client@example.com', 'client');
        $seller = $this->createSeller();
        $product = $this->createProduct($seller);

        $this->actingAs($admin, 'web');

        $this->delete(route('admin.users.destroy', $client))->assertRedirect();
        $this->assertDatabaseMissing('users', ['id' => $client->id]);

        $this->delete(route('admin.products.destroy', $product))->assertRedirect();
        $this->assertDatabaseMissing('products', ['id' => $product->id]);

        $product = $this->createProduct($seller);
        $this->delete(route('admin.sellers.destroy', $seller))->assertRedirect();
        $this->assertDatabaseMissing('sellers', ['id' => $seller->id]);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_admin_cannot_delete_self_or_the_last_admin(): void
    {
        $admin = $this->createUser('admin@example.com', 'admin');
        $this->actingAs($admin, 'web');

        $this->delete(route('admin.users.destroy', $admin))->assertForbidden();

        $anotherAdmin = $this->createUser('second-admin@example.com', 'admin');
        $this->delete(route('admin.users.destroy', $anotherAdmin))->assertRedirect();
        $this->assertDatabaseMissing('users', ['id' => $anotherAdmin->id]);
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_public_registration_cannot_assign_admin_role(): void
    {
        $this->post(route('client.register.store'), [
            'name' => 'Public User',
            'email' => 'public@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin',
        ])->assertRedirect(route('marketplace.index'));

        $this->assertDatabaseHas('users', [
            'email' => 'public@example.com',
            'role' => 'client',
        ]);
    }

    private function createUser(string $email, string $role): User
    {
        $user = User::create([
            'name' => $role === 'admin' ? 'Administrator' : 'Client',
            'email' => $email,
            'password' => 'password123',
        ]);

        $user->role = $role;
        $user->save();

        return $user;
    }

    private function createSeller(string $name = 'Boutique locale', string $email = 'seller@example.com'): Seller
    {
        return Seller::create([
            'name' => $name,
            'email' => $email,
            'phone' => '+225 07 00 00 00 00',
            'password' => 'password123',
        ]);
    }

    private function createProduct(Seller $seller, string $name = 'Lampe de bureau', string $category = 'Maison'): Product
    {
        return Product::create([
            'seller_id' => $seller->id,
            'name' => $name,
            'description' => 'Une lampe en bois.',
            'category' => $category,
            'price' => 18000,
        ]);
    }
}
