<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $activeProducts = Product::where('status', 'active')->count();
        $totalCategories = Category::count();
        $totalValue = Product::where('status', 'active')->sum('price');
        
        $recentCategories = Category::latest()->take(5)->get();
        $recentProducts = Product::with('category')->latest()->take(5)->get();
        
        return view('admin.dashboard', compact(
            'totalProducts',
            'activeProducts',
            'totalCategories',
            'totalValue',
            'recentCategories',
            'recentProducts'
        ));
    }
}
