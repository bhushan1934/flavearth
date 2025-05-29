<section class="about-section position-relative py-5">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 order-lg-1 order-2">
                <div class="about-content">
                    <span class="badge bg-primary-light text-primary mb-3 px-3 py-2 rounded-pill">About Flavearth</span>
                    <h2 class="display-5 fw-bold mb-4">The Flavearth Story</h2>
                    <div class="separator mb-4"></div>
                    
                    <p class="lead mb-4">Flavearth - From international export markets to your kitchen — bringing premium spice excellence to local customers.</p>
                    
                    <div class="about-text">
                        <p class="mb-4">For over a decade, Flavearth has been proudly exporting premium quality spices to various countries worldwide, earning recognition for exceptional standards and authentic flavor profiles. Our Flavearth red chili and turmeric powders have graced professional kitchens and homes across continents.</p>
                        
                        <p class="mb-4">Today, Flavearth is excited to bring that same export-quality excellence directly to local customers. We believe everyone deserves access to the finest Flavearth spices — the very same products trusted by international markets and celebrated chefs globally.</p>
                        
                        <p class="mb-4">Flavearth's commitment remains unwavering: delivering meticulously sourced, traditionally processed spices that transform ordinary meals into extraordinary culinary experiences. Each Flavearth product in our collection represents our dedication to quality, purity, and the preservation of authentic flavors.</p>
                        
                        <div class="d-flex flex-wrap align-items-center mb-4 mt-5 gap-3">
                            <div class="d-flex me-3 stats-item">
                                <span class="display-6 text-primary fw-bold me-2">10+</span>
                                <span class="d-flex flex-column justify-content-center">
                                    <span class="fw-bold">Years of</span>
                                    <span>Excellence</span>
                                </span>
                            </div>
                            <div class="d-flex me-3 stats-item">
                                <span class="display-6 text-primary fw-bold me-2">25+</span>
                                <span class="d-flex flex-column justify-content-center">
                                    <span class="fw-bold">Countries</span>
                                    <span>Served</span>
                                </span>
                            </div>
                            <div class="d-flex stats-item">
                                <span class="display-6 text-primary fw-bold me-2">100%</span>
                                <span class="d-flex flex-column justify-content-center">
                                    <span class="fw-bold">Premium</span>
                                    <span>Quality</span>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 d-flex flex-wrap gap-3">
                        <a href="{{ route('shop') }}" class="btn btn-primary btn-lg rounded-pill px-5">
                            Shop Now <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                        <a href="{{ route('about') }}" class="btn btn-outline-dark btn-lg rounded-pill px-5">Learn More</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 order-lg-2 order-1">
                <div class="position-relative">
                    <img src="{{ asset('images/categories/Export.png') }}" alt="Our Premium Spices" class="img-fluid rounded-4 shadow-lg position-relative z-1">
                    
                    <div class="position-absolute start-0 bottom-0 translate-middle-y z-0 d-none d-lg-block">
                        <div class="bg-primary rounded-4 opacity-10" style="width: 200px; height: 200px;"></div>
                    </div>
                    
                    <div class="position-absolute top-0 end-0 translate-middle z-0 d-none d-lg-block">
                        <div class="bg-warning rounded-4 opacity-10" style="width: 150px; height: 150px;"></div>
                    </div>
                    
                    <div class="position-absolute about-badge badge-export">
                        <div class="d-flex align-items-center bg-white p-3 rounded-4 shadow-lg">
                            <i class="fas fa-globe-americas text-primary fs-3 me-3"></i>
                            <div>
                                <h4 class="h6 mb-0">Global Export</h4>
                                <p class="small mb-0">Quality</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="position-absolute about-badge badge-organic">
                        <div class="d-flex align-items-center bg-white p-3 rounded-4 shadow-lg">
                            <i class="fas fa-leaf text-success fs-3 me-3"></i>
                            <div>
                                <h4 class="h6 mb-0">100% Organic</h4>
                                <p class="small mb-0">Certified</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.about-section {
    background-color: #f8f9fa;
    overflow: hidden;
}

.separator {
    height: 3px;
    width: 60px;
    background: linear-gradient(to right, #FF5722, #FFC107);
    border-radius: 3px;
}

.text-primary {
    color: #0d6efd !important;
}

.bg-primary-light {
    background-color: rgba(13, 110, 253, 0.1);
}

.about-badge {
    z-index: 2;
    transition: all 0.3s ease;
}

.badge-export {
    top: 10%;
    right: -5%;
    animation: float 6s ease-in-out infinite;
}

.badge-organic {
    bottom: 15%;
    left: -5%;
    animation: float 8s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-15px); }
}

@media (max-width: 991.98px) {
    .about-badge {
        display: none;
    }
}

@media (max-width: 767.98px) {
    .about-section {
        padding: 40px 0;
    }
    
    .display-5 {
        font-size: 2rem;
    }
    
    .display-6 {
        font-size: 2rem;
    }
    
    .lead {
        font-size: 1rem;
    }
    
    .stats-item {
        flex-basis: 100%;
        justify-content: center;
        margin-bottom: 1rem;
    }
    
    .btn-lg {
        padding: 0.5rem 2rem;
        font-size: 1rem;
    }
    
    .about-content p {
        font-size: 0.95rem;
    }
}

@media (max-width: 575.98px) {
    .display-5 {
        font-size: 1.75rem;
    }
    
    .display-6 {
        font-size: 1.5rem;
    }
    
    .btn {
        width: 100%;
    }
}
</style>