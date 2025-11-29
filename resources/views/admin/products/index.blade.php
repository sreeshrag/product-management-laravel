@extends('layouts.master')

@section('title', 'Products')
@section('page-title', 'Products')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-box-seam me-2"></i>All Products</span>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> Add New Product
        </a>
    </div>
    <div class="card-body">
        @if($products->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="8%">Image</th>
                            <th width="25%">Name</th>
                            <th width="15%">Category</th>
                            <th width="12%">Price</th>
                            <th width="10%">Status</th>
                            <th width="12%">Created</th>
                            <th width="13%" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $index => $product)
                            <tr>
                                <td>{{ $products->firstItem() + $index }}</td>
                                <td>
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" 
                                             alt="{{ $product->name }}" 
                                             class="img-thumbnail"
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $product->name }}</strong>
                                    @if($product->attributes->count() > 0)
                                        <br>
                                        <small class="text-muted">
                                            <i class="bi bi-tags"></i> {{ $product->attributes->count() }} attributes
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-warning text-dark">{{ $product->category->name }}</span>
                                </td>
                                <td>
                                    <strong>${{ number_format($product->price, 2) }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $product->status == 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($product->status) }}
                                    </span>
                                </td>
                                <td>{{ $product->created_at->format('M d, Y') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.products.show', $product) }}" 
                                       class="btn btn-sm btn-outline-info" 
                                       title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteProduct({{ $product->id }})"
                                            title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <form id="delete-form-{{ $product->id }}" 
                                          action="{{ route('admin.products.destroy', $product) }}" 
                                          method="POST" 
                                          class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-box-seam" style="font-size: 48px; color: #ccc;"></i>
                <p class="text-muted mt-3">No products found. Create your first product!</p>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Product
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteProduct(id) {
    if (confirm('Are you sure you want to delete this product?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush
