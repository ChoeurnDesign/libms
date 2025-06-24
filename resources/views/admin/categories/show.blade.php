@extends('layouts.admin')

@section('page-title', 'Category Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">
            <i class="fas fa-tag text-warning me-2"></i>{{ $category->name }}
        </h4>
        <p class="text-muted mb-0">{{ $category->description ?: 'No description available' }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary rounded-pill">
            <i class="fas fa-arrow-left me-2"></i>Back to Categories
        </a>
        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-primary rounded-pill">
            <i class="fas fa-edit me-2"></i>Edit Category
        </a>
    </div>
</div>

<!-- Category Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 bg-primary bg-opacity-10 text-center p-3">
            <h3 class="text-primary mb-0">{{ $category->books()->count() }}</h3>
            <small class="text-muted">Total Books</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 bg-success bg-opacity-10 text-center p-3">
            <h3 class="text-success mb-0">{{ $category->books()->sum('available_quantity') }}</h3>
            <small class="text-muted">Available Books</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 bg-info bg-opacity-10 text-center p-3">
            <h3 class="text-info mb-0">{{ $category->books()->sum('quantity') - $category->books()->sum('available_quantity') }}</h3>
            <small class="text-muted">Borrowed Books</small>
        </div>
    </div>
</div>

<!-- Books in this Category -->
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-transparent border-0 p-4">
        <h6 class="mb-0 fw-bold">Books in this Category</h6>
    </div>
    <div class="card-body p-0">
        @if(isset($books) && $books->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 fw-bold p-4">Book</th>
                            <th class="border-0 fw-bold p-4">Author</th>
                            <th class="border-0 fw-bold p-4">Availability</th>
                            <th class="border-0 fw-bold p-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($books as $book)
                        <tr>
                            <td class="p-4">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        @if($book->cover_image)
                                            <img src="{{ asset('storage/' . $book->cover_image) }}"
                                                 alt="{{ $book->title }}"
                                                 class="rounded"
                                                 style="width: 40px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-primary bg-opacity-10 rounded d-flex align-items-center justify-content-center"
                                                 style="width: 40px; height: 50px;">
                                                <i class="fas fa-book text-primary"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $book->title }}</div>
                                        <small class="text-muted">{{ $book->isbn ?: 'No ISBN' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">{{ $book->author }}</td>
                            <td class="p-4">
                                <span class="badge bg-{{ $book->available_quantity > 0 ? 'success' : 'danger' }} rounded-pill">
                                    {{ $book->available_quantity }}/{{ $book->quantity }}
                                </span>
                            </td>
                            <td class="p-4">
                                <a href="{{ route('admin.books.show', $book) }}" class="btn btn-sm btn-outline-info rounded-pill">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-top">
                {{ $books->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No Books in this Category</h5>
                <p class="text-muted">Add some books to this category to see them here.</p>
                <a href="{{ route('admin.books.create') }}" class="btn btn-primary rounded-pill">
                    <i class="fas fa-plus me-2"></i>Add Book
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
