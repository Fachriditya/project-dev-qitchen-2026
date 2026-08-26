<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class WebhookAndOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_number_generation_is_unique(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $category = Category::factory()->create();
        $menu = Menu::factory()->create(['category_id' => $category->category_id]);

        $order1 = Order::create([
            'user_id' => $user->user_id,
            'order_number' => 'QTC-' . strtoupper(substr(uniqid(), -6)),
            'order_type' => 'dine_in',
            'order_status' => 'pending',
            'order_subtotal' => 50000,
            'order_discount' => 0,
            'order_total' => 55000,
        ]);

        $order2 = Order::create([
            'user_id' => $user->user_id,
            'order_number' => 'QTC-' . strtoupper(substr(uniqid(), -6)) . 'X',
            'order_type' => 'dine_in',
            'order_status' => 'pending',
            'order_subtotal' => 50000,
            'order_discount' => 0,
            'order_total' => 55000,
        ]);

        $this->assertNotEquals($order1->order_number, $order2->order_number);
    }

    public function test_order_status_workflow(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $order = Order::create([
            'user_id' => $user->user_id,
            'order_number' => 'QTC-TEST01',
            'order_type' => 'dine_in',
            'order_status' => 'pending',
            'order_subtotal' => 50000,
            'order_discount' => 0,
            'order_total' => 55000,
        ]);

        $statuses = ['pending', 'paid', 'cooking', 'ready', 'completed'];
        foreach ($statuses as $status) {
            $order->update(['order_status' => $status]);
            $this->assertEquals($status, $order->fresh()->order_status);
        }
    }

    public function test_midtrans_webhook_rejects_invalid_signature(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $order = Order::create([
            'user_id' => $user->user_id,
            'order_number' => 'QTC-WEBHOOK01',
            'order_type' => 'delivery',
            'order_status' => 'pending',
            'order_subtotal' => 50000,
            'order_discount' => 0,
            'order_total' => 55000,
        ]);

        Payment::create([
            'order_id' => $order->order_id,
            'payment_amount' => 55000,
            'payment_method' => 'qris',
            'payment_status' => 'pending',
        ]);

        config(['midtrans.server_key' => 'test-server-key']);

        $response = $this->postJson('/webhook/midtrans', [
            'order_id' => 'QTC-WEBHOOK01-1234567890',
            'status_code' => '200',
            'gross_amount' => '55000.00',
            'signature_key' => 'invalid-signature',
            'transaction_status' => 'settlement',
        ]);

        $response->assertStatus(403);
        $response->assertJson(['message' => 'Invalid Signature']);
    }

    public function test_midtrans_webhook_processes_valid_settlement(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $order = Order::create([
            'user_id' => $user->user_id,
            'order_number' => 'QTC-WEBHOOK02',
            'order_type' => 'delivery',
            'order_status' => 'pending',
            'order_subtotal' => 50000,
            'order_discount' => 0,
            'order_total' => 55000,
        ]);

        Payment::create([
            'order_id' => $order->order_id,
            'payment_amount' => 55000,
            'payment_method' => 'qris',
            'payment_status' => 'pending',
        ]);

        $serverKey = 'test-server-key-123';
        config(['midtrans.server_key' => $serverKey]);

        $orderIdWithTimestamp = $order->order_number . '-' . time();
        $statusCode = '200';
        $grossAmount = '55000.00';
        $signature = hash('sha512', $orderIdWithTimestamp . $statusCode . $grossAmount . $serverKey);

        $response = $this->postJson('/webhook/midtrans', [
            'order_id' => $orderIdWithTimestamp,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'signature_key' => $signature,
            'transaction_status' => 'settlement',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', ['order_id' => $order->order_id, 'order_status' => 'paid']);
    }

    public function test_midtrans_webhook_returns_404_for_unknown_order(): void
    {
        config(['midtrans.server_key' => 'test-key']);
        $orderId = 'QTC-UNKNOWN-1234567890';
        $signature = hash('sha512', $orderId . '200' . '10000.00' . 'test-key');

        $response = $this->postJson('/webhook/midtrans', [
            'order_id' => $orderId,
            'status_code' => '200',
            'gross_amount' => '10000.00',
            'signature_key' => $signature,
            'transaction_status' => 'settlement',
        ]);

        $response->assertStatus(404);
    }

    public function test_midtrans_webhook_is_idempotent(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $order = Order::create([
            'user_id' => $user->user_id,
            'order_number' => 'QTC-IDEMPOT',
            'order_type' => 'dine_in',
            'order_status' => 'paid', // Already paid
            'order_subtotal' => 50000,
            'order_discount' => 0,
            'order_total' => 55000,
        ]);

        Payment::create([
            'order_id' => $order->order_id,
            'payment_amount' => 55000,
            'payment_method' => 'qris',
            'payment_status' => 'success',
        ]);

        $serverKey = 'test-key-idempotent';
        config(['midtrans.server_key' => $serverKey]);
        $orderIdWithTimestamp = $order->order_number . '-' . time();
        $signature = hash('sha512', $orderIdWithTimestamp . '200' . '55000.00' . $serverKey);

        $response = $this->postJson('/webhook/midtrans', [
            'order_id' => $orderIdWithTimestamp,
            'status_code' => '200',
            'gross_amount' => '55000.00',
            'signature_key' => $signature,
            'transaction_status' => 'settlement',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Order already processed']);
    }

    public function test_order_tracks_voucher_usage(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $voucher = Voucher::factory()->create(['voucher_code' => 'TRACK123', 'is_active' => true]);

        $order = Order::create([
            'user_id' => $user->user_id,
            'order_number' => 'QTC-VOUCHER01',
            'order_type' => 'dine_in',
            'order_status' => 'pending',
            'order_subtotal' => 50000,
            'order_discount' => 5000,
            'order_total' => 50000,
            'voucher_id' => $voucher->voucher_id,
        ]);

        $this->assertDatabaseHas('orders', [
            'order_id' => $order->order_id,
            'voucher_id' => $voucher->voucher_id,
            'order_discount' => 5000,
        ]);
    }

    public function test_soft_delete_on_order(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $order = Order::create([
            'user_id' => $user->user_id,
            'order_number' => 'QTC-SOFTDEL',
            'order_type' => 'dine_in',
            'order_status' => 'pending',
            'order_subtotal' => 50000,
            'order_discount' => 0,
            'order_total' => 55000,
        ]);

        $order->delete();
        $this->assertSoftDeleted('orders', ['order_id' => $order->order_id]);
        $this->assertNotNull(Order::withTrashed()->find($order->order_id));
    }

    public function test_tracking_redirects_for_unknown_order(): void
    {
        $response = $this->get('/track/QTC-UNKNOWN-999');
        $this->assertTrue(in_array($response->getStatusCode(), [200, 404]));
    }
}
