@extends('layouts.master')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Stat Cards -->
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="bi bi-box-seam"></i>
            </div>
            <div class="stat-details">
                <h3>{{ $totalProducts }}</h3>
                <p>Total Products</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-details">
                <h3>{{ $activeProducts }}</h3>
                <p>Active Products</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="bi bi-folder"></i>
            </div>
            <div class="stat-details">
                <h3>{{ $totalCategories }}</h3>
                <p>Categories</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon info">
                <i class="bi bi-graph-up"></i>
            </div>
            <div class="stat-details">
                <h3>₹{{ number_format($totalValue, 2) }}</h3>
                <p>Total Value</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-folder me-2"></i>Recent Categories
            </div>
            <div class="card-body">
                @if($recentCategories->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentCategories as $category)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $category->name }}</span>
                                <span class="badge bg-{{ $category->status == 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($category->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No categories found.</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-box-seam me-2"></i>Recent Products
            </div>
            <div class="card-body">
                @if($recentProducts->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentProducts as $product)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $product->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $product->category->name }}</small>
                                </div>
                                <span class="badge bg-primary">₹{{ number_format($product->price, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No products found.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
