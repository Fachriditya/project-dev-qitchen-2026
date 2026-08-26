<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');
        $response->assertStatus(200);
    }

    public function test_reset_password_link_can_be_requested(): void
    {
        Mail::fake();
        $user = User::factory()->create();

        $response = $this->post('/forgot-password', ['email' => $user->email]);

        $response->assertRedirect(route('password.verify', absolute: false));
        $user->refresh();
        $this->assertNotNull($user->otp);
    }

    public function test_reset_password_link_fails_for_unknown_email(): void
    {
        $response = $this->post('/forgot-password', ['email' => 'unknown@example.com']);
        $response->assertSessionHasErrors(['email']);
    }

    public function test_otp_verification_for_password_reset(): void
    {
        Mail::fake();
        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);
        $user->refresh();

        // Wrong OTP should fail
        $response = $this->post('/forgot-password/verify', ['otp' => '000000']);
        $response->assertSessionHasErrors(['otp']);

        // Correct OTP should pass
        $response = $this->post('/forgot-password/verify', ['otp' => $user->otp]);
        $response->assertRedirect(route('password.reset', absolute: false));
    }

    public function test_password_can_be_reset_via_otp_flow(): void
    {
        Mail::fake();
        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);
        $user->refresh();
        $this->post('/forgot-password/verify', ['otp' => $user->otp]);

        $response = $this->post('/reset-password', [
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ]);

        $response->assertRedirect(route('login', absolute: false));
        $user->refresh();
        $this->assertNull($user->otp);
    }

    public function test_reset_password_requires_confirmation(): void
    {
        Mail::fake();
        $user = User::factory()->create();
        $this->post('/forgot-password', ['email' => $user->email]);
        $user->refresh();
        $this->post('/forgot-password/verify', ['otp' => $user->otp]);

        $response = $this->post('/reset-password', [
            'password' => 'new-password-123',
            'password_confirmation' => 'different',
        ]);

        $response->assertSessionHasErrors(['password']);
    }
}
