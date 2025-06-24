@extends('layouts.app')

@section('title', 'Book Categories')

@section('content')
@include('layouts.navbar')

<div class="container-fluid bg-light min-vh-100">
    <div class="container py-5">
        <!-- Header -->
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h1 class="fw-bold text-dark mb-3">Book Categories</h1>
                <p class="text-muted lead">Explore our collection by category</p>
                <div class="row justify-content-center mt-4">
                    <div class="col-md-8">
                        <div class="row text-center">
                            <div class="col-4">
                                <h3 class="fw-bold text-primary">{{ $totalCategories }}</h3>
                                <small class="text-muted">Categories</small>
                            </div>
                            <div class="col-4">
                                <h3 class="fw-bold text-success">{{ $totalBooks }}</h3>
                                <small class="text-muted">Available Books</small>
                            </div>
                            <div class="col-4">
                                <h3 class="fw-bold text-info">{{ $categories->sum('books_count') }}</h3>
                                <small class="text-muted">Total Books</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories Grid -->
        <div class="row">
            @foreach($categories as $category)
                <div class="col-lg-4 col-md-6 mb-4">
                    <a href="{{ route('user.categories.show', $category) }}" class="text-decoration-none">
                        <div class="card border-0 rounded-4 shadow-sm h-100 category-card">
                            <div class="card-body p-4 text-center">
                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                     style="width: 80px; height: 80px; background-color: {{ $category->color }}20;">
                                    <i class="fas fa-book-open fs-2" style="color: {{ $category->color }};"></i>
                                </div>
                                <h4 class="fw-bold text-dark mb-2">{{ $category->name }}</h4>
                                <p class="text-muted mb-3">{{ $category->description ?: 'Discover amazing books in this category' }}</p>
                                <div class="d-flex justify-content-center align-items-center">
                                    <span class="badge rounded-pill px-3 py-2"
                                          style="background-color: {{ $category->color }};">
                                        {{ $category->books_count }} {{ Str::plural('Book', $category->books_count) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Quick Actions -->
        <div class="row mt-5">
            <div class="col-12 text-center">
                <h4 class="fw-bold mb-4">Quick Actions</h4>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="{{ route('user.books.index') }}" class="btn btn-primary rounded-pill px-4">
                        <i class="fas fa-search me-2"></i>Browse All Books
                    </a>
                    <a href="{{ route('user.books.index', ['status' => 'available']) }}" class="btn btn-success rounded-pill px-4">
                        <i class="fas fa-check me-2"></i>Available Books Only
                    </a>
                    <a href="{{ route('user.borrowings.index') }}" class="btn btn-info rounded-pill px-4">
                        <i class="fas fa-book-open me-2"></i>My Borrowed Books
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.category-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}
</style>
@endsection
