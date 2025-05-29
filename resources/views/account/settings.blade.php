@extends('layouts.app')

@section('title', 'Account Settings - Premium Spices')

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
                            <a class="nav-link active" href="{{ route('account.settings') }}">
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
            <h2 class="mb-4">Account Settings</h2>
            
            <!-- Update Profile Information -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Update Profile Information</h5>
                    
                    <form method="POST" action="#" id="profile-form">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="{{ Auth::user()->name }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ Auth::user()->email }}" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </form>
                </div>
            </div>
            
            <!-- Change Password -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Change Password</h5>
                    
                    <form method="POST" action="#" id="password-form">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="current_password" 
                                   name="current_password" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="password" 
                                   name="password" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="password_confirmation" 
                                   name="password_confirmation" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle profile update
    document.getElementById('profile-form').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Profile update functionality will be implemented soon!');
    });
    
    // Handle password change
    document.getElementById('password-form').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Password change functionality will be implemented soon!');
    });
});
</script>
@endsection