<section class="featured-products-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-danger bg-opacity-10 text-danger mb-2 px-3 py-2 rounded-pill">Our Signature Products</span>
            <h2 class="display-5 fw-bold">Premium Spice Collection</h2>
            <div class="separator my-3 mx-auto"></div>
            <p class="lead text-muted mx-auto" style="max-width: 700px;">Our carefully sourced, traditionally processed premium spices that elevate every culinary creation</p>
        </div>

        <div class="row g-4 justify-content-center">
            @foreach($featuredProducts as $product)
            <div class="col-md-5">
                <div class="product-card h-100">
                    <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none d-block h-100">
                        <div class="card border-0 rounded-4 shadow-sm h-100 product-hover">
                            <div class="position-relative">
                                @if($product->badge)
                                <span class="badge bg-{{ $product->badge_color }} position-absolute top-0 end-0 m-3 z-1">{{ $product->badge }}</span>
                                @endif
                                <div class="product-image-container rounded-top-4 overflow-hidden">
                                    <img src="{{ asset($product->image) }}" 
                                         class="card-img-top product-image" 
                                         alt="{{ $product->name }}"
                                         onerror="this.onerror=null; this.src='https://via.placeholder.com/400x300/228B22/FFFFFF?text={{ urlencode($product->name) }}';">
                                </div>
                            </div>
                            <div class="card-body p-4">
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
                                <h3 class="h4 fw-bold mb-2 text-dark">{{ $product->name }}</h3>
                                <p class="card-text text-muted mb-3">{{ Str::limit($product->description, 150) }}</p>
                                <div class="product-details">
                                    <div class="d-flex align-items-center mb-3">
                                        @foreach($product->tags as $tag)
                                        <div class="badge bg-light text-dark me-2">
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
                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <div class="product-price">
                                        <span class="h4 fw-bold text-success mb-0">{{ $product->price_range }}</span>
                                    </div>
                                    <button class="btn btn-success rounded-pill px-4" onclick="event.preventDefault(); event.stopPropagation(); window.location.href='{{ route('product.show', $product->slug) }}';">
                                        <i class="fas fa-shopping-cart me-2"></i>View Options
                                    </button>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-5">
            <a href="{{ route('shop') }}" class="btn btn-outline-success btn-lg rounded-pill px-5">
                <i class="fas fa-th-large me-2"></i>View All Products
            </a>
        </div>
    </div>
</section>

<style>
/* Product Card Hover Effects */
.product-hover {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.product-hover:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
}

.product-image-container {
    height: 300px;
    overflow: hidden;
    background-color: #f8f9fa;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-hover:hover .product-image {
    transform: scale(1.05);
}

/* Separator */
.separator {
    width: 60px;
    height: 4px;
    background: linear-gradient(to right, #28a745, #20c997);
    border-radius: 2px;
}

/* Badge Animation */
.badge {
    animation: fadeInDown 0.5s ease;
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>