@extends('layouts.admin')

@section('page-title', 'Book Categories')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">
            <i class="fas fa-tags text-warning me-2"></i>Book Categories
        </h4>
        <p class="text-muted mb-0">Organize and manage book categories</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary rounded-pill">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-success rounded-pill">
            <i class="fas fa-plus me-2"></i>Add Category
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    @if(isset($categories) && $categories->count() > 0)
        @foreach($categories as $category)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-0 shadow-sm rounded-3 h-100"
                 style="transition: all 0.3s ease;"
                 onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 25px rgba(0,0,0,0.15)'"
                 onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 0.125rem 0.25rem rgba(0,0,0,0.075)'">

                <!-- Category Header -->
                <div class="card-header bg-transparent border-0 p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning bg-opacity-20 rounded-circle p-3 me-3">
                                <i class="fas fa-tag text-warning fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold text-dark">{{ $category->name }}</h5>
                                <small class="text-muted">Category</small>
                            </div>
                        </div>
                        <span class="badge bg-info bg-opacity-20 text-info rounded-pill fs-6">
                            {{ $category->books_count ?? 0 }} books
                        </span>
                    </div>
                </div>

                <!-- Category Body -->
                <div class="card-body p-4 pt-0">
                    <p class="text-muted mb-4">
                        {{ $category->description ?: 'No description available for this category.' }}
                    </p>

                    <!-- Category Stats -->
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <div class="bg-light rounded-3 p-3 text-center">
                                <div class="fw-bold text-primary h5 mb-0">{{ $category->books_count ?? 0 }}</div>
                                <small class="text-muted">Total Books</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light rounded-3 p-3 text-center">
                                <div class="fw-bold text-success h5 mb-0">
                                    @if($category->books)
                                        {{ $category->books->sum('available_quantity') }}
                                    @else
                                        0
                                    @endif
                                </div>
                                <small class="text-muted">Available</small>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons - REMOVED SHOW ROUTE -->
                    <div class="d-flex gap-2 justify-content-between">
                        <span class="btn btn-outline-secondary btn-sm rounded-pill flex-fill disabled">
                            <i class="fas fa-eye me-1"></i>{{ $category->books_count ?? 0 }} Books
                        </span>
                        <a href="{{ route('admin.categories.edit', $category) }}"
                           class="btn btn-outline-primary btn-sm rounded-pill">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="btn btn-outline-danger btn-sm rounded-pill"
                                    onclick="return confirm('Are you sure you want to delete this category?')"
                                    title="Delete Category">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body text-center py-5">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-tags fa-2x text-muted"></i>
                    </div>
                    <h5 class="text-muted mb-2">No Categories Found</h5>
                    <p class="text-muted mb-4">Start organizing your library by creating book categories.</p>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-success rounded-pill">
                        <i class="fas fa-plus me-2"></i>Create First Category
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
