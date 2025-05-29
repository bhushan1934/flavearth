@extends('admin.layouts.app')

@section('title', 'Edit Order')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Edit Order #{{ $order->id }}</h1>
    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Order
    </a>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="row">
        <div class="col-lg-8">
            <!-- Order Status -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Order Status</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Order Status</label>
                            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="payment_status" class="form-label">Payment Status</label>
                            <select class="form-control @error('payment_status') is-invalid @enderror" id="payment_status" name="payment_status">
                                <option value="pending" {{ ($order->payment_status ?? 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ ($order->payment_status ?? 'pending') == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="failed" {{ ($order->payment_status ?? 'pending') == 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="refunded" {{ ($order->payment_status ?? 'pending') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                            @error('payment_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tracking_number" class="form-label">Tracking Number</label>
                        <input type="text" class="form-control @error('tracking_number') is-invalid @enderror" 
                               id="tracking_number" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}">
                        @error('tracking_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Order Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="3">{{ old('notes', $order->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Shipping Address</h5>
                </div>
                <div class="card-body">
                    @php
                        $shippingAddress = is_string($order->shipping_address) ? json_decode($order->shipping_address, true) : $order->shipping_address;
                    @endphp
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="shipping_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="shipping_name" name="shipping_address[name]" 
                                   value="{{ old('shipping_address.name', $shippingAddress['name'] ?? '') }}">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="shipping_phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="shipping_phone" name="shipping_address[phone]" 
                                   value="{{ old('shipping_address.phone', $shippingAddress['phone'] ?? '') }}">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="shipping_address_line_1" class="form-label">Address Line 1</label>
                        <input type="text" class="form-control" id="shipping_address_line_1" name="shipping_address[address_line_1]" 
                               value="{{ old('shipping_address.address_line_1', $shippingAddress['address_line_1'] ?? '') }}">
                    </div>
                    
                    <div class="mb-3">
                        <label for="shipping_address_line_2" class="form-label">Address Line 2</label>
                        <input type="text" class="form-control" id="shipping_address_line_2" name="shipping_address[address_line_2]" 
                               value="{{ old('shipping_address.address_line_2', $shippingAddress['address_line_2'] ?? '') }}">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="shipping_city" class="form-label">City</label>
                            <input type="text" class="form-control" id="shipping_city" name="shipping_address[city]" 
                                   value="{{ old('shipping_address.city', $shippingAddress['city'] ?? '') }}">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="shipping_state" class="form-label">State</label>
                            <input type="text" class="form-control" id="shipping_state" name="shipping_address[state]" 
                                   value="{{ old('shipping_address.state', $shippingAddress['state'] ?? '') }}">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="shipping_postal_code" class="form-label">Postal Code</label>
                            <input type="text" class="form-control" id="shipping_postal_code" name="shipping_address[postal_code]" 
                                   value="{{ old('shipping_address.postal_code', $shippingAddress['postal_code'] ?? '') }}">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="shipping_country" class="form-label">Country</label>
                        <input type="text" class="form-control" id="shipping_country" name="shipping_address[country]" 
                               value="{{ old('shipping_address.country', $shippingAddress['country'] ?? 'India') }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Order Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Order Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>Order ID:</strong> #{{ $order->id }}</p>
                    <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                    <p><strong>Customer:</strong> {{ $order->user ? $order->user->name : 'Guest' }}</p>
                    <p><strong>Total:</strong> ₹{{ number_format($order->total, 2) }}</p>
                </div>
            </div>

            <!-- Order Items Summary -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Order Items</h5>
                </div>
                <div class="card-body">
                    @foreach($order->items as $item)
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            <small>{{ $item->product ? $item->product->name : ($item->product_name ?? 'Product') }}</small>
                            @if($item->variant_name)
                                <small class="text-muted d-block">{{ $item->variant_name }}</small>
                            @endif
                        </div>
                        <small>{{ $item->quantity }} × ₹{{ number_format($item->price, 2) }}</small>
                    </div>
                    @endforeach
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Total:</strong>
                        <strong>₹{{ number_format($order->total, 2) }}</strong>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="card">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save"></i> Update Order
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show warning when changing order status to cancelled
    document.getElementById('status').addEventListener('change', function() {
        if (this.value === 'cancelled') {
            if (!confirm('Are you sure you want to cancel this order? This action may trigger refunds.')) {
                this.value = '{{ $order->status }}';
            }
        }
    });
});
</script>
@endsection