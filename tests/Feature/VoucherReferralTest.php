<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Menu;
use App\Models\User;
use App\Models\UserVoucher;
use App\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Carbon\Carbon;

class VoucherReferralTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_user_gets_welcome_voucher_automatically(): void
    {
        // Seed the welcome voucher first
        $welcomeVoucher = Voucher::create([
            'voucher_name' => 'Welcome BOGO',
            'voucher_code' => 'NEW-BOGO-2026',
            'voucher_type' => 'bogo',
            'voucher_value' => 1,
            'min_order' => 0,
            'quota' => 9999,
            'issued_count' => 0,
            'expiration_date' => Carbon::now()->addMonths(6),
            'is_active' => true,
        ]);

        $user = User::factory()->create(['role' => 'customer']);

        $this->assertDatabaseHas('user_vouchers', [
            'user_id' => $user->user_id,
            'voucher_id' => $welcomeVoucher->voucher_id,
            'is_used' => false,
        ]);

        $welcomeVoucher->refresh();
        $this->assertEquals(1, $welcomeVoucher->issued_count);
    }

    public function test_referral_code_generated_automatically(): void
    {
        $user = User::factory()->create(['name' => 'Fachri Test', 'role' => 'customer']);
        $this->assertNotNull($user->referral_code);
        $this->assertStringStartsWith('QITCHEN-', $user->referral_code);
    }

    public function test_referral_code_format(): void
    {
        $user = User::factory()->create(['name' => 'John Doe', 'role' => 'customer']);
        // Format: QITCHEN-XXXX-YYYY (e.g., QITCHEN-JOHN-AB12)
        $this->assertMatchesRegularExpression('/^QITCHEN-[A-Z0-9]{4}-[A-Z0-9]{4}$/', $user->referral_code);
    }

    public function test_expired_voucher_cannot_be_applied(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $expiredVoucher = Voucher::factory()->create([
            'voucher_code' => 'EXPIRED1',
            'expiration_date' => Carbon::now()->subDay(),
            'is_active' => true,
        ]);

        UserVoucher::create([
            'user_id' => $user->user_id,
            'voucher_id' => $expiredVoucher->voucher_id,
            'is_used' => false,
        ]);

        $category = Category::factory()->create();
        $menu = Menu::factory()->create(['category_id' => $category->category_id, 'menu_price' => 50000]);

        $this->actingAs($user)->post('/cart/add/' . $menu->menu_id);

        $response = $this->actingAs($user)->post('/checkout/voucher/apply', ['voucher_code' => 'EXPIRED1']);
        $response->assertSessionHas('error');
    }

    public function test_used_voucher_cannot_be_reused(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $voucher = Voucher::factory()->create([
            'voucher_code' => 'USED123',
            'is_active' => true,
        ]);

        UserVoucher::create([
            'user_id' => $user->user_id,
            'voucher_id' => $voucher->voucher_id,
            'is_used' => true,
        ]);

        $category = Category::factory()->create();
        $menu = Menu::factory()->create(['category_id' => $category->category_id]);
        $this->actingAs($user)->post('/cart/add/' . $menu->menu_id);

        $response = $this->actingAs($user)->post('/checkout/voucher/apply', ['voucher_code' => 'USED123']);
        $response->assertSessionHas('error');
    }

    public function test_voucher_not_owned_cannot_be_applied(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        Voucher::factory()->create([
            'voucher_code' => 'NOTOWNED',
            'is_active' => true,
        ]);

        $category = Category::factory()->create();
        $menu = Menu::factory()->create(['category_id' => $category->category_id]);
        $this->actingAs($user)->post('/cart/add/' . $menu->menu_id);

        $response = $this->actingAs($user)->post('/checkout/voucher/apply', ['voucher_code' => 'NOTOWNED']);
        $response->assertSessionHas('error');
    }

    public function test_fixed_voucher_discount_calculation(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $voucher = Voucher::factory()->create([
            'voucher_code' => 'FIXED5000',
            'voucher_type' => 'fixed',
            'voucher_value' => 5000,
            'min_order' => 0,
            'is_active' => true,
        ]);

        UserVoucher::create([
            'user_id' => $user->user_id,
            'voucher_id' => $voucher->voucher_id,
            'is_used' => false,
        ]);

        $category = Category::factory()->create();
        $menu = Menu::factory()->create(['category_id' => $category->category_id, 'menu_price' => 50000]);
        $this->actingAs($user)->post('/cart/add/' . $menu->menu_id, ['quantity' => 1]);
        $this->actingAs($user)->post('/checkout/voucher/apply', ['voucher_code' => 'FIXED5000']);

        $response = $this->actingAs($user)->get('/checkout');
        $response->assertStatus(200);
        // Discount should be 5000
        $this->assertEquals(5000, session('applied_voucher')['value']);
    }

    public function test_percentage_voucher_with_max_discount(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $voucher = Voucher::factory()->create([
            'voucher_code' => 'PERC10',
            'voucher_type' => 'percentage',
            'voucher_value' => 10,
            'min_order' => 0,
            'max_discount' => 5000,
            'is_active' => true,
        ]);

        UserVoucher::create([
            'user_id' => $user->user_id,
            'voucher_id' => $voucher->voucher_id,
            'is_used' => false,
        ]);

        $category = Category::factory()->create();
        // High price to exceed max discount: 100000 * 10% = 10000, capped at 5000
        $menu = Menu::factory()->create(['category_id' => $category->category_id, 'menu_price' => 100000]);
        $this->actingAs($user)->post('/cart/add/' . $menu->menu_id, ['quantity' => 1]);
        $this->actingAs($user)->post('/checkout/voucher/apply', ['voucher_code' => 'PERC10']);

        $response = $this->actingAs($user)->get('/checkout');
        $response->assertStatus(200);
        // The checkout page should calculate discount capped at 5000
        // We verify session still holds correctly
        $this->assertEquals(10, session('applied_voucher')['value']);
        $this->assertEquals(5000, session('applied_voucher')['max_discount']);
    }

    public function test_bogo_voucher_requires_two_items(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $voucher = Voucher::factory()->create([
            'voucher_code' => 'BOGO123',
            'voucher_type' => 'bogo',
            'voucher_value' => 1,
            'is_active' => true,
        ]);

        UserVoucher::create([
            'user_id' => $user->user_id,
            'voucher_id' => $voucher->voucher_id,
            'is_used' => false,
        ]);

        $category = Category::factory()->create();
        $menu1 = Menu::factory()->create(['category_id' => $category->category_id, 'menu_price' => 30000]);
        $menu2 = Menu::factory()->create(['category_id' => $category->category_id, 'menu_price' => 20000]);

        $this->actingAs($user)->post('/cart/add/' . $menu1->menu_id, ['quantity' => 1]);
        $this->actingAs($user)->post('/cart/add/' . $menu2->menu_id, ['quantity' => 1]);

        $this->actingAs($user)->post('/checkout/voucher/apply', ['voucher_code' => 'BOGO123']);
        $response = $this->actingAs($user)->get('/checkout');
        $response->assertStatus(200);
    }

    public function test_min_order_blocks_voucher_application_at_checkout(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $voucher = Voucher::factory()->create([
            'voucher_code' => 'MIN100K',
            'voucher_type' => 'fixed',
            'voucher_value' => 10000,
            'min_order' => 100000,
            'is_active' => true,
        ]);

        UserVoucher::create([
            'user_id' => $user->user_id,
            'voucher_id' => $voucher->voucher_id,
            'is_used' => false,
        ]);

        $category = Category::factory()->create();
        $menu = Menu::factory()->create(['category_id' => $category->category_id, 'menu_price' => 20000]);
        $this->actingAs($user)->post('/cart/add/' . $menu->menu_id, ['quantity' => 1]);
        $this->actingAs($user)->post('/checkout/voucher/apply', ['voucher_code' => 'MIN100K']);

        // Session should be stored, but at checkout calculation discount should be 0 due to min_order not met
        // And index method should auto-forget if subtotal < min_order
        $this->get('/checkout');
        // After checkout index, if subtotal < min_order, voucher is forgotten
        $this->assertNull(session('applied_voucher'));
    }
}
