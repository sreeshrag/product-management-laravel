<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductAttribute;

class TestProductAttributes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:product-attributes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test product attribute creation and update';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Test 1: Create a product with attributes
        $this->info('Test 1: Creating a product with attributes...');

        $category = Category::first();
        if (!$category) {
            $this->error('No categories found. Please create a category first.');
            return 1;
        }

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Test Product ' . time(),
            'description' => 'Test description',
            'price' => 99.99,
            'status' => 'active',
        ]);

        $this->info("Product created with ID: {$product->id}");

        // Simulate form data
        $attributes = [
            ['key' => 'Color', 'value' => 'Red'],
            ['key' => 'Size', 'value' => 'Large'],
            ['key' => 'Material', 'value' => 'Cotton'],
        ];

        foreach ($attributes as $attribute) {
            if (isset($attribute['key']) && isset($attribute['value']) && 
                !empty(trim($attribute['key'])) && !empty(trim($attribute['value']))) {
                $product->attributes()->create([
                    'attribute_key' => trim($attribute['key']),
                    'attribute_value' => trim($attribute['value']),
                ]);
            }
        }

        $this->info('Attributes created.');

        // Verify attributes were saved
        $savedAttributes = $product->attributes()->get();
        $this->info("Number of attributes saved: " . $savedAttributes->count());

        foreach ($savedAttributes as $attr) {
            $this->line("  - {$attr->attribute_key}: {$attr->attribute_value}");
        }

        // Test 2: Update attributes
        $this->info("\nTest 2: Updating product attributes...");

        // Delete old attributes
        $product->attributes()->delete();

        // Add new attributes
        $newAttributes = [
            ['key' => 'Color', 'value' => 'Blue'],
            ['key' => 'Size', 'value' => 'Medium'],
        ];

        foreach ($newAttributes as $attribute) {
            if (isset($attribute['key']) && isset($attribute['value']) && 
                !empty(trim($attribute['key'])) && !empty(trim($attribute['value']))) {
                $product->attributes()->create([
                    'attribute_key' => trim($attribute['key']),
                    'attribute_value' => trim($attribute['value']),
                ]);
            }
        }

        // Verify updated attributes
        $updatedAttributes = $product->fresh()->attributes;
        $this->info("Number of attributes after update: " . $updatedAttributes->count());

        foreach ($updatedAttributes as $attr) {
            $this->line("  - {$attr->attribute_key}: {$attr->attribute_value}");
        }

        $this->info("\nTest completed successfully!");
        
        return 0;
    }
}
