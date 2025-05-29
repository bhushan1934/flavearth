@extends('layouts.app')

@section('title', 'My Orders - Premium Spices')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-lg-3">
            <!-- Account Sidebar -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">My Account</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('account') }}">
                                <i class="fas fa-user"></i> Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('orders') }}">
                                <i class="fas fa-shopping-bag"></i> Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('wishlist') }}">
                                <i class="fas fa-heart"></i> Wishlist
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('account.settings') }}">
                                <i class="fas fa-cog"></i> Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link text-start w-100">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-lg-9">
            <h2 class="mb-4">My Orders</h2>
            
            @php
                $orders = Auth::user()->orders()->with(['items.product', 'items.variant', 'shipment'])->latest()->get();
            @endphp
            
            @if($orders->isEmpty())
                <div class="alert alert-info">
                    <p class="mb-0">You haven't placed any orders yet.</p>
                    <a href="{{ route('shop') }}" class="btn btn-primary mt-3">Start Shopping</a>
                </div>
            @else
                @foreach($orders as $order)
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <strong>Order #{{ $order->order_number }}</strong><br>
                                <small class="text-muted">Placed on {{ $order->created_at->format('F d, Y') }}</small>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <span class="badge bg-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'processing' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                                @if($order->shipment && $order->shipment->waybill)
                                    <br>
                                    <small class="text-muted">
                                        <i class="fas fa-truck"></i> {{ $order->shipment->waybill }}
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                @foreach($order->items as $item)
                                <div class="d-flex mb-2">
                                    <div>
                                        <strong>{{ $item->product->name }}</strong>
                                        @if($item->variant)
                                            <small class="text-muted">({{ $item->variant->weight }})</small>
                                        @endif
                                        <small class="text-muted">× {{ $item->quantity }}</small>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="col-md-4 text-md-end">
                                <p class="mb-1">Subtotal: ₹{{ number_format($order->subtotal, 2) }}</p>
                                <p class="mb-1">GST: ₹{{ number_format($order->gst, 2) }}</p>
                                @if($order->shipping_cost > 0)
                                    <p class="mb-1">Shipping: ₹{{ number_format($order->shipping_cost, 2) }}</p>
                                @endif
                                <p class="mb-0"><strong>Total: ₹{{ number_format($order->total, 2) }}</strong></p>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <strong>Shipping Method:</strong> {{ ucfirst($order->shipping_method) }}
                                </small>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <a href="{{ route('checkout.confirmation', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection