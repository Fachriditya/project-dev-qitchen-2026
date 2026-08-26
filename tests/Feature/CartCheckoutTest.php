<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Menu;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CartCheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected function createMenu(array $overrides = []): Menu
    {
        $category = Category::factory()->create();
        return Menu::factory()->create(array_merge(['category_id' => $category->category_id], $overrides));
    }

    public function test_add_to_cart_increments_quantity(): void
    {
        $menu = $this->createMenu(['menu_price' => 25000]);

        $response = $this->post('/cart/add/' . $menu->menu_id);
        $response->assertRedirect();

        $cart = session('cart');
        $this->assertNotEmpty($cart);
        $this->assertEquals(1, $cart[$menu->menu_id]['quantity']);

        // Add again should increment
        $this->post('/cart/add/' . $menu->menu_id);
        $cart = session('cart');
        $this->assertEquals(2, $cart[$menu->menu_id]['quantity']);
    }

    public function test_add_to_cart_with_specific_quantity(): void
    {
        $menu = $this->createMenu();

        $this->post('/cart/add/' . $menu->menu_id, ['quantity' => 3]);
        $cart = session('cart');
        $this->assertEquals(3, $cart[$menu->menu_id]['quantity']);
    }

    public function test_add_to_cart_with_zero_quantity_removes_item(): void
    {
        $menu = $this->createMenu();

        // First add
        $this->post('/cart/add/' . $menu->menu_id, ['quantity' => 2]);
        $this->assertNotEmpty(session('cart'));

        // Then set to 0 should remove
        $this->post('/cart/add/' . $menu->menu_id, ['quantity' => 0]);
        $cart = session('cart');
        $this->assertTrue(empty($cart) || !isset($cart[$menu->menu_id]));
    }

    public function test_update_cart_increase_and_decrease(): void
    {
        $menu = $this->createMenu();

        $this->post('/cart/add/' . $menu->menu_id);
        $this->post('/cart/update/' . $menu->menu_id, ['action' => 'increase']);
        $this->assertEquals(2, session('cart')[$menu->menu_id]['quantity']);

        $this->post('/cart/update/' . $menu->menu_id, ['action' => 'decrease']);
        $this->assertEquals(1, session('cart')[$menu->menu_id]['quantity']);

        // Decrease to 0 should auto-remove
        $this->post('/cart/update/' . $menu->menu_id, ['action' => 'decrease']);
        $cart = session('cart');
        $this->assertTrue(empty($cart) || !isset($cart[$menu->menu_id]));
    }

    public function test_remove_from_cart(): void
    {
        $menu = $this->createMenu();

        $this->post('/cart/add/' . $menu->menu_id);
        $this->assertNotEmpty(session('cart'));

        $this->post('/cart/remove/' . $menu->menu_id);
        $cart = session('cart');
        $this->assertTrue(empty($cart) || !isset($cart[$menu->menu_id]));
    }

    public function test_checkout_page_with_items(): void
    {
        $menu = $this->createMenu(['menu_price' => 30000]);

        $this->post('/cart/add/' . $menu->menu_id, ['quantity' => 2]);

        $response = $this->get('/checkout');
        $response->assertStatus(200);
    }

    public function test_checkout_voucher_apply_requires_login(): void
    {
        $response = $this->post('/checkout/voucher/apply', ['voucher_code' => 'TEST123']);
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_checkout_voucher_apply_validates_owned_voucher(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $this->actingAs($user);

        // Create voucher but not assigned to user - should fail "not owned"
        $voucher = Voucher::factory()->create([
            'voucher_code' => 'OWNED123',
            'voucher_type' => 'fixed',
            'voucher_value' => 10000,
            'is_active' => true,
        ]);

        $this->post('/cart/add/' . $this->createMenu(['menu_price' => 50000])->menu_id);

        $response = $this->post('/checkout/voucher/apply', ['voucher_code' => 'OWNED123']);
        $response->assertSessionHas('error');
    }

    public function test_checkout_voucher_apply_success_for_owned_voucher(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $voucher = Voucher::factory()->create([
            'voucher_code' => 'MYVOUCHER1',
            'voucher_type' => 'fixed',
            'voucher_value' => 10000,
            'min_order' => 0,
            'is_active' => true,
        ]);

        // Assign voucher to user
        \App\Models\UserVoucher::create([
            'user_id' => $user->user_id,
            'voucher_id' => $voucher->voucher_id,
            'is_used' => false,
        ]);

        $menu = $this->createMenu(['menu_price' => 50000]);
        $this->actingAs($user)->post('/cart/add/' . $menu->menu_id);

        $response = $this->actingAs($user)->post('/checkout/voucher/apply', ['voucher_code' => 'MYVOUCHER1']);
        $response->assertSessionHas('success');
        $this->assertNotNull(session('applied_voucher'));
        $this->assertEquals('MYVOUCHER1', session('applied_voucher')['code']);
    }

    public function test_checkout_voucher_remove(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $voucher = Voucher::factory()->create([
            'voucher_code' => 'REMOVEME',
            'voucher_type' => 'fixed',
            'voucher_value' => 5000,
            'is_active' => true,
        ]);
        \App\Models\UserVoucher::create([
            'user_id' => $user->user_id,
            'voucher_id' => $voucher->voucher_id,
            'is_used' => false,
        ]);

        $menu = $this->createMenu(['menu_price' => 50000]);
        $this->actingAs($user)->post('/cart/add/' . $menu->menu_id);
        $this->actingAs($user)->post('/checkout/voucher/apply', ['voucher_code' => 'REMOVEME']);

        $this->assertNotNull(session('applied_voucher'));

        $response = $this->post('/checkout/voucher/remove');
        $response->assertRedirect();
        $this->assertNull(session('applied_voucher'));
    }

    public function test_checkout_process_creates_order_and_payment(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $menu = $this->createMenu(['menu_price' => 40000]);

        $this->actingAs($user);
        $this->post('/cart/add/' . $menu->menu_id, ['quantity' => 1]);

        $response = $this->post('/checkout/process', [
            'order_type' => 'dine_in',
            'dinein_name' => 'Test Customer',
            'order_notes' => 'No spicy',
        ]);

        // In testing env, CheckoutController bypasses Midtrans and returns payment view with dummy token
        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', ['order_type' => 'dine_in']);
        $this->assertDatabaseHas('payments', ['payment_method' => 'qris']);
        $this->assertDatabaseHas('orders', ['order_notes' => 'Name: Test Customer | Notes: No spicy']);
    }

    public function test_checkout_process_fails_with_empty_cart(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $this->actingAs($user);

        $response = $this->post('/checkout/process', [
            'order_type' => 'dine_in',
            'dinein_name' => 'Test',
        ]);

        $response->assertRedirect(route('cart.index', absolute: false));
    }
}
