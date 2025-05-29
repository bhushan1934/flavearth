<section class="newsletter-section py-5">
    <div class="container">
        <div class="newsletter-wrapper">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="newsletter-content">
                        <span class="badge bg-white text-success mb-3 px-3 py-2 rounded-pill">
                            <i class="fas fa-envelope me-2"></i>Newsletter
                        </span>
                        <h2 class="display-6 fw-bold text-white mb-3">Stay in the Spice Loop</h2>
                        <p class="text-white-50 lead">
                            Join our culinary community and be the first to know about new arrivals, exclusive recipes, and special offers delivered straight to your inbox.
                        </p>
                        <div class="newsletter-features mt-4">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="feature-item">
                                        <div class="feature-icon">
                                            <i class="fas fa-gift"></i>
                                        </div>
                                        <div class="feature-content">
                                            <h5 class="feature-title">Exclusive Benefits</h5>
                                            <p class="feature-text">Get special discounts & early access to new products</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="feature-item">
                                        <div class="feature-icon">
                                            <i class="fas fa-book-open"></i>
                                        </div>
                                        <div class="feature-content">
                                            <h5 class="feature-title">Culinary Inspiration</h5>
                                            <p class="feature-text">Weekly recipes & professional cooking tips</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="feature-item">
                                        <div class="feature-icon">
                                            <i class="fas fa-leaf"></i>
                                        </div>
                                        <div class="feature-content">
                                            <h5 class="feature-title">Stay Updated</h5>
                                            <p class="feature-text">Be first to know about new product launches</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="newsletter-form-wrapper">
                        <form action="{{ route('newsletter.subscribe') }}" method="POST" class="newsletter-form">
                            @csrf
                            <div class="form-group mb-4">
                                <label for="newsletter-name" class="form-label text-white fw-medium mb-2">Your Name</label>
                                <input type="text" 
                                       class="form-control form-control-lg" 
                                       id="newsletter-name" 
                                       name="name" 
                                       placeholder="John Doe"
                                       required>
                            </div>
                            <div class="form-group mb-4">
                                <label for="newsletter-email" class="form-label text-white fw-medium mb-2">Email Address *</label>
                                <input type="email" 
                                       class="form-control form-control-lg" 
                                       id="newsletter-email" 
                                       name="email" 
                                       placeholder="john@example.com"
                                       required>
                            </div>
                            <button type="submit" class="btn btn-white btn-lg w-100 rounded-pill py-3">
                                <i class="fas fa-paper-plane me-2"></i>Subscribe Now
                            </button>
                            <p class="text-white-50 text-center mt-3 mb-0 small">
                                <i class="fas fa-lock me-1"></i>We respect your privacy. Unsubscribe anytime.
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Decorative Elements -->
    <div class="newsletter-decoration newsletter-decoration-1">
        <i class="fas fa-pepper-hot"></i>
    </div>
    <div class="newsletter-decoration newsletter-decoration-2">
        <i class="fas fa-seedling"></i>
    </div>
</section>

<style>
.newsletter-section {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    position: relative;
    overflow: hidden;
}

.newsletter-wrapper {
    position: relative;
    z-index: 2;
}

.newsletter-content h2 {
    line-height: 1.2;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    transition: all 0.3s ease;
}

.feature-item:hover {
    background: rgba(255, 255, 255, 0.15);
    transform: translateX(5px);
}

.feature-icon {
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.3rem;
    flex-shrink: 0;
}

.feature-content {
    flex: 1;
}

.feature-title {
    color: white;
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.feature-text {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.9rem;
    margin-bottom: 0;
}

.newsletter-form-wrapper {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    padding: 40px;
}

.newsletter-form .form-control {
    background: rgba(255, 255, 255, 0.9);
    border: 2px solid transparent;
    border-radius: 10px;
    padding: 12px 20px;
    transition: all 0.3s ease;
}

.newsletter-form .form-control:focus {
    background: white;
    border-color: white;
    box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.25);
}

.newsletter-form .form-control::placeholder {
    color: #6c757d;
}

.btn-white {
    background: white;
    color: #28a745;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-white:hover {
    background: #f8f9fa;
    color: #28a745;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.text-white-50 {
    color: rgba(255, 255, 255, 0.8) !important;
}

/* Decorative Elements */
.newsletter-decoration {
    position: absolute;
    font-size: 8rem;
    color: rgba(255, 255, 255, 0.1);
    z-index: 1;
}

.newsletter-decoration-1 {
    top: -30px;
    right: -30px;
    transform: rotate(25deg);
    animation: float 8s ease-in-out infinite;
}

.newsletter-decoration-2 {
    bottom: -40px;
    left: -20px;
    transform: rotate(-15deg);
    animation: float 10s ease-in-out infinite reverse;
}

@keyframes float {
    0%, 100% { transform: translateY(0) rotate(25deg); }
    50% { transform: translateY(-20px) rotate(25deg); }
}

/* Responsive Styles */
@media (max-width: 991.98px) {
    .newsletter-form-wrapper {
        padding: 30px;
    }
    
    .newsletter-content {
        text-align: center;
        margin-bottom: 30px;
    }
    
    .newsletter-features {
        justify-content: center;
    }
    
    .newsletter-features > div {
        justify-content: center;
    }
}

@media (max-width: 767.98px) {
    .newsletter-section {
        padding: 40px 0;
    }
    
    .display-6 {
        font-size: 1.75rem;
    }
    
    .lead {
        font-size: 1rem;
    }
    
    .newsletter-form-wrapper {
        padding: 25px;
        margin: 0 -10px;
    }
    
    .newsletter-decoration {
        font-size: 5rem;
    }
}

@media (max-width: 575.98px) {
    .newsletter-form-wrapper {
        padding: 20px;
    }
    
    .feature-item {
        padding: 12px;
    }
    
    .feature-icon {
        width: 40px;
        height: 40px;
        font-size: 1.1rem;
    }
    
    .feature-title {
        font-size: 1rem;
    }
    
    .feature-text {
        font-size: 0.85rem;
    }
}
</style>