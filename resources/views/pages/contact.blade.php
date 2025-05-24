@extends('layouts.app')

@section('title', 'Contact Us - Flavearth')

@section('content')
<!-- Contact Hero Section -->
<section class="contact-hero-section position-relative">
    <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient-green"></div>
    
    <div class="container py-5 position-relative">
        <div class="text-center text-white">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb justify-content-center bg-transparent">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                    <li class="breadcrumb-item active text-white" aria-current="page">Contact Us</li>
                </ol>
            </nav>
            <h1 class="display-4 fw-bold mb-3">Get In Touch</h1>
            <p class="lead mb-0">We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
        </div>
    </div>
</section>

<!-- Contact Information Cards -->
<section class="contact-info-section py-5">
    <div class="container">
        <div class="row g-4 mb-5">
            <!-- Phone Contact -->
            <div class="col-lg-3 col-md-6">
                <div class="contact-info-card h-100 text-center">
                    <div class="contact-icon mb-3">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <h4 class="h5 fw-bold mb-3">Call Us</h4>
                    <div class="contact-details">
                        <a href="tel:+918484089076" class="contact-link d-block mb-2">+91 8484089076</a>
                        <a href="tel:+918484089077" class="contact-link d-block">+91 8484089077</a>
                    </div>
                    <p class="text-muted small mt-2">Mon - Sat: 9:00 AM - 7:00 PM</p>
                </div>
            </div>

            <!-- Email Contact -->
            <div class="col-lg-3 col-md-6">
                <div class="contact-info-card h-100 text-center">
                    <div class="contact-icon mb-3">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h4 class="h5 fw-bold mb-3">Email Us</h4>
                    <div class="contact-details">
                        <a href="mailto:flavearth@gmail.com" class="contact-link d-block">flavearth@gmail.com</a>
                    </div>
                    <p class="text-muted small mt-2">We'll respond within 24 hours</p>
                </div>
            </div>

            <!-- Address -->
            <div class="col-lg-3 col-md-6">
                <div class="contact-info-card h-100 text-center">
                    <div class="contact-icon mb-3">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h4 class="h5 fw-bold mb-3">Visit Us</h4>
                    <div class="contact-details">
                        <address class="mb-0">
                            Fatima Nagar Wanowrie,<br>
                            Pune, Maharashtra<br>
                            411040, India
                        </address>
                    </div>
                    <p class="text-muted small mt-2">Business Hours: 9 AM - 7 PM</p>
                </div>
            </div>

            <!-- Social Media -->
            <div class="col-lg-3 col-md-6">
                <div class="contact-info-card h-100 text-center">
                    <div class="contact-icon mb-3">
                        <i class="fas fa-share-alt"></i>
                    </div>
                    <h4 class="h5 fw-bold mb-3">Follow Us</h4>
                    <div class="social-links">
                        <a href="#" class="social-link me-2" title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-link me-2" title="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-link me-2" title="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="social-link" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                    <p class="text-muted small mt-2">Stay connected with us</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Form & Map Section -->
<section class="contact-form-section py-5 bg-light">
    <div class="container">
        <div class="row g-5">
            <!-- Contact Form -->
            <div class="col-lg-6">
                <div class="contact-form-container">
                    <div class="section-header mb-4">
                        <h2 class="h3 fw-bold mb-3">Send Us a Message</h2>
                        <p class="text-muted">Have a question about our premium spices? We'd love to help you find the perfect ingredients for your culinary journey.</p>
                    </div>

                    @if(session('success'))
                    <div class="alert alert-success d-flex align-items-center mb-4">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('contact.send') }}" method="POST" class="contact-form">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">Full Name *</label>
                                <input type="text" class="form-control form-control-lg" id="name" name="name" value="{{ old('name') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Email Address *</label>
                                <input type="email" class="form-control form-control-lg" id="email" name="email" value="{{ old('email') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold">Phone Number</label>
                                <input type="tel" class="form-control form-control-lg" id="phone" name="phone" value="{{ old('phone') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="subject" class="form-label fw-semibold">Subject *</label>
                                <select class="form-select form-select-lg" id="subject" name="subject" required>
                                    <option value="">Select a subject</option>
                                    <option value="Product Inquiry" {{ old('subject') == 'Product Inquiry' ? 'selected' : '' }}>Product Inquiry</option>
                                    <option value="Bulk Order" {{ old('subject') == 'Bulk Order' ? 'selected' : '' }}>Bulk Order</option>
                                    <option value="Quality Question" {{ old('subject') == 'Quality Question' ? 'selected' : '' }}>Quality Question</option>
                                    <option value="Partnership" {{ old('subject') == 'Partnership' ? 'selected' : '' }}>Partnership Opportunity</option>
                                    <option value="Custom Blend" {{ old('subject') == 'Custom Blend' ? 'selected' : '' }}>Custom Spice Blend</option>
                                    <option value="Support" {{ old('subject') == 'Support' ? 'selected' : '' }}>Customer Support</option>
                                    <option value="Other" {{ old('subject') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="message" class="form-label fw-semibold">Message *</label>
                                <textarea class="form-control form-control-lg" id="message" name="message" rows="6" placeholder="Tell us about your spice needs, preferred quantities, or any specific questions you have..." required>{{ old('message') }}</textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-success btn-lg px-5 rounded-pill submit-btn">
                                    <i class="fas fa-paper-plane me-2"></i>Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Map & Additional Info -->
            <div class="col-lg-6">
                <div class="map-container mb-4">
                    <h3 class="h4 fw-bold mb-3">Find Our Location</h3>
                    <div class="map-wrapper rounded-4 overflow-hidden shadow-sm">
                        <!-- Google Maps Embed for Pune, Maharashtra -->
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3784.506712347956!2d73.8762!3d18.4649!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bc2eaf37f36f5dd%3A0x4a6b48afc4a4b1d9!2sFatima%20Nagar%2C%20Wanowrie%2C%20Pune%2C%20Maharashtra%20411040!5e0!3m2!1sen!2sin!4v1234567890"
                            width="100%" 
                            height="300" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>

                <!-- Business Hours -->
                <div class="business-hours-card">
                    <h3 class="h4 fw-bold mb-3">Business Hours</h3>
                    <div class="hours-list">
                        <div class="hours-item d-flex justify-content-between">
                            <span>Monday - Friday</span>
                            <span class="fw-semibold">9:00 AM - 7:00 PM</span>
                        </div>
                        <div class="hours-item d-flex justify-content-between">
                            <span>Saturday</span>
                            <span class="fw-semibold">9:00 AM - 6:00 PM</span>
                        </div>
                        <div class="hours-item d-flex justify-content-between">
                            <span>Sunday</span>
                            <span class="fw-semibold text-muted">Closed</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Contact Info -->
                <div class="quick-contact mt-4">
                    <h3 class="h4 fw-bold mb-3">Need Immediate Assistance?</h3>
                    <div class="quick-contact-grid">
                        <div class="quick-contact-item">
                            <i class="fas fa-phone text-success me-2"></i>
                            <strong>Call:</strong> <a href="tel:+918484089076">+91 8484089076</a>
                        </div>
                        <div class="quick-contact-item">
                            <i class="fas fa-whatsapp text-success me-2"></i>
                            <strong>WhatsApp:</strong> <a href="https://wa.me/918484089076">+91 8484089076</a>
                        </div>
                        <div class="quick-contact-item">
                            <i class="fas fa-envelope text-success me-2"></i>
                            <strong>Email:</strong> <a href="mailto:flavearth@gmail.com">flavearth@gmail.com</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us for Contact -->
<section class="contact-cta-section py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="h3 fw-bold mb-3">Why Our Customers Love Working With Us</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="feature-item">
                            <i class="fas fa-clock text-success mb-2"></i>
                            <h4 class="h6 fw-bold">Quick Response</h4>
                            <p class="small text-muted mb-0">We respond to all inquiries within 24 hours</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-item">
                            <i class="fas fa-user-tie text-success mb-2"></i>
                            <h4 class="h6 fw-bold">Expert Guidance</h4>
                            <p class="small text-muted mb-0">Our spice experts help you find the right products</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-item">
                            <i class="fas fa-shipping-fast text-success mb-2"></i>
                            <h4 class="h6 fw-bold">Fast Delivery</h4>
                            <p class="small text-muted mb-0">Quick and secure shipping across India</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('shop') }}" class="btn btn-success btn-lg rounded-pill px-4">
                    <i class="fas fa-shopping-bag me-2"></i>Browse Our Products
                </a>
            </div>
        </div>
    </div>
</section>

<style>
/* Contact Hero Section */
.contact-hero-section {
    background: linear-gradient(135deg, #228B22 0%, #32CD32 100%);
    padding: 100px 0 60px 0;
}

.bg-gradient-green {
    background: linear-gradient(135deg, rgba(34, 139, 34, 0.9) 0%, rgba(50, 205, 50, 0.9) 100%);
}

/* Contact Info Cards */
.contact-info-section {
    margin-top: -40px;
    position: relative;
    z-index: 2;
}

.contact-info-card {
    background: white;
    border-radius: 20px;
    padding: 40px 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    border: 1px solid rgba(0,0,0,0.05);
}

.contact-info-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 60px rgba(0,0,0,0.15);
}

.contact-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #228B22, #32CD32);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    color: white;
    font-size: 1.5rem;
}

.contact-link {
    color: #228B22;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
}

.contact-link:hover {
    color: #32CD32;
}

.social-links {
    display: flex;
    justify-content: center;
    gap: 10px;
}

.social-link {
    width: 40px;
    height: 40px;
    background: #f8f9fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #228B22;
    text-decoration: none;
    transition: all 0.3s ease;
}

.social-link:hover {
    background: #228B22;
    color: white;
    transform: translateY(-3px);
}

/* Contact Form */
.contact-form-container {
    background: white;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
}

.form-control-lg, .form-select-lg {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    padding: 15px 20px;
    transition: border-color 0.3s ease;
}

.form-control-lg:focus, .form-select-lg:focus {
    border-color: #228B22;
    box-shadow: 0 0 0 0.2rem rgba(34, 139, 34, 0.25);
}

.submit-btn {
    background: linear-gradient(135deg, #228B22 0%, #32CD32 100%);
    border: none;
    transition: all 0.3s ease;
}

.submit-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(34, 139, 34, 0.3);
}

/* Map and Business Hours */
.map-wrapper {
    border: 3px solid #f8f9fa;
}

.business-hours-card {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.hours-item {
    padding: 10px 0;
    border-bottom: 1px solid #f8f9fa;
}

.hours-item:last-child {
    border-bottom: none;
}

.quick-contact {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.quick-contact-item {
    padding: 8px 0;
    display: flex;
    align-items: center;
}

.quick-contact-item a {
    color: #228B22;
    text-decoration: none;
    margin-left: 5px;
}

.quick-contact-item a:hover {
    color: #32CD32;
}

/* Features Section */
.contact-cta-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.feature-item {
    text-align: center;
}

.feature-item i {
    font-size: 2rem;
}

/* Responsive Design */
@media (max-width: 991.98px) {
    .contact-hero-section {
        padding: 80px 0 40px 0;
    }
    
    .contact-info-section {
        margin-top: -20px;
    }
    
    .contact-info-card {
        padding: 30px 20px;
        margin-bottom: 20px;
    }
    
    .contact-form-container,
    .business-hours-card,
    .quick-contact {
        padding: 25px;
        margin-bottom: 30px;
    }
}

@media (max-width: 767.98px) {
    .contact-info-card {
        padding: 25px 15px;
    }
    
    .contact-form-container {
        padding: 20px;
    }
    
    .map-wrapper iframe {
        height: 250px;
    }
    
    .social-links {
        gap: 15px;
    }
    
    .quick-contact-item {
        flex-direction: column;
        align-items: flex-start;
        text-align: left;
    }
}

/* Animation for form submission */
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.submit-btn:active {
    animation: pulse 0.3s ease;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form submission handling
    const contactForm = document.querySelector('.contact-form');
    const submitBtn = document.querySelector('.submit-btn');
    
    contactForm.addEventListener('submit', function(e) {
        // Add loading state
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
        submitBtn.disabled = true;
        
        // Form will submit normally, but we show loading state
        setTimeout(() => {
            if (!submitBtn.disabled) {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        }, 3000);
    });
    
    // Phone number formatting
    const phoneInput = document.getElementById('phone');
    phoneInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 10) {
            value = value.substring(0, 10);
            e.target.value = value.replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
        }
    });
    
    // Smooth scroll to form on CTA click
    const scrollToForm = document.querySelector('a[href="#contact-form"]');
    if (scrollToForm) {
        scrollToForm.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelector('.contact-form-section').scrollIntoView({
                behavior: 'smooth'
            });
        });
    }
});
</script>
@endsection