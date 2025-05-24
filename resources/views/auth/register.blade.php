@extends('layouts.app')

@section('title', 'Register - Flavearth')

@section('content')
<section class="auth-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <img src="{{ asset('images/categories/logo.png') }}" alt="Flavearth Logo" class="mb-3" style="width: 80px; height: 80px; border-radius: 50%;">
                            <h2 class="h3 fw-bold text-success">Create Account</h2>
                            <p class="text-muted">Join us to explore premium spices</p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-user text-muted"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control border-start-0 @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           placeholder="Enter your full name"
                                           required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-envelope text-muted"></i>
                                    </span>
                                    <input type="email" 
                                           class="form-control border-start-0 @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           placeholder="Enter your email"
                                           required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-lock text-muted"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control border-start-0 border-end-0 @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Create a password (min. 8 characters)"
                                           required>
                                    <span class="input-group-text bg-light border-start-0 cursor-pointer" onclick="togglePassword('password', 'togglePasswordIcon')">
                                        <i class="fas fa-eye text-muted" id="togglePasswordIcon"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-lock text-muted"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control border-start-0 border-end-0" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           placeholder="Confirm your password"
                                           required>
                                    <span class="input-group-text bg-light border-start-0 cursor-pointer" onclick="togglePassword('password_confirmation', 'togglePasswordConfirmIcon')">
                                        <i class="fas fa-eye text-muted" id="togglePasswordConfirmIcon"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="form-check mb-4">
                                <input type="checkbox" class="form-check-input" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#" class="text-success">Terms & Conditions</a> and <a href="#" class="text-success">Privacy Policy</a>
                                </label>
                            </div>

                            <button type="submit" class="btn btn-success btn-lg w-100 rounded-pill mb-3">
                                <i class="fas fa-user-plus me-2"></i>Create Account
                            </button>

                            <div class="text-center">
                                <p class="mb-0">Already have an account? 
                                    <a href="{{ route('login') }}" class="text-success fw-semibold text-decoration-none">Login</a>
                                </p>
                            </div>
                        </form>

                        <div class="divider my-4">
                            <span class="divider-text">OR</span>
                        </div>

                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-secondary rounded-pill">
                                <i class="fab fa-google me-2"></i>Sign up with Google
                            </button>
                            <button class="btn btn-outline-secondary rounded-pill">
                                <i class="fab fa-facebook me-2"></i>Sign up with Facebook
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.auth-section {
    min-height: calc(100vh - 200px);
    display: flex;
    align-items: center;
}

.cursor-pointer {
    cursor: pointer;
}

.divider {
    position: relative;
    text-align: center;
}

.divider::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: #dee2e6;
    transform: translateY(-50%);
}

.divider-text {
    background: white;
    padding: 0 1rem;
    position: relative;
    color: #6c757d;
    font-size: 0.875rem;
}

.input-group-text {
    background: transparent;
    border-color: #ced4da;
}

.form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

.form-control:focus + .input-group-text {
    border-color: #28a745;
}
</style>

<script>
function togglePassword(inputId, iconId) {
    const passwordInput = document.getElementById(inputId);
    const toggleIcon = document.getElementById(iconId);
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}
</script>
@endsection