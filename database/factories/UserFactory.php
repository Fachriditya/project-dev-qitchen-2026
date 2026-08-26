<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => 'customer',
            'phone' => '08' . fake()->numerify('##########'),
            'referral_code' => null,
            'referred_by' => null,
            'otp' => null,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => ['role' => 'admin']);
    }

    public function cashier(): static
    {
        return $this->state(fn (array $attributes) => ['role' => 'cashier']);
    }

    public function kitchen(): static
    {
        return $this->state(fn (array $attributes) => ['role' => 'kitchen']);
    }

    public function courier(): static
    {
        return $this->state(fn (array $attributes) => ['role' => 'courier']);
    }

    public function customer(): static
    {
        return $this->state(fn (array $attributes) => ['role' => 'customer']);
    }
}
