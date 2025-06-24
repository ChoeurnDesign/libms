@extends('layouts.simple')

@section('title', 'Register')

@section('content')
@include('layouts.navbar')

<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light px-3">
    <div class="bg-white rounded-4 shadow-lg border p-5" style="max-width: 32rem; width: 100%; backdrop-filter: blur(10px);">
        <!-- Header -->
        <div class="text-center">
            <div class="mb-2">
                <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="fas fa-user-plus text-success fs-1"></i>
                </div>
            </div>
            <h2 class="fw-semibold text-dark mb-2 fs-2">Create your account</h2>
            <p class="text-muted small">Join our Library Management System</p>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="alert alert-danger border-0 rounded-3 mb-4">
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong class="small">Registration Failed</strong>
                </div>
                <ul class="list-unstyled mb-0">
                    @foreach ($errors->all() as $error)
                        <li class="small text-danger">• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Registration Form -->
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name & Email Row -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label small fw-medium text-dark">Full Name</label>
                    <input type="text"
                           id="name"
                           name="name"
                           class="form-control rounded-pill border-2 px-4 py-2 small"
                           placeholder="Enter your full name"
                           value="{{ old('name') }}"
                           required>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label small fw-medium text-dark">Email</label>
                    <input type="email"
                           id="email"
                           name="email"
                           class="form-control rounded-pill border-2 px-4 py-2 small"
                           placeholder="your@email.com"
                           value="{{ old('email') }}"
                           required>
                </div>
            </div>

            <!-- Phone & Address Row -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="phone" class="form-label small fw-medium text-dark">
                        Phone <span class="text-muted">(Optional)</span>
                    </label>
                    <input type="tel"
                           id="phone"
                           name="phone"
                           class="form-control rounded-pill border-2 px-4 py-2 small"
                           placeholder="+855 12 345 678"
                           value="{{ old('phone') }}">
                </div>
                <div class="col-md-6">
                    <label for="address" class="form-label small fw-medium text-dark">
                        Address <span class="text-muted">(Optional)</span>
                    </label>
                    <input type="text"
                           id="address"
                           name="address"
                           class="form-control rounded-pill border-2 px-4 py-2 small"
                           placeholder="Your address"
                           value="{{ old('address') }}">
                </div>
            </div>

            <!-- Password Row -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="password" class="form-label small fw-medium text-dark">Password</label>
                    <input type="password"
                           id="password"
                           name="password"
                           class="form-control rounded-pill border-2 px-4 py-2 small"
                           placeholder="••••••••"
                           required>
                </div>
                <div class="col-md-6">
                    <label for="password_confirmation" class="form-label small fw-medium text-dark">Confirm Password</label>
                    <input type="password"
                           id="password_confirmation"
                           name="password_confirmation"
                           class="form-control rounded-pill border-2 px-4 py-2 small"
                           placeholder="••••••••"
                           required>
                </div>
            </div>

            <!-- Terms Agreement -->
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" id="terms" required>
                <label class="form-check-label small text-muted" for="terms">
                    I agree to the
                    <a href="#" class="text-decoration-none text-primary">Terms of Service</a>
                    and
                    <a href="#" class="text-decoration-none text-primary">Privacy Policy</a>
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-success btn-md w-100 rounded-pill fw-semibold py-2 mb-2">
                <i class="fas fa-user-plus me-2"></i>Create Account
            </button>

            <!-- Login Link -->
            <div class="text-center">
                <span class="text-muted small">Already have an account? </span>
                <a href="{{ route('login') }}" class="text-decoration-none text-primary fw-medium">
                    Sign in here
                </a>
            </div>
        </form>

        <!-- Trust Indicators -->
        <div class="text-center mt-4">
            <div class="row text-center g-3">
                <div class="col-4">
                    <i class="fas fa-shield-alt text-success me-1"></i>
                    <small class="text-muted d-block">Secure</small>
                </div>
                <div class="col-4">
                    <i class="fas fa-clock text-success me-1"></i>
                    <small class="text-muted d-block">Quick</small>
                </div>
                <div class="col-4">
                    <i class="fas fa-book text-success me-1"></i>
                    <small class="text-muted d-block">Free</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
