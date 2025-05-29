<section class="contact-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-success bg-opacity-10 text-success mb-3 px-3 py-2 rounded-pill">Get In Touch</span>
            <h2 class="display-5 fw-bold mb-3">Contact Us</h2>
            <div class="separator my-3 mx-auto"></div>
            <p class="lead text-muted mx-auto" style="max-width: 600px;">
                Have questions about our products? We're here to help! Reach out to us and we'll get back to you as soon as possible.
            </p>
        </div>

        <div class="row g-5">
            <!-- Contact Information -->
            <div class="col-lg-5">
                <div class="contact-info-wrapper">
                    <h3 class="h4 fw-bold mb-4">Let's Connect</h3>
                    <p class="text-muted mb-4">
                        Whether you're looking for bulk orders, custom spice blends, or just have a question about our products, we'd love to hear from you.
                    </p>

                    <div class="contact-info-cards">
                        <!-- Address Card -->
                        <div class="info-card mb-4">
                            <div class="info-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="info-content">
                                <h5 class="fw-bold mb-2">Visit Our Store</h5>
                                <p class="text-muted mb-0">
                                    Fatima Nagar Wanowrie<br>
                                    Pune, Maharashtra 411040<br>
                                    India
                                </p>
                            </div>
                        </div>

                        <!-- Phone Card -->
                        <div class="info-card mb-4">
                            <div class="info-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="info-content">
                                <h5 class="fw-bold mb-2">Call Us</h5>
                                <p class="mb-1">
                                    <a href="tel:+918484089076" class="text-decoration-none text-muted">+91 8484089076</a>
                                </p>
                                <p class="mb-0">
                                    <a href="tel:+918484089077" class="text-decoration-none text-muted">+91 8484089077</a>
                                </p>
                            </div>
                        </div>

                        <!-- Email Card -->
                        <div class="info-card mb-4">
                            <div class="info-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="info-content">
                                <h5 class="fw-bold mb-2">Email Us</h5>
                                <p class="mb-0">
                                    <a href="mailto:flavearth@gmail.com" class="text-decoration-none text-muted">flavearth@gmail.com</a>
                                </p>
                            </div>
                        </div>

                        <!-- Business Hours -->
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="info-content">
                                <h5 class="fw-bold mb-2">Business Hours</h5>
                                <p class="mb-0 text-muted">
                                    Monday - Saturday: 9:00 AM - 7:00 PM<br>
                                    Sunday: 10:00 AM - 5:00 PM
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="social-links mt-4">
                        <h5 class="fw-bold mb-3">Follow Us</h5>
                        <div class="d-flex gap-3">
                            <a href="#" class="social-link">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="social-link">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="social-link">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="social-link">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-7">
                <div class="contact-form-wrapper">
                    <form action="{{ route('contact.send') }}" method="POST" class="contact-form">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label fw-medium">Your Name *</label>
                                    <input type="text" class="form-control form-control-lg" id="name" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-label fw-medium">Email Address *</label>
                                    <input type="email" class="form-control form-control-lg" id="email" name="email" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="form-label fw-medium">Phone Number</label>
                                    <input type="tel" class="form-control form-control-lg" id="phone" name="phone">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="subject" class="form-label fw-medium">Subject *</label>
                                    <select class="form-control form-control-lg" id="subject" name="subject" required>
                                        <option value="">Select a subject</option>
                                        <option value="general">General Inquiry</option>
                                        <option value="bulk">Bulk Orders</option>
                                        <option value="custom">Custom Spice Blends</option>
                                        <option value="partnership">Business Partnership</option>
                                        <option value="feedback">Feedback</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="message" class="form-label fw-medium">Your Message *</label>
                                    <textarea class="form-control form-control-lg" id="message" name="message" rows="5" required placeholder="Tell us more about your inquiry..."></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-success btn-lg px-5 py-3 rounded-pill">
                                    <i class="fas fa-paper-plane me-2"></i>Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.contact-section {
    background-color: #f8f9fa;
}

.separator {
    width: 60px;
    height: 4px;
    background: linear-gradient(to right, #28a745, #20c997);
    border-radius: 2px;
}

/* Contact Info Cards */
.contact-info-wrapper {
    background: white;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    height: 100%;
}

.info-card {
    display: flex;
    align-items: flex-start;
    gap: 20px;
}

.info-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #28a745, #20c997);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.info-content h5 {
    font-size: 1.1rem;
    color: #333;
}

.info-content a:hover {
    color: #28a745 !important;
}

/* Social Links */
.social-links {
    padding-top: 30px;
    border-top: 1px solid #e9ecef;
}

.social-link {
    width: 45px;
    height: 45px;
    background: #f8f9fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    text-decoration: none;
    transition: all 0.3s ease;
}

.social-link:hover {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    transform: translateY(-3px);
}

/* Contact Form */
.contact-form-wrapper {
    background: white;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
}

.form-control {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.1);
}

.form-label {
    color: #495057;
    margin-bottom: 8px;
}

/* Mobile Responsive */
@media (max-width: 991.98px) {
    .contact-info-wrapper,
    .contact-form-wrapper {
        padding: 30px;
    }
}

@media (max-width: 767.98px) {
    .contact-section {
        padding: 40px 0;
    }
    
    .display-5 {
        font-size: 2rem;
    }
    
    .lead {
        font-size: 1rem;
    }
    
    .contact-info-wrapper,
    .contact-form-wrapper {
        padding: 25px;
        margin-bottom: 30px;
    }
    
    .info-card {
        flex-direction: column;
        text-align: center;
        align-items: center;
    }
    
    .info-icon {
        margin-bottom: 15px;
    }
    
    .social-links {
        text-align: center;
    }
    
    .social-links .d-flex {
        justify-content: center;
    }
    
    .btn-lg {
        width: 100%;
    }
}

@media (max-width: 575.98px) {
    .contact-info-wrapper,
    .contact-form-wrapper {
        padding: 20px;
    }
    
    .info-icon {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
    
    .social-link {
        width: 40px;
        height: 40px;
    }
}
</style>