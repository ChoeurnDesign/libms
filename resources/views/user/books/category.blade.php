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
                <p class="text-muted lead">Explore books by category</p>
            </div>
        </div>

        <!-- Categories Grid -->
        <div class="row">
            @foreach($categories as $category)
                <div class="col-lg-4 col-md-6 mb-4">
                    <a href="{{ route('categories.show', $category->slug) }}" class="text-decoration-none">
                        <div class="card border-0 rounded-4 shadow-sm h-100 category-card">
                            <div class="card-body p-4 text-center">
                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                     style="width: 80px; height: 80px; background-color: {{ $category->color }}20;">
                                    <i class="fas fa-book-open fs-2" style="color: {{ $category->color }};"></i>
                                </div>
                                <h4 class="fw-bold text-dark mb-2">{{ $category->name }}</h4>
                                <p class="text-muted mb-3">{{ $category->description }}</p>
                                <div class="d-flex justify-content-center align-items-center">
                                    <span class="badge rounded-pill px-3 py-2"
                                          style="background-color: {{ $category->color }};">
                                        {{ $category->books_count }} Books
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
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
