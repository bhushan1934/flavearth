@extends('admin.layouts.app')

@section('title', 'Order #' . $order->order_number)
@section('subtitle', 'Order details and delivery tracking')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
<li class="breadcrumb-item active">Order #{{ $order->order_number }}</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
    <div>
        <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-primary btn-modern">
            <i class="fas fa-edit"></i> Edit Order
        </a>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-modern">
            <i class="fas fa-arrow-left"></i> Back to Orders
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-lg-8">
        <!-- Order Information -->
        <div class="modern-card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Order Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">Order ID:</th>
                        <td>#{{ $order->id }}</td>
                    </tr>
                    <tr>
                        <th>Order Date:</th>
                        <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            <span class="badge bg-{{ 
                                $order->status == 'delivered' ? 'success' : 
                                ($order->status == 'cancelled' ? 'danger' : 
                                ($order->status == 'shipped' ? 'info' : 'warning')) 
                            }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Payment Method:</th>
                        <td>{{ ucfirst(str_replace('_', ' ', $order->payment_method ?? 'N/A')) }}</td>
                    </tr>
                    <tr>
                        <th>Payment Status:</th>
                        <td>
                            <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}">
                                {{ ucfirst($order->payment_status ?? 'pending') }}
                            </span>
                        </td>
                    </tr>
                    @if($order->notes)
                    <tr>
                        <th>Order Notes:</th>
                        <td>{{ $order->notes }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        <!-- Order Items -->
        <div class="modern-card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Order Items</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Variant</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    @if($item->product)
                                        <a href="{{ route('admin.products.show', $item->product->id) }}">
                                            {{ $item->product->name }}
                                        </a>
                                    @else
                                        {{ $item->product_name ?? 'Product Deleted' }}
                                    @endif
                                </td>
                                <td>{{ $item->variant_name ?? '-' }}</td>
                                <td>₹{{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end">Subtotal:</th>
                                <th>₹{{ number_format($order->subtotal, 2) }}</th>
                            </tr>
                            @if($order->discount > 0)
                            <tr>
                                <th colspan="4" class="text-end">Discount:</th>
                                <th class="text-danger">-₹{{ number_format($order->discount, 2) }}</th>
                            </tr>
                            @endif
                            @if($order->tax > 0)
                            <tr>
                                <th colspan="4" class="text-end">Tax:</th>
                                <th>₹{{ number_format($order->tax, 2) }}</th>
                            </tr>
                            @endif
                            @if($order->shipping > 0)
                            <tr>
                                <th colspan="4" class="text-end">Shipping:</th>
                                <th>₹{{ number_format($order->shipping, 2) }}</th>
                            </tr>
                            @endif
                            <tr>
                                <th colspan="4" class="text-end">Total:</th>
                                <th class="text-primary">₹{{ number_format($order->total, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Status History -->
        {{-- Status history feature can be implemented later --}}
    </div>

    <div class="col-lg-4">
        <!-- Customer Information -->
        <div class="modern-card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Customer Information</h5>
            </div>
            <div class="card-body">
                @if($order->user)
                    <h6>{{ $order->user->name }}</h6>
                    <p class="mb-1">{{ $order->user->email }}</p>
                    <p class="mb-0">{{ $order->user->phone ?? 'No phone' }}</p>
                    <hr>
                    <a href="{{ route('admin.users.show', $order->user->id) }}" class="btn btn-sm btn-outline-primary">
                        View Customer Profile
                    </a>
                @else
                    <p class="text-muted">Guest Order</p>
                @endif
            </div>
        </div>

        <!-- Shipping Address -->
        <div class="modern-card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Shipping Address</h5>
            </div>
            <div class="card-body">
                @if($order->shipping_address)
                    @php
                        $address = is_string($order->shipping_address) ? json_decode($order->shipping_address, true) : $order->shipping_address;
                    @endphp
                    @if($address)
                        <p class="mb-1"><strong>{{ $address['name'] ?? '' }}</strong></p>
                        <p class="mb-1">{{ $address['address_line_1'] ?? '' }}</p>
                        @if(isset($address['address_line_2']) && $address['address_line_2'])
                            <p class="mb-1">{{ $address['address_line_2'] }}</p>
                        @endif
                        <p class="mb-1">{{ $address['city'] ?? '' }}, {{ $address['state'] ?? '' }} {{ $address['postal_code'] ?? '' }}</p>
                        <p class="mb-1">{{ $address['country'] ?? '' }}</p>
                        @if(isset($address['phone']) && $address['phone'])
                            <p class="mb-0"><i class="fas fa-phone"></i> {{ $address['phone'] }}</p>
                        @endif
                    @else
                        <p class="text-muted">No shipping address</p>
                    @endif
                @else
                    <p class="text-muted">No shipping address</p>
                @endif
            </div>
        </div>

        <!-- Billing Address -->
        @if($order->billing_address)
        <div class="modern-card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Billing Address</h5>
            </div>
            <div class="card-body">
                @php
                    $billingAddress = is_string($order->billing_address) ? json_decode($order->billing_address, true) : $order->billing_address;
                @endphp
                @if($billingAddress)
                    <p class="mb-1"><strong>{{ $billingAddress['name'] ?? '' }}</strong></p>
                    <p class="mb-1">{{ $billingAddress['address_line_1'] ?? '' }}</p>
                    @if(isset($billingAddress['address_line_2']) && $billingAddress['address_line_2'])
                        <p class="mb-1">{{ $billingAddress['address_line_2'] }}</p>
                    @endif
                    <p class="mb-1">{{ $billingAddress['city'] ?? '' }}, {{ $billingAddress['state'] ?? '' }} {{ $billingAddress['postal_code'] ?? '' }}</p>
                    <p class="mb-1">{{ $billingAddress['country'] ?? '' }}</p>
                @endif
            </div>
        </div>
        @endif

        <!-- Quick Actions -->
        <div class="modern-card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($order->status == 'pending')
                        <button class="btn btn-success update-status" data-status="processing">
                            <i class="fas fa-check"></i> Mark as Processing
                        </button>
                    @elseif($order->status == 'processing')
                        <button class="btn btn-info update-status" data-status="shipped">
                            <i class="fas fa-truck"></i> Mark as Shipped
                        </button>
                    @elseif($order->status == 'shipped')
                        <button class="btn btn-success update-status" data-status="delivered">
                            <i class="fas fa-check-circle"></i> Mark as Delivered
                        </button>
                    @endif
                    
                    @if($order->status != 'cancelled' && $order->status != 'delivered')
                        <button class="btn btn-danger update-status" data-status="cancelled">
                            <i class="fas fa-times"></i> Cancel Order
                        </button>
                    @endif
                    
                    <button class="btn btn-secondary" onclick="window.print()">
                        <i class="fas fa-print"></i> Print Order
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Delivery Tracking -->
        <div class="modern-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Delivery Tracking</h5>
                @if($order->shipment)
                    <span class="badge bg-{{ 
                        $order->shipment->status == 'delivered' ? 'success' : 
                        ($order->shipment->status == 'cancelled' ? 'danger' : 
                        ($order->shipment->status == 'out_for_delivery' ? 'warning' : 'info')) 
                    }}">
                        {{ ucwords(str_replace('_', ' ', $order->shipment->status ?? 'pending')) }}
                    </span>
                @endif
            </div>
            <div class="card-body">
                @if($order->shipment)
                    <!-- Shipment Details -->
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong>Waybill Number:</strong>
                                    <code>{{ $order->shipment->waybill }}</code>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong>Courier Partner:</strong>
                                    <span class="text-capitalize">{{ $order->shipment->courier_name ?? 'Delhivery' }}</span>
                                </div>
                                @if($order->shipment->shipped_at)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong>Shipped Date:</strong>
                                    <span>{{ \Carbon\Carbon::parse($order->shipment->shipped_at)->format('M d, Y H:i') }}</span>
                                </div>
                                @endif
                                @if($order->shipment->pickup_location)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong>Pickup Location:</strong>
                                    <span>{{ $order->shipment->pickup_location }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Live Tracking Display -->
                    <div class="tracking-container mb-3" id="tracking-container">
                        @if($order->shipment->tracking_data)
                            @php
                                $trackingData = is_string($order->shipment->tracking_data) 
                                    ? json_decode($order->shipment->tracking_data, true) 
                                    : $order->shipment->tracking_data;
                            @endphp
                            @if(isset($trackingData['ShipmentData'][0]))
                                @php $shipmentInfo = $trackingData['ShipmentData'][0]['Shipment']; @endphp
                                <div class="alert alert-info">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>Current Status:</strong> {{ $shipmentInfo['Status']['Status'] ?? 'Unknown' }}
                                        </div>
                                        <small class="text-muted">
                                            {{ isset($shipmentInfo['Status']['StatusDateTime']) ? \Carbon\Carbon::parse($shipmentInfo['Status']['StatusDateTime'])->format('M d, Y H:i') : '' }}
                                        </small>
                                    </div>
                                    @if(isset($shipmentInfo['Status']['Instructions']))
                                        <div class="mt-2">
                                            <small>{{ $shipmentInfo['Status']['Instructions'] }}</small>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @else
                            <div class="alert alert-secondary">
                                <i class="fas fa-info-circle"></i> Click "Update Tracking" to fetch latest delivery status
                            </div>
                        @endif
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-sm" id="update-tracking">
                            <i class="fas fa-sync-alt"></i> Update Tracking Status
                        </button>
                        <button class="btn btn-info btn-sm" id="view-tracking-details" data-bs-toggle="modal" data-bs-target="#trackingModal">
                            <i class="fas fa-map-marker-alt"></i> View Full Tracking Details
                        </button>
                        <a href="{{ route('admin.orders.shipment.packing-slip', $order->id) }}" 
                           class="btn btn-secondary btn-sm" target="_blank">
                            <i class="fas fa-file-pdf"></i> Download Packing Slip
                        </a>
                        @if(!in_array($order->shipment->status, ['delivered', 'cancelled']))
                            <button class="btn btn-danger btn-sm" id="cancel-shipment">
                                <i class="fas fa-times"></i> Cancel Shipment
                            </button>
                        @endif
                    </div>
                @else
                    @if(in_array($order->status, ['pending', 'processing']) && $order->payment_status === 'paid')
                        <p class="text-muted mb-3">No shipment created yet.</p>
                        @if(config('services.delhivery.use_mock'))
                            <div class="alert alert-warning mb-3">
                                <i class="fas fa-exclamation-triangle"></i> <strong>Mock Mode Active</strong><br>
                                <small>Delhivery API is experiencing issues. Using mock service for testing.</small>
                            </div>
                        @endif
                        <button class="btn btn-primary w-100" id="create-shipment">
                            <i class="fas fa-shipping-fast"></i> Create Delhivery Shipment
                        </button>
                    @else
                        <p class="text-muted">Shipment can only be created for paid orders in pending/processing status.</p>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Tracking Details Modal -->
<div class="modal fade" id="trackingModal" tabindex="-1" aria-labelledby="trackingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="trackingModalLabel">
                    <i class="fas fa-route"></i> Delivery Tracking Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if($order->shipment)
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Waybill:</strong> {{ $order->shipment->waybill }}
                            </div>
                            <div class="col-md-6">
                                <strong>Courier:</strong> {{ ucfirst($order->shipment->courier_name ?? 'Delhivery') }}
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div id="detailed-tracking-info">
                        @if($order->shipment->tracking_data)
                            @php
                                $trackingData = is_string($order->shipment->tracking_data) 
                                    ? json_decode($order->shipment->tracking_data, true) 
                                    : $order->shipment->tracking_data;
                            @endphp
                            @if(isset($trackingData['ShipmentData'][0]))
                                @php 
                                    $shipmentInfo = $trackingData['ShipmentData'][0]['Shipment'];
                                    $scanDetails = $shipmentInfo['Scans'] ?? [];
                                @endphp
                                
                                <!-- Current Status -->
                                <div class="alert alert-primary mb-4">
                                    <h6><i class="fas fa-info-circle"></i> Current Status</h6>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <strong>{{ $shipmentInfo['Status']['Status'] ?? 'Unknown' }}</strong>
                                            @if(isset($shipmentInfo['Status']['Instructions']))
                                                <br><small class="text-muted">{{ $shipmentInfo['Status']['Instructions'] }}</small>
                                            @endif
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <small>{{ isset($shipmentInfo['Status']['StatusDateTime']) ? \Carbon\Carbon::parse($shipmentInfo['Status']['StatusDateTime'])->format('M d, Y H:i A') : '' }}</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tracking Timeline -->
                                @if(!empty($scanDetails))
                                    <h6><i class="fas fa-history"></i> Tracking History</h6>
                                    <div class="timeline">
                                        @foreach($scanDetails as $scan)
                                            <div class="timeline-item">
                                                <div class="timeline-badge bg-{{ 
                                                    str_contains(strtolower($scan['StatusType'] ?? ''), 'deliver') ? 'success' : 
                                                    (str_contains(strtolower($scan['StatusType'] ?? ''), 'transit') ? 'info' : 'secondary') 
                                                }}">
                                                    <i class="fas fa-{{ 
                                                        str_contains(strtolower($scan['StatusType'] ?? ''), 'deliver') ? 'check' : 
                                                        (str_contains(strtolower($scan['StatusType'] ?? ''), 'transit') ? 'truck' : 'circle') 
                                                    }}"></i>
                                                </div>
                                                <div class="timeline-panel">
                                                    <div class="d-flex justify-content-between">
                                                        <strong>{{ $scan['Status'] ?? 'Status Update' }}</strong>
                                                        <small class="text-muted">
                                                            {{ isset($scan['StatusDateTime']) ? \Carbon\Carbon::parse($scan['StatusDateTime'])->format('M d, H:i A') : '' }}
                                                        </small>
                                                    </div>
                                                    @if(isset($scan['Instructions']))
                                                        <p class="mb-1">{{ $scan['Instructions'] }}</p>
                                                    @endif
                                                    @if(isset($scan['ScannedLocation']))
                                                        <small class="text-muted">
                                                            <i class="fas fa-map-marker-alt"></i> {{ $scan['ScannedLocation'] }}
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Additional Info -->
                                @if(isset($shipmentInfo['Consignee']) || isset($shipmentInfo['Origin']) || isset($shipmentInfo['Destination']))
                                    <hr>
                                    <h6><i class="fas fa-info"></i> Shipment Details</h6>
                                    <div class="row">
                                        @if(isset($shipmentInfo['Origin']))
                                            <div class="col-md-6">
                                                <strong>Origin:</strong> {{ $shipmentInfo['Origin'] }}
                                            </div>
                                        @endif
                                        @if(isset($shipmentInfo['Destination']))
                                            <div class="col-md-6">
                                                <strong>Destination:</strong> {{ $shipmentInfo['Destination'] }}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            @endif
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-exclamation-triangle fa-2x text-warning mb-3"></i>
                                <p>No tracking data available. Click "Update Tracking Status" to fetch latest information.</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="refresh-tracking-modal">
                    <i class="fas fa-sync-alt"></i> Refresh Tracking
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .timeline {
        position: relative;
        padding: 20px 0;
    }
    .timeline-item {
        position: relative;
        padding-left: 40px;
        margin-bottom: 20px;
    }
    .timeline-badge {
        position: absolute;
        left: 0;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }
    .timeline-panel {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
    }
    @media print {
        .btn, .navbar, .sidebar {
            display: none !important;
        }
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update status buttons
    document.querySelectorAll('.update-status').forEach(button => {
        button.addEventListener('click', function() {
            const status = this.dataset.status;
            const statusLabels = {
                'processing': 'Processing',
                'shipped': 'Shipped',
                'delivered': 'Delivered',
                'cancelled': 'Cancelled'
            };
            
            if (confirm(`Are you sure you want to mark this order as ${statusLabels[status]}?`)) {
                fetch(`{{ route('admin.orders.update', $order->id) }}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ status: status })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Failed to update order status: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating the order: ' + error.message);
                });
            }
        });
    });
    
    // Create shipment
    const createShipmentBtn = document.getElementById('create-shipment');
    if (createShipmentBtn) {
        createShipmentBtn.addEventListener('click', function() {
            if (confirm('Create Delhivery shipment for this order?')) {
                this.disabled = true;
                this.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Creating...';
                
                fetch(`{{ route('admin.orders.shipment.create', $order->id) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Shipment created successfully! Waybill: ' + data.waybill);
                        window.location.reload();
                    } else {
                        alert('Failed to create shipment: ' + (data.message || 'Unknown error'));
                        this.disabled = false;
                        this.innerHTML = '<i class="fas fa-shipping-fast"></i> Create Delhivery Shipment';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while creating shipment');
                    this.disabled = false;
                    this.innerHTML = '<i class="fas fa-shipping-fast"></i> Create Delhivery Shipment';
                });
            }
        });
    }
    
    // Update tracking status
    const updateTrackingBtn = document.getElementById('update-tracking');
    if (updateTrackingBtn) {
        updateTrackingBtn.addEventListener('click', function() {
            updateTrackingStatus(this);
        });
    }

    // Refresh tracking in modal
    const refreshTrackingModalBtn = document.getElementById('refresh-tracking-modal');
    if (refreshTrackingModalBtn) {
        refreshTrackingModalBtn.addEventListener('click', function() {
            updateTrackingStatus(this, true);
        });
    }

    // Function to update tracking status
    function updateTrackingStatus(button, isModal = false) {
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Updating...';
        
        fetch(`{{ route('admin.orders.shipment.track', $order->id) }}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showNotification('Tracking status updated successfully!', 'success');
                    
                    // Update the tracking display dynamically
                    updateTrackingDisplay(data.tracking_data);
                    
                    if (isModal) {
                        // If called from modal, reload page to refresh modal content
                        setTimeout(() => window.location.reload(), 1500);
                    }
                } else {
                    showNotification('Failed to update tracking status: ' + (data.message || 'Unknown error'), 'error');
                }
                button.disabled = false;
                button.innerHTML = originalText;
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred while updating tracking status', 'error');
                button.disabled = false;
                button.innerHTML = originalText;
            });
    }

    // Function to update tracking display
    function updateTrackingDisplay(trackingData) {
        const container = document.getElementById('tracking-container');
        if (!container || !trackingData) return;

        if (trackingData.ShipmentData && trackingData.ShipmentData[0]) {
            const shipmentInfo = trackingData.ShipmentData[0].Shipment;
            const status = shipmentInfo.Status;
            
            container.innerHTML = `
                <div class="alert alert-info">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Current Status:</strong> ${status.Status || 'Unknown'}
                        </div>
                        <small class="text-muted">
                            ${status.StatusDateTime ? new Date(status.StatusDateTime).toLocaleString() : ''}
                        </small>
                    </div>
                    ${status.Instructions ? `<div class="mt-2"><small>${status.Instructions}</small></div>` : ''}
                </div>
            `;
        }
    }

    // Function to show notifications
    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'success' ? 'success' : (type === 'error' ? 'danger' : 'info')} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }
    
    // Cancel shipment
    const cancelShipmentBtn = document.getElementById('cancel-shipment');
    if (cancelShipmentBtn) {
        cancelShipmentBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to cancel this shipment?')) {
                this.disabled = true;
                this.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Cancelling...';
                
                fetch(`{{ route('admin.orders.shipment.cancel', $order->id) }}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Shipment cancelled successfully!');
                        window.location.reload();
                    } else {
                        alert('Failed to cancel shipment');
                        this.disabled = false;
                        this.innerHTML = '<i class="fas fa-times"></i> Cancel Shipment';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while cancelling shipment');
                    this.disabled = false;
                    this.innerHTML = '<i class="fas fa-times"></i> Cancel Shipment';
                });
            }
        });
    }
});
</script>
@endsection