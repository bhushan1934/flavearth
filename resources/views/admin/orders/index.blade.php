@extends('admin.layouts.app')

@section('title', 'Orders')
@section('subtitle', 'Manage customer orders and shipments')

@section('breadcrumb')
<li class="breadcrumb-item active">Orders</li>
@endsection

@section('content')

<!-- Search and Filter -->
<div class="modern-card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" 
                       placeholder="Search by order number or customer..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-control">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="payment_status" class="form-control">
                    <option value="">All Payment Status</option>
                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary w-100">Clear</a>
            </div>
        </form>
    </div>
</div>

<!-- Orders Table -->
<div class="modern-table">
    <div class="card-body">
        @if($orders->isEmpty())
            <p class="text-muted text-center">No orders found.</p>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Delivery</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>
                                {{ $order->user->name }}<br>
                                <small class="text-muted">{{ $order->user->email }}</small>
                            </td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                            <td>₹{{ number_format($order->total, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    $order->status === 'delivered' ? 'success' : 
                                    ($order->status === 'processing' ? 'warning' : 
                                    ($order->status === 'shipped' ? 'info' : 
                                    ($order->status === 'cancelled' ? 'danger' : 'secondary'))) 
                                }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ 
                                    $order->payment_status === 'paid' ? 'success' : 
                                    ($order->payment_status === 'failed' ? 'danger' : 
                                    ($order->payment_status === 'refunded' ? 'warning' : 'secondary')) 
                                }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                            <td>
                                @if($order->shipment)
                                    <div class="delivery-info">
                                        <small class="d-block">
                                            <strong>{{ $order->shipment->waybill }}</strong>
                                        </small>
                                        <span class="badge bg-{{ 
                                            $order->shipment->status == 'delivered' ? 'success' : 
                                            ($order->shipment->status == 'cancelled' ? 'danger' : 
                                            ($order->shipment->status == 'out_for_delivery' ? 'warning' : 'info')) 
                                        }}">
                                            {{ ucwords(str_replace('_', ' ', $order->shipment->status ?? 'pending')) }}
                                        </span>
                                        @if($order->shipment->tracking_data)
                                            @php
                                                $trackingData = is_string($order->shipment->tracking_data) 
                                                    ? json_decode($order->shipment->tracking_data, true) 
                                                    : $order->shipment->tracking_data;
                                                $lastUpdate = null;
                                                if (isset($trackingData['ShipmentData'][0]['Shipment']['Status']['StatusDateTime'])) {
                                                    $lastUpdate = \Carbon\Carbon::parse($trackingData['ShipmentData'][0]['Shipment']['Status']['StatusDateTime']);
                                                }
                                            @endphp
                                            @if($lastUpdate)
                                                <small class="d-block text-muted">
                                                    Updated: {{ $lastUpdate->diffForHumans() }}
                                                </small>
                                            @endif
                                        @endif
                                        <button class="btn btn-xs btn-outline-primary mt-1 update-tracking-btn" 
                                                data-order-id="{{ $order->id }}" 
                                                title="Update tracking status">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </div>
                                @else
                                    <small class="text-muted">
                                        @if(in_array($order->status, ['pending', 'processing']) && $order->payment_status === 'paid')
                                            <i class="fas fa-clock"></i> Ready to ship
                                        @else
                                            No shipment
                                        @endif
                                    </small>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group-vertical btn-group-sm">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" 
                                       class="btn btn-info">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    @if($order->shipment)
                                        <a href="{{ route('admin.orders.show', $order->id) }}#delivery-tracking" 
                                           class="btn btn-secondary">
                                            <i class="fas fa-truck"></i> Track
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $orders->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
    .delivery-info {
        min-width: 150px;
    }
    .btn-xs {
        padding: 0.125rem 0.25rem;
        font-size: 0.75rem;
        line-height: 1.5;
        border-radius: 0.2rem;
    }
    .update-tracking-btn {
        opacity: 0.7;
        transition: opacity 0.2s;
    }
    .update-tracking-btn:hover {
        opacity: 1;
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle tracking update buttons
    document.querySelectorAll('.update-tracking-btn').forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.dataset.orderId;
            const originalHtml = this.innerHTML;
            
            // Show loading state
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            
            // Make request to update tracking
            fetch(`/admin/orders/${orderId}/shipment/track`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success and reload to see updated data
                        showNotification('Tracking updated successfully!', 'success');
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        showNotification('Failed to update tracking: ' + (data.message || 'Unknown error'), 'error');
                        this.disabled = false;
                        this.innerHTML = originalHtml;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error updating tracking status', 'error');
                    this.disabled = false;
                    this.innerHTML = originalHtml;
                });
        });
    });
    
    // Notification function
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'success' ? 'success' : (type === 'error' ? 'danger' : 'info')} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }
});
</script>
@endsection