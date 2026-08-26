<?php

namespace Database\Factories;

use App\Models\Voucher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * @extends Factory<Voucher>
 */
class VoucherFactory extends Factory
{
    protected $model = Voucher::class;

    public function definition(): array
    {
        return [
            'voucher_name' => fake()->words(3, true),
            'voucher_code' => strtoupper(Str::random(8)) . fake()->unique()->randomNumber(3),
            'voucher_description' => fake()->sentence(),
            'voucher_type' => fake()->randomElement(['percentage', 'fixed', 'bogo', 'free_delivery']),
            'voucher_value' => fake()->numberBetween(5000, 50000),
            'min_order' => 0,
            'max_discount' => null,
            'quota' => 100,
            'issued_count' => 0,
            'expiration_date' => Carbon::now()->addMonths(6),
            'is_active' => true,
        ];
    }

    public function percentage(): static
    {
        return $this->state(fn (array $attributes) => [
            'voucher_type' => 'percentage',
            'voucher_value' => 10,
            'max_discount' => 20000,
            'min_order' => 50000,
        ]);
    }

    public function fixed(): static
    {
        return $this->state(fn (array $attributes) => [
            'voucher_type' => 'fixed',
            'voucher_value' => 15000,
            'min_order' => 30000,
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expiration_date' => Carbon::now()->subDay(),
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => ['is_active' => false]);
    }
}
