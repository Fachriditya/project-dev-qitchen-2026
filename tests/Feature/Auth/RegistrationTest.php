<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        Mail::fake();

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        // App redirects to OTP verification, not dashboard
        $response->assertRedirect(route('otp.show', absolute: false));
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
        // OTP mail is sent
        $this->assertDatabaseHas('users', ['email' => 'test@example.com', 'role' => 'customer']);
    }

    public function test_registration_with_referral_code(): void
    {
        Mail::fake();

        $referrer = \App\Models\User::factory()->create(['role' => 'customer']);
        // Ensure referrer has referral_code (generated via booted)
        $referrer->refresh();

        $response = $this->post('/register', [
            'name' => 'Referred User',
            'email' => 'referred@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'referral_code' => $referrer->referral_code,
        ]);

        $response->assertRedirect(route('otp.show', absolute: false));
        $this->assertDatabaseHas('users', [
            'email' => 'referred@example.com',
            'referred_by' => $referrer->user_id,
        ]);
    }

    public function test_registration_requires_valid_data(): void
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'not-an-email',
            'password' => 'short',
            'password_confirmation' => 'mismatch',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
    }
}
