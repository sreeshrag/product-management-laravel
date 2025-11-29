<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics', 'status' => 'active'],
            ['name' => 'Clothing', 'status' => 'active'],
            ['name' => 'Books', 'status' => 'active'],
            ['name' => 'Home & Garden', 'status' => 'active'],
            ['name' => 'Sports', 'status' => 'inactive'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
