<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/profile/update', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '081234567890',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $user->refresh();
        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
    }

    public function test_profile_update_requires_name_and_email(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/profile/update', [
            'name' => '',
            'email' => 'not-an-email',
        ]);

        $response->assertSessionHasErrors(['name', 'email']);
    }

    public function test_password_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/profile/password', [
            'current_password' => 'password',
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('new-password-123', $user->refresh()->password));
    }

    public function test_password_update_requires_correct_current_password(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/profile/password', [
            'current_password' => 'wrong-password',
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ]);

        $response->assertSessionHasErrors(['current_password']);
    }

    public function test_guest_cannot_access_profile(): void
    {
        $response = $this->get('/profile');
        $response->assertRedirect(route('login', absolute: false));
    }

    public function test_profile_history_page(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/profile/history');
        $response->assertOk();
    }

    public function test_profile_vouchers_page(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/profile/vouchers');
        $response->assertOk();
    }

    public function test_profile_referral_page(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/profile/referral');
        $response->assertOk();
    }
}
