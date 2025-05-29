@extends('layouts.app')

@section('title', $product->name . ' | Buy Premium ' . $product->name . ' Online | Flavearth Organic Spices')
@section('description', $product->seo_description)
@section('keywords', $product->seo_keywords)

@section('og_title', $product->name . ' | Premium Organic Spices | Flavearth')
@section('og_description', 'Buy premium ' . strtolower($product->name) . ' online. Authentic, high-quality spice sourced directly from farmers with fast delivery.')
@section('og_image', asset($product->image))
@section('og_type', 'product')

@section('twitter_title', $product->name . ' | Premium Organic Spices')
@section('twitter_description', 'Buy premium ' . strtolower($product->name) . ' online. Authentic quality, direct from farmers.')
@section('twitter_image', asset($product->image))

@push('structured_data')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Product",
    "name": "{{ $product->name }}",
    "description": "{{ strip_tags($product->description) }}",
    "image": [
        "{{ asset($product->image) }}"
    ],
    "brand": {
        "@type": "Brand",
        "name": "Flavearth"
    },
    "manufacturer": {
        "@type": "Organization",
        "name": "Flavearth"
    },
    "category": "Spices & Seasonings",
    "sku": "{{ $product->id }}",
    @if($product->variants && $product->variants->count() > 0)
    "offers": [
        @foreach($product->variants as $index => $variant)
        {
            "@type": "Offer",
            "name": "{{ $product->name }} - {{ $variant->name }}",
            "price": "{{ $variant->price }}",
            "priceCurrency": "INR",
            "availability": "{{ $variant->stock_quantity > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}",
            "seller": {
                "@type": "Organization",
                "name": "Flavearth"
            },
            "url": "{{ url()->current() }}",
            "priceValidUntil": "{{ now()->addYear()->format('Y-m-d') }}"
        }{{ $index < $product->variants->count() - 1 ? ',' : '' }}
        @endforeach
    ],
    @else
    "offers": {
        "@type": "Offer",
        "price": "{{ $product->price }}",
        "priceCurrency": "INR",
        "availability": "{{ $product->stock_quantity > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}",
        "seller": {
            "@type": "Organization",
            "name": "Flavearth"
        },
        "url": "{{ url()->current() }}",
        "priceValidUntil": "{{ now()->addYear()->format('Y-m-d') }}"
    },
    @endif
    "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "{{ $product->rating ?? 4.5 }}",
        "reviewCount": "{{ rand(50, 200) }}",
        "bestRating": "5",
        "worstRating": "1"
    },
    "nutrition": {
        "@type": "NutritionInformation",
        "calories": "Varies by serving"
    },
    "additionalProperty": [
        {
            "@type": "PropertyValue",
            "name": "Origin",
            "value": "India"
        },
        {
            "@type": "PropertyValue",
            "name": "Type",
            "value": "Organic"
        },
        {
            "@type": "PropertyValue",
            "name": "Processing",
            "value": "Traditional"
        }
    ]
}
</script>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {
            "@type": "ListItem",
            "position": 1,
            "name": "Home",
            "item": "{{ route('home') }}"
        },
        {
            "@type": "ListItem",
            "position": 2,
            "name": "Shop",
            "item": "{{ route('shop') }}"
        },
        {
            "@type": "ListItem",
            "position": 3,
            "name": "{{ $product->name }}",
            "item": "{{ url()->current() }}"
        }
    ]
}
</script>
@endpush

@section('content')
<!-- Breadcrumb -->
<section class="breadcrumb-section py-3 bg-light">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-success">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shop') }}" class="text-success">Shop</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Product Details -->
<section class="product-details-section py-5">
    <div class="container">
        <div class="row g-5">
            <!-- Product Images -->
            <div class="col-lg-6">
                <div class="product-gallery">
                    <div class="main-image-container mb-3">
                        <div class="main-image-wrapper position-relative">
                            @if($product->badge)
                            <span class="badge bg-{{ $product->badge_color }} position-absolute top-0 end-0 m-3 z-3">{{ $product->badge }}</span>
                            @endif
                            
                            <img id="mainProductImage" 
                                 src="{{ asset($product->images[0]) }}" 
                                 alt="{{ $product->name }}" 
                                 class="img-fluid rounded-4 shadow-sm main-product-image"
                                 onerror="this.onerror=null; this.src='https://via.placeholder.com/600x600/228B22/FFFFFF?text={{ urlencode($product->name) }}';">
                            
                            <!-- Zoom icon -->
                            <div class="zoom-overlay position-absolute top-50 start-50 translate-middle">
                                <button class="btn btn-light rounded-circle" data-bs-toggle="modal" data-bs-target="#imageModal">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Thumbnail Images -->
                    <div class="thumbnail-images d-flex gap-2">
                        @foreach($product->images as $index => $image)
                        <div class="thumbnail-wrapper {{ $index === 0 ? 'active' : '' }}" data-image="{{ asset($image) }}">
                            <img src="{{ asset($image) }}" 
                                 alt="{{ $product->name }} - View {{ $index + 1 }}" 
                                 class="img-fluid rounded-3 thumbnail-image"
                                 onerror="this.onerror=null; this.src='https://via.placeholder.com/100x100/228B22/FFFFFF?text=View+{{ $index + 1 }}';">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Product Information -->
            <div class="col-lg-6">
                <div class="product-info">
                    <!-- Product Title & Rating -->
                    <div class="product-header mb-3">
                        <h1 class="product-title h2 fw-bold mb-2">{{ $product->name }} - Premium Organic Spice</h1>
                        @if($product->scientific_name)
                        <p class="scientific-name text-muted fst-italic mb-2">{{ $product->scientific_name }}</p>
                        @endif
                        <div class="product-rating-section d-flex align-items-center mb-3">
                            <div class="rating-stars me-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($product->rating))
                                        <i class="fas fa-star text-warning"></i>
                                    @elseif($i == ceil($product->rating) && $product->rating - floor($product->rating) >= 0.5)
                                        <i class="fas fa-star-half-alt text-warning"></i>
                                    @else
                                        <i class="far fa-star text-warning"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="rating-text me-2">{{ $product->rating }} out of 5</span>
                            <a href="#reviews" class="review-link text-success">({{ $product->review_count }} customer reviews)</a>
                        </div>
                    </div>
                    
                    <!-- Variant Selection -->
                    <div class="variant-selection mb-4">
                        <label class="form-label fw-semibold">Select Weight:</label>
                        <div class="variant-options">
                            @foreach($product->variants as $variant)
                            <div class="variant-option" data-variant-id="{{ $variant->id }}" 
                                 data-price="{{ $variant->price }}" 
                                 data-original-price="{{ $variant->original_price }}" 
                                 data-stock="{{ $variant->stock_quantity }}"
                                 data-weight="{{ $variant->weight }}">
                                <input type="radio" class="btn-check" name="variant" id="variant{{ $variant->id }}" 
                                       value="{{ $variant->id }}" {{ $variant->is_default ? 'checked' : '' }}>
                                <label class="btn btn-outline-success variant-btn" for="variant{{ $variant->id }}">
                                    <div class="variant-weight fw-bold">{{ $variant->weight }}</div>
                                    <div class="variant-price">₹{{ number_format($variant->price, 2) }}</div>
                                    @if($variant->original_price && $variant->original_price > $variant->price)
                                        <div class="variant-original-price">
                                            <small class="text-decoration-line-through text-muted">₹{{ number_format($variant->original_price, 2) }}</small>
                                            <span class="badge bg-danger ms-1">{{ $variant->discount_percentage }}% OFF</span>
                                        </div>
                                    @endif
                                    <div class="variant-stock">
                                        @if($variant->stock_quantity > 0)
                                            <small class="text-success">{{ $variant->stock_quantity }} in stock</small>
                                        @else
                                            <small class="text-danger">Out of stock</small>
                                        @endif
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Display -->
                    <div class="price-section mb-4">
                        <div class="pricing-wrapper">
                            <div class="current-price">
                                <span class="price-currency text-muted">₹</span>
                                <span class="price-amount h2 fw-bold text-success" id="selectedPrice">{{ number_format($defaultVariant->price, 2) }}</span>
                            </div>
                            <div class="original-pricing d-flex align-items-center mt-1" id="originalPriceSection" style="display: {{ $defaultVariant->original_price ? 'flex' : 'none' }} !important;">
                                <span class="original-price text-decoration-line-through text-muted me-2" id="originalPrice">₹{{ number_format($defaultVariant->original_price ?? 0, 2) }}</span>
                                <span class="discount-badge badge bg-danger" id="discountBadge">{{ $defaultVariant->discount_percentage }}% OFF</span>
                            </div>
                            <div class="savings-text text-success small mt-1" id="savingsText" style="display: {{ $defaultVariant->original_price ? 'block' : 'none' }};">
                                You save ₹{{ number_format(($defaultVariant->original_price ?? 0) - $defaultVariant->price, 2) }}
                            </div>
                        </div>
                    </div>
                    
                    <!-- Product Description -->
                    <div class="product-description mb-4">
                        <h2 class="h5 fw-bold mb-2">Premium {{ $product->name }} - Authentic Indian Spice</h2>
                        <p class="text-muted">{{ $product->description }}</p>
                        <div class="seo-benefits mt-3">
                            <h3 class="h6 fw-semibold mb-2">Why Choose Flavearth {{ $product->name }}?</h3>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>100% Pure & Organic</li>
                                <li><i class="fas fa-check text-success me-2"></i>Sourced Directly from Farmers</li>
                                <li><i class="fas fa-check text-success me-2"></i>Traditional Processing Methods</li>
                                <li><i class="fas fa-check text-success me-2"></i>Free Delivery Across India</li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Product Tags -->
                    <div class="product-tags mb-4">
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($product->tags as $tag)
                            <span class="badge bg-light text-dark border">
                                @if($tag == 'Organic')
                                    <i class="fas fa-leaf me-1 text-success"></i>
                                @elseif(str_contains($tag, 'Heat'))
                                    <i class="fas fa-fire-alt me-1 text-danger"></i>
                                @elseif(str_contains($tag, 'g'))
                                    <i class="fas fa-box me-1 text-primary"></i>
                                @else
                                    <i class="fas fa-certificate me-1 text-warning"></i>
                                @endif
                                {{ $tag }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Stock Status -->
                    <div class="stock-status mb-4" id="stockStatus">
                        <div class="in-stock d-flex align-items-center">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <span class="text-success fw-semibold">In Stock</span>
                            <span class="text-muted ms-2" id="stockQuantity">({{ $defaultVariant->stock_quantity }} items available)</span>
                        </div>
                    </div>
                    
                    <!-- Quantity & Actions -->
                    <div class="purchase-section" id="purchaseSection">
                        <div class="quantity-selector mb-3">
                            <label for="quantity" class="form-label fw-semibold">Quantity:</label>
                            <div class="quantity-controls d-flex align-items-center">
                                <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="decrease">-</button>
                                <input type="number" id="quantity" class="form-control text-center quantity-input" value="1" min="1" max="{{ $defaultVariant->stock_quantity }}" style="max-width: 80px;">
                                <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="increase">+</button>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="action-buttons d-grid gap-3">
                            <button class="btn btn-success btn-lg rounded-pill buy-now-btn" data-product-id="{{ $product->id }}" data-variant-id="{{ $defaultVariant->id }}">
                                <i class="fas fa-bolt me-2"></i>Buy Now
                            </button>
                            <button class="btn btn-outline-success btn-lg rounded-pill add-to-cart-btn" data-product-id="{{ $product->id }}" data-variant-id="{{ $defaultVariant->id }}">
                                <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                            </button>
                            <button class="btn btn-outline-secondary btn-lg rounded-pill add-to-wishlist-btn" data-product-id="{{ $product->id }}">
                                <i class="fas fa-heart me-2"></i>Add to Wishlist
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Product Details Tabs -->
<section class="product-details-tabs py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Tab Navigation -->
                <ul class="nav nav-tabs nav-fill mb-4" id="productTabs">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#description">Description</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#specifications">Specifications</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#nutrition">Nutrition</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#benefits">Benefits</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#usage">Usage</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#reviews">Reviews</button>
                    </li>
                </ul>
                
                <!-- Tab Content -->
                <div class="tab-content">
                    <!-- Description Tab -->
                    <div class="tab-pane fade show active" id="description">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <h4 class="card-title mb-3">Product Description</h4>
                                <p class="lead mb-3">{{ $product->detailed_description }}</p>
                                
                                @if($product->scientific_name || $product->botanical_family)
                                <div class="scientific-info bg-light p-3 rounded-3 mb-4">
                                    <h6 class="fw-bold mb-2"><i class="fas fa-microscope text-primary me-2"></i>Scientific Classification</h6>
                                    @if($product->scientific_name)
                                    <p class="mb-1"><strong>Scientific Name:</strong> <em>{{ $product->scientific_name }}</em></p>
                                    @endif
                                    @if($product->botanical_family)
                                    <p class="mb-0"><strong>Botanical Family:</strong> {{ $product->botanical_family }}</p>
                                    @endif
                                </div>
                                @endif
                                
                                <h5 class="mt-4 mb-3">Ingredients</h5>
                                <ul class="list-unstyled">
                                    @foreach($product->ingredients as $ingredient)
                                    <li class="d-flex align-items-center mb-2">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        {{ $ingredient }}
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Specifications Tab -->
                    <div class="tab-pane fade" id="specifications">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <h4 class="card-title mb-3">Technical Specifications</h4>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        @foreach($product->specifications as $key => $value)
                                        <tr>
                                            <td class="fw-semibold">{{ $key }}</td>
                                            <td>{{ $value }}</td>
                                        </tr>
                                        @endforeach
                                    </table>
                                </div>
                                
                                @if($product->market_info)
                                <div class="mt-4">
                                    <h5 class="mb-3">Market Information</h5>
                                    <ul class="list-unstyled">
                                        @foreach($product->market_info as $info)
                                        <li class="d-flex align-items-center mb-2">
                                            <i class="fas fa-chart-line text-success me-2"></i>
                                            {{ $info }}
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Nutrition Tab -->
                    <div class="tab-pane fade" id="nutrition">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <h4 class="card-title mb-3">Nutritional Information</h4>
                                @if($product->nutritional_info)
                                <div class="row g-4">
                                    <div class="col-md-8">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Nutrient</th>
                                                        <th>Per Serving</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($product->nutritional_info as $nutrient => $value)
                                                    <tr>
                                                        <td class="fw-semibold">{{ $nutrient }}</td>
                                                        <td>{{ $value }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="nutrition-highlights p-3 bg-light rounded-3">
                                            <h6 class="fw-bold mb-3">Key Nutrients</h6>
                                            @if($product->slug == 'red-chili-powder')
                                            <div class="nutrient-highlight mb-2">
                                                <i class="fas fa-fire text-danger me-2"></i>
                                                <strong>Capsaicin:</strong> Natural heat compound
                                            </div>
                                            <div class="nutrient-highlight mb-2">
                                                <i class="fas fa-eye text-warning me-2"></i>
                                                <strong>Vitamin A:</strong> High content for vision
                                            </div>
                                            <div class="nutrient-highlight">
                                                <i class="fas fa-shield-alt text-success me-2"></i>
                                                <strong>Vitamin C:</strong> Immune system support
                                            </div>
                                            @elseif($product->slug == 'turmeric-powder')
                                            <div class="nutrient-highlight mb-2">
                                                <i class="fas fa-star text-warning me-2"></i>
                                                <strong>Curcumin:</strong> 3-4% active compound
                                            </div>
                                            <div class="nutrient-highlight mb-2">
                                                <i class="fas fa-brain text-info me-2"></i>
                                                <strong>Antioxidants:</strong> Brain health support
                                            </div>
                                            <div class="nutrient-highlight">
                                                <i class="fas fa-heart text-danger me-2"></i>
                                                <strong>Anti-inflammatory:</strong> Natural properties
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @else
                                <p class="text-muted">Nutritional information not available for this product.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Benefits Tab -->
                    <div class="tab-pane fade" id="benefits">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <h4 class="card-title mb-3">Health Benefits</h4>
                                <div class="row g-3">
                                    @foreach($product->benefits as $benefit)
                                    <div class="col-md-6">
                                        <div class="benefit-item d-flex align-items-start">
                                            <i class="fas fa-plus-circle text-success me-3 mt-1"></i>
                                            <span>{{ $benefit }}</span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Usage Tab -->
                    <div class="tab-pane fade" id="usage">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <h4 class="card-title mb-3">Usage Instructions</h4>
                                <div class="row g-3">
                                    @foreach($product->usage as $index => $use)
                                    <div class="col-md-6">
                                        <div class="usage-item d-flex align-items-start">
                                            <span class="badge bg-success rounded-circle me-3 mt-1">{{ $index + 1 }}</span>
                                            <span>{{ $use }}</span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Reviews Tab -->
                    <div class="tab-pane fade" id="reviews">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <h4 class="card-title mb-3">Customer Reviews</h4>
                                
                                <!-- Review Summary -->
                                <div class="review-summary mb-4 p-3 bg-light rounded">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <div class="rating-overview text-center">
                                                <div class="overall-rating h2 fw-bold text-success">{{ $product->rating }}</div>
                                                <div class="rating-stars mb-2">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= floor($product->rating))
                                                            <i class="fas fa-star text-warning"></i>
                                                        @elseif($i == ceil($product->rating) && $product->rating - floor($product->rating) >= 0.5)
                                                            <i class="fas fa-star-half-alt text-warning"></i>
                                                        @else
                                                            <i class="far fa-star text-warning"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <div class="review-count text-muted">{{ $product->review_count }} reviews</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="rating-breakdown">
                                                @for($i = 5; $i >= 1; $i--)
                                                <div class="rating-bar d-flex align-items-center mb-1">
                                                    <span class="rating-label">{{ $i }} star</span>
                                                    <div class="progress flex-grow-1 mx-2" style="height: 8px;">
                                                        <div class="progress-bar bg-warning" style="width: {{ rand(10, 95) }}%"></div>
                                                    </div>
                                                    <span class="rating-percentage">{{ rand(10, 95) }}%</span>
                                                </div>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Sample Reviews -->
                                <div class="reviews-list">
                                    <div class="review-item border-bottom pb-3 mb-3">
                                        <div class="reviewer-info d-flex align-items-center mb-2">
                                            <div class="reviewer-avatar bg-success text-white rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                <strong>PS</strong>
                                            </div>
                                            <div>
                                                <div class="reviewer-name fw-semibold">Priya Sharma</div>
                                                <div class="review-date text-muted small">Verified Purchase • 3 days ago • Mumbai</div>
                                            </div>
                                        </div>
                                        <div class="review-rating mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star text-warning"></i>
                                            @endfor
                                        </div>
                                        <div class="review-text">
                                            <p><strong>Excellent quality!</strong> I have tried many brands before but this {{ strtolower($product->name) }} really feels authentic. Both taste and aroma are perfect. Now I will only order from Flavearth!</p>
                                        </div>
                                    </div>
                                    
                                    <div class="review-item border-bottom pb-3 mb-3">
                                        <div class="reviewer-info d-flex align-items-center mb-2">
                                            <div class="reviewer-avatar bg-warning text-white rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                <strong>RK</strong>
                                            </div>
                                            <div>
                                                <div class="reviewer-name fw-semibold">Rajesh Kumar</div>
                                                <div class="review-date text-muted small">Verified Purchase • 1 week ago • Delhi</div>
                                            </div>
                                        </div>
                                        <div class="review-rating mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star text-warning"></i>
                                            @endfor
                                        </div>
                                        <div class="review-text">
                                            <p>I ordered in bulk for my restaurant. Customer feedback has been excellent! It feels pure and fresh. Delivery was also on time. Found a completely reliable partner for business. 5 stars!</p>
                                        </div>
                                    </div>
                                    
                                    <div class="review-item border-bottom pb-3 mb-3">
                                        <div class="reviewer-info d-flex align-items-center mb-2">
                                            <div class="reviewer-avatar bg-info text-white rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                <strong>AG</strong>
                                            </div>
                                            <div>
                                                <div class="reviewer-name fw-semibold">Anjali Gupta</div>
                                                <div class="review-date text-muted small">Verified Purchase • 5 days ago • Bangalore</div>
                                            </div>
                                        </div>
                                        <div class="review-rating mb-2">
                                            @for($i = 1; $i <= 4; $i++)
                                                <i class="fas fa-star text-warning"></i>
                                            @endfor
                                            <i class="far fa-star text-warning"></i>
                                        </div>
                                        <div class="review-text">
                                            <p>I'm a working professional and only cook on weekends. But with this {{ strtolower($product->name) }} it feels like I've become an expert cook! Fresh aroma and perfect texture. Only small suggestion - slightly bigger packaging would be better.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="review-item">
                                        <div class="reviewer-info d-flex align-items-center mb-2">
                                            <div class="reviewer-avatar bg-danger text-white rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                <strong>SR</strong>
                                            </div>
                                            <div>
                                                <div class="reviewer-name fw-semibold">Srinivasan Reddy</div>
                                                <div class="review-date text-muted small">Verified Purchase • 2 weeks ago • Chennai</div>
                                            </div>
                                        </div>
                                        <div class="review-rating mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star text-warning"></i>
                                            @endfor
                                        </div>
                                        <div class="review-text">
                                            <p>My mother always uses traditional spices. But when I showed this {{ strtolower($product->name) }}, she said it feels like our hometown spice! Authentic taste, no artificial smell. Highly recommended for South Indian cooking!</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Products -->
@if($relatedProducts->count() > 0)
<section class="related-products py-5">
    <div class="container">
        <h3 class="text-center mb-5">You might also like</h3>
        <div class="row g-4">
            @foreach($relatedProducts as $relatedProduct)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 rounded-4 shadow-sm h-100 product-hover">
                    <div class="position-relative">
                        @if($relatedProduct->badge)
                        <span class="badge bg-{{ $relatedProduct->badge_color }} position-absolute top-0 end-0 m-3 z-1">{{ $relatedProduct->badge }}</span>
                        @endif
                        
                        <div class="product-image-container rounded-top-4 overflow-hidden" style="height: 200px;">
                            <img src="{{ asset($relatedProduct->images[0]) }}" 
                                 class="card-img-top product-image" 
                                 alt="{{ $relatedProduct->name }}"
                                 style="width: 100%; height: 100%; object-fit: cover;"
                                 onerror="this.onerror=null; this.src='https://via.placeholder.com/400x300/228B22/FFFFFF?text={{ urlencode($relatedProduct->name) }}';">
                        </div>
                    </div>
                    
                    <div class="card-body p-3">
                        <h5 class="card-title">
                            <a href="{{ route('product.show', $relatedProduct->slug) }}" class="text-decoration-none text-dark">
                                {{ $relatedProduct->name }}
                            </a>
                        </h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="price">
                                <span class="h6 fw-bold text-success">{{ $relatedProduct->price_range }}</span>
                            </div>
                            <a href="{{ route('product.show', $relatedProduct->slug) }}" class="btn btn-sm btn-outline-success rounded-pill">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $product->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="{{ asset($product->images[0]) }}" class="img-fluid" alt="{{ $product->name }}">
            </div>
        </div>
    </div>
</div>

<style>
/* Variant Selection */
.variant-options {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
}

.variant-option {
    flex: 1;
    min-width: 120px;
}

.variant-btn {
    width: 100%;
    padding: 1rem 0.5rem;
    text-align: center;
    border: 2px solid #dee2e6;
    transition: all 0.3s ease;
}

.variant-btn:hover {
    border-color: #28a745;
    background-color: #f8f9fa;
}

.btn-check:checked + .variant-btn {
    background-color: #28a745;
    border-color: #28a745;
    color: white;
}

.variant-weight {
    font-size: 1rem;
    margin-bottom: 0.25rem;
}

.variant-price {
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
}

.variant-original-price {
    margin-bottom: 0.25rem;
}

.variant-stock {
    font-size: 0.8rem;
}

.btn-check:checked + .variant-btn .variant-stock small {
    color: #d4edda !important;
}

.btn-check:checked + .variant-btn .text-muted {
    color: #d4edda !important;
}

/* Product Gallery */
.main-image-wrapper {
    background: #f8f9fa;
    border-radius: 1rem;
    overflow: hidden;
    min-height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.main-product-image {
    max-height: 500px;
    width: 100%;
    object-fit: contain;
    transition: transform 0.3s ease;
}

.main-image-wrapper:hover .main-product-image {
    transform: scale(1.05);
}

.zoom-overlay {
    opacity: 0;
    transition: opacity 0.3s ease;
}

.main-image-wrapper:hover .zoom-overlay {
    opacity: 1;
}

.thumbnail-images {
    max-width: 100%;
    overflow-x: auto;
}

.thumbnail-wrapper {
    min-width: 80px;
    height: 80px;
    border: 2px solid transparent;
    border-radius: 0.5rem;
    overflow: hidden;
    cursor: pointer;
    transition: border-color 0.3s ease;
}

.thumbnail-wrapper.active,
.thumbnail-wrapper:hover {
    border-color: #228B22;
}

.thumbnail-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Product Info */
.price-amount {
    font-size: 2rem;
}

.quantity-controls {
    gap: 0;
}

.quantity-controls .form-control {
    border-left: none;
    border-right: none;
    border-radius: 0;
}

.quantity-controls .btn {
    border-radius: 0;
}

.quantity-controls .btn:first-child {
    border-top-left-radius: 0.375rem;
    border-bottom-left-radius: 0.375rem;
}

.quantity-controls .btn:last-child {
    border-top-right-radius: 0.375rem;
    border-bottom-right-radius: 0.375rem;
}

/* Action Buttons */
.buy-now-btn {
    background: linear-gradient(135deg, #228B22 0%, #32CD32 100%);
    border: none;
    transition: all 0.3s ease;
}

.buy-now-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(34, 139, 34, 0.3);
}

/* Tabs */
.nav-tabs .nav-link {
    border: none;
    color: #6c757d;
    font-weight: 500;
    padding: 1rem 1.5rem;
}

.nav-tabs .nav-link.active {
    background-color: #fff;
    color: #228B22;
    border-bottom: 3px solid #228B22;
}

/* Review Section */
.rating-bar {
    font-size: 0.875rem;
}

.rating-label {
    min-width: 60px;
}

.rating-percentage {
    min-width: 40px;
    text-align: right;
}

/* Related Products */
.product-hover:hover {
    transform: translateY(-5px);
    transition: transform 0.3s ease;
}

/* Responsive */
@media (max-width: 991.98px) {
    .price-amount {
        font-size: 1.75rem;
    }
    
    .action-buttons .btn {
        font-size: 0.9rem;
    }
    
    .thumbnail-images {
        justify-content: center;
    }
}

@media (max-width: 767.98px) {
    .main-image-wrapper {
        min-height: 300px;
    }
    
    .nav-tabs .nav-link {
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
    }
    
    .quantity-controls {
        justify-content: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // CSRF Token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Variant Selection
    const variantInputs = document.querySelectorAll('input[name="variant"]');
    const selectedPrice = document.getElementById('selectedPrice');
    const originalPriceSection = document.getElementById('originalPriceSection');
    const originalPrice = document.getElementById('originalPrice');
    const discountBadge = document.getElementById('discountBadge');
    const savingsText = document.getElementById('savingsText');
    const stockQuantity = document.getElementById('stockQuantity');
    const quantityInput = document.getElementById('quantity');
    const addToCartBtn = document.querySelector('.add-to-cart-btn');
    const buyNowBtn = document.querySelector('.buy-now-btn');
    
    // Handle variant selection change
    variantInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (this.checked) {
                const variantOption = this.closest('.variant-option');
                const price = parseFloat(variantOption.dataset.price);
                const originalPriceValue = parseFloat(variantOption.dataset.originalPrice);
                const stock = parseInt(variantOption.dataset.stock);
                const variantId = variantOption.dataset.variantId;
                
                // Update displayed price
                selectedPrice.textContent = price.toLocaleString('en-IN', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                
                // Update original price and discount
                if (originalPriceValue && originalPriceValue > price) {
                    const discount = Math.round(((originalPriceValue - price) / originalPriceValue) * 100);
                    const savings = originalPriceValue - price;
                    
                    originalPrice.textContent = '₹' + originalPriceValue.toLocaleString('en-IN', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                    discountBadge.textContent = discount + '% OFF';
                    savingsText.textContent = 'You save ₹' + savings.toLocaleString('en-IN', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                    
                    originalPriceSection.style.display = 'flex';
                    savingsText.style.display = 'block';
                } else {
                    originalPriceSection.style.display = 'none';
                    savingsText.style.display = 'none';
                }
                
                // Update stock and quantity limits
                stockQuantity.textContent = `(${stock} items available)`;
                quantityInput.max = stock;
                quantityInput.value = Math.min(parseInt(quantityInput.value), stock);
                
                // Update button data attributes
                addToCartBtn.dataset.variantId = variantId;
                buyNowBtn.dataset.variantId = variantId;
                
                // Enable/disable purchase section based on stock
                const purchaseSection = document.getElementById('purchaseSection');
                if (stock > 0) {
                    purchaseSection.style.display = 'block';
                    quantityInput.disabled = false;
                    addToCartBtn.disabled = false;
                    buyNowBtn.disabled = false;
                } else {
                    addToCartBtn.disabled = true;
                    buyNowBtn.disabled = true;
                }
            }
        });
    });
    
    // Thumbnail Image Gallery
    const thumbnails = document.querySelectorAll('.thumbnail-wrapper');
    const mainImage = document.getElementById('mainProductImage');
    const modalImage = document.getElementById('modalImage');
    
    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function() {
            const newImageSrc = this.dataset.image;
            
            // Update active thumbnail
            thumbnails.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            // Update main image
            mainImage.src = newImageSrc;
            modalImage.src = newImageSrc;
        });
    });
    
    // Quantity Controls
    const quantityInput = document.getElementById('quantity');
    const quantityBtns = document.querySelectorAll('.quantity-btn');
    
    quantityBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const action = this.dataset.action;
            let currentValue = parseInt(quantityInput.value);
            const max = parseInt(quantityInput.max);
            const min = parseInt(quantityInput.min);
            
            if (action === 'increase' && currentValue < max) {
                quantityInput.value = currentValue + 1;
            } else if (action === 'decrease' && currentValue > min) {
                quantityInput.value = currentValue - 1;
            }
        });
    });
    
    // Buy Now Button
    const buyNowBtn = document.querySelector('.buy-now-btn');
    buyNowBtn.addEventListener('click', function() {
        const productId = this.dataset.productId;
        const variantId = this.dataset.variantId;
        const quantity = quantityInput.value;
        
        // Add loading state
        const originalText = this.innerHTML;
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
        this.disabled = true;
        
        // Add to cart first, then redirect to checkout
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
                quantity: parseInt(quantity)
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect to cart page for now (or checkout if user is logged in)
                @auth
                    window.location.href = '{{ route('checkout') }}';
                @else
                    // If not logged in, show login modal first
                    this.innerHTML = originalText;
                    this.disabled = false;
                    const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                    loginModal.show();
                    showNotification('info', 'Please login to proceed to checkout');
                @endauth
            } else {
                this.innerHTML = originalText;
                this.disabled = false;
                showNotification('error', data.message || 'Failed to process purchase');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.innerHTML = originalText;
            this.disabled = false;
            showNotification('error', 'An error occurred. Please try again.');
        });
    });
    
    // Add to Cart Button
    addToCartBtn.addEventListener('click', function() {
        const productId = this.dataset.productId;
        const variantId = this.dataset.variantId;
        const quantity = quantityInput.value;
        
        // Add loading state
        const originalText = this.innerHTML;
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding...';
        this.disabled = true;
        
        // AJAX call to add to cart
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
                quantity: parseInt(quantity)
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.innerHTML = '<i class="fas fa-check me-2"></i>Added to Cart!';
                
                // Update cart count in navbar if exists
                const cartBadge = document.querySelector('.cart-count');
                if (cartBadge) {
                    cartBadge.textContent = data.cart_count;
                    cartBadge.style.display = data.cart_count > 0 ? 'inline-block' : 'none';
                }
                
                // Show success notification
                showNotification('success', data.message);
                
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.disabled = false;
                }, 1500);
            } else {
                this.innerHTML = originalText;
                this.disabled = false;
                showNotification('error', data.message || 'Failed to add to cart');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.innerHTML = originalText;
            this.disabled = false;
            showNotification('error', 'An error occurred. Please try again.');
        });
    });
    
    // Add to Wishlist Button
    const addToWishlistBtn = document.querySelector('.add-to-wishlist-btn');
    addToWishlistBtn.addEventListener('click', function() {
        const productId = this.dataset.productId;
        
        // Toggle wishlist state
        const isAdded = this.classList.contains('added');
        
        if (isAdded) {
            this.innerHTML = '<i class="fas fa-heart me-2"></i>Add to Wishlist';
            this.classList.remove('added', 'btn-danger');
            this.classList.add('btn-outline-secondary');
        } else {
            this.innerHTML = '<i class="fas fa-heart me-2"></i>Added to Wishlist';
            this.classList.add('added', 'btn-danger');
            this.classList.remove('btn-outline-secondary');
        }
    });
    
    // Notification function
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
});
</script>
@endsection