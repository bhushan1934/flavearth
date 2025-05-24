@extends('layouts.app')

@section('title', 'Shop - Premium Spices')

@section('content')
<!-- Shop Hero Section -->
<section class="shop-hero-section position-relative">
    <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient-green"></div>
    
    <div class="container py-5 position-relative">
        <div class="text-center text-white">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb justify-content-center bg-transparent">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                    <li class="breadcrumb-item active text-white" aria-current="page">Shop</li>
                </ol>
            </nav>
            <h1 class="display-4 fw-bold mb-3">Premium Spice Collection</h1>
            <p class="lead mb-0">Discover our handpicked collection of premium spices sourced from around the world</p>
        </div>
    </div>
</section>

<!-- Shop Filters & Search -->
<section class="shop-filters-section py-4 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="d-flex align-items-center">
                    <span class="text-muted me-3">Showing {{ count($products) }} products</span>
                    <div class="filter-badges">
                        <span class="badge bg-success me-2">All Categories</span>
                        <span class="badge bg-outline-secondary me-2">In Stock</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex gap-3 justify-content-md-end">
                    <div class="search-box">
                        <input type="text" class="form-control" placeholder="Search products..." id="productSearch">
                    </div>
                    <select class="form-select" style="width: auto;">
                        <option value="name">Sort by Name</option>
                        <option value="price_low">Price: Low to High</option>
                        <option value="price_high">Price: High to Low</option>
                        <option value="rating">Top Rated</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Products Grid -->
<section class="products-section py-5">
    <div class="container">
        <div class="row g-4" id="productsGrid">
            @foreach($products as $product)
            <div class="col-lg-4 col-md-6 product-item" data-name="{{ strtolower($product->name) }}" data-price="{{ $product->min_price }}" data-rating="{{ $product->rating }}">
                <div class="product-card h-100">
                    <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none d-block h-100">
                        <div class="card border-0 rounded-4 shadow-sm h-100 product-hover">
                            <div class="position-relative">
                                @if($product->badge)
                                <span class="badge bg-{{ $product->badge_color }} position-absolute top-0 end-0 m-3 z-1">{{ $product->badge }}</span>
                                @endif
                                
                                <div class="product-image-container rounded-top-4 overflow-hidden">
                                    <img src="{{ asset($product->images[0] ?? $product->image ?? 'images/products/default.jpg') }}" 
                                         class="card-img-top product-image" 
                                         alt="{{ $product->name }}"
                                         onerror="this.onerror=null; this.src='https://via.placeholder.com/400x300/228B22/FFFFFF?text={{ urlencode($product->name) }}';">
                                    
                                    <!-- Wishlist & Quick View Overlay -->
                                    <div class="product-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
                                        <div class="overlay-buttons opacity-0">
                                            <button class="btn btn-light btn-sm rounded-circle me-2" title="Add to Wishlist" onclick="event.preventDefault(); event.stopPropagation();">
                                                <i class="fas fa-heart"></i>
                                            </button>
                                            <button class="btn btn-light btn-sm rounded-circle" title="Quick View" onclick="event.preventDefault(); event.stopPropagation();">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-body p-4">
                                <!-- Rating -->
                                <div class="product-rating mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($product->rating))
                                            <i class="fas fa-star text-warning"></i>
                                        @elseif($i == ceil($product->rating) && $product->rating - floor($product->rating) >= 0.5)
                                            <i class="fas fa-star-half-alt text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                    <span class="ms-1 text-muted small">({{ $product->rating }}/5)</span>
                                </div>
                                
                                <!-- Product Name -->
                                <h3 class="h5 fw-bold mb-2 text-dark">{{ $product->name }}</h3>
                                
                                <!-- Description -->
                                <p class="card-text text-muted mb-3">{{ Str::limit($product->description, 120) }}</p>
                                
                                <!-- Tags -->
                                <div class="product-details mb-3">
                                    <div class="d-flex align-items-center flex-wrap gap-1">
                                        @foreach($product->tags as $tag)
                                        <div class="badge bg-light text-dark">
                                            @if($tag == 'Organic')
                                                <i class="fas fa-leaf me-1"></i>
                                            @elseif(str_contains($tag, 'Heat'))
                                                <i class="fas fa-fire-alt me-1"></i>
                                            @elseif(str_contains($tag, 'g'))
                                                <i class="fas fa-box me-1"></i>
                                            @else
                                                <i class="fas fa-certificate me-1"></i>
                                            @endif
                                            {{ $tag }}
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <!-- Price & Add to Cart -->
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <div class="product-price">
                                        <span class="h5 fw-bold text-success mb-0">{{ $product->price_range }}</span>
                                        @if($product->defaultVariant && $product->defaultVariant->original_price && $product->defaultVariant->original_price > $product->defaultVariant->price)
                                        <div class="small text-muted">
                                            <span class="text-decoration-line-through">₹{{ number_format($product->defaultVariant->original_price, 2) }}</span>
                                            <span class="badge bg-danger ms-1">{{ $product->defaultVariant->discount_percentage }}% OFF</span>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <div class="product-actions">
                                        <button class="btn btn-success rounded-pill px-4" onclick="event.preventDefault(); event.stopPropagation(); window.location.href='{{ route('product.show', $product->slug) }}';">
                                            <i class="fas fa-shopping-cart me-2"></i>Select Options
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Empty State (hidden by default) -->
        <div id="emptyState" class="text-center py-5" style="display: none;">
            <div class="empty-state-icon mb-3">
                <i class="fas fa-search fa-3x text-muted"></i>
            </div>
            <h4 class="text-muted">No products found</h4>
            <p class="text-muted">Try adjusting your search or filter criteria</p>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="newsletter-section py-5 bg-success text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3 class="fw-bold mb-2">Stay Updated with Our Latest Products</h3>
                <p class="mb-0">Get notified about new spice arrivals and exclusive offers</p>
            </div>
            <div class="col-md-6">
                <form class="d-flex gap-2">
                    <input type="email" class="form-control" placeholder="Enter your email">
                    <button type="submit" class="btn btn-light text-success px-4">Subscribe</button>
                </form>
            </div>
        </div>
    </div>
</section>

<style>
/* Shop Hero Section */
.shop-hero-section {
    background: linear-gradient(135deg, #228B22 0%, #32CD32 100%);
    padding: 100px 0 60px 0;
}

.bg-gradient-green {
    background: linear-gradient(135deg, rgba(34, 139, 34, 0.9) 0%, rgba(50, 205, 50, 0.9) 100%);
}

/* Shop Filters */
.shop-filters-section {
    border-bottom: 1px solid #e9ecef;
}

.search-box {
    position: relative;
    min-width: 250px;
}

/* Product Cards */
.products-section {
    min-height: 60vh;
}

.product-card {
    transition: all 0.4s ease;
    height: 100%;
}

.product-card a {
    cursor: pointer;
}

.product-hover {
    cursor: pointer;
    transition: all 0.4s ease;
}

.product-hover:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
}

.product-image-container {
    height: 280px;
    overflow: hidden;
    position: relative;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
}

.product-hover:hover .product-image {
    transform: scale(1.05);
}

/* Product Overlay */
.product-overlay {
    background: rgba(0,0,0,0.3);
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 2;
    pointer-events: none; /* Allow clicks to pass through to the image link */
}

.product-hover:hover .product-overlay {
    opacity: 1;
    pointer-events: auto; /* Enable interaction with overlay buttons on hover */
}

.product-hover:hover .overlay-buttons {
    opacity: 1 !important;
}

.overlay-buttons {
    transition: opacity 0.3s ease 0.1s;
    pointer-events: auto;
}

/* Product Info */
.product-rating .fas, .product-rating .far {
    font-size: 0.875rem;
}

.badge {
    font-weight: 500;
    padding: 0.4rem 0.8rem;
}

/* Buttons */
.add-to-cart-btn {
    transition: all 0.3s ease;
    font-weight: 500;
}

.add-to-cart-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(34, 139, 34, 0.3);
}

/* Newsletter Section */
.newsletter-section {
    background: linear-gradient(135deg, #228B22 0%, #32CD32 100%);
}

/* Responsive Design */
@media (max-width: 991.98px) {
    .shop-hero-section {
        padding: 80px 0 40px 0;
    }
    
    .shop-filters-section .row > div {
        margin-bottom: 1rem;
    }
    
    .d-flex.gap-3 {
        flex-direction: column;
        gap: 1rem !important;
    }
}

@media (max-width: 767.98px) {
    .product-image-container {
        height: 220px;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch !important;
    }
    
    .search-box {
        min-width: 100%;
    }
}

/* Animation for loading */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.product-item {
    animation: fadeInUp 0.6s ease forwards;
}

.product-item:nth-child(1) { animation-delay: 0.1s; }
.product-item:nth-child(2) { animation-delay: 0.2s; }
.product-item:nth-child(3) { animation-delay: 0.3s; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Debug: Log when product links are clicked
    const productLinks = document.querySelectorAll('a[href*="product/"]');
    console.log('Found', productLinks.length, 'product links on shop page');
    
    productLinks.forEach((link, index) => {
        console.log(`Product link ${index + 1}:`, link.href, 'Element:', link);
        
        link.addEventListener('click', function(e) {
            console.log('SHOP PAGE: Product link clicked:', this.href);
            console.log('Link element:', this);
            console.log('Event target:', e.target);
            // Don't prevent default - let navigation happen
        });
    });

    const searchInput = document.getElementById('productSearch');
    const sortSelect = document.querySelector('select');
    const productsGrid = document.getElementById('productsGrid');
    const emptyState = document.getElementById('emptyState');
    const productItems = document.querySelectorAll('.product-item');

    // Search functionality
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        filterAndSort();
    });

    // Sort functionality
    sortSelect.addEventListener('change', function() {
        filterAndSort();
    });

    function filterAndSort() {
        const searchTerm = searchInput.value.toLowerCase();
        const sortBy = sortSelect.value;
        let visibleItems = [];

        // Filter items
        productItems.forEach(item => {
            const productName = item.dataset.name;
            if (productName.includes(searchTerm)) {
                item.style.display = 'block';
                visibleItems.push(item);
            } else {
                item.style.display = 'none';
            }
        });

        // Sort visible items
        if (visibleItems.length > 0) {
            visibleItems.sort((a, b) => {
                switch(sortBy) {
                    case 'name':
                        return a.dataset.name.localeCompare(b.dataset.name);
                    case 'price_low':
                        return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
                    case 'price_high':
                        return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
                    case 'rating':
                        return parseFloat(b.dataset.rating) - parseFloat(a.dataset.rating);
                    default:
                        return 0;
                }
            });

            // Reorder DOM elements
            visibleItems.forEach((item, index) => {
                productsGrid.appendChild(item);
            });

            emptyState.style.display = 'none';
            productsGrid.style.display = '';
        } else {
            emptyState.style.display = 'block';
            productsGrid.style.display = 'none';
        }
    }

    // Add to cart functionality
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            console.log('Add to cart button clicked, preventing any link navigation');
            e.preventDefault(); // Prevent any default link behavior
            e.stopPropagation(); // Stop event bubbling
            
            const productId = this.dataset.productId;
            
            // Add loading state
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding...';
            this.disabled = true;

            // Simulate API call
            setTimeout(() => {
                this.innerHTML = '<i class="fas fa-check me-2"></i>Added!';
                
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.disabled = false;
                }, 1500);
            }, 1000);
        });
    });
});
</script>
@endsection