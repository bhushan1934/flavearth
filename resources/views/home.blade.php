{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('title', 'Flavearth - Official Store | Premium Organic Spices & Natural Food Products Online')
@section('description', 'Flavearth Official - Discover premium organic spices, traditional seasonings, and natural food products. Shop authentic, high-quality Flavearth spices sourced directly from Indian farmers. Free delivery across India on orders above ₹500.')
@section('keywords', 'Flavearth, Flavearth official, Flavearth spices, Flavearth store, organic spices online, premium Indian spices, natural food products, traditional seasonings, authentic spices, turmeric powder, red chili powder, garam masala, spice store India, organic food delivery')

@section('og_title', 'Flavearth - Premium Organic Spices & Natural Food Products')
@section('og_description', 'Discover premium organic spices and natural food products at Flavearth. Authentic, high-quality spices sourced directly from farmers with fast delivery across India.')
@section('twitter_title', 'Flavearth - Premium Organic Spices')
@section('twitter_description', 'Premium organic spices and natural food products. Authentic quality, direct from farmers.')

@push('structured_data')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "Flavearth Official Store",
    "alternateName": ["Flavearth", "Flavearth Spices", "Flavearth Organic"],
    "url": "{{ url('/') }}",
    "description": "Flavearth - Premium organic spices and natural food products sourced directly from farmers. Official online store.",
    "keywords": "Flavearth, Flavearth official, Flavearth spices, organic spices, premium spices",
    "potentialAction": {
        "@type": "SearchAction",
        "target": "{{ route('shop') }}?search={search_term_string}",
        "query-input": "required name=search_term_string"
    }
}
</script>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Store",
    "name": "Flavearth",
    "description": "Premium organic spices and natural food products sourced directly from farmers",
    "url": "{{ url('/') }}",
    "logo": "{{ asset('images/categories/logo.png') }}",
    "image": "{{ asset('images/categories/hero.png') }}",
    "hasOfferCatalog": {
        "@type": "OfferCatalog",
        "name": "Organic Spices & Natural Products",
        "itemListElement": [
            {
                "@type": "Offer",
                "itemOffered": {
                    "@type": "Product",
                    "name": "Premium Turmeric Powder",
                    "category": "Spices",
                    "brand": "Flavearth"
                }
            },
            {
                "@type": "Offer", 
                "itemOffered": {
                    "@type": "Product",
                    "name": "Organic Red Chili Powder",
                    "category": "Spices",
                    "brand": "Flavearth"
                }
            }
        ]
    }
}
</script>
@endpush

@section('content')
    <!-- Hero Section -->
    @include('sections.hero')
    
    <!-- Featured Products Section -->
    @include('sections.featured-products')
    
    <!-- About Section -->
    @include('sections.about')
    
    <!-- Testimonials Section -->
    @include('sections.testimonials')
    
    <!-- Contact Section -->
    @include('sections.contact')
    
    <!-- Newsletter Section -->
    @include('sections.newsletter')
@endsection