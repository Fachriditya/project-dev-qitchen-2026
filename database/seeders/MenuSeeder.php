<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $menusByCategory = [
            'Indonesian' => ['Nasi Goreng Special', 'Beef Rendang', 'Chicken Satay', 'Opor Ayam', 'Ayam Betutu Bali', 'Soto Ayam', 'Bakso Urat', 'Gado-Gado', 'Rujak Cingur', 'Rawon'],
            'Japanese' => ['Salmon Sushi', 'Chicken Ramen', 'Beef Udon', 'Shrimp Tempura', 'Takoyaki', 'Okonomiyaki'],
            'Korean' => ['Kimchi', 'Bibimbap', 'Tteokbokki', 'Jajangmyeon', 'Bulgogi', 'Kimbap'],
            'Arabic' => ['Chicken Mandi', 'Nasi Kebuli', 'Chicken Biryani', 'Chicken Kabsa', 'Bukhari Rice'],
            'Italian' => ['Pizza Margherita', 'Spaghetti Carbonara', 'Lasagna', 'Mushroom Risotto'],
            'French' => ['Butter Croissant', 'French Baguette'],
            'American' => ['Classic Hamburger', 'Beef Hot Dog', 'Crispy Fried Chicken', 'French Fries', 'Chicken Shawarma', 'Beef Burrito', 'Beef Taco'],
            'Coffee' => ['Espresso', 'Americano', 'Cappuccino', 'Caffè Latte', 'Caramel Macchiato', 'Cold Brew', 'Mochaccino', 'Affogato'],
            'Non-Coffee' => ['Signature Matcha', 'Signature Boba', 'Signature Yogurt'],
            'Tea' => ['Original Tea', 'Lemon Tea', 'Peach Tea', 'Grape Tea', 'Apple Tea', 'Lychee Tea', 'Strawberry Tea'],
            'Milkshake' => ['Chocolate Milkshake', 'Strawberry Milkshake', 'Vanilla Milkshake', 'Oreo Milkshake', 'Cookies & Cream Milkshake', 'Matcha Milkshake', 'Taro Milkshake', 'Banana Milkshake', 'Durian Milkshake'],
            'Juice' => ['Avocado Juice', 'Guava Juice', 'Strawberry Juice', 'Mango Juice', 'Watermelon Juice', 'Orange Juice', 'Pineapple Juice', 'Apple Juice', 'Melon Juice', 'Carrot Juice', 'Soursop Juice', 'Jackfruit Juice', 'Durian Juice'],
        ];

        $priceRanges = [
            'Indonesian' => [25000, 55000],
            'Japanese' => [35000, 70000],
            'Korean' => [35000, 75000],
            'Arabic' => [60000, 90000],
            'Italian' => [50000, 80000],
            'French' => [50000, 80000],
            'American' => [25000, 55000],
            'Coffee' => [30000, 50000],
            'Non-Coffee' => [30000, 50000],
            'Tea' => [12000, 25000],
            'Milkshake' => [22000, 38000],
            'Juice' => [18000, 35000],
        ];

        foreach ($menusByCategory as $categoryName => $menuList) {
            $category = Category::where('category_name', $categoryName)->first();
            
            if (!$category) continue; 

            foreach ($menuList as $menuName) {
                $min = $priceRanges[$categoryName][0];
                $max = $priceRanges[$categoryName][1];
                $price = round($faker->numberBetween($min, $max) / 1000) * 1000;

                Menu::create([
                    'category_id' => $category->category_id,
                    'menu_name' => $menuName,
                    'menu_slug' => Str::slug($menuName),
                    'menu_description' => $faker->sentence(10),
                    'menu_price' => $price,
                    'menu_image' => 'https://placehold.co/600x400/png?text=' . urlencode($menuName),
                    'is_available' => true,
                ]);
            }
        }
    }
}