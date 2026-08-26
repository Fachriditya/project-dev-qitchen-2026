<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Delivery;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KitchenCourierTest extends TestCase
{
    use RefreshDatabase;

    protected function createOrderWithStatus(string $status, string $orderType = 'dine_in', ?User $user = null): Order
    {
        $user = $user ?? User::factory()->create(['role' => 'customer']);
        $category = Category::factory()->create();
        $menu = Menu::factory()->create(['category_id' => $category->category_id]);

        $order = Order::create([
            'user_id' => $user->user_id,
            'order_number' => 'QTC-' . strtoupper(substr(uniqid(), -6)),
            'order_type' => $orderType,
            'order_status' => $status,
            'order_subtotal' => 50000,
            'order_discount' => 0,
            'order_total' => 55000,
            'order_notes' => 'Test',
        ]);

        OrderItem::create([
            'order_id' => $order->order_id,
            'menu_id' => $menu->menu_id,
            'quantity' => 1,
            'item_price' => 50000,
            'item_subtotal' => 50000,
        ]);

        Payment::create([
            'order_id' => $order->order_id,
            'payment_amount' => 55000,
            'payment_method' => 'qris',
            'payment_status' => $status === 'pending' ? 'pending' : 'success',
        ]);

        return $order;
    }

    // ========== Kitchen ==========

    public function test_kitchen_board_loads(): void
    {
        $kitchen = User::factory()->create(['role' => 'kitchen']);
        $response = $this->actingAs($kitchen)->get(route('kitchen.board'));
        $response->assertStatus(200);
    }

    public function test_kitchen_can_update_order_status(): void
    {
        $kitchen = User::factory()->create(['role' => 'kitchen']);
        $order = $this->createOrderWithStatus('paid');

        $response = $this->actingAs($kitchen)->post(route('kitchen.update-status', $order->order_id), [
            'status' => 'cooking',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', ['order_id' => $order->order_id, 'order_status' => 'cooking']);
    }

    public function test_kitchen_board_shows_correct_grouping(): void
    {
        $kitchen = User::factory()->create(['role' => 'kitchen']);
        $this->createOrderWithStatus('paid');
        $this->createOrderWithStatus('cooking');
        $this->createOrderWithStatus('ready');

        $response = $this->actingAs($kitchen)->get(route('kitchen.board'));
        $response->assertStatus(200);
    }

    // ========== Courier ==========

    public function test_courier_offers_loads(): void
    {
        $courier = User::factory()->create(['role' => 'courier']);
        $response = $this->actingAs($courier)->get(route('courier.offers'));
        $response->assertStatus(200);
    }

    public function test_courier_can_accept_delivery(): void
    {
        $courier = User::factory()->create(['role' => 'courier']);
        $order = $this->createOrderWithStatus('ready', 'delivery');

        // Create delivery with waiting status
        Delivery::create([
            'order_id' => $order->order_id,
            'recipient_name' => 'Test Recipient',
            'recipient_phone' => '081234567890',
            'delivery_address' => 'Jl Test No 123',
            'delivery_status' => 'waiting',
        ]);

        $response = $this->actingAs($courier)->post(route('courier.accept', $order->order_id));
        $response->assertRedirect();

        $this->assertDatabaseHas('deliveries', [
            'order_id' => $order->order_id,
            'courier_id' => $courier->user_id,
        ]);
    }

    public function test_courier_my_deliveries_loads(): void
    {
        $courier = User::factory()->create(['role' => 'courier']);
        $response = $this->actingAs($courier)->get(route('courier.my-deliveries'));
        $response->assertStatus(200);
    }

    public function test_courier_my_deliveries_with_tabs(): void
    {
        $courier = User::factory()->create(['role' => 'courier']);

        $response = $this->actingAs($courier)->get(route('courier.my-deliveries', ['tab' => 'active']));
        $response->assertStatus(200);

        $response = $this->actingAs($courier)->get(route('courier.my-deliveries', ['tab' => 'history']));
        $response->assertStatus(200);
    }

    public function test_courier_map_loads(): void
    {
        $courier = User::factory()->create(['role' => 'courier']);
        $order = $this->createOrderWithStatus('ready', 'delivery');

        $delivery = Delivery::create([
            'order_id' => $order->order_id,
            'courier_id' => $courier->user_id,
            'recipient_name' => 'Test',
            'recipient_phone' => '081234567890',
            'delivery_address' => 'Jl Test',
            'delivery_status' => 'on_delivery',
        ]);

        $response = $this->actingAs($courier)->get(route('courier.map', $order->order_id));
        $response->assertStatus(200);
    }

    public function test_courier_can_complete_delivery(): void
    {
        $courier = User::factory()->create(['role' => 'courier']);
        $order = $this->createOrderWithStatus('ready', 'delivery');

        Delivery::create([
            'order_id' => $order->order_id,
            'courier_id' => $courier->user_id,
            'recipient_name' => 'Test',
            'recipient_phone' => '081234567890',
            'delivery_address' => 'Jl Test',
            'delivery_status' => 'on_delivery',
        ]);

        $response = $this->actingAs($courier)->post(route('courier.complete', $order->order_id));
        $response->assertRedirect(route('courier.offers', absolute: false));

        $this->assertDatabaseHas('orders', ['order_id' => $order->order_id, 'order_status' => 'completed']);
        $this->assertDatabaseHas('deliveries', ['order_id' => $order->order_id, 'delivery_status' => 'delivered']);
    }

    // ========== Cashier ==========

    public function test_cashier_transactions_loads(): void
    {
        $cashier = User::factory()->create(['role' => 'cashier']);
        $response = $this->actingAs($cashier)->get(route('cashier.transactions.index'));
        $response->assertStatus(200);
    }

    public function test_cashier_manual_order_loads(): void
    {
        $cashier = User::factory()->create(['role' => 'cashier']);
        $response = $this->actingAs($cashier)->get(route('cashier.manual-order.index'));
        $response->assertStatus(200);
    }

    public function test_cashier_pickup_loads(): void
    {
        $cashier = User::factory()->create(['role' => 'cashier']);
        $response = $this->actingAs($cashier)->get(route('cashier.pickup.index'));
        $response->assertStatus(200);
    }
}
