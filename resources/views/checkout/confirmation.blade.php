@extends('layouts.app')

@section('title', 'Order Confirmation - Premium Spices')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-4">
                <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                <h2 class="mt-3">Thank You for Your Order!</h2>
                <p class="text-muted">Your order has been successfully placed.</p>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Order Details</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Order Number:</strong><br>
                            {{ $order->order_number }}
                        </div>
                        <div class="col-md-6 text-md-end">
                            <strong>Order Date:</strong><br>
                            {{ $order->created_at->format('F d, Y h:i A') }}
                        </div>
                    </div>
                    
                    @if($order->shipment && $order->shipment->waybill)
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Tracking Number:</strong><br>
                            {{ $order->shipment->waybill }}
                        </div>
                        <div class="col-md-6 text-md-end">
                            <strong>Shipment Status:</strong><br>
                            <span class="badge bg-{{ $order->shipment->status_badge }}">
                                {{ $order->shipment->formatted_status }}
                            </span>
                        </div>
                    </div>
                    @endif
                    
                    <hr>
                    
                    <h6 class="mb-3">Items Ordered</h6>
                    @foreach($order->items as $item)
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            <span>{{ $item->product->name }}</span>
                            @if($item->variant)
                                <small class="text-muted">({{ $item->variant->weight }})</small>
                            @endif
                            <small class="text-muted">× {{ $item->quantity }}</small>
                        </div>
                        <span>₹{{ number_format($item->total, 2) }}</span>
                    </div>
                    @endforeach
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>₹{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>GST (18%)</span>
                        <span>₹{{ number_format($order->gst, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping ({{ ucfirst($order->shipping_method) }})</span>
                        <span>{{ $order->shipping_cost > 0 ? '₹' . number_format($order->shipping_cost, 2) : 'FREE' }}</span>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <strong>Total</strong>
                        <strong>₹{{ number_format($order->total, 2) }}</strong>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Shipping Information</h5>
                </div>
                <div class="card-body">
                    <address class="mb-0">
                        <strong>{{ $order->shipping_address['name'] }}</strong><br>
                        {{ $order->shipping_address['address_line_1'] }}<br>
                        @if(!empty($order->shipping_address['address_line_2']))
                            {{ $order->shipping_address['address_line_2'] }}<br>
                        @endif
                        {{ $order->shipping_address['city'] }}, {{ $order->shipping_address['state'] }} {{ $order->shipping_address['pincode'] }}<br>
                        {{ $order->shipping_address['country'] ?? 'India' }}<br>
                        Phone: {{ $order->shipping_address['phone'] }}
                    </address>
                </div>
            </div>
            
            <div class="text-center">
                <a href="{{ route('shop') }}" class="btn btn-primary">Continue Shopping</a>
                <a href="{{ route('orders') }}" class="btn btn-outline-primary">View All Orders</a>
            </div>
        </div>
    </div>
</div>
@endsection