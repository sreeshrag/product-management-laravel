<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Create a user to act as admin
        $this->user = User::factory()->create();
        // Create a category
        $this->category = Category::create(['name' => 'Test Category ' . time(), 'status' => 'active']);
    }

    protected function tearDown(): void
    {
        // Cleanup
        if (isset($this->category)) $this->category->delete();
        if (isset($this->user)) $this->user->delete();
        parent::tearDown();
    }

    public function test_can_create_product_with_attributes()
    {
        $productData = [
            'category_id' => $this->category->id,
            'name' => 'Feature Test Product',
            'description' => 'Description',
            'price' => 100,
            'status' => 'active',
            'attributes' => [
                [
                    'key' => 'TestKey1',
                    'value' => 'TestValue1'
                ],
                [
                    'key' => 'TestKey2',
                    'value' => 'TestValue2'
                ]
            ]
        ];

        // Simulate the request
        $response = $this->actingAs($this->user)
                         ->post(route('admin.products.store'), $productData);

        // Check for validation errors
        if ($response->getSession()->has('errors')) {
            dump($response->getSession()->get('errors')->all());
        }

        $response->assertStatus(302); // Redirect after success
        $response->assertSessionHasNoErrors();

        // Verify Database
        $this->assertDatabaseHas('products', ['name' => 'Feature Test Product']);
        $product = Product::where('name', 'Feature Test Product')->first();
        
        $this->assertDatabaseHas('product_attributes', [
            'product_id' => $product->id,
            'attribute_key' => 'TestKey1',
            'attribute_value' => 'TestValue1'
        ]);
        
        // Cleanup product
        $product->delete();
    }
}
