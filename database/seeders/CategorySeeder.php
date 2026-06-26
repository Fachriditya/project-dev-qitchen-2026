<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Indonesian','Japanese', 'Korean', 'Arabic', 'Italian', 
            'French', 'American', 'Coffee', 'Non-Coffee', 'Tea', 
            'Milkshake', 'Juice'
        ];

        foreach ($categories as $categoryName) {
            Category::create([
                'category_name' => $categoryName,
                'category_slug' => Str::slug($categoryName),
            ]);
        }
    }
}