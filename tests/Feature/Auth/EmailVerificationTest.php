<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verification_screen_requires_session(): void
    {
        // Without otp_email in session, should redirect to register
        $response = $this->get('/verify-otp');
        $response->assertRedirect(route('register', absolute: false));
    }

    public function test_otp_verification_flow(): void
    {
        \Illuminate\Support\Facades\Mail::fake();

        // Register creates user with otp
        $this->post('/register', [
            'name' => 'OTP User',
            'email' => 'otp@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::where('email', 'otp@example.com')->first();
        $this->assertNotNull($user->otp);

        // OTP screen can be rendered when session exists
        $response = $this->get('/verify-otp');
        $response->assertStatus(200);

        // Verify with correct OTP
        $response = $this->post('/verify-otp', ['otp' => $user->otp]);
        $response->assertRedirect(route('profile.index', absolute: false));
        $this->assertAuthenticated();

        $user->refresh();
        $this->assertNotNull($user->email_verified_at);
        $this->assertNull($user->otp);
    }

    public function test_otp_verification_fails_with_wrong_code(): void
    {
        \Illuminate\Support\Facades\Mail::fake();

        $this->post('/register', [
            'name' => 'OTP Fail User',
            'email' => 'otpfail@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response = $this->post('/verify-otp', ['otp' => '000000']);
        $response->assertSessionHasErrors(['otp']);
        $this->assertGuest();
    }

    public function test_resend_otp_generates_new_code(): void
    {
        \Illuminate\Support\Facades\Mail::fake();

        $this->post('/register', [
            'name' => 'Resend User',
            'email' => 'resend@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::where('email', 'resend@example.com')->first();
        $oldOtp = $user->otp;

        $response = $this->post('/resend-otp');
        $response->assertSessionHas('resend_success');

        $user->refresh();
        $this->assertNotEquals($oldOtp, $user->otp);
    }
}
