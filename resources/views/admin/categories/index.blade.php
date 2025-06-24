@extends('layouts.admin')

@section('page-title', 'Book Categories')

@section('content')
<!-- Header -->
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

<!-- Messages -->
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

<!-- Categories Grid -->
<div class="row g-4">
    @forelse($categories as $category)
    <div class="col-xl-4 col-lg-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <!-- Header with Circular Yellow Icon -->
            <div class="card-header bg-transparent border-0 d-flex align-items-center justify-content-between p-4">
                <div class="d-flex align-items-center">
                    <!-- Circular Yellow Icon -->
                    <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center me-3"
                         style="width: 60px; height: 60px;">
                        <i class="fas fa-tag text-dark fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold text-dark">{{ $category->name }}</h5>
                        <small class="text-muted">Category</small>
                    </div>
                </div>
                <span class="badge bg-info bg-opacity-20 text-dark rounded-pill fs-6">
                    {{ $category->books_count ?? 0 }} books
                </span>
            </div>

            <!-- Body -->
            <div class="card-body p-4 pt-0">
                <p class="text-dark mb-4">{{ $category->description ?: 'No description available.' }}</p>

                <!-- Stats -->
                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="bg-light rounded-3 p-3 text-center">
                            <div class="fw-bold text-primary h5 mb-0">{{ $category->books_count ?? 0 }}</div>
                            <small class="text-dark">Total Books</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light rounded-3 p-3 text-center">
                            <div class="fw-bold text-success h5 mb-0">
                                {{ $category->books ? $category->books->sum('available_quantity') : 0 }}
                            </div>
                            <small class="text-dark">Available</small>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-flex gap-2">
                    @if(($category->books_count ?? 0) > 0)
                        <a href="{{ route('admin.categories.show', $category) }}"
                           class="btn btn-info btn-sm rounded-pill flex-fill text-white">
                            <i class="fas fa-eye me-1"></i>View Books
                        </a>
                    @else
                        <span class="btn btn-outline-secondary btn-sm rounded-pill flex-fill disabled">
                            <i class="fas fa-inbox me-1"></i>No Books
                        </span>
                    @endif

                    <a href="{{ route('admin.categories.edit', $category) }}"
                       class="btn btn-outline-primary btn-sm rounded-pill">
                        <i class="fas fa-edit"></i>
                    </a>

                    <button onclick="deleteCategory('{{ $category->name }}', '{{ route('admin.categories.destroy', $category) }}', {{ $category->books_count ?? 0 }})"
                            class="btn btn-outline-danger btn-sm rounded-pill">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @empty
    <!-- Empty State -->
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body text-center py-5">
                <!-- Circular Yellow Icon for Empty State -->
                <div class="bg-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                     style="width: 80px; height: 80px;">
                    <i class="fas fa-tags fa-2x text-dark"></i>
                </div>
                <h5 class="text-dark mb-2">No Categories Found</h5>
                <p class="text-muted mb-4">Start organizing your library by creating book categories.</p>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-success rounded-pill">
                    <i class="fas fa-plus me-2"></i>Create First Category
                </a>
            </div>
        </div>
    </div>
    @endforelse
</div>

<!-- Quick Stats -->
@if(isset($categories) && $categories->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-transparent border-0 p-4">
                <h6 class="mb-0 fw-bold text-dark">
                    <i class="fas fa-chart-pie text-info me-2"></i>Overview
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-3 text-center">
                    <div class="col-md-3">
                        <div class="display-6 fw-bold text-warning">{{ $categories->count() }}</div>
                        <small class="text-dark">Categories</small>
                    </div>
                    <div class="col-md-3">
                        <div class="display-6 fw-bold text-primary">{{ $categories->sum('books_count') }}</div>
                        <small class="text-dark">Total Books</small>
                    </div>
                    <div class="col-md-3">
                        <div class="display-6 fw-bold text-success">
                            {{ $categories->avg('books_count') ? round($categories->avg('books_count'), 1) : 0 }}
                        </div>
                        <small class="text-dark">Avg Books</small>
                    </div>
                    <div class="col-md-3">
                        <div class="display-6 fw-bold text-info">
                            {{ $categories->where('books_count', '>', 0)->count() }}
                        </div>
                        <small class="text-dark">Active</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-3">
            <div class="modal-header">
                <h5 class="modal-title text-dark">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-dark">Delete category <strong id="categoryName"></strong>?</p>
                <div id="warning" class="alert alert-warning d-none">
                    <span class="text-dark">Cannot delete category with books!</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" id="deleteBtn" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteCategory(name, url, bookCount) {
    document.getElementById('categoryName').textContent = name;
    document.getElementById('deleteForm').action = url;

    const warning = document.getElementById('warning');
    const deleteBtn = document.getElementById('deleteBtn');

    if (bookCount > 0) {
        warning.classList.remove('d-none');
        deleteBtn.disabled = true;
        deleteBtn.textContent = 'Cannot Delete';
    } else {
        warning.classList.add('d-none');
        deleteBtn.disabled = false;
        deleteBtn.textContent = 'Delete';
    }

    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endsection
