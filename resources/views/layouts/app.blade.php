<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Flavearth')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @stack('styles')
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .hero-section {
            background-color: #f8f9fa;
        }
        .product-card {
            transition: all 0.3s;
            margin-bottom: 20px;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .category-card {
            height: 200px;
            background-size: cover;
            background-position: center;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }
        .category-overlay {
            position: absolute;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        .about-section {
            background-color: #f8f9fa;
            padding: 80px 0;
        }
        .newsletter-section {
            padding: 60px 0;
            background-color: #17a2b8;
            color: white;
        }

        /* Enhanced Header Responsive Styles */
        .responsive-navbar {
            min-height: 80px;
            position: relative;
        }

        .navbar-brand-logo {
            display: flex;
            align-items: center;
            text-decoration: none;
            gap: 10px;
        }

        .brand-logo {
            width: 150px;
            height: 80px;
            border-radius: 50%;
            transition: transform 0.3s ease;
        }

        .brand-logo:hover {
            transform: scale(1.1);
        }

        .brand-text {
            font-size: 1.5rem;
            font-weight: 800;
            color: #28a745;
            margin: 0;
            display: none;
        }

        .navbar-toggler {
            border: none;
            padding: 8px 12px;
            font-size: 1.1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            background: rgba(40, 167, 69, 0.1);
        }

        .navbar-toggler:focus {
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%2840, 167, 69, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='m4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        .navbar-collapse {
            flex-grow: 1;
        }

        .navbar-nav {
            margin: 0 auto;
            gap: 10px;
        }

        .nav-link {
            color: #333 !important;
            font-weight: 500;
            padding: 12px 16px !important;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
            text-align: center;
        }

        .nav-link:hover {
            color: #28a745 !important;
            transform: translateY(-2px);
        }

        .nav-link.active {
            color: #28a745 !important;
            background-color: transparent;
            font-weight: 600;
        }

        .icons-container {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: auto;
        }

        .header-icon {
            color: #333;
            font-size: 1.1rem;
            padding: 10px;
            border-radius: 50%;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            position: relative;
            width: 44px;
            height: 44px;
        }

        .header-icon:hover {
            color: #28a745;
            background-color: rgba(40, 167, 69, 0.1);
            transform: translateY(-2px);
        }

        .header-icon .badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background: #dc3545;
            color: white;
            font-size: 0.7rem;
            padding: 2px 6px;
            border-radius: 10px;
            min-width: 18px;
            text-align: center;
        }

        /* Mobile Responsive Styles */
        @media (max-width: 991.98px) {
            .responsive-navbar {
                min-height: 70px;
                padding: 10px 0;
            }

            .brand-text {
                display: block;
                font-size: 1.3rem;
            }

            .brand-logo {
                width: 45px;
                height: 45px;
            }

            .navbar-collapse {
                margin-top: 15px;
                background: rgba(255, 255, 255, 0.98);
                border-radius: 12px;
                padding: 15px;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }

            .navbar-nav {
                margin: 0;
                text-align: center;
                gap: 5px;
            }

            .nav-link {
                padding: 15px 20px !important;
                margin: 5px 0;
                font-size: 1.1rem;
                border-radius: 10px;
            }

            .icons-container {
                justify-content: center;
                margin: 20px 0 10px 0;
                padding-top: 15px;
                border-top: 1px solid rgba(0, 0, 0, 0.1);
                gap: 20px;
            }

            .header-icon {
                flex-direction: column;
                gap: 5px;
                width: auto;
                height: auto;
                padding: 12px 16px;
                border-radius: 10px;
                font-size: 1.2rem;
            }

            .header-icon::after {
                content: attr(title);
                font-size: 0.75rem;
                font-weight: 500;
                margin-top: 2px;
            }
        }

        @media (max-width: 767.98px) {
            .responsive-navbar {
                min-height: 65px;
                padding: 8px 0;
            }

            .brand-logo {
                width: 40px;
                height: 40px;
            }

            .brand-text {
                font-size: 1.2rem;
            }

            .navbar-collapse {
                margin-top: 12px;
                padding: 12px;
            }

            .nav-link {
                padding: 12px 16px !important;
                font-size: 1rem;
            }

            .icons-container {
                gap: 15px;
            }

            .header-icon {
                padding: 10px 12px;
                font-size: 1.1rem;
            }
        }

        @media (max-width: 575.98px) {
            .responsive-navbar {
                min-height: 60px;
                padding: 5px 0;
            }

            .brand-text {
                font-size: 1.1rem;
            }

            .brand-logo {
                width: 35px;
                height: 35px;
            }

            .navbar-collapse {
                margin-top: 10px;
                padding: 10px;
            }

            .nav-link {
                padding: 10px 12px !important;
                font-size: 0.95rem;
            }

            .icons-container {
                gap: 12px;
                flex-wrap: wrap;
            }

            .header-icon {
                padding: 8px 10px;
                font-size: 1rem;
            }
        }

        /* Smooth transitions for mobile menu */
        .navbar-collapse.collapsing {
            transition: height 0.35s ease;
        }

        .navbar-collapse.show {
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Active page indicator */
        .nav-item.active .nav-link::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 3px;
            background: #28a745;
            border-radius: 2px;
        }
        
        /* Modal Backdrop Blur Effect */
        .modal-backdrop.show {
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            background-color: rgba(0, 0, 0, 0.5);
        }
        
        /* Modal Animation */
        .modal.fade .modal-dialog {
            transition: transform 0.3s ease-out;
            transform: scale(0.8);
        }
        
        .modal.show .modal-dialog {
            transform: scale(1);
        }
        
        /* Modal Content */
        .modal-content {
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }
        
        /* Cursor pointer for clickable elements */
        .cursor-pointer {
            cursor: pointer;
        }
        
        /* Input group styling */
        .modal .input-group-text {
            background: transparent;
            border-color: #ced4da;
        }
        
        .modal .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
        
        .modal .form-control:focus + .input-group-text {
            border-color: #28a745;
        }

        /* Hover effects for desktop */
        @media (min-width: 992px) {
            .nav-link::before {
                content: '';
                position: absolute;
                bottom: 0;
                left: 50%;
                transform: translateX(-50%);
                width: 0;
                height: 3px;
                background: #28a745;
                border-radius: 2px;
                transition: width 0.3s ease;
            }

            .nav-link:hover::before {
                width: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Enhanced Responsive Header -->
    <header class="bg-white shadow-sm sticky-top">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light responsive-navbar">
                <!-- Brand Logo -->
                <a href="{{ route('home') }}" class="navbar-brand-logo">
                    <img src="{{ asset('images/categories/logo.png') }}" 
                         class="brand-logo" 
                         alt="Flavearth Logo"
                         onerror="this.src='https://via.placeholder.com/50x50/28a745/FFFFFF?text=F'">
                    <span class="brand-text">Flavearth</span>
                </a>

                <!-- Mobile Menu Toggle -->
                <button class="navbar-toggler" 
                        type="button" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#navbarNav" 
                        aria-controls="navbarNav" 
                        aria-expanded="false" 
                        aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Navigation Menu -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <!-- Navigation Links -->
                    <ul class="navbar-nav">
                        <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                                Home
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('shop*') ? 'active' : '' }}">
                            <a href="{{ route('shop') }}" class="nav-link {{ request()->routeIs('shop*') ? 'active' : '' }}">
                                Shop
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('about') ? 'active' : '' }}">
                            <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">
                                About
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('contact') ? 'active' : '' }}">
                            <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">
                                Contact
                            </a>
                        </li>
                    </ul>

                    <!-- Header Icons -->
                    <div class="icons-container">
                        @guest
                            <button type="button" class="btn btn-outline-success rounded-pill px-4 me-2" data-bs-toggle="modal" data-bs-target="#loginModal">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                            <button type="button" class="btn btn-success rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#registerModal">
                                <i class="fas fa-user-plus me-2"></i>Sign Up
                            </button>
                        @else
                            <a href="{{ route('cart') }}" class="header-icon" title="Cart" aria-label="Shopping Cart">
                                <i class="fas fa-shopping-cart"></i>
                                @if(auth()->user()->cart && auth()->user()->cart->items->count() > 0)
                                    <span class="badge">{{ auth()->user()->cart->items->count() }}</span>
                                @endif
                            </a>
                            <a href="{{ route('wishlist') }}" class="header-icon" title="Wishlist" aria-label="Wishlist">
                                <i class="fas fa-heart"></i>
                                @if(auth()->user()->wishlist && auth()->user()->wishlist->count() > 0)
                                    <span class="badge">{{ auth()->user()->wishlist->count() }}</span>
                                @endif
                            </a>
                            <div class="dropdown">
                                <a href="#" class="header-icon dropdown-toggle" title="Account" aria-label="My Account" data-bs-toggle="dropdown">
                                    <i class="fas fa-user"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3">
                                    <li class="dropdown-header">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar bg-success text-white rounded-circle me-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                                                <small class="text-muted">{{ auth()->user()->email }}</small>
                                            </div>
                                        </div>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('account') }}"><i class="fas fa-user-circle me-2"></i>My Profile</a></li>
                                    <li><a class="dropdown-item" href="{{ route('orders') }}"><i class="fas fa-box me-2"></i>My Orders</a></li>
                                    <li><a class="dropdown-item" href="{{ route('wishlist') }}"><i class="fas fa-heart me-2"></i>My Wishlist</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endguest
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Enhanced Header JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navbar = document.querySelector('.responsive-navbar');
            const navbarCollapse = document.querySelector('#navbarNav');
            const navbarToggler = document.querySelector('.navbar-toggler');
            
            // Close mobile menu when clicking on nav links
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 992) {
                        const bsCollapse = new bootstrap.Collapse(navbarCollapse, {
                            toggle: false
                        });
                        bsCollapse.hide();
                    }
                });
            });

            // Close mobile menu when clicking outside
            document.addEventListener('click', function(event) {
                const isClickInsideNav = navbar.contains(event.target);
                const isNavOpen = navbarCollapse.classList.contains('show');
                
                if (!isClickInsideNav && isNavOpen && window.innerWidth < 992) {
                    const bsCollapse = new bootstrap.Collapse(navbarCollapse, {
                        toggle: false
                    });
                    bsCollapse.hide();
                }
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 992) {
                    navbarCollapse.classList.remove('show');
                    navbarToggler.classList.add('collapsed');
                    navbarToggler.setAttribute('aria-expanded', 'false');
                }
            });

            // Add smooth scrolling for anchor links
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

            // Add loading state to navigation links
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Only add loading for external pages, not anchors
                    if (!this.getAttribute('href').startsWith('#')) {
                        this.style.opacity = '0.7';
                        this.style.pointerEvents = 'none';
                        
                        // Remove loading state after navigation
                        setTimeout(() => {
                            this.style.opacity = '';
                            this.style.pointerEvents = '';
                        }, 2000);
                    }
                });
            });

            // Header scroll effect (optional)
            let lastScrollTop = 0;
            const header = document.querySelector('header');
            
            window.addEventListener('scroll', function() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                
                // Add shadow on scroll
                if (scrollTop > 10) {
                    header.style.boxShadow = '0 2px 20px rgba(0,0,0,0.1)';
                } else {
                    header.style.boxShadow = '0 2px 4px rgba(0,0,0,0.1)';
                }
                
                lastScrollTop = scrollTop;
            });

            // Badge animation for cart/wishlist (when items are added)
            function animateBadge(iconSelector) {
                const badge = document.querySelector(`${iconSelector} .badge`);
                if (badge) {
                    badge.style.transform = 'scale(1.3)';
                    setTimeout(() => {
                        badge.style.transform = 'scale(1)';
                    }, 200);
                }
            }

            // Example usage: animateBadge('.header-icon[title="Cart"]');
            window.animateCartBadge = () => animateBadge('.header-icon[title="Cart"]');
            window.animateWishlistBadge = () => animateBadge('.header-icon[title="Wishlist"]');
        });
    </script>
    
    @stack('scripts')
    
    <!-- Login Modal -->
    @guest
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-5 pt-0">
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/categories/logo.png') }}" alt="Flavearth Logo" class="mb-3" style="width: 80px; height: 80px; border-radius: 50%;">
                        <h3 class="modal-title fw-bold" id="loginModalLabel">Welcome Back!</h3>
                        <p class="text-muted">Login to your account to continue</p>
                    </div>
                    
                    <div id="loginError" class="alert alert-danger d-none" role="alert"></div>
                    
                    <form id="modalLoginForm" onsubmit="handleLogin(event)">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="loginEmail" class="form-label fw-semibold">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-envelope text-muted"></i>
                                </span>
                                <input type="email" class="form-control border-start-0" id="loginEmail" name="email" placeholder="Enter your email" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="loginPassword" class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-lock text-muted"></i>
                                </span>
                                <input type="password" class="form-control border-start-0 border-end-0" id="loginPassword" name="password" placeholder="Enter your password" required>
                                <span class="input-group-text bg-light border-start-0 cursor-pointer" onclick="togglePasswordVisibility('loginPassword', 'loginPasswordIcon')">
                                    <i class="fas fa-eye text-muted" id="loginPasswordIcon"></i>
                                </span>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="loginRemember" name="remember">
                                <label class="form-check-label" for="loginRemember">
                                    Remember me
                                </label>
                            </div>
                            <a href="#" class="text-success text-decoration-none">Forgot password?</a>
                        </div>
                        
                        <button type="submit" class="btn btn-success btn-lg w-100 rounded-pill mb-3" id="loginSubmitBtn">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </button>
                        
                        <div class="text-center">
                            <p class="mb-0">Don't have an account? 
                                <a href="#" class="text-success fw-semibold text-decoration-none" onclick="switchToRegister()">Sign up</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-5 pt-0">
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/categories/logo.png') }}" alt="Flavearth Logo" class="mb-3" style="width: 80px; height: 80px; border-radius: 50%;">
                        <h3 class="modal-title fw-bold" id="registerModalLabel">Create Account</h3>
                        <p class="text-muted">Join us to explore premium spices</p>
                    </div>
                    
                    <div id="registerError" class="alert alert-danger d-none" role="alert"></div>
                    
                    <form id="modalRegisterForm" onsubmit="handleRegister(event)">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="registerName" class="form-label fw-semibold">Full Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-user text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" id="registerName" name="name" placeholder="Enter your full name" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="registerEmail" class="form-label fw-semibold">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-envelope text-muted"></i>
                                </span>
                                <input type="email" class="form-control border-start-0" id="registerEmail" name="email" placeholder="Enter your email" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="registerPassword" class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-lock text-muted"></i>
                                </span>
                                <input type="password" class="form-control border-start-0 border-end-0" id="registerPassword" name="password" placeholder="Create a password (min. 8 characters)" required minlength="8">
                                <span class="input-group-text bg-light border-start-0 cursor-pointer" onclick="togglePasswordVisibility('registerPassword', 'registerPasswordIcon')">
                                    <i class="fas fa-eye text-muted" id="registerPasswordIcon"></i>
                                </span>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="registerPasswordConfirm" class="form-label fw-semibold">Confirm Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-lock text-muted"></i>
                                </span>
                                <input type="password" class="form-control border-start-0" id="registerPasswordConfirm" name="password_confirmation" placeholder="Confirm your password" required>
                            </div>
                        </div>
                        
                        <div class="form-check mb-4">
                            <input type="checkbox" class="form-check-input" id="registerTerms" required>
                            <label class="form-check-label" for="registerTerms">
                                I agree to the <a href="#" class="text-success">Terms & Conditions</a>
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-success btn-lg w-100 rounded-pill mb-3" id="registerSubmitBtn">
                            <i class="fas fa-user-plus me-2"></i>Create Account
                        </button>
                        
                        <div class="text-center">
                            <p class="mb-0">Already have an account? 
                                <a href="#" class="text-success fw-semibold text-decoration-none" onclick="switchToLogin()">Login</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endguest

    <!-- Global JavaScript -->
    <script>
    // Check if user is authenticated
    const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
    
    // CSRF Token for AJAX requests
    const csrfToken = '{{ csrf_token() }}';
    
    // Setup AJAX defaults
    if (typeof $ !== 'undefined') {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });
    }
    
    // Cart and Wishlist Functions
    function addToCart(productId, quantity = 1) {
        if (!isAuthenticated) {
            // Show login modal
            $('#redirectAfterLogin').val(window.location.href);
            $('#loginModal').modal('show');
            return;
        }
        
        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('success', data.message);
                updateCartCount(data.cart_count);
            } else {
                showNotification('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'Failed to add item to cart');
        });
    }
    
    function addToWishlist(productId) {
        if (!isAuthenticated) {
            // Show login modal
            $('#redirectAfterLogin').val(window.location.href);
            $('#loginModal').modal('show');
            return;
        }
        
        fetch('{{ route("wishlist.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('success', data.message);
                updateWishlistCount(data.wishlist_count);
            } else {
                showNotification('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'Failed to add item to wishlist');
        });
    }
    
    function updateCartCount(count) {
        const cartBadge = document.querySelector('a[href="{{ route("cart") }}"] .badge');
        if (cartBadge) {
            cartBadge.textContent = count;
        } else if (count > 0) {
            const cartIcon = document.querySelector('a[href="{{ route("cart") }}"]');
            if (cartIcon) {
                const badge = document.createElement('span');
                badge.className = 'badge';
                badge.textContent = count;
                cartIcon.appendChild(badge);
            }
        }
    }
    
    function updateWishlistCount(count) {
        const wishlistBadge = document.querySelector('a[href="{{ route("wishlist") }}"] .badge');
        if (wishlistBadge) {
            wishlistBadge.textContent = count;
        } else if (count > 0) {
            const wishlistIcon = document.querySelector('a[href="{{ route("wishlist") }}"]');
            if (wishlistIcon) {
                const badge = document.createElement('span');
                badge.className = 'badge';
                badge.textContent = count;
                wishlistIcon.appendChild(badge);
            }
        }
    }
    
    function showNotification(type, message) {
        // Create toast notification
        const toastHtml = `
            <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        
        // Create container if it doesn't exist
        let toastContainer = document.getElementById('toastContainer');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toastContainer';
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            toastContainer.style.zIndex = '9999';
            document.body.appendChild(toastContainer);
        }
        
        // Add toast to container
        const toastElement = document.createElement('div');
        toastElement.innerHTML = toastHtml;
        toastContainer.appendChild(toastElement.firstElementChild);
        
        // Initialize and show toast
        const toast = new bootstrap.Toast(toastContainer.lastElementChild);
        toast.show();
        
        // Remove toast after it's hidden
        toastContainer.lastElementChild.addEventListener('hidden.bs.toast', function() {
            this.remove();
        });
    }
    
    // Handle add to cart buttons
    document.addEventListener('DOMContentLoaded', function() {
        // Add to cart buttons
        document.querySelectorAll('.add-to-cart-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const productId = this.dataset.productId;
                const quantity = document.getElementById('quantity')?.value || 1;
                addToCart(productId, quantity);
            });
        });
        
        // Add to wishlist buttons
        document.querySelectorAll('.add-to-wishlist-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const productId = this.dataset.productId;
                addToWishlist(productId);
            });
        });
    });
    
    // Modal Functions
    function switchToRegister() {
        $('#loginModal').modal('hide');
        setTimeout(() => {
            $('#registerModal').modal('show');
        }, 300);
    }
    
    function switchToLogin() {
        $('#registerModal').modal('hide');
        setTimeout(() => {
            $('#loginModal').modal('show');
        }, 300);
    }
    
    function togglePasswordVisibility(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const toggleIcon = document.getElementById(iconId);
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
    
    // Handle Login
    function handleLogin(event) {
        event.preventDefault();
        
        const form = event.target;
        const submitBtn = document.getElementById('loginSubmitBtn');
        const errorDiv = document.getElementById('loginError');
        
        // Disable submit button
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Logging in...';
        
        // Clear previous errors
        errorDiv.classList.add('d-none');
        
        // Get form data
        const formData = new FormData(form);
        
        // Send AJAX request
        fetch('{{ route("login") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success || response.redirected) {
                // Login successful
                showNotification('success', 'Login successful! Redirecting...');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                // Show error
                errorDiv.textContent = data.message || 'Invalid credentials. Please try again.';
                errorDiv.classList.remove('d-none');
                
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i>Login';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            errorDiv.textContent = 'An error occurred. Please try again.';
            errorDiv.classList.remove('d-none');
            
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i>Login';
        });
    }
    
    // Handle Register
    function handleRegister(event) {
        event.preventDefault();
        
        const form = event.target;
        const submitBtn = document.getElementById('registerSubmitBtn');
        const errorDiv = document.getElementById('registerError');
        
        // Check if passwords match
        const password = document.getElementById('registerPassword').value;
        const confirmPassword = document.getElementById('registerPasswordConfirm').value;
        
        if (password !== confirmPassword) {
            errorDiv.textContent = 'Passwords do not match.';
            errorDiv.classList.remove('d-none');
            return;
        }
        
        // Disable submit button
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Creating account...';
        
        // Clear previous errors
        errorDiv.classList.add('d-none');
        
        // Get form data
        const formData = new FormData(form);
        
        // Send AJAX request
        fetch('{{ route("register") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success || response.redirected) {
                // Registration successful
                showNotification('success', 'Registration successful! Welcome to Flavearth!');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                // Show error
                if (data.errors) {
                    const errors = Object.values(data.errors).flat().join('<br>');
                    errorDiv.innerHTML = errors;
                } else {
                    errorDiv.textContent = data.message || 'Registration failed. Please try again.';
                }
                errorDiv.classList.remove('d-none');
                
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-user-plus me-2"></i>Create Account';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            errorDiv.textContent = 'An error occurred. Please try again.';
            errorDiv.classList.remove('d-none');
            
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-user-plus me-2"></i>Create Account';
        });
    }
    </script>
    
    @stack('scripts')
</body>
</html>