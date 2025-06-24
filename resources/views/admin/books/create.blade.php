@extends('layouts.admin')

@section('page-title', 'Add New Book')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">
            <i class="fas fa-plus text-success me-2"></i>Add New Book
        </h4>
        <p class="text-muted mb-0">Fill in the details to add a new book to your library</p>
    </div>
    <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary rounded-pill">
        <i class="fas fa-arrow-left me-2"></i>Back to Books
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-transparent border-0 p-4">
                <h6 class="mb-0 fw-bold">Book Information</h6>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.books.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <!-- Book Cover Image -->
                        <div class="col-md-12">
                            <label class="form-label fw-semibold text-dark">
                                <i class="fas fa-image me-2 text-info"></i>Book Cover Image (Optional)
                            </label>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="border-2 border-dashed border-secondary rounded-3 p-4 text-center"
                                         style="min-height: 200px;"
                                         id="imagePreview">
                                        <i class="fas fa-book fa-3x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">Book Cover Preview</p>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <input type="file"
                                           name="cover_image"
                                           class="form-control rounded-3 @error('cover_image') is-invalid @enderror"
                                           accept="image/*"
                                           onchange="previewImage(this)">
                                    @error('cover_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted mt-2 d-block">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Accepted formats: JPG, PNG, GIF. Max size: 2MB
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Book Title -->
                        <div class="col-md-12">
                            <label class="form-label fw-semibold text-dark">
                                <i class="fas fa-book me-2 text-primary"></i>Book Title *
                            </label>
                            <input type="text"
                                   name="title"
                                   class="form-control rounded-3 @error('title') is-invalid @enderror"
                                   value="{{ old('title') }}"
                                   placeholder="Enter book title"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Author -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">
                                <i class="fas fa-user me-2 text-success"></i>Author *
                            </label>
                            <input type="text"
                                   name="author"
                                   class="form-control rounded-3 @error('author') is-invalid @enderror"
                                   value="{{ old('author') }}"
                                   placeholder="Enter author name"
                                   required>
                            @error('author')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- ISBN -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">
                                <i class="fas fa-barcode me-2 text-info"></i>ISBN (Optional)
                            </label>
                            <input type="text"
                                   name="isbn"
                                   class="form-control rounded-3 @error('isbn') is-invalid @enderror"
                                   value="{{ old('isbn') }}"
                                   placeholder="Enter ISBN number">
                            @error('isbn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">
                                <i class="fas fa-tags me-2 text-warning"></i>Category *
                            </label>
                            <select name="category_id"
                                    class="form-select rounded-3 @error('category_id') is-invalid @enderror"
                                    required>
                                <option value="">Select Category</option>
                                @if(isset($categories) && $categories->count() > 0)
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="" disabled>No categories available - Create one first</option>
                                @endif
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if(!isset($categories) || $categories->count() == 0)
                                <small class="text-warning">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    <a href="{{ route('admin.categories.create') }}" class="text-warning">Create a category first</a>
                                </small>
                            @endif
                        </div>

                        <!-- Total Copies -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">
                                <i class="fas fa-layer-group me-2 text-danger"></i>Total Copies *
                            </label>
                            <input type="number"
                                   name="total_copies"
                                   class="form-control rounded-3 @error('total_copies') is-invalid @enderror"
                                   value="{{ old('total_copies', 1) }}"
                                   min="1"
                                   placeholder="Enter quantity"
                                   required>
                            @error('total_copies')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Location -->
                        <div class="col-md-12">
                            <label class="form-label fw-semibold text-dark">
                                <i class="fas fa-map-marker-alt me-2 text-primary"></i>Location (Optional)
                            </label>
                            <input type="text"
                                   name="location"
                                   class="form-control rounded-3"
                                   value="{{ old('location') }}"
                                   placeholder="e.g., Shelf A-1, Section Fiction, Floor 2">
                        </div>

                        <!-- Description -->
                        <div class="col-md-12">
                            <label class="form-label fw-semibold text-dark">
                                <i class="fas fa-align-left me-2 text-secondary"></i>Description (Optional)
                            </label>
                            <textarea name="description"
                                      class="form-control rounded-3"
                                      rows="4"
                                      placeholder="Enter book description, summary, or notes...">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end gap-3 mt-4 pt-3 border-top">
                        <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-success rounded-pill px-4">
                            <i class="fas fa-save me-2"></i>Add Book
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Quick Tips -->
        <div class="card border-0 bg-light mt-4">
            <div class="card-body p-3">
                <h6 class="fw-bold text-dark mb-2">
                    <i class="fas fa-lightbulb text-warning me-2"></i>Quick Tips
                </h6>
                <ul class="small text-muted mb-0">
                    <li>Upload a clear book cover image for better visual appeal</li>
                    <li>Make sure the book title is accurate and complete</li>
                    <li>Include the full author name for better searchability</li>
                    <li>ISBN helps in identifying unique editions</li>
                    <li>Select appropriate category for better organization</li>
                    <li>Specify location to help find books quickly</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.innerHTML = `
                <img src="${e.target.result}"
                     class="img-fluid rounded-3"
                     style="max-height: 180px; object-fit: cover;"
                     alt="Book Cover Preview">
                <p class="text-success mt-2 mb-0 small">
                    <i class="fas fa-check me-1"></i>Image selected
                </p>
            `;
        }

        reader.readAsDataURL(input.files[0]);
    } else {
        preview.innerHTML = `
            <i class="fas fa-book fa-3x text-muted mb-3"></i>
            <p class="text-muted mb-0">Book Cover Preview</p>
        `;
    }
}
</script>
@endsection
