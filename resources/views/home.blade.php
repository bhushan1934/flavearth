{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('title', 'Flavearth - Premium Spices')

@section('content')
    <!-- Hero Section -->
    @include('sections.hero')
    
    <!-- Featured Products Section -->
    @include('sections.featured-products')
    
    <!-- About Section -->
    @include('sections.about')
    
    <!-- Testimonials Section -->
    @include('sections.testimonials')
    
    <!-- Newsletter Section -->
    @include('sections.newsletter')
@endsection