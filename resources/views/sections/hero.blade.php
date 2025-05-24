<section class="hero-section position-relative overflow-hidden">
    <!-- Background gradient overlay -->
    <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient-primary-light"></div>
    
    <div class="container py-3 position-relative">
        <div class="hero-card animate__animated animate__fadeInUp">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 order-lg-1 order-2">
                    <div class="hero-content">
                        <span class="badge bg-primary-light text-primary mb-3 px-3 py-2 rounded-pill">Premium Quality Spices</span>
                        <h1 class="display-4 fw-bold mb-3">Elevate Your <span class="text-primary">Culinary Journey</span></h1>
                        <p class="lead mb-4">Discover our handpicked collection of premium spices sourced from around the world to transform everyday meals into extraordinary culinary experiences.</p>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="{{ route('shop') }}" class="btn btn-success btn-lg px-4 rounded-pill">
                                Explore Collection
                            </a>
                            <a href="{{ route('about') }}" class="btn btn-outline-success btn-lg px-4 rounded-pill">
                                Our Story
                            </a>
                        </div>
                        <div class="mt-4 d-flex align-items-center">
                            <div class="ratings me-2">
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                            </div>
                            <span class="text-muted">| Trusted by 10,000+ chefs worldwide</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 order-lg-2 order-1 text-center text-lg-end">
                    <div class="position-relative">
                        <div class="hero-image-container">
                            <img src="{{ asset('images/categories/hero.png') }}" alt="Premium Spices Collection" class="img-fluid rounded-4 hero-main-image" onerror="this.onerror=null; this.src='https://via.placeholder.com/800x600/FF5722/FFFFFF?text=Premium+Spices'; this.className='img-fluid rounded-4'">
                            
                            <!-- Decorative elements -->
                            <div class="position-absolute hero-floating-spice hero-spice-1">
                                <div class=" p-3">
                                    <img src="{{ asset('images/categories/rc.png') }}" class="rounded-circle" alt="chilly" width="240">
                                </div>
                            </div>
                            <div class="position-absolute hero-floating-spice hero-spice-2">
                                <div class=" p-3">
                                    <img src="{{ asset('images/categories/turmeric.png') }}" class="rounded-circle" alt="turmeric" width="200">
                                </div>
                            </div>
                            <div class="position-absolute hero-floating-spice hero-spice-3">
                                <div class="p-3">
                                    <img src="{{ asset('images/categories/organic-icon.png') }}" class="rounded-circle" alt="organic" width="120">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    .hero-section {
        background-color: #f8f9fa;
        padding: 40px 0 80px 0;
        min-height: 85vh;
        display: flex;
        align-items: center;
    }

    .bg-gradient-primary-light {
        background: linear-gradient(135deg, rgba(255,255,255,1) 0%, rgba(240,250,245,1) 100%);
    }

    .bg-primary-light {
        background-color: rgba(34, 139, 34, 0.1);
    }

    .text-primary {
        color: #228B22 !important;
    }


    .hero-content {
        z-index: 1;
    }

    .hero-main-image {
        max-height: 90vh;
        width: 100%;
        object-fit: cover;
        z-index: 1;
        transform: scale(1.1);
    }

    .hero-floating-spice {
        z-index: 2;
        transition: all 0.5s ease;
    }

    .hero-spice-1 {
        top: 0%;
        left:-20%;
        animation: float1 8s ease-in-out infinite;
        rotate: 90%
    }

    .hero-spice-2 {
        top:10%;
        right: -10%;
        animation: float2 7s ease-in-out infinite;
    }

    .hero-spice-3 {
        bottom: 10%;
        left: -10%;
        animation: float3 6s ease-in-out infinite;
    }

    @keyframes float1 {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(5deg); }
    }

    @keyframes float2 {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(20px) rotate(-5deg); }
    }

    @keyframes float3 {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(3deg); }
    }

    @media (max-width: 991.98px) {
        .hero-section {
            padding: 20px 0 60px 0;
            min-height: auto;
        }
        
        .hero-card {
            margin-top: 0;
        }
        
        .hero-main-image {
            max-height: 60vh;
            margin-bottom: 30px;
            transform: scale(1.05);
        }
        
        .hero-floating-spice {
            display: none;
        }
    }

    /* Add animate.css classes */
    .animate__animated {
        animation-duration: 1s;
    }

    .animate__fadeInLeft {
        animation-name: fadeInLeft;
    }

    .animate__fadeInRight {
        animation-name: fadeInRight;
    }

    .animate__fadeInUp {
        animation-name: fadeInUp;
    }

    @keyframes fadeInLeft {
        from {
            opacity: 0;
            transform: translate3d(-50px, 0, 0);
        }
        to {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }
    }

    @keyframes fadeInRight {
        from {
            opacity: 0;
            transform: translate3d(50px, 0, 0);
        }
        to {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translate3d(0, 40px, 0);
        }
        to {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }
    }
    </style>
</section>