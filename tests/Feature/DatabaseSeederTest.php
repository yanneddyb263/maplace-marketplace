<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_seeder_is_repeatable_and_preserves_existing_admins(): void
    {
        $admin = User::factory()->create(['email' => 'admin@example.com']);
        $admin->role = 'admin';
        $admin->save();

        $seeder = app(DatabaseSeeder::class);
        $seeder->run();
        $seeder->run();

        $this->assertSame(4, User::count());
        $this->assertSame(4, Seller::count());
        $this->assertSame(12, Product::count());
        $this->assertSame('admin', $admin->fresh()->role);
        $this->assertSame(12, Product::where('is_active', true)->count());
    }
}
