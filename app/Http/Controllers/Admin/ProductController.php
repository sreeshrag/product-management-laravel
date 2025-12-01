<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('status', 'active')->orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        dd($request->input('attributes'));
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
            'attributes' => 'nullable|array|max:10',
            'attributes.*.key' => 'nullable|string|max:100',
            'attributes.*.value' => 'nullable|string|max:255',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // Create product
        $product = Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imagePath,
            'status' => $request->status,
        ]);

        // Create product attributes
        $attributes = $request->input('attributes');
        
        if ($request->has('attributes') && is_array($attributes)) {
            foreach ($attributes as $attribute) {
                // Check if both key and value exist and are not empty
                if (isset($attribute['key']) && isset($attribute['value']) && 
                    !empty(trim($attribute['key'])) && !empty(trim($attribute['value']))) {
                    
                    $product->attributes()->create([
                        'attribute_key' => trim($attribute['key']),
                        'attribute_value' => trim($attribute['value']),
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully!');
    }

    public function show(Product $product)
    {
        $product->load('category', 'attributes');
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('status', 'active')->orderBy('name')->get();
        $product->load('attributes');
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
            'attributes' => 'nullable|array|max:10',
            'attributes.*.key' => 'nullable|string|max:100',
            'attributes.*.value' => 'nullable|string|max:255',
        ]);
        // dd($request->all());

        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // Update product
        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imagePath,
            'status' => $request->status,
        ]);

        // Update product attributes - delete old ones and create new ones
        $attributes = $request->input('attributes');
        
        $product->attributes()->delete();

        if ($request->has('attributes') && is_array($attributes)) {
            foreach ($attributes as $attribute) {
                // Check if both key and value exist and are not empty
                if (isset($attribute['key']) && isset($attribute['value']) && 
                    !empty(trim($attribute['key'])) && !empty(trim($attribute['value']))) {
                    
                    $product->attributes()->create([
                        'attribute_key' => trim($attribute['key']),
                        'attribute_value' => trim($attribute['value']),
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete(); // Attributes auto-delete via cascade

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }
}
