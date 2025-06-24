@extends('layouts.admin')

@section('page-title', 'Manage Books')

@section('content')
<!-- Header section remains same -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">
            <i class="fas fa-book text-primary me-2"></i>Manage Books
        </h4>
        <p class="text-muted mb-0">View, add, edit, and delete books from your library</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary rounded-pill">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
        <a href="{{ route('admin.books.create') }}" class="btn btn-primary rounded-pill">
            <i class="fas fa-plus me-2"></i>Add New Book
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        @if(isset($books) && $books->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 fw-bold text-dark p-4">Book Details</th>
                            <th class="border-0 fw-bold text-dark p-4">Author</th>
                            <th class="border-0 fw-bold text-dark p-4">Category</th>
                            <th class="border-0 fw-bold text-dark p-4">Location</th>
                            <th class="border-0 fw-bold text-dark p-4">Availability</th>
                            <th class="border-0 fw-bold text-dark p-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($books as $book)
                        <tr>
                            <td class="p-4">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        @if($book->cover_image && Storage::disk('public')->exists($book->cover_image))
                                            <img src="{{ asset('storage/' . $book->cover_image) }}"
                                                 alt="{{ $book->title }}"
                                                 class="rounded-3"
                                                 style="width: 50px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-primary bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center"
                                                 style="width: 50px; height: 60px;">
                                                <i class="fas fa-book text-primary"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $book->title }}</div>
                                        <small class="text-muted">ISBN: {{ $book->isbn ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="fw-semibold text-dark">{{ $book->author }}</div>
                            </td>
                            <td class="p-4">
                                <span class="badge bg-info bg-opacity-20 text-dark rounded-pill">
                                    {{ $book->category->name ?? 'Uncategorized' }}
                                </span>
                            </td>
                            <td class="p-4">
                                <small class="text-muted">{{ $book->location ?? 'Not specified' }}</small>
                            </td>
                            <td class="p-4">
                                <div class="d-flex align-items-center">
                                    <div class="progress me-2" style="width: 60px; height: 8px;">
                                        <div class="progress-bar bg-success"
                                             style="width: {{ $book->quantity > 0 ? ($book->available_quantity / $book->quantity) * 100 : 0 }}%"></div>
                                    </div>
                                    <small class="text-muted">{{ $book->available_quantity }}/{{ $book->quantity }}</small>
                                </div>
                                <small class="text-{{ $book->available_quantity > 0 ? 'success' : 'danger' }}">
                                    {{ $book->available_quantity > 0 ? 'Available' : 'Out of Stock' }}
                                </small>
                            </td>
                            <td class="p-4">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.books.show', $book) }}"
                                       class="btn btn-sm btn-outline-info rounded-pill" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.books.edit', $book) }}"
                                       class="btn btn-sm btn-outline-primary rounded-pill" title="Edit Book">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.books.destroy', $book) }}" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger rounded-pill"
                                                title="Delete Book"
                                                onclick="return confirm('Are you sure you want to delete this book?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                     style="width: 80px; height: 80px;">
                    <i class="fas fa-book fa-2x text-muted"></i>
                </div>
                <h5 class="text-muted mb-2">No Books Found</h5>
                <p class="text-muted mb-4">Start building your library by adding your first book.</p>
                <a href="{{ route('admin.books.create') }}" class="btn btn-primary rounded-pill">
                    <i class="fas fa-plus me-2"></i>Add First Book
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
