<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Display public product listing
    public function index(Request $request)
    {
        $categories = Category::where('status', 'active')
            ->withCount(['products' => function ($query) {
                $query->where('status', 'active');
            }])
            ->having('products_count', '>', 0)
            ->orderBy('name')
            ->get();

        // Get min and max price for filter
        $priceRange = Product::where('status', 'active')
            ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
            ->first();

        // Get total active products count
        $totalProducts = Product::where('status', 'active')->count();

        return view('products.index', compact('categories', 'priceRange', 'totalProducts'));
    }

    // AJAX endpoint for filtering products
    public function filter(Request $request)
    {
        $query = Product::with(['category', 'attributes'])->where('status', 'active');

        // Category filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Keyword search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->latest()->get();

        return response()->json([
            'success' => true,
            'products' => $products,
            'count' => $products->count()
        ]);
    }
}
