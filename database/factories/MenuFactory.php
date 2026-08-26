<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Menu>
 */
class MenuFactory extends Factory
{
    protected $model = Menu::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);
        return [
            'category_id' => Category::factory(),
            'menu_name' => ucwords($name),
            'menu_slug' => Str::slug($name) . '-' . fake()->unique()->randomNumber(4),
            'menu_description' => fake()->sentence(10),
            'menu_price' => fake()->numberBetween(15000, 75000),
            'menu_image' => 'menus/test.jpg',
            'is_available' => true,
        ];
    }

    public function unavailable(): static
    {
        return $this->state(fn (array $attributes) => ['is_available' => false]);
    }
}
