@extends('layouts.master')

@section('title', 'Edit Product')
@section('page-title', 'Edit Product')

@section('content')
<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header">
                    <i class="bi bi-info-circle me-2"></i>Product Information
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $product->name) }}" 
                               placeholder="Enter product name"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                        <select class="form-select @error('category_id') is-invalid @enderror" 
                                id="category_id" 
                                name="category_id" 
                                required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="4"
                                  placeholder="Enter product description">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" 
                                   class="form-control @error('price') is-invalid @enderror" 
                                   id="price" 
                                   name="price" 
                                   value="{{ old('price', $product->price) }}" 
                                   step="0.01"
                                   min="0"
                                   placeholder="0.00"
                                   required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Attributes -->
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-tags me-2"></i>Product Attributes</span>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addAttribute()">
                        <i class="bi bi-plus-circle"></i> Add More
                    </button>
                </div>
                <div class="card-body">
                    <div id="attributes-container">
                        @forelse($product->attributes as $index => $attribute)
                            <div class="row mb-2 attribute-row">
                                <div class="col-md-5">
                                    <input type="text" 
                                           class="form-control" 
                                           name="attributes[{{ $index }}][key]" 
                                           value="{{ $attribute->attribute_key }}"
                                           placeholder="e.g., Size, Color">
                                </div>
                                <div class="col-md-5">
                                    <input type="text" 
                                           class="form-control" 
                                           name="attributes[{{ $index }}][value]" 
                                           value="{{ $attribute->attribute_value }}"
                                           placeholder="e.g., Large, Red">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeAttribute(this)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="row mb-2 attribute-row">
                                <div class="col-md-5">
                                    <input type="text" 
                                           class="form-control" 
                                           name="attributes[0][key]" 
                                           placeholder="e.g., Size, Color">
                                </div>
                                <div class="col-md-5">
                                    <input type="text" 
                                           class="form-control" 
                                           name="attributes[0][value]" 
                                           placeholder="e.g., Large, Red">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeAttribute(this)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    <small class="text-muted">
                        <i class="bi bi-info-circle"></i> Add custom attributes like size, color, material, etc.
                    </small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">
                    <i class="bi bi-image me-2"></i>Product Image
                </div>
                <div class="card-body">
                    @if($product->image)
                        <div class="mb-3 text-center">
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="img-fluid rounded"
                                 id="current-image"
                                 style="max-height: 200px;">
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <input type="file" 
                               class="form-control @error('image') is-invalid @enderror" 
                               id="image" 
                               name="image" 
                               accept="image/*"
                               onchange="previewImage(event)">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Max size: 2MB (jpg, jpeg, png, gif)</small>
                    </div>
                    <div id="image-preview" class="text-center" style="display: none;">
                        <img id="preview" src="" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <i class="bi bi-gear me-2"></i>Product Status
                </div>
                <div class="card-body">
                    <select class="form-select @error('status') is-invalid @enderror" 
                            name="status" 
                            required>
                        <option value="">Select Status</option>
                        <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Update Product
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
let attributeIndex = {{ $product->attributes->count() }};

function addAttribute() {
    const container = document.getElementById('attributes-container');
    const newRow = `
        <div class="row mb-2 attribute-row">
            <div class="col-md-5">
                <input type="text" 
                       class="form-control" 
                       name="attributes[${attributeIndex}][key]" 
                       placeholder="e.g., Size, Color">
            </div>
            <div class="col-md-5">
                <input type="text" 
                       class="form-control" 
                       name="attributes[${attributeIndex}][value]" 
                       placeholder="e.g., Large, Red">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeAttribute(this)">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', newRow);
    attributeIndex++;
}

function removeAttribute(button) {
    const row = button.closest('.attribute-row');
    row.remove();
}

function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
        const preview = document.getElementById('preview');
        const previewContainer = document.getElementById('image-preview');
        const currentImage = document.getElementById('current-image');
        
        preview.src = reader.result;
        previewContainer.style.display = 'block';
        
        if (currentImage) {
            currentImage.style.display = 'none';
        }
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>
@endpush
