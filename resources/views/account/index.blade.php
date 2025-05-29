@extends('layouts.app')

@section('title', 'My Account - Premium Spices')

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
                            <a class="nav-link active" href="{{ route('account') }}">
                                <i class="fas fa-user"></i> Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('orders') }}">
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
            <h2 class="mb-4">My Profile</h2>
            
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Account Information</h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Name:</strong>
                        </div>
                        <div class="col-md-9">
                            {{ Auth::user()->name }}
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Email:</strong>
                        </div>
                        <div class="col-md-9">
                            {{ Auth::user()->email }}
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Member Since:</strong>
                        </div>
                        <div class="col-md-9">
                            {{ Auth::user()->created_at->format('F Y') }}
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Addresses Section -->
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">Saved Addresses</h5>
                    
                    @php
                        $addresses = Auth::user()->addresses;
                    @endphp
                    
                    @if($addresses->isEmpty())
                        <p class="text-muted">No saved addresses yet.</p>
                    @else
                        <div class="row">
                            @foreach($addresses as $address)
                            <div class="col-md-6 mb-3">
                                <div class="border rounded p-3">
                                    <strong>{{ $address->name }}</strong>
                                    @if($address->is_default)
                                        <span class="badge bg-primary float-end">Default</span>
                                    @endif
                                    <br>
                                    {{ $address->address_line_1 }}<br>
                                    @if($address->address_line_2)
                                        {{ $address->address_line_2 }}<br>
                                    @endif
                                    {{ $address->city }}, {{ $address->state }} {{ $address->pincode }}<br>
                                    {{ $address->country }}<br>
                                    Phone: {{ $address->phone }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Recent Orders Summary -->
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">Recent Orders</h5>
                    
                    @php
                        $recentOrders = Auth::user()->orders()->latest()->take(3)->get();
                    @endphp
                    
                    @if($recentOrders->isEmpty())
                        <p class="text-muted">No orders yet.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                    <tr>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                                        <td>₹{{ number_format($order->total, 2) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'processing' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('checkout.confirmation', $order->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <a href="{{ route('orders') }}" class="btn btn-primary">View All Orders</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection