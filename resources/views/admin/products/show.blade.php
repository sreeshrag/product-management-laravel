@extends('layouts.master')

@section('title', 'View Product')
@section('page-title', 'Product Details')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-box-seam me-2"></i>Product Information</span>
                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-pencil"></i> Edit Product
                </a>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="30%">Product Name:</th>
                        <td>{{ $product->name }}</td>
                    </tr>
                    <tr>
                        <th>Category:</th>
                        <td><span class="badge bg-warning text-dark">{{ $product->category->name }}</span></td>
                    </tr>
                    <tr>
                        <th>Price:</th>
                        <td><strong class="text-success">${{ number_format($product->price, 2) }}</strong></td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            <span class="badge bg-{{ $product->status == 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($product->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Description:</th>
                        <td>{{ $product->description ?? 'No description provided' }}</td>
                    </tr>
                    <tr>
                        <th>Created:</th>
                        <td>{{ $product->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                    <tr>
                        <th>Last Updated:</th>
                        <td>{{ $product->updated_at->format('M d, Y h:i A') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @if($product->attributes->count() > 0)
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-tags me-2"></i>Product Attributes
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="40%">Attribute</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($product->attributes as $attribute)
                                <tr>
                                    <td><strong>{{ $attribute->attribute_key }}</strong></td>
                                    <td>{{ $attribute->attribute_value }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-image me-2"></i>Product Image
            </div>
            <div class="card-body text-center">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" 
                         alt="{{ $product->name }}" 
                         class="img-fluid rounded">
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 300px;">
                        <div>
                            <i class="bi bi-image" style="font-size: 48px; color: #ccc;"></i>
                            <p class="text-muted mt-2">No image available</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="d-grid gap-2 mt-3">
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Products
            </a>
        </div>
    </div>
</div>
@endsection
