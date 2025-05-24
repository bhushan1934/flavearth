@extends('layouts.app')

@section('title', 'Shopping Cart - Flavearth')

@section('content')
<section class="cart-section py-5">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-success">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shop') }}" class="text-success">Shop</a></li>
                <li class="breadcrumb-item active" aria-current="page">Shopping Cart</li>
            </ol>
        </nav>

        <h1 class="h2 fw-bold mb-4">Shopping Cart</h1>

        @if($cartItems->count() > 0)
        <div class="row g-4">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr class="border-bottom">
                                        <th scope="col" class="border-0 text-muted fw-normal">Product</th>
                                        <th scope="col" class="border-0 text-muted fw-normal text-center">Price</th>
                                        <th scope="col" class="border-0 text-muted fw-normal text-center">Quantity</th>
                                        <th scope="col" class="border-0 text-muted fw-normal text-center">Total</th>
                                        <th scope="col" class="border-0 text-muted fw-normal text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $item)
                                    <tr class="cart-item" data-item-id="{{ $item->id }}">
                                        <td class="py-4">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset($item->product->images[0] ?? $item->product->image ?? 'images/products/default.jpg') }}" 
                                                     alt="{{ $item->product->name }}" 
                                                     class="rounded-3 me-3"
                                                     style="width: 80px; height: 80px; object-fit: cover;">
                                                <div>
                                                    <h6 class="mb-1 fw-semibold">{{ $item->product->name }}</h6>
                                                    <small class="text-muted d-block">
                                                        @if($item->variant_info)
                                                            <span class="badge bg-success me-2">{{ $item->variant_info }}</span>
                                                        @endif
                                                        @if($item->product->tags)
                                                            @foreach(array_slice($item->product->tags, 0, 2) as $tag)
                                                                <span class="badge bg-light text-dark me-1">{{ $tag }}</span>
                                                            @endforeach
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center py-4">
                                            <span class="fw-semibold">₹{{ number_format($item->price, 2) }}</span>
                                        </td>
                                        <td class="text-center py-4">
                                            <div class="quantity-selector d-inline-flex align-items-center">
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-secondary quantity-btn" 
                                                        onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                                        {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <input type="number" 
                                                       class="form-control form-control-sm text-center mx-2 quantity-input" 
                                                       value="{{ $item->quantity }}" 
                                                       min="1" 
                                                       max="{{ $item->variant ? $item->variant->stock_quantity : $item->product->stock_quantity }}"
                                                       style="width: 60px;"
                                                       onchange="updateQuantity({{ $item->id }}, this.value)"
                                                       data-item-id="{{ $item->id }}">
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-secondary quantity-btn" 
                                                        onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                                        {{ $item->quantity >= ($item->variant ? $item->variant->stock_quantity : $item->product->stock_quantity) ? 'disabled' : '' }}>
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="text-center py-4">
                                            <span class="fw-bold text-success item-total" data-item-id="{{ $item->id }}" data-price="{{ $item->price }}">
                                                ₹{{ number_format($item->subtotal, 2) }}
                                            </span>
                                        </td>
                                        <td class="text-center py-4">
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger rounded-circle"
                                                    onclick="removeItem({{ $item->id }})"
                                                    title="Remove item">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <a href="{{ route('shop') }}" class="btn btn-outline-success rounded-pill">
                                <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                            </a>
                            <button type="button" class="btn btn-link text-danger" onclick="clearCart()">
                                <i class="fas fa-trash me-2"></i>Clear Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 100px;">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold mb-4">Order Summary</h5>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal</span>
                                <span class="fw-semibold" id="subtotal">₹{{ number_format($total, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Shipping</span>
                                <span class="fw-semibold">
                                    @if($total >= 1000)
                                        <span class="text-success">FREE</span>
                                    @else
                                        ₹50.00
                                    @endif
                                </span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Tax (GST 18%)</span>
                                <span class="fw-semibold" id="tax">₹{{ number_format($total * 0.18, 2) }}</span>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between mb-4">
                            <span class="h5 fw-bold mb-0">Total</span>
                            <span class="h5 fw-bold text-success mb-0" id="total">
                                ₹{{ number_format($total + ($total * 0.18) + ($total < 1000 ? 50 : 0), 2) }}
                            </span>
                        </div>

                        <!-- Promo Code -->
                        <div class="mb-4">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Promo code" id="promoCode">
                                <button class="btn btn-outline-secondary" type="button" onclick="applyPromo()">
                                    Apply
                                </button>
                            </div>
                        </div>

                        <!-- Shipping Info -->
                        <div class="alert alert-info bg-info bg-opacity-10 border-0 mb-4">
                            <small>
                                <i class="fas fa-truck me-2"></i>
                                @if($total < 1000)
                                    Add ₹{{ number_format(1000 - $total, 2) }} more for FREE shipping!
                                @else
                                    You qualify for FREE shipping!
                                @endif
                            </small>
                        </div>

                        @auth
                            <a href="{{ route('checkout') }}" class="btn btn-success btn-lg w-100 rounded-pill mb-3">
                                <i class="fas fa-lock me-2"></i>Proceed to Checkout
                            </a>
                        @else
                            <button type="button" class="btn btn-success btn-lg w-100 rounded-pill mb-3" 
                                    data-bs-toggle="modal" data-bs-target="#loginModal">
                                <i class="fas fa-lock me-2"></i>Login to Checkout
                            </button>
                        @endauth

                        <!-- Security badges -->
                        <div class="text-center">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt me-1"></i>Secure Checkout
                            </small>
                        </div>

                        <!-- Payment methods -->
                        <div class="d-flex justify-content-center mt-3 gap-2">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Visa.svg" alt="Visa" height="20">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard" height="20">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" alt="PayPal" height="20">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @else
        <!-- Empty Cart -->
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card border-0 shadow-sm rounded-4 text-center py-5">
                    <div class="card-body">
                        <i class="fas fa-shopping-cart text-muted mb-4" style="font-size: 5rem;"></i>
                        <h3 class="fw-bold mb-3">Your cart is empty</h3>
                        <p class="text-muted mb-4">Looks like you haven't added any items to your cart yet.</p>
                        <a href="{{ route('shop') }}" class="btn btn-success btn-lg rounded-pill">
                            <i class="fas fa-shopping-bag me-2"></i>Start Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Recently Viewed / Recommendations -->
        @if($cartItems->count() > 0)
        <section class="mt-5">
            <h3 class="h4 fw-bold mb-4">You May Also Like</h3>
            <div class="row g-4">
                @php
                    $recommendations = \App\Models\Product::where('featured', true)
                        ->whereNotIn('id', $cartItems->pluck('product_id'))
                        ->limit(4)
                        ->get();
                @endphp
                
                @foreach($recommendations as $product)
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 rounded-3 shadow-sm h-100 product-card">
                        <img src="{{ asset($product->images[0] ?? $product->image ?? 'images/products/default.jpg') }}" 
                             class="card-img-top rounded-top-3" 
                             alt="{{ $product->name }}"
                             style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h6 class="card-title fw-semibold">{{ $product->name }}</h6>
                            <p class="card-text text-muted small">{{ Str::limit($product->description, 60) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h6 fw-bold text-success mb-0">{{ $product->price_range }}</span>
                                <button class="btn btn-sm btn-outline-success rounded-pill" 
                                        onclick="addToCart({{ $product->id }}, {{ $product->defaultVariant ? $product->defaultVariant->id : 'null' }})">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif
    </div>
</section>

<style>
.cart-section {
    min-height: 60vh;
}

.quantity-selector {
    white-space: nowrap;
}

.quantity-btn {
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.quantity-input::-webkit-inner-spin-button,
.quantity-input::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.product-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}

.sticky-top {
    transition: all 0.3s ease;
}

/* Responsive adjustments */
@media (max-width: 991.98px) {
    .sticky-top {
        position: relative !important;
        top: 0 !important;
    }
}
</style>

@push('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

function updateQuantity(itemId, newQuantity) {
    console.log('Updating quantity for item:', itemId, 'to:', newQuantity);
    if (newQuantity < 1) return;
    
    // Show loading state
    const input = document.querySelector(`input[data-item-id="${itemId}"]`);
    const row = document.querySelector(`tr[data-item-id="${itemId}"]`);
    if (!input || !row) {
        console.error('Could not find input or row for item:', itemId);
        return;
    }
    
    const oldValue = input.value;
    input.disabled = true;
    input.value = newQuantity; // Update input immediately for better UX
    
    fetch(`{{ url('/cart/update') }}/${itemId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            quantity: parseInt(newQuantity)
        })
    })
    .then(response => {
        console.log('Update response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Update response data:', data);
        if (data.success) {
            // Update the item total
            const itemTotal = row.querySelector('.item-total');
            if (itemTotal) {
                const price = parseFloat(itemTotal.dataset.price || 0);
                const newTotal = price * newQuantity;
                itemTotal.textContent = `₹${newTotal.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
            }
            
            // Update cart UI
            updateCartUI(data);
            showNotification('success', 'Cart updated successfully');
            
            // Update button states
            updateQuantityButtons(itemId, newQuantity);
        } else {
            // Reset to old value if failed
            input.value = oldValue;
            showNotification('error', data.message || 'Failed to update cart');
        }
    })
    .catch(error => {
        console.error('Update error:', error);
        // Reset to old value if failed
        input.value = oldValue;
        showNotification('error', 'Failed to update cart');
    })
    .finally(() => {
        input.disabled = false;
    });
}

function updateQuantityButtons(itemId, quantity) {
    const row = document.querySelector(`tr[data-item-id="${itemId}"]`);
    if (!row) return;
    
    const decreaseBtn = row.querySelector('.quantity-btn:first-child');
    const increaseBtn = row.querySelector('.quantity-btn:last-child');
    const input = row.querySelector('.quantity-input');
    
    if (decreaseBtn) {
        decreaseBtn.disabled = quantity <= 1;
        decreaseBtn.onclick = () => updateQuantity(itemId, quantity - 1);
    }
    
    if (increaseBtn && input) {
        const maxQuantity = parseInt(input.max);
        increaseBtn.disabled = quantity >= maxQuantity;
        increaseBtn.onclick = () => updateQuantity(itemId, quantity + 1);
    }
}

function removeItem(itemId) {
    console.log('Removing item with ID:', itemId);
    if (!confirm('Are you sure you want to remove this item?')) return;
    
    // Show loading state
    const button = document.querySelector(`button[onclick="removeItem(${itemId})"]`);
    const originalHtml = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    button.disabled = true;
    
    fetch(`{{ url('/cart/remove') }}/${itemId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        console.log('Remove response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Remove response data:', data);
        if (data.success) {
            // Remove item from DOM
            const row = document.querySelector(`tr[data-item-id="${itemId}"]`);
            if (row) {
                row.style.transition = 'opacity 0.3s ease';
                row.style.opacity = '0';
                setTimeout(() => {
                    row.remove();
                    // Check if cart is empty
                    if (document.querySelectorAll('.cart-item').length === 0) {
                        location.reload();
                    }
                }, 300);
            }
            
            updateCartUI(data);
            showNotification('success', 'Item removed from cart');
        } else {
            button.innerHTML = originalHtml;
            button.disabled = false;
            showNotification('error', data.message || 'Failed to remove item');
        }
    })
    .catch(error => {
        console.error('Remove error:', error);
        button.innerHTML = originalHtml;
        button.disabled = false;
        showNotification('error', 'Failed to remove item: ' + error.message);
    });
}

function clearCart() {
    console.log('Clearing cart...');
    if (!confirm('Are you sure you want to clear your entire cart?')) return;
    
    fetch('{{ route('cart.clear') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Clear cart response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Clear cart response data:', data);
        if (data.success) {
            showNotification('success', 'Cart cleared successfully');
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showNotification('error', data.message);
        }
    })
    .catch(error => {
        console.error('Clear cart error:', error);
        showNotification('error', 'Failed to clear cart');
    });
}

function updateCartUI(data) {
    // Update totals
    if (data.total !== undefined) {
        const subtotal = data.total;
        const tax = subtotal * 0.18; // GST 18%
        const shipping = subtotal < 1000 ? 50 : 0;
        const total = subtotal + tax + shipping;
        
        document.getElementById('subtotal').textContent = `₹${subtotal.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
        document.getElementById('tax').textContent = `₹${tax.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
        document.getElementById('total').textContent = `₹${total.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
        
        // Update shipping info
        const shippingInfo = document.querySelector('.alert-info small');
        if (subtotal < 1000) {
            shippingInfo.innerHTML = `<i class="fas fa-truck me-2"></i>Add ₹${(1000 - subtotal).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})} more for FREE shipping!`;
        } else {
            shippingInfo.innerHTML = `<i class="fas fa-truck me-2"></i>You qualify for FREE shipping!`;
        }
    }
    
    // Update cart count in header
    if (data.cart_count !== undefined) {
        updateCartCount(data.cart_count);
    }
}

function applyPromo() {
    const promoCode = document.getElementById('promoCode').value;
    if (!promoCode) {
        showNotification('error', 'Please enter a promo code');
        return;
    }
    
    // Here you would typically send the promo code to the server
    showNotification('info', 'Promo code feature coming soon!');
}

function showNotification(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 
                      type === 'error' ? 'alert-danger' : 
                      type === 'info' ? 'alert-info' : 'alert-warning';
    
    const notification = document.createElement('div');
    notification.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 350px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

function updateCartCount(count) {
    const cartBadge = document.querySelector('.navbar .cart-count');
    if (cartBadge) {
        cartBadge.textContent = count;
        cartBadge.style.display = count > 0 ? 'inline-block' : 'none';
    }
}

function addToCart(productId, variantId = null) {
    if (!variantId) {
        showNotification('error', 'Please select a product variant');
        return;
    }
    
    fetch('{{ route('cart.add') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            product_id: productId,
            variant_id: variantId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartCount(data.cart_count);
            showNotification('success', data.message);
        } else {
            showNotification('error', data.message || 'Failed to add item to cart');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'An error occurred. Please try again.');
    });
}
</script>
@endpush
@endsection