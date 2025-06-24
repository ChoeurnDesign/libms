@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@include('layouts.navbar')

<div class="container-fluid bg-light min-vh-100">
    <div class="container py-5">
        <!-- Welcome Header -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-0 bg-primary text-white rounded-4 shadow">
                    <div class="card-body p-5">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h1 class="fw-bold mb-2">Welcome back, {{ Auth::user()->name }}!</h1>
                                <p class="mb-0 opacity-75">Ready to explore your library today?</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <i class="fas fa-book-reader display-3 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row mb-5">
            <div class="col-md-3 mb-4">
                <div class="card border-0 rounded-4 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-book text-success fs-4"></i>
                        </div>
                        <h3 class="fw-bold text-success mb-1">{{ $stats['borrowed_books'] }}</h3>
                        <p class="text-muted small mb-0">Books Borrowed</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-0 rounded-4 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-clock text-warning fs-4"></i>
                        </div>
                        <h3 class="fw-bold text-warning mb-1">{{ $stats['due_soon'] }}</h3>
                        <p class="text-muted small mb-0">Due Soon</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-0 rounded-4 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-bookmark text-info fs-4"></i>
                        </div>
                        <h3 class="fw-bold text-info mb-1">{{ $stats['favorites'] }}</h3>
                        <p class="text-muted small mb-0">Favorites</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-0 rounded-4 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-history text-primary fs-4"></i>
                        </div>
                        <h3 class="fw-bold text-primary mb-1">{{ $stats['total_read'] }}</h3>
                        <p class="text-muted small mb-0">Total Read</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Due Soon Alert -->
        @if($dueSoonBooks->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-warning border-0 rounded-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-3 fs-4"></i>
                        <div>
                            <strong>Books Due Soon!</strong>
                            <p class="mb-0">You have {{ $dueSoonBooks->count() }} book(s) due within 3 days.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Actions & Recent Activity -->
        <div class="row mb-5">
            <!-- Quick Actions -->
            <div class="col-md-6 mb-4">
                <div class="card border-0 rounded-4 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">
                            <i class="fas fa-rocket text-primary me-2"></i>Quick Actions
                        </h5>
                        <div class="d-grid gap-3">
                            <a href="{{ route('books.index') }}" class="btn btn-outline-primary btn-lg rounded-3 text-start">
                                <i class="fas fa-search me-3"></i>Browse Books
                            </a>
                            <a href="{{ route('user.borrowings.index') }}" class="btn btn-outline-success btn-lg rounded-3 text-start">
                                <i class="fas fa-book-open me-3"></i>My Borrowed Books
                            </a>
                            <a href="{{ route('user.favorites.index') }}" class="btn btn-outline-info btn-lg rounded-3 text-start">
                                <i class="fas fa-heart me-3"></i>My Favorites
                            </a>
                            <a href="{{ route('user.profile.show') }}" class="btn btn-outline-warning btn-lg rounded-3 text-start">
                                <i class="fas fa-user-edit me-3"></i>Update Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="col-md-6 mb-4">
                <div class="card border-0 rounded-4 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">
                            <i class="fas fa-clock text-success me-2"></i>Recent Activity
                        </h5>
                        @if($recentBorrowings->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($recentBorrowings as $borrowing)
                                <div class="list-group-item border-0 px-0">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-{{ $borrowing->status === 'borrowed' ? 'success' : 'primary' }} bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="fas fa-{{ $borrowing->status === 'borrowed' ? 'book' : 'undo' }} text-{{ $borrowing->status === 'borrowed' ? 'success' : 'primary' }} small"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="mb-1 small fw-medium">
                                                {{ $borrowing->status === 'borrowed' ? 'Borrowed' : 'Returned' }}
                                                {{-- FIXED: Use direct property instead of relationship --}}
                                                "{{ Str::limit($borrowing->title ?? 'Unknown Book', 30) }}"
                                            </p>
                                            <p class="mb-0 text-muted small">
                                                {{-- FIXED: Handle date safely --}}
                                                {{ isset($borrowing->borrowed_date) ? \Carbon\Carbon::parse($borrowing->borrowed_date)->diffForHumans() : 'Recently' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-history text-muted mb-2"></i>
                                <p class="text-muted small">No recent activity</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Recommended Books -->
        @if($recommendedBooks->count() > 0)
        <div class="row">
            <div class="col-12 mb-4">
                <h4 class="fw-bold text-dark">
                    <i class="fas fa-star text-warning me-2"></i>Recommended for You
                </h4>
            </div>
            @foreach($recommendedBooks as $book)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-0 rounded-4 shadow-sm h-100 book-card">
                    <div class="position-relative">
                        {{-- {{ dd($book->cover_image) }} --}}
                        {{-- FIXED: Handle cover image safely --}}
                        @if(isset($book->cover_image) && $book->cover_image)
                            <img src="{{ asset($book->cover_image) }}" class="card-img-top rounded-top-4" style="width: 100%; object-fit: contain; height: 250px;"  alt="{{ $book->title }}">
                        @else
                            <div class="card-img-top rounded-top-4 bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-book fa-3x text-muted"></i>
                            </div>
                        @endif

                        {{-- FIXED: Check status safely --}}
                        @if(isset($book->status) && $book->status === 'available')
                            <span class="badge bg-success position-absolute top-0 end-0 m-2">Available</span>
                        @elseif(isset($book->available_copies) && $book->available_copies > 0)
                            <span class="badge bg-success position-absolute top-0 end-0 m-2">Available</span>
                        @else
                            <span class="badge bg-warning position-absolute top-0 end-0 m-2">Limited</span>
                        @endif
                    </div>
                    <div class="card-body p-3">
                        <h6 class="card-title fw-bold mb-1">{{ Str::limit($book->title, 25) }}</h6>
                        <p class="text-muted small mb-2">by {{ $book->author ?? 'Unknown Author' }}</p>
                        {{-- FIXED: Use book ID instead of slug --}}
                        <a href="{{ route('books.show', $book->id) }}" class="btn btn-sm btn-primary rounded-pill w-100">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

<style>
.book-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.book-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1) !important;
}
</style>
@endsection
