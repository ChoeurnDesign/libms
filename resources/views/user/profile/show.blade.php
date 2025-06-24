@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
@include('layouts.navbar')

<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-4">
            <!-- Profile Card -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary mb-3">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                    <div class="text-center">
                        {{-- Conditionally display uploaded profile picture or fallback to UI Avatars --}}
                        <img class="profile-user-img img-fluid rounded-circle" {{-- Changed img-circle to rounded-circle --}}
                             src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=007bff&color=fff&size=100' }}"
                             alt="User profile picture"
                             style="width: 100px; height: 100px; object-fit: cover;"> {{-- Added style for consistency --}}
                    </div>

                    <h3 class="profile-username text-center">{{ $user->name }}</h3>

                    <p class="text-muted text-center">
                        @if($user->role === 'admin')
                            <span class="badge bg-danger">Admin</span>
                        @else
                            <span class="badge bg-primary">Student</span>
                        @endif
                    </p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Email</b>
                            <span class="float-right">{{ $user->email }}</span>
                        </li>
                        @if(isset($user->student_id) && $user->student_id)
                        <li class="list-group-item">
                            <b>Student ID</b>
                            <span class="float-right">{{ $user->student_id }}</span>
                        </li>
                        @endif
                        @if(isset($user->phone) && $user->phone)
                        <li class="list-group-item">
                            <b>Phone</b>
                            <span class="float-right">{{ $user->phone }}</span>
                        </li>
                        @endif
                        <li class="list-group-item">
                            <b>Member Since</b>
                            <span class="float-right">{{ $user->created_at->format('M Y') }}</span>
                        </li>
                    </ul>

                    <a href="{{ route('user.profile.edit') }}" class="btn btn-primary btn-block">
                        <i class="fas fa-edit"></i> Edit Profile
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Statistics -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar"></i>
                        Library Statistics
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="fas fa-book text-info mb-2" style="font-size: 2rem;"></i>
                                    <h4 class="mb-1">{{ $stats['total_borrowed'] }}</h4>
                                    <p class="text-muted mb-0">Total Borrowed</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="fas fa-bookmark text-warning mb-2" style="font-size: 2rem;"></i>
                                    <h4 class="mb-1">{{ $stats['currently_borrowed'] }}</h4>
                                    <p class="text-muted mb-0">Currently Borrowed</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="fas fa-check-circle text-success mb-2" style="font-size: 2rem;"></i>
                                    <h4 class="mb-1">{{ $stats['books_returned'] }}</h4>
                                    <p class="text-muted mb-0">Books Returned</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="fas fa-exclamation-triangle text-danger mb-2" style="font-size: 2rem;"></i>
                                    <h4 class="mb-1">{{ $stats['overdue_books'] }}</h4>
                                    <p class="text-muted mb-0">Overdue Books</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($stats['total_fines'] > 0)
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Outstanding Fines</h5>
                        You have total fines of ${{ number_format($stats['total_fines'], 2) }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
