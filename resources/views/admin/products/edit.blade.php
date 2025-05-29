@extends('admin.layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Edit Product</h1>
    <div>
        <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-info">
            <i class="fas fa-eye"></i> View Product
        </a>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Products
        </a>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
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
                                   id="name" name="name" value="{{ old('name', $product->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                   id="slug" name="slug" value="{{ old('slug', $product->slug) }}">
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="scientific_name" class="form-label">Scientific Name</label>
                            <input type="text" class="form-control @error('scientific_name') is-invalid @enderror" 
                                   id="scientific_name" name="scientific_name" value="{{ old('scientific_name', $product->scientific_name) }}">
                            @error('scientific_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="botanical_family" class="form-label">Botanical Family</label>
                            <input type="text" class="form-control @error('botanical_family') is-invalid @enderror" 
                                   id="botanical_family" name="botanical_family" value="{{ old('botanical_family', $product->botanical_family) }}">
                            @error('botanical_family')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="short_description" class="form-label">Short Description</label>
                        <textarea class="form-control @error('short_description') is-invalid @enderror" 
                                  id="short_description" name="short_description" rows="2">{{ old('short_description', $product->short_description) }}</textarea>
                        @error('short_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description *</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4" required>{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="detailed_description" class="form-label">Detailed Description</label>
                        <textarea class="form-control @error('detailed_description') is-invalid @enderror" 
                                  id="detailed_description" name="detailed_description" rows="6">{{ old('detailed_description', $product->detailed_description) }}</textarea>
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
                                       id="price" name="price" value="{{ old('price', $product->price) }}" required>
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
                                       id="original_price" name="original_price" value="{{ old('original_price', $product->original_price) }}">
                                @error('original_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="stock_quantity" class="form-label">Stock Quantity</label>
                            <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror" 
                                   id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}">
                            @error('stock_quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="in_stock" name="in_stock" 
                                       {{ old('in_stock', $product->in_stock) ? 'checked' : '' }}>
                                <label class="form-check-label" for="in_stock">
                                    In Stock
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="featured" name="featured" 
                                       {{ old('featured', $product->featured) ? 'checked' : '' }}>
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
                    <small class="text-muted">Set stock to 0 to mark a variant as out of stock</small>
                </div>
                <div class="card-body">
                    <div id="variants-container">
                        @php $variantIndex = 0; @endphp
                        @forelse($product->variants as $variant)
                            <div class="variant-row mb-3" data-index="{{ $variantIndex }}">
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="variants[{{ $variantIndex }}][weight]" 
                                               value="{{ $variant->weight }}" placeholder="Weight (e.g., 250gm)">
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <span class="input-group-text">₹</span>
                                            <input type="number" step="0.01" class="form-control" 
                                                   name="variants[{{ $variantIndex }}][price]" 
                                                   value="{{ $variant->price }}" placeholder="Price">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <input type="number" class="form-control stock-input" name="variants[{{ $variantIndex }}][stock]" 
                                                   value="{{ $variant->stock_quantity }}" placeholder="Stock" min="0" step="1"
                                                   data-variant-index="{{ $variantIndex }}">
                                            <span class="input-group-text stock-status">
                                                @if($variant->stock_quantity == 0)
                                                    <span class="badge bg-danger">Out</span>
                                                @elseif($variant->stock_quantity <= 5)
                                                    <span class="badge bg-warning">Low</span>
                                                @else
                                                    <span class="badge bg-success">OK</span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger btn-sm remove-variant" 
                                                {{ $product->variants->count() > 1 ? '' : 'style=display:none' }}>
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @php $variantIndex++; @endphp
                        @empty
                            <div class="variant-row mb-3" data-index="0">
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
                                               placeholder="Stock">
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-danger btn-sm remove-variant" style="display: none;">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforelse
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
                                  placeholder="Weight: 250gm&#10;Origin: Kerala, India&#10;Shelf Life: 12 months">{{ old('specifications', is_array($product->specifications) ? implode("\n", $product->specifications) : $product->specifications) }}</textarea>
                        @error('specifications')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="nutritional_info" class="form-label">Nutritional Information (One per line)</label>
                        <textarea class="form-control @error('nutritional_info') is-invalid @enderror" 
                                  id="nutritional_info" name="nutritional_info" rows="4"
                                  placeholder="Calories: 100 per 100g&#10;Protein: 5g&#10;Carbs: 20g">{{ old('nutritional_info', is_array($product->nutritional_info) ? implode("\n", $product->nutritional_info) : $product->nutritional_info) }}</textarea>
                        @error('nutritional_info')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="ingredients" class="form-label">Ingredients (One per line)</label>
                        <textarea class="form-control @error('ingredients') is-invalid @enderror" 
                                  id="ingredients" name="ingredients" rows="3"
                                  placeholder="100% Pure Turmeric Powder&#10;No additives&#10;No preservatives">{{ old('ingredients', is_array($product->ingredients) ? implode("\n", $product->ingredients) : $product->ingredients) }}</textarea>
                        @error('ingredients')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="benefits" class="form-label">Benefits (One per line)</label>
                        <textarea class="form-control @error('benefits') is-invalid @enderror" 
                                  id="benefits" name="benefits" rows="4"
                                  placeholder="Anti-inflammatory properties&#10;Boosts immunity&#10;Rich in antioxidants">{{ old('benefits', is_array($product->benefits) ? implode("\n", $product->benefits) : $product->benefits) }}</textarea>
                        @error('benefits')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="usage" class="form-label">Usage Instructions (One per line)</label>
                        <textarea class="form-control @error('usage') is-invalid @enderror" 
                                  id="usage" name="usage" rows="3"
                                  placeholder="Add 1 tsp to warm milk&#10;Use in cooking&#10;Mix with honey for paste">{{ old('usage', is_array($product->usage) ? implode("\n", $product->usage) : $product->usage) }}</textarea>
                        @error('usage')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="market_info" class="form-label">Market Information (One per line)</label>
                        <textarea class="form-control @error('market_info') is-invalid @enderror" 
                                  id="market_info" name="market_info" rows="3"
                                  placeholder="Premium Grade&#10;Export Quality&#10;Organic Certified">{{ old('market_info', is_array($product->market_info) ? implode("\n", $product->market_info) : $product->market_info) }}</textarea>
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
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                               id="tags" name="tags" 
                               value="{{ old('tags', is_array($product->tags) ? implode(', ', $product->tags) : $product->tags) }}"
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
                        @if($product->image_url)
                            <div class="mb-2">
                                <img src="{{ $product->image_url }}" alt="Current image" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        @endif
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*">
                        <small class="text-muted">Leave empty to keep current image</small>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="images" class="form-label">Additional Images</label>
                        @if($product->image_urls && count($product->image_urls) > 0)
                            <div class="mb-2">
                                @foreach($product->image_urls as $image)
                                    <img src="{{ $image }}" alt="Product image" class="img-thumbnail me-1" style="max-width: 80px;">
                                @endforeach
                            </div>
                        @endif
                        <input type="file" class="form-control @error('images.*') is-invalid @enderror" 
                               id="images" name="images[]" accept="image/*" multiple>
                        <small class="text-muted">New images will be added to existing ones</small>
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
                               id="badge" name="badge" value="{{ old('badge', $product->badge) }}"
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
                            <option value="primary" {{ old('badge_color', $product->badge_color) == 'primary' ? 'selected' : '' }}>Primary (Blue)</option>
                            <option value="success" {{ old('badge_color', $product->badge_color) == 'success' ? 'selected' : '' }}>Success (Green)</option>
                            <option value="danger" {{ old('badge_color', $product->badge_color) == 'danger' ? 'selected' : '' }}>Danger (Red)</option>
                            <option value="warning" {{ old('badge_color', $product->badge_color) == 'warning' ? 'selected' : '' }}>Warning (Yellow)</option>
                            <option value="info" {{ old('badge_color', $product->badge_color) == 'info' ? 'selected' : '' }}>Info (Light Blue)</option>
                            <option value="dark" {{ old('badge_color', $product->badge_color) == 'dark' ? 'selected' : '' }}>Dark</option>
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
                        <i class="fas fa-save"></i> Update Product
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
    let variantIndex = {{ $product->variants->count() ?: 1 }};
    
    // Debug form submission (removed to not interfere)
    
    // Update stock status badge on input change
    function updateStockStatus(input) {
        const value = parseInt(input.value) || 0;
        const statusSpan = input.parentElement.querySelector('.stock-status');
        
        if (value === 0) {
            statusSpan.innerHTML = '<span class="badge bg-danger">Out</span>';
        } else if (value <= 5) {
            statusSpan.innerHTML = '<span class="badge bg-warning">Low</span>';
        } else {
            statusSpan.innerHTML = '<span class="badge bg-success">OK</span>';
        }
    }
    
    // Add event listeners to existing stock inputs
    document.querySelectorAll('.stock-input').forEach(input => {
        input.addEventListener('input', function() {
            updateStockStatus(this);
            console.log('Stock input changed:', this.name, '=', this.value);
        });
    });
    
    // Add variant
    document.getElementById('add-variant').addEventListener('click', function() {
        const container = document.getElementById('variants-container');
        const newVariant = document.createElement('div');
        newVariant.className = 'variant-row mb-3';
        newVariant.setAttribute('data-index', variantIndex);
        newVariant.innerHTML = `
            <div class="row align-items-center">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="variants[${variantIndex}][weight]" 
                           placeholder="Weight (e.g., 500gm)">
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text">₹</span>
                        <input type="number" step="0.01" class="form-control" 
                               name="variants[${variantIndex}][price]" placeholder="Price">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <input type="number" class="form-control stock-input" name="variants[${variantIndex}][stock]" 
                               placeholder="Stock" min="0" step="1">
                        <span class="input-group-text stock-status">
                            <span class="badge bg-success">OK</span>
                        </span>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-sm remove-variant">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
        container.appendChild(newVariant);
        variantIndex++;
        
        // Add event listener to new stock input
        const newStockInput = newVariant.querySelector('.stock-input');
        newStockInput.addEventListener('input', function() {
            updateStockStatus(this);
        });
        
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
});
</script>
@endsection