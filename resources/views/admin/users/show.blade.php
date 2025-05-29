@extends('admin.layouts.app')

@section('title', 'User Details')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2">User Details</h1>
            <div>
                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Edit User
                </a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Users
                </a>
            </div>
        </div>

        <div class="row">
            <!-- User Information -->
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">User Information</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Name:</strong> {{ $user->name }}</p>
                        <p><strong>Email:</strong> {{ $user->email }}</p>
                        <p>
                            <strong>Type:</strong> 
                            @if($user->is_admin)
                                <span class="badge bg-danger">Admin</span>
                            @else
                                <span class="badge bg-primary">Customer</span>
                            @endif
                        </p>
                        <p>
                            <strong>Status:</strong> 
                            @if($user->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </p>
                        <p><strong>Joined:</strong> {{ $user->created_at->format('F d, Y') }}</p>
                        <p><strong>Last Updated:</strong> {{ $user->updated_at->format('F d, Y h:i A') }}</p>
                    </div>
                </div>

                <!-- User Statistics -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <h4 class="mb-1">{{ $stats['total_orders'] }}</h4>
                                <small class="text-muted">Total Orders</small>
                            </div>
                            <div class="col-6 mb-3">
                                <h4 class="mb-1">₹{{ number_format($stats['total_spent'], 2) }}</h4>
                                <small class="text-muted">Total Spent</small>
                            </div>
                            <div class="col-6">
                                <h4 class="mb-1">{{ $stats['addresses'] }}</h4>
                                <small class="text-muted">Addresses</small>
                            </div>
                            <div class="col-6">
                                <h4 class="mb-1">{{ $stats['wishlist_items'] }}</h4>
                                <small class="text-muted">Wishlist Items</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Recent Orders</h5>
                    </div>
                    <div class="card-body">
                        @if($user->orders->isEmpty())
                            <p class="text-muted text-center">No orders found.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Total</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($user->orders as $order)
                                        <tr>
                                            <td>#{{ $order->id }}</td>
                                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                                            <td>
                                                @switch($order->status)
                                                    @case('pending')
                                                        <span class="badge bg-warning">Pending</span>
                                                        @break
                                                    @case('processing')
                                                        <span class="badge bg-info">Processing</span>
                                                        @break
                                                    @case('shipped')
                                                        <span class="badge bg-primary">Shipped</span>
                                                        @break
                                                    @case('delivered')
                                                        <span class="badge bg-success">Delivered</span>
                                                        @break
                                                    @case('cancelled')
                                                        <span class="badge bg-danger">Cancelled</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                                @endswitch
                                            </td>
                                            <td>₹{{ number_format($order->total, 2) }}</td>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $order->id) }}" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($user->orders()->count() > 5)
                                <div class="text-center mt-3">
                                    <a href="{{ route('admin.orders.index', ['user_id' => $user->id]) }}" 
                                       class="btn btn-sm btn-primary">
                                        View All Orders ({{ $user->orders()->count() }})
                                    </a>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- User Addresses -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Addresses</h5>
                    </div>
                    <div class="card-body">
                        @if($user->addresses->isEmpty())
                            <p class="text-muted text-center">No addresses found.</p>
                        @else
                            <div class="row">
                                @foreach($user->addresses as $address)
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            @if($address->is_default)
                                                <span class="badge bg-primary mb-2">Default</span>
                                            @endif
                                            <p class="mb-1"><strong>{{ $address->type }}</strong></p>
                                            <p class="mb-0">
                                                {{ $address->street_address }}<br>
                                                @if($address->apartment_suite)
                                                    {{ $address->apartment_suite }}<br>
                                                @endif
                                                {{ $address->city }}, {{ $address->state }} {{ $address->zip_code }}<br>
                                                {{ $address->country }}<br>
                                                Phone: {{ $address->phone }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">Actions</h5>
                        <div class="d-flex gap-2">
                            @if(!$user->is_admin && $user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.toggle-active', $user->id) }}" 
                                      class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn {{ $user->is_active ? 'btn-warning' : 'btn-success' }}">
                                        <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }} me-2"></i>
                                        {{ $user->is_active ? 'Deactivate' : 'Activate' }} User
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" 
                                      class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash me-2"></i>Delete User
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection