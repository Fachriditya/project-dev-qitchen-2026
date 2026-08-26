<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect(route('login', absolute: false));
    }

    public function test_customer_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $response = $this->actingAs($user)->get('/admin/dashboard');
        $response->assertStatus(403);
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);
    }

    public function test_non_cashier_cannot_access_cashier_routes(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $response = $this->actingAs($customer)->get('/cashier/transactions');
        $response->assertStatus(403);

        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->get('/cashier/transactions');
        $response->assertStatus(403);
    }

    public function test_cashier_can_access_cashier_routes(): void
    {
        $cashier = User::factory()->create(['role' => 'cashier']);
        $response = $this->actingAs($cashier)->get('/cashier/transactions');
        $response->assertStatus(200);
    }

    public function test_non_kitchen_cannot_access_kitchen_board(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $response = $this->actingAs($customer)->get('/kitchen/kitchen/board');
        $response->assertStatus(403);
    }

    public function test_kitchen_can_access_board(): void
    {
        $kitchen = User::factory()->create(['role' => 'kitchen']);
        $response = $this->actingAs($kitchen)->get('/kitchen/kitchen/board');
        $response->assertStatus(200);
    }

    public function test_non_courier_cannot_access_courier_offers(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $response = $this->actingAs($customer)->get('/courier/offers');
        $response->assertStatus(403);
    }

    public function test_courier_can_access_offers(): void
    {
        $courier = User::factory()->create(['role' => 'courier']);
        $response = $this->actingAs($courier)->get('/courier/offers');
        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_customer_profile(): void
    {
        $response = $this->get('/profile');
        $response->assertRedirect(route('login', absolute: false));
    }

    public function test_customer_can_access_profile_sections(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $this->actingAs($customer);

        $this->get('/profile')->assertStatus(200);
        $this->get('/profile/history')->assertStatus(200);
        $this->get('/profile/vouchers')->assertStatus(200);
        $this->get('/profile/referral')->assertStatus(200);
    }

    public function test_admin_menus_require_admin_role(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $this->actingAs($customer)->get('/admin/menus')->assertStatus(403);

        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->get('/admin/menus')->assertStatus(200);
    }
}
