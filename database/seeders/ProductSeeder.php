<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            $this->command->warn('No categories found. Please seed categories first.');
            return;
        }

        $products = [
            [
                'name' => 'Wireless Headphones',
                'description' => 'High-quality wireless headphones with noise cancellation',
                'price' => 99.99,
                'category_id' => 1,
                'attributes' => [
                    ['key' => 'Color', 'value' => 'Black'],
                    ['key' => 'Battery Life', 'value' => '20 hours'],
                ]
            ],
            [
                'name' => 'Smart Watch',
                'description' => 'Feature-rich smartwatch with health tracking',
                'price' => 199.99,
                'category_id' => 1,
                'attributes' => [
                    ['key' => 'Screen Size', 'value' => '1.4 inch'],
                    ['key' => 'Water Resistant', 'value' => 'Yes'],
                ]
            ],
            [
                'name' => 'Cotton T-Shirt',
                'description' => '100% cotton comfortable t-shirt',
                'price' => 19.99,
                'category_id' => 2,
                'attributes' => [
                    ['key' => 'Size', 'value' => 'Large'],
                    ['key' => 'Color', 'value' => 'Blue'],
                    ['key' => 'Material', 'value' => '100% Cotton'],
                ]
            ],
            [
                'name' => 'Programming Guide',
                'description' => 'Complete guide to modern programming',
                'price' => 39.99,
                'category_id' => 3,
                'attributes' => [
                    ['key' => 'Pages', 'value' => '500'],
                    ['key' => 'Author', 'value' => 'John Doe'],
                ]
            ],
        ];

        foreach ($products as $productData) {
            $attributes = $productData['attributes'];
            unset($productData['attributes']);
            
            $product = Product::create($productData);
            
            foreach ($attributes as $attr) {
                ProductAttribute::create([
                    'product_id' => $product->id,
                    'attribute_key' => $attr['key'],
                    'attribute_value' => $attr['value'],
                ]);
            }
        }

        $this->command->info('Products seeded successfully!');
    }
}
