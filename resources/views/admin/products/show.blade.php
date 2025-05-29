@extends('admin.layouts.app')

@section('title', 'Product Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Product Details</h1>
    <div>
        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit Product
        </a>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Products
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Basic Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Basic Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">Product Name:</th>
                        <td>{{ $product->name }}</td>
                    </tr>
                    <tr>
                        <th>Slug:</th>
                        <td>{{ $product->slug }}</td>
                    </tr>
                    @if($product->scientific_name)
                    <tr>
                        <th>Scientific Name:</th>
                        <td>{{ $product->scientific_name }}</td>
                    </tr>
                    @endif
                    @if($product->botanical_family)
                    <tr>
                        <th>Botanical Family:</th>
                        <td>{{ $product->botanical_family }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th>Category:</th>
                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            <span class="badge bg-{{ $product->in_stock ? 'success' : 'danger' }}">
                                {{ $product->in_stock ? 'In Stock' : 'Out of Stock' }}
                            </span>
                            @if($product->featured)
                                <span class="badge bg-warning ms-1">Featured</span>
                            @endif
                            @if($product->badge)
                                <span class="badge bg-{{ $product->badge_color ?? 'secondary' }} ms-1">
                                    {{ $product->badge }}
                                </span>
                            @endif
                        </td>
                    </tr>
                </table>
                
                @if($product->short_description)
                <div class="mt-3">
                    <h6>Short Description:</h6>
                    <p>{{ $product->short_description }}</p>
                </div>
                @endif
                
                <div class="mt-3">
                    <h6>Description:</h6>
                    <p>{{ $product->description }}</p>
                </div>
                
                @if($product->detailed_description)
                <div class="mt-3">
                    <h6>Detailed Description:</h6>
                    <p>{{ $product->detailed_description }}</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Pricing & Inventory -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Pricing & Inventory</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">Current Price:</th>
                        <td>₹{{ number_format($product->price, 2) }}</td>
                    </tr>
                    @if($product->original_price)
                    <tr>
                        <th>Original Price:</th>
                        <td>
                            ₹{{ number_format($product->original_price, 2) }}
                            @if($product->discount_percentage)
                                <span class="badge bg-danger ms-2">{{ $product->discount_percentage }}% OFF</span>
                            @endif
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <th>Stock Quantity:</th>
                        <td>{{ $product->stock_quantity ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Product Variants -->
        @if($product->variants->count() > 0)
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Product Variants</h5>
                <a href="{{ route('admin.products.edit', $product->id) }}#variants-container" class="btn btn-sm btn-primary">
                    <i class="fas fa-edit"></i> Edit Variants
                </a>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-3">
                    <i class="fas fa-info-circle"></i> <strong>Stock Management:</strong>
                    <ul class="mb-0 mt-2">
                        <li>To mark a variant as <strong>out of stock</strong>: Set stock quantity to 0 in the edit page</li>
                        <li>To <strong>restock</strong>: Update the stock quantity to the new value</li>
                        <li>Variants with 0 stock are automatically marked as unavailable</li>
                    </ul>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Weight</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($product->variants as $variant)
                        <tr id="variant-{{ $variant->id }}">
                            <td>{{ $variant->weight }}</td>
                            <td>₹{{ number_format($variant->price, 2) }}</td>
                            <td>
                                <span class="stock-quantity">{{ $variant->stock_quantity }}</span>
                                @if($variant->stock_quantity <= 5 && $variant->stock_quantity > 0)
                                    <span class="badge bg-warning ms-1">Low Stock</span>
                                @elseif($variant->stock_quantity == 0)
                                    <span class="badge bg-danger ms-1">Out of Stock</span>
                                @endif
                            </td>
                            <td>
                                <span class="status-badge badge bg-{{ $variant->is_available && $variant->stock_quantity > 0 ? 'success' : 'danger' }}">
                                    {{ $variant->is_available && $variant->stock_quantity > 0 ? 'Available' : 'Unavailable' }}
                                </span>
                            </td>
                            <td>
                                @if($variant->stock_quantity > 0)
                                    <button type="button" class="btn btn-sm btn-warning mark-out-of-stock" 
                                            data-variant-id="{{ $variant->id }}"
                                            data-product-id="{{ $product->id }}"
                                            title="Mark as out of stock">
                                        <i class="fas fa-ban"></i> Mark Out of Stock
                                    </button>
                                @else
                                    <span class="text-muted">Edit to restock</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
        
        <!-- Additional Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Additional Information</h5>
            </div>
            <div class="card-body">
                @if($product->specifications && count($product->specifications) > 0)
                <div class="mb-4">
                    <h6>Specifications:</h6>
                    <ul>
                        @foreach($product->specifications as $spec)
                            <li>{{ $spec }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                @if($product->nutritional_info && count($product->nutritional_info) > 0)
                <div class="mb-4">
                    <h6>Nutritional Information:</h6>
                    <ul>
                        @foreach($product->nutritional_info as $info)
                            <li>{{ $info }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                @if($product->ingredients && count($product->ingredients) > 0)
                <div class="mb-4">
                    <h6>Ingredients:</h6>
                    <ul>
                        @foreach($product->ingredients as $ingredient)
                            <li>{{ $ingredient }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                @if($product->benefits && count($product->benefits) > 0)
                <div class="mb-4">
                    <h6>Benefits:</h6>
                    <ul>
                        @foreach($product->benefits as $benefit)
                            <li>{{ $benefit }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                @if($product->usage && count($product->usage) > 0)
                <div class="mb-4">
                    <h6>Usage Instructions:</h6>
                    <ul>
                        @foreach($product->usage as $use)
                            <li>{{ $use }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                @if($product->market_info && count($product->market_info) > 0)
                <div class="mb-4">
                    <h6>Market Information:</h6>
                    <ul>
                        @foreach($product->market_info as $info)
                            <li>{{ $info }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Images -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Product Images</h5>
            </div>
            <div class="card-body">
                @if($product->image_url)
                    <div class="mb-3">
                        <h6>Main Image:</h6>
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-fluid rounded" style="max-width: 100%;">
                    </div>
                @endif
                
                @if($product->image_urls && count($product->image_urls) > 0)
                    <div>
                        <h6>Additional Images:</h6>
                        <div class="row g-2">
                            @foreach($product->image_urls as $image)
                                <div class="col-6">
                                    <img src="{{ $image }}" alt="{{ $product->name }}" class="img-fluid rounded">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                @if(!$product->image && (!$product->images || count($product->images) == 0))
                    <p class="text-muted">No images available</p>
                @endif
            </div>
        </div>
        
        <!-- Tags -->
        @if($product->tags && count($product->tags) > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Tags</h5>
            </div>
            <div class="card-body">
                @foreach($product->tags as $tag)
                    <span class="badge bg-secondary me-1 mb-1">{{ $tag }}</span>
                @endforeach
            </div>
        </div>
        @endif
        
        <!-- Metadata -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Metadata</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <th>ID:</th>
                        <td>{{ $product->id }}</td>
                    </tr>
                    <tr>
                        <th>Created:</th>
                        <td>{{ $product->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Updated:</th>
                        <td>{{ $product->updated_at->format('M d, Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Rating:</th>
                        <td>{{ $product->rating }}/5 ({{ $product->review_count }} reviews)</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle mark out of stock buttons
    document.querySelectorAll('.mark-out-of-stock').forEach(button => {
        button.addEventListener('click', function() {
            if (!confirm('Are you sure you want to mark this variant as out of stock?')) {
                return;
            }
            
            const variantId = this.dataset.variantId;
            const productId = this.dataset.productId;
            const row = document.getElementById(`variant-${variantId}`);
            
            // Disable button
            this.disabled = true;
            
            fetch(`/admin/products/${productId}/variants/${variantId}/toggle-stock`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the UI
                    const stockCell = row.querySelector('.stock-quantity');
                    const statusBadge = row.querySelector('.status-badge');
                    const actionCell = row.cells[4];
                    
                    stockCell.textContent = '0';
                    stockCell.insertAdjacentHTML('afterend', ' <span class="badge bg-danger ms-1">Out of Stock</span>');
                    
                    statusBadge.classList.remove('bg-success');
                    statusBadge.classList.add('bg-danger');
                    statusBadge.textContent = 'Unavailable';
                    
                    actionCell.innerHTML = '<span class="text-muted">Edit to restock</span>';
                    
                    // Show success message
                    alert('Variant marked as out of stock successfully!');
                } else {
                    alert('Failed to update variant stock status');
                    this.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating stock status');
                this.disabled = false;
            });
        });
    });
});
</script>
@endsection