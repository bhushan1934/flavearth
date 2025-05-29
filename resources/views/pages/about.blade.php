@extends('layouts.app')

@section('title', 'About Us - Flavearth')

@section('content')
<!-- About Hero Section -->
<section class="about-hero-section position-relative">
    <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient-green"></div>
    
    <div class="container py-5 position-relative">
        <div class="text-center text-white">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb justify-content-center bg-transparent">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                    <li class="breadcrumb-item active text-white" aria-current="page">About Us</li>
                </ol>
            </nav>
            <h1 class="display-4 fw-bold mb-3">About Flavearth</h1>
            <p class="lead mb-0">Delivering authentic Indian spices to the world with quality, tradition, and innovation</p>
        </div>
    </div>
</section>

<!-- Company Overview -->
<section class="company-overview-section py-5">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="company-story">
                    <span class="badge bg-success bg-opacity-10 text-success mb-3 px-3 py-2 rounded-pill">Our Story</span>
                    <h2 class="display-5 fw-bold mb-4">Flavearth Private Limited</h2>
                    <p class="lead mb-4">Founded in May 2023, Flavearth Private Limited is a dynamic spice export company based in Pune, Maharashtra, India. We specialize in delivering high-quality Indian spices to global markets, bridging the gap between traditional Indian spice heritage and modern international demands.</p>
                    
                    <p class="mb-4">As a forward-thinking spice export business, we are committed to enhancing the global spice trade by continuously improving our sourcing, processing, and distribution methods. Our expertise spans across B2B spice export, B2C retail sales, and customized spice blends.</p>
                    
                    <div class="company-highlights">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="highlight-item">
                                    <i class="fas fa-calendar-alt text-success me-2"></i>
                                    <strong>Founded:</strong> May 2023
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="highlight-item">
                                    <i class="fas fa-map-marker-alt text-success me-2"></i>
                                    <strong>Location:</strong> Pune, Maharashtra
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="highlight-item">
                                    <i class="fas fa-globe text-success me-2"></i>
                                    <strong>Focus:</strong> Global Spice Export
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="highlight-item">
                                    <i class="fas fa-award text-success me-2"></i>
                                    <strong>Specialty:</strong> Premium Quality
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="company-image-container">
                    <div class="main-image">
                        <img src="{{ asset('images/categories/Export.png') }}" alt="Flavearth Company" class="img-fluid rounded-4 shadow-lg">
                    </div>
                    {{-- <div class="floating-elements">
                        <div class="floating-spice spice-1">
                            <img src="{{ asset('images/categories/rc.png') }}" alt="Red Chili" class="rounded-circle" width="80">
                        </div>
                        <div class="floating-spice spice-2">
                            <img src="{{ asset('images/categories/turmeric.png') }}" alt="Turmeric" class="rounded-circle" width="60">
                        </div>
                        <div class="floating-spice spice-3">
                            <img src="{{ asset('images/categories/organic-icon.png') }}" alt="Organic" class="rounded-circle" width="50">
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission, Vision & Values -->
<section class="mission-vision-section py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">Our Mission, Vision & Values</h2>
            <p class="lead text-muted">The driving forces behind everything we do</p>
        </div>
        
        <div class="row g-4">
            <!-- Mission -->
            <div class="col-lg-4">
                <div class="mission-card h-100 text-center">
                    <div class="mission-icon mb-4">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h3 class="h4 fw-bold mb-3">Our Mission</h3>
                    <p class="mb-4">"To enhance the global spice trade by continuously improving our sourcing, processing, and distribution methods."</p>
                    <ul class="list-unstyled text-start">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Deliver premium quality spices</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Build strong international partnerships</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Educate customers about Indian spice heritage</li>
                    </ul>
                </div>
            </div>

            <!-- Vision -->
            <div class="col-lg-4">
                <div class="vision-card h-100 text-center">
                    <div class="vision-icon mb-4">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3 class="h4 fw-bold mb-3">Our Vision</h3>
                    <p class="mb-4">To become the global leader in authentic Indian spice exports, setting new standards for quality, sustainability, and innovation in the spice industry.</p>
                    <ul class="list-unstyled text-start">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Expand product offerings globally</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Push boundaries of innovation</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Support environmental sustainability</li>
                    </ul>
                </div>
            </div>

            <!-- Values -->
            <div class="col-lg-4">
                <div class="values-card h-100 text-center">
                    <div class="values-icon mb-4">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3 class="h4 fw-bold mb-3">Our Values</h3>
                    <p class="mb-4">Core principles that guide our business practices and customer relationships every day.</p>
                    <ul class="list-unstyled text-start">
                        <li class="mb-2"><i class="fas fa-star text-warning me-2"></i>Quality and Authenticity</li>
                        <li class="mb-2"><i class="fas fa-graduation-cap text-info me-2"></i>Customer Education</li>
                        <li class="mb-2"><i class="fas fa-leaf text-success me-2"></i>Sustainability</li>
                        <li class="mb-2"><i class="fas fa-lightbulb text-warning me-2"></i>Innovation in Spice Trade</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="services-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-success bg-opacity-10 text-success mb-3 px-3 py-2 rounded-pill">What We Offer</span>
            <h2 class="display-5 fw-bold mb-3">Our Key Services</h2>
            <p class="lead text-muted">Comprehensive spice solutions for businesses and consumers worldwide</p>
        </div>
        
        <div class="row g-4">
            <!-- B2B Export -->
            <div class="col-lg-4 col-md-6">
                <div class="service-card h-100">
                    <div class="service-icon mb-3">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h3 class="h5 fw-bold mb-3">B2B Spice Export</h3>
                    <p class="mb-4">Wholesale export of premium Indian spices to international markets. We partner with distributors, retailers, and food manufacturers worldwide.</p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-arrow-right text-success me-2"></i>Bulk quantities available</li>
                        <li class="mb-2"><i class="fas fa-arrow-right text-success me-2"></i>Flexible packaging options</li>
                        <li class="mb-2"><i class="fas fa-arrow-right text-success me-2"></i>Competitive pricing</li>
                        <li class="mb-2"><i class="fas fa-arrow-right text-success me-2"></i>Reliable supply chain</li>
                    </ul>
                </div>
            </div>

            <!-- B2C Retail -->
            <div class="col-lg-4 col-md-6">
                <div class="service-card h-100">
                    <div class="service-icon mb-3">
                        <i class="fas fa-store"></i>
                    </div>
                    <h3 class="h5 fw-bold mb-3">B2C Retail Sales</h3>
                    <p class="mb-4">Direct-to-consumer sales of premium spices through online platforms, bringing authentic Indian flavors to home kitchens globally.</p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-arrow-right text-success me-2"></i>Premium consumer packaging</li>
                        <li class="mb-2"><i class="fas fa-arrow-right text-success me-2"></i>Fast worldwide shipping</li>
                        <li class="mb-2"><i class="fas fa-arrow-right text-success me-2"></i>Educational content included</li>
                        <li class="mb-2"><i class="fas fa-arrow-right text-success me-2"></i>Customer support</li>
                    </ul>
                </div>
            </div>

            <!-- Custom Blends -->
            <div class="col-lg-4 col-md-6">
                <div class="service-card h-100">
                    <div class="service-icon mb-3">
                        <i class="fas fa-palette"></i>
                    </div>
                    <h3 class="h5 fw-bold mb-3">Customized Spice Blends</h3>
                    <p class="mb-4">Bespoke spice blends created according to specific requirements for restaurants, food manufacturers, and culinary professionals.</p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-arrow-right text-success me-2"></i>Recipe development</li>
                        <li class="mb-2"><i class="fas fa-arrow-right text-success me-2"></i>Private labeling options</li>
                        <li class="mb-2"><i class="fas fa-arrow-right text-success me-2"></i>Quality consistency</li>
                        <li class="mb-2"><i class="fas fa-arrow-right text-success me-2"></i>Scalable production</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quality & Process -->
<section class="quality-process-section py-5 bg-light">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="quality-content">
                    <span class="badge bg-success bg-opacity-10 text-success mb-3 px-3 py-2 rounded-pill">Quality Assurance</span>
                    <h2 class="display-5 fw-bold mb-4">Our Commitment to Quality</h2>
                    <p class="lead mb-4">We maintain the highest quality standards throughout our sourcing, processing, and distribution chain to ensure that every grain of spice meets international quality benchmarks.</p>
                    
                    <div class="quality-features">
                        <div class="quality-feature mb-3">
                            <div class="d-flex align-items-start">
                                <div class="feature-icon me-3">
                                    <i class="fas fa-seedling text-success"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-2">Sustainable Sourcing</h5>
                                    <p class="text-muted mb-0">Direct partnerships with farmers to ensure quality from farm to table while supporting sustainable agriculture practices.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="quality-feature mb-3">
                            <div class="d-flex align-items-start">
                                <div class="feature-icon me-3">
                                    <i class="fas fa-microscope text-success"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-2">Quality Testing</h5>
                                    <p class="text-muted mb-0">Rigorous testing at every stage to ensure purity, potency, and adherence to international food safety standards.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="quality-feature mb-3">
                            <div class="d-flex align-items-start">
                                <div class="feature-icon me-3">
                                    <i class="fas fa-shield-alt text-success"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-2">Food Safety Compliance</h5>
                                    <p class="text-muted mb-0">Full compliance with international food safety regulations and certifications for global market access.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="process-timeline">
                    <h3 class="h4 fw-bold mb-4">Our Process</h3>
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker">1</div>
                            <div class="timeline-content">
                                <h5 class="fw-bold">Sourcing</h5>
                                <p class="text-muted">Careful selection of premium spices from trusted farmers and suppliers across India.</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker">2</div>
                            <div class="timeline-content">
                                <h5 class="fw-bold">Processing</h5>
                                <p class="text-muted">Traditional and modern processing methods to preserve flavor, aroma, and nutritional value.</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker">3</div>
                            <div class="timeline-content">
                                <h5 class="fw-bold">Quality Control</h5>
                                <p class="text-muted">Multiple quality checks and testing to ensure product excellence and safety.</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker">4</div>
                            <div class="timeline-content">
                                <h5 class="fw-bold">Packaging & Distribution</h5>
                                <p class="text-muted">Professional packaging and efficient global distribution to maintain freshness.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Company Stats -->
<section class="company-stats-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">Flavearth by Numbers</h2>
            <p class="lead text-muted">Growing impact in the global spice industry</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="stat-card text-center">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-calendar-check text-success"></i>
                    </div>
                    <div class="stat-number h2 fw-bold text-success">2023</div>
                    <div class="stat-label text-muted">Founded</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card text-center">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-box text-warning"></i>
                    </div>
                    <div class="stat-number h2 fw-bold text-warning">50+</div>
                    <div class="stat-label text-muted">Product Varieties</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card text-center">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-globe text-primary"></i>
                    </div>
                    <div class="stat-number h2 fw-bold text-primary">25+</div>
                    <div class="stat-label text-muted">Countries Served</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card text-center">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-users text-info"></i>
                    </div>
                    <div class="stat-number h2 fw-bold text-info">1000+</div>
                    <div class="stat-label text-muted">Happy Customers</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="about-cta-section py-5 bg-success text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="h3 fw-bold mb-3">Ready to Experience Authentic Indian Spices?</h2>
                <p class="lead mb-0">Join thousands of customers worldwide who trust Flavearth for their spice needs. From traditional home cooking to professional culinary applications, we have the perfect spices for you.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                <div class="cta-buttons">
                    <a href="{{ route('shop') }}" class="btn btn-light btn-lg rounded-pill px-4 me-3">
                        <i class="fas fa-shopping-bag me-2"></i>Shop Now
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg rounded-pill px-4">
                        <i class="fas fa-envelope me-2"></i>Contact Us
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* About Hero Section */
.about-hero-section {
    background: linear-gradient(135deg, #228B22 0%, #32CD32 100%);
    padding: 100px 0 60px 0;
}

.bg-gradient-green {
    background: linear-gradient(135deg, rgba(34, 139, 34, 0.9) 0%, rgba(50, 205, 50, 0.9) 100%);
}

/* Company Overview */
.company-overview-section {
    position: relative;
    z-index: 2;
    margin-top: -40px;
}

.company-story {
    background: white;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
}

.highlight-item {
    padding: 8px 0;
    border-bottom: 1px solid #f8f9fa;
}

.highlight-item:last-child {
    border-bottom: none;
}

.company-image-container {
    position: relative;
}

.floating-elements {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
}

.floating-spice {
    position: absolute;
    animation: float 6s ease-in-out infinite;
}

.spice-1 {
    top: 10%;
    right: -10%;
    animation-delay: 0s;
}

.spice-2 {
    bottom: 20%;
    left: -5%;
    animation-delay: 2s;
}

.spice-3 {
    top: 50%;
    right: 5%;
    animation-delay: 4s;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(5deg); }
}

/* Mission, Vision, Values */
.mission-card, .vision-card, .values-card {
    background: white;
    padding: 40px 30px;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    transition: transform 0.3s ease;
}

.mission-card:hover, .vision-card:hover, .values-card:hover {
    transform: translateY(-10px);
}

.mission-icon, .vision-icon, .values-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #228B22, #32CD32);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    color: white;
    font-size: 1.8rem;
}

/* Services */
.service-card {
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border-left: 4px solid #228B22;
}

.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.12);
}

.service-icon {
    width: 60px;
    height: 60px;
    background: #f8f9fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #228B22;
    font-size: 1.5rem;
}

/* Quality & Process */
.feature-icon {
    width: 40px;
    height: 40px;
    background: #f8f9fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #228B22;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 0;
    width: 30px;
    height: 30px;
    background: #228B22;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 0.9rem;
}

.timeline-content {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    margin-left: 20px;
}

/* Company Stats */
.stat-card {
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-icon {
    font-size: 2.5rem;
}

.stat-number {
    font-size: 3rem;
    margin: 10px 0;
}

/* CTA Section */
.about-cta-section {
    background: linear-gradient(135deg, #228B22 0%, #32CD32 100%);
}

/* Responsive Design */
@media (max-width: 991.98px) {
    .about-hero-section {
        padding: 80px 0 40px 0;
    }
    
    .company-overview-section {
        margin-top: -20px;
    }
    
    .company-story {
        padding: 30px;
        margin-bottom: 40px;
    }
    
    .mission-card, .vision-card, .values-card {
        padding: 30px 20px;
        margin-bottom: 30px;
    }
    
    .floating-spice {
        display: none;
    }
}

@media (max-width: 767.98px) {
    .company-story {
        padding: 20px;
    }
    
    .timeline {
        padding-left: 20px;
    }
    
    .timeline-marker {
        left: -15px;
        width: 25px;
        height: 25px;
    }
    
    .timeline-content {
        margin-left: 15px;
        padding: 15px;
    }
    
    .cta-buttons {
        text-align: center;
    }
    
    .cta-buttons .btn {
        display: block;
        margin: 10px 0;
        width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate stats on scroll
    const statNumbers = document.querySelectorAll('.stat-number');
    
    const animateStats = () => {
        statNumbers.forEach(stat => {
            const rect = stat.getBoundingClientRect();
            if (rect.top < window.innerHeight && rect.bottom > 0) {
                const finalNumber = stat.textContent;
                if (!stat.classList.contains('animated')) {
                    stat.classList.add('animated');
                    animateNumber(stat, finalNumber);
                }
            }
        });
    };
    
    const animateNumber = (element, finalNumber) => {
        const isYear = finalNumber.includes('2023');
        const hasPlus = finalNumber.includes('+');
        const number = parseInt(finalNumber.replace(/[^0-9]/g, ''));
        
        if (isYear) {
            element.textContent = finalNumber;
            return;
        }
        
        let current = 0;
        const increment = number / 50;
        const timer = setInterval(() => {
            current += increment;
            if (current >= number) {
                current = number;
                clearInterval(timer);
            }
            element.textContent = Math.floor(current) + (hasPlus ? '+' : '');
        }, 50);
    };
    
    // Initial check
    animateStats();
    
    // Check on scroll
    window.addEventListener('scroll', animateStats);
    
    // Smooth scroll for internal links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>
@endsection