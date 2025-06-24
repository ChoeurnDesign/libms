@extends('layouts.admin')

@section('page-title', 'Edit Book')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-dark">Edit Book</h4>
    <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary rounded-pill">
        <i class="fas fa-arrow-left me-2"></i>Back to Books
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.books.update', $book) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Book Cover Image -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">
                            <i class="fas fa-image me-2 text-info"></i>Book Cover Image (Optional)
                        </label>
                        @if($book->cover_image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="Current Cover" style="max-height:120px;">
                            </div>
                        @endif
                        <input type="file"
                               name="cover_image"
                               class="form-control rounded-3 @error('cover_image') is-invalid @enderror"
                               accept="image/*">
                        @error('cover_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Book Title -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">
                            <i class="fas fa-book me-2 text-primary"></i>Book Title *
                        </label>
                        <input type="text"
                               name="title"
                               class="form-control rounded-3 @error('title') is-invalid @enderror"
                               value="{{ old('title', $book->title) }}"
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Author -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">
                            <i class="fas fa-user me-2 text-success"></i>Author *
                        </label>
                        <input type="text"
                               name="author"
                               class="form-control rounded-3 @error('author') is-invalid @enderror"
                               value="{{ old('author', $book->author) }}"
                               required>
                        @error('author')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- ISBN -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">
                            <i class="fas fa-barcode me-2 text-info"></i>ISBN (Optional)
                        </label>
                        <input type="text"
                               name="isbn"
                               class="form-control rounded-3 @error('isbn') is-invalid @enderror"
                               value="{{ old('isbn', $book->isbn) }}">
                        @error('isbn')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">
                            <i class="fas fa-tags me-2 text-warning"></i>Category *
                        </label>
                        <select name="category_id"
                                class="form-select rounded-3 @error('category_id') is-invalid @enderror"
                                required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Total Copies -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">
                            <i class="fas fa-layer-group me-2 text-danger"></i>Total Copies *
                        </label>
                        <input type="number"
                               name="total_copies"
                               class="form-control rounded-3 @error('total_copies') is-invalid @enderror"
                               value="{{ old('total_copies', $book->total_copies) }}"
                               min="1"
                               required>
                        @error('total_copies')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Location -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">
                            <i class="fas fa-map-marker-alt me-2 text-primary"></i>Location (Optional)
                        </label>
                        <input type="text"
                               name="location"
                               class="form-control rounded-3"
                               value="{{ old('location', $book->location) }}">
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">
                            <i class="fas fa-align-left me-2 text-secondary"></i>Description (Optional)
                        </label>
                        <textarea name="description"
                                  class="form-control rounded-3"
                                  rows="4">{{ old('description', $book->description) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Book</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
