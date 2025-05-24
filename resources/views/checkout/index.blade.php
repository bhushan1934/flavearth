@extends('layouts.app')

@section('title', 'Checkout - Premium Spices')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h2 class="text-center mb-4">Secure Checkout</h2>
            
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i>
                Checkout functionality is coming soon! Your items are safely stored in your cart.
            </div>
            
            <div class="text-center mt-4">
                <a href="{{ route('cart') }}" class="btn btn-primary me-3">
                    <i class="fas fa-shopping-cart me-2"></i>View Cart
                </a>
                <a href="{{ route('shop') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                </a>
            </div>
        </div>
    </div>
</div>
@endsection