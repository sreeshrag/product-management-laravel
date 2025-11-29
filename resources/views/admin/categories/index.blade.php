@extends('layouts.master')

@section('title', 'Categories')
@section('page-title', 'Categories')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-folder me-2"></i>All Categories</span>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> Add New Category
        </a>
    </div>
    <div class="card-body">
        @if($categories->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="35%">Name</th>
                            <th width="15%">Products</th>
                            <th width="15%">Status</th>
                            <th width="15%">Created</th>
                            <th width="15%" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $index => $category)
                            <tr>
                                <td>{{ $categories->firstItem() + $index }}</td>
                                <td><strong>{{ $category->name }}</strong></td>
                                <td>
                                    <span class="badge bg-info">{{ $category->products_count }} Products</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $category->status == 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($category->status) }}
                                    </span>
                                </td>
                                <td>{{ $category->created_at->format('M d, Y') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.categories.edit', $category) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteCategory({{ $category->id }})"
                                            title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <form id="delete-form-{{ $category->id }}" 
                                          action="{{ route('admin.categories.destroy', $category) }}" 
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
                {{ $categories->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-folder-x" style="font-size: 48px; color: #ccc;"></i>
                <p class="text-muted mt-3">No categories found. Create your first category!</p>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Category
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteCategory(id) {
    if (confirm('Are you sure you want to delete this category?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush
