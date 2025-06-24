@extends('layouts.admin')

@section('page-title', 'Edit Category')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">
            <i class="fas fa-edit text-primary me-2"></i>Edit Category
        </h4>
        <p class="text-muted mb-0">Update category information</p>
    </div>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary rounded-pill">
        <i class="fas fa-arrow-left me-2"></i>Back to Categories
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-transparent border-0 p-4">
                <h6 class="mb-0 fw-bold">Category Information</h6>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.categories.update', $category) }}">
                    @csrf @method('PUT')

                    <!-- Category Name -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark">
                            <i class="fas fa-tag me-2 text-warning"></i>Category Name *
                        </label>
                        <input type="text"
                               name="name"
                               class="form-control form-control-lg rounded-3 @error('name') is-invalid @enderror"
                               value="{{ old('name', $category->name) }}"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark">
                            <i class="fas fa-align-left me-2 text-info"></i>Description (Optional)
                        </label>
                        <textarea name="description"
                                  class="form-control rounded-3"
                                  rows="4">{{ old('description', $category->description) }}</textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end gap-3 mt-4 pt-3 border-top">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            <i class="fas fa-save me-2"></i>Update Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
