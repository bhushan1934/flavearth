@extends('admin.layouts.app')

@section('title', 'Create Product')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Create New Product</h1>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Products
    </a>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="row">
        <!-- Main Product Information -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Basic Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Product Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="slug" class="form-label">Slug (Leave empty for auto-generate)</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                   id="slug" name="slug" value="{{ old('slug') }}">
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="scientific_name" class="form-label">Scientific Name</label>
                            <input type="text" class="form-control @error('scientific_name') is-invalid @enderror" 
                                   id="scientific_name" name="scientific_name" value="{{ old('scientific_name') }}">
                            @error('scientific_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="botanical_family" class="form-label">Botanical Family</label>
                            <input type="text" class="form-control @error('botanical_family') is-invalid @enderror" 
                                   id="botanical_family" name="botanical_family" value="{{ old('botanical_family') }}">
                            @error('botanical_family')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="short_description" class="form-label">Short Description</label>
                        <textarea class="form-control @error('short_description') is-invalid @enderror" 
                                  id="short_description" name="short_description" rows="2">{{ old('short_description') }}</textarea>
                        @error('short_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description *</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="detailed_description" class="form-label">Detailed Description</label>
                        <textarea class="form-control @error('detailed_description') is-invalid @enderror" 
                                  id="detailed_description" name="detailed_description" rows="6">{{ old('detailed_description') }}</textarea>
                        @error('detailed_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Pricing Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Pricing & Inventory</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="price" class="form-label">Current Price *</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price') }}" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="original_price" class="form-label">Original Price</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" step="0.01" class="form-control @error('original_price') is-invalid @enderror" 
                                       id="original_price" name="original_price" value="{{ old('original_price') }}">
                                @error('original_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="stock_quantity" class="form-label">Stock Quantity</label>
                            <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror" 
                                   id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', 0) }}">
                            @error('stock_quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="in_stock" name="in_stock" 
                                       {{ old('in_stock', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="in_stock">
                                    In Stock
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="featured" name="featured" 
                                       {{ old('featured') ? 'checked' : '' }}>
                                <label class="form-check-label" for="featured">
                                    Featured Product
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Product Variants -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Product Variants</h5>
                    <small class="text-muted">Set stock to 0 to create a variant as out of stock</small>
                </div>
                <div class="card-body">
                    <div id="variants-container">
                        <div class="variant-row mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="variants[0][weight]" 
                                           placeholder="Weight (e.g., 250gm)">
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-text">₹</span>
                                        <input type="number" step="0.01" class="form-control" 
                                               name="variants[0][price]" placeholder="Price">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" class="form-control" name="variants[0][stock]" 
                                           placeholder="Stock" min="0">
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger btn-sm remove-variant" style="display: none;">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-secondary" id="add-variant">
                        <i class="fas fa-plus"></i> Add Variant
                    </button>
                </div>
            </div>
            
            <!-- Additional Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Additional Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="specifications" class="form-label">Specifications (One per line)</label>
                        <textarea class="form-control @error('specifications') is-invalid @enderror" 
                                  id="specifications" name="specifications" rows="4" 
                                  placeholder="Weight: 250gm&#10;Origin: Kerala, India&#10;Shelf Life: 12 months">{{ old('specifications') }}</textarea>
                        @error('specifications')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="nutritional_info" class="form-label">Nutritional Information (One per line)</label>
                        <textarea class="form-control @error('nutritional_info') is-invalid @enderror" 
                                  id="nutritional_info" name="nutritional_info" rows="4"
                                  placeholder="Calories: 100 per 100g&#10;Protein: 5g&#10;Carbs: 20g">{{ old('nutritional_info') }}</textarea>
                        @error('nutritional_info')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="ingredients" class="form-label">Ingredients (One per line)</label>
                        <textarea class="form-control @error('ingredients') is-invalid @enderror" 
                                  id="ingredients" name="ingredients" rows="3"
                                  placeholder="100% Pure Turmeric Powder&#10;No additives&#10;No preservatives">{{ old('ingredients') }}</textarea>
                        @error('ingredients')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="benefits" class="form-label">Benefits (One per line)</label>
                        <textarea class="form-control @error('benefits') is-invalid @enderror" 
                                  id="benefits" name="benefits" rows="4"
                                  placeholder="Anti-inflammatory properties&#10;Boosts immunity&#10;Rich in antioxidants">{{ old('benefits') }}</textarea>
                        @error('benefits')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="usage" class="form-label">Usage Instructions (One per line)</label>
                        <textarea class="form-control @error('usage') is-invalid @enderror" 
                                  id="usage" name="usage" rows="3"
                                  placeholder="Add 1 tsp to warm milk&#10;Use in cooking&#10;Mix with honey for paste">{{ old('usage') }}</textarea>
                        @error('usage')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="market_info" class="form-label">Market Information (One per line)</label>
                        <textarea class="form-control @error('market_info') is-invalid @enderror" 
                                  id="market_info" name="market_info" rows="3"
                                  placeholder="Premium Grade&#10;Export Quality&#10;Organic Certified">{{ old('market_info') }}</textarea>
                        @error('market_info')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Category & Tags -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Category & Tags</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category *</label>
                        <select class="form-control @error('category_id') is-invalid @enderror" 
                                id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="tags" class="form-label">Tags (comma separated)</label>
                        <input type="text" class="form-control @error('tags') is-invalid @enderror" 
                               id="tags" name="tags" value="{{ old('tags') }}"
                               placeholder="organic, premium, spice">
                        @error('tags')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Images -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Product Images</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="image" class="form-label">Main Image</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="images" class="form-label">Additional Images</label>
                        <input type="file" class="form-control @error('images.*') is-invalid @enderror" 
                               id="images" name="images[]" accept="image/*" multiple>
                        @error('images.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Badge -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Product Badge</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="badge" class="form-label">Badge Text</label>
                        <input type="text" class="form-control @error('badge') is-invalid @enderror" 
                               id="badge" name="badge" value="{{ old('badge') }}"
                               placeholder="New, Sale, Hot">
                        @error('badge')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="badge_color" class="form-label">Badge Color</label>
                        <select class="form-control @error('badge_color') is-invalid @enderror" 
                                id="badge_color" name="badge_color">
                            <option value="">Select Color</option>
                            <option value="primary" {{ old('badge_color') == 'primary' ? 'selected' : '' }}>Primary (Blue)</option>
                            <option value="success" {{ old('badge_color') == 'success' ? 'selected' : '' }}>Success (Green)</option>
                            <option value="danger" {{ old('badge_color') == 'danger' ? 'selected' : '' }}>Danger (Red)</option>
                            <option value="warning" {{ old('badge_color') == 'warning' ? 'selected' : '' }}>Warning (Yellow)</option>
                            <option value="info" {{ old('badge_color') == 'info' ? 'selected' : '' }}>Info (Light Blue)</option>
                            <option value="dark" {{ old('badge_color') == 'dark' ? 'selected' : '' }}>Dark</option>
                        </select>
                        @error('badge_color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Submit Button -->
            <div class="card">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save"></i> Create Product
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let variantIndex = 1;
    
    // Add variant
    document.getElementById('add-variant').addEventListener('click', function() {
        const container = document.getElementById('variants-container');
        const newVariant = document.createElement('div');
        newVariant.className = 'variant-row mb-3';
        newVariant.innerHTML = `
            <div class="row">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="variants[${variantIndex}][weight]" 
                           placeholder="Weight (e.g., 500gm)">
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text">₹</span>
                        <input type="number" step="0.01" class="form-control" 
                               name="variants[${variantIndex}][price]" placeholder="Price">
                    </div>
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control" name="variants[${variantIndex}][stock]" 
                           placeholder="Stock" min="0">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm remove-variant">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
        container.appendChild(newVariant);
        variantIndex++;
        
        // Show remove buttons
        document.querySelectorAll('.remove-variant').forEach(btn => {
            btn.style.display = 'block';
        });
    });
    
    // Remove variant
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-variant') || e.target.parentElement.classList.contains('remove-variant')) {
            const row = e.target.closest('.variant-row');
            row.remove();
            
            // Hide remove button if only one variant left
            const remainingVariants = document.querySelectorAll('.variant-row');
            if (remainingVariants.length === 1) {
                remainingVariants[0].querySelector('.remove-variant').style.display = 'none';
            }
        }
    });
    
    // Auto-generate slug
    document.getElementById('name').addEventListener('input', function() {
        const slug = document.getElementById('slug');
        if (slug.value === '') {
            slug.value = this.value.toLowerCase()
                .replace(/[^\w ]+/g, '')
                .replace(/ +/g, '-');
        }
    });
});
</script>
@endsection