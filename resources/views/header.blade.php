{{-- resources/views/partials/header.blade.php --}}
<header class="bg-white shadow-md">
    <div class="container-custom">
        <div class="flex items-center justify-between py-4">
            <!-- Logo -->
            <a href="/" class="flex items-center">
                <span class="text-2xl font-display font-bold text-gray-900">Flav<span class="text-brand">earth</span></span>
            </a>
            
            <!-- Navigation - Desktop -->
            <nav class="hidden md:flex space-x-8">
                <a href="{{ route('home') }}" class="font-medium text-brand hover:text-brand-dark transition-colors">Home</a>
                <a href="{{ route('shop') }}" class="font-medium text-gray-700 hover:text-brand transition-colors">Shop</a>
                <a href="{{ route('about') }}" class="font-medium text-gray-700 hover:text-brand transition-colors">About</a>
                <a href="{{ route('contact') }}" class="font-medium text-gray-700 hover:text-brand transition-colors">Contact</a>
            </nav>
            
            <!-- Icons -->
            <div class="flex items-center space-x-4">
                <a href="#" class="text-gray-700 hover:text-brand transition-colors" aria-label="Search">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </a>
                <a href="{{ route('account') }}" class="text-gray-700 hover:text-brand transition-colors" aria-label="Account">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </a>
                <a href="{{ route('wishlist') }}" class="text-gray-700 hover:text-brand transition-colors" aria-label="Wishlist">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </a>
                <a href="{{ route('cart') }}" class="text-gray-700 hover:text-brand transition-colors relative" aria-label="Cart">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    @if(session()->has('cart_count') && session('cart_count') > 0)
                        <span class="absolute -top-2 -right-2 bg-brand text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                            {{ session('cart_count') }}
                        </span>
                    @endif
                </a>
                
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-button" class="md:hidden text-gray-700 hover:text-brand transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="md:hidden pb-4 hidden">
            <nav class="flex flex-col space-y-3">
                <a href="{{ route('home') }}" class="font-medium text-brand hover:text-brand-dark transition-colors py-2">Home</a>
                <a href="{{ route('shop') }}" class="font-medium text-gray-700 hover:text-brand transition-colors py-2">Shop</a>
                <a href="{{ route('about') }}" class="font-medium text-gray-700 hover:text-brand transition-colors py-2">About</a>
                <a href="{{ route('contact') }}" class="font-medium text-gray-700 hover:text-brand transition-colors py-2">Contact</a>
            </nav>
        </div>
    </div>
</header>

<script>
    // Mobile menu toggle
    document.addEventListener('DOMContentLoaded', function() {
        const menuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        
        menuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    });
</script>