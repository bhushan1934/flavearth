@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">My Wishlist</h2>
            
            @if($wishlistItems->count() > 0)
                <div class="row">
                    @foreach($wishlistItems as $item)
                        <div class="col-md-6 col-lg-4 mb-4" id="wishlist-item-{{ $item->id }}">
                            <div class="card h-100 shadow-sm">
                                <div class="position-relative">
                                    <img src="{{ asset($item->product->images[0] ?? 'images/products/default.jpg') }}" 
                                         class="card-img-top" 
                                         alt="{{ $item->product->name }}"
                                         style="height: 250px; object-fit: cover;">
                                    <button class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2"
                                            onclick="removeFromWishlist({{ $item->product->id }})"
                                            title="Remove from wishlist">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $item->product->name }}</h5>
                                    <p class="card-text text-muted small">{{ Str::limit($item->product->description, 100) }}</p>
                                    
                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div>
                                                @if($item->product->sale_price)
                                                    <span class="h5 text-danger mb-0">₹{{ number_format($item->product->sale_price, 2) }}</span>
                                                    <span class="text-muted text-decoration-line-through small">₹{{ number_format($item->product->price, 2) }}</span>
                                                @else
                                                    <span class="h5 mb-0">₹{{ number_format($item->product->price, 2) }}</span>
                                                @endif
                                            </div>
                                            @if($item->product->stock > 0)
                                                <span class="badge bg-success">In Stock</span>
                                            @else
                                                <span class="badge bg-danger">Out of Stock</span>
                                            @endif
                                        </div>
                                        
                                        <div class="d-grid gap-2">
                                            @if($item->product->stock > 0)
                                                <button class="btn btn-primary" onclick="addToCart({{ $item->product->id }})">
                                                    <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                                                </button>
                                            @else
                                                <button class="btn btn-secondary" disabled>
                                                    Out of Stock
                                                </button>
                                            @endif
                                            <a href="{{ route('products.show', $item->product->slug) }}" class="btn btn-outline-secondary">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="row mt-4">
                    <div class="col-12 text-center">
                        <a href="{{ route('shop') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                        </a>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-heart fa-4x text-muted mb-3"></i>
                    <h4>Your wishlist is empty</h4>
                    <p class="text-muted">Save your favorite items here to buy them later!</p>
                    <a href="{{ route('shop') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-shopping-bag me-2"></i>Start Shopping
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function removeFromWishlist(productId) {
    if (!confirm('Remove this item from your wishlist?')) {
        return;
    }
    
    fetch(`/wishlist/remove/${productId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove the item from the page
            const item = document.getElementById(`wishlist-item-${data.wishlistItemId}`);
            if (item) {
                item.remove();
            }
            
            // Update wishlist count in navbar
            updateWishlistCount();
            
            // Show success message
            showAlert('success', data.message);
            
            // Check if wishlist is now empty
            const remainingItems = document.querySelectorAll('[id^="wishlist-item-"]');
            if (remainingItems.length === 0) {
                location.reload();
            }
        } else {
            showAlert('danger', data.message || 'Failed to remove item from wishlist');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'An error occurred. Please try again.');
    });
}

function addToCart(productId) {
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartCount();
            showAlert('success', data.message);
        } else {
            showAlert('danger', data.message || 'Failed to add item to cart');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'An error occurred. Please try again.');
    });
}

function updateWishlistCount() {
    fetch('/wishlist/count')
        .then(response => response.json())
        .then(data => {
            const badge = document.querySelector('.wishlist-count');
            if (badge) {
                badge.textContent = data.count;
                badge.style.display = data.count > 0 ? 'inline-block' : 'none';
            }
        });
}

function updateCartCount() {
    fetch('/cart/count')
        .then(response => response.json())
        .then(data => {
            const badge = document.querySelector('.cart-count');
            if (badge) {
                badge.textContent = data.count;
                badge.style.display = data.count > 0 ? 'inline-block' : 'none';
            }
        });
}

function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3`;
    alertDiv.style.zIndex = '9999';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}
</script>
@endsection