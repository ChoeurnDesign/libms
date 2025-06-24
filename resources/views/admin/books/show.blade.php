@extends('layouts.admin')

@section('page-title', 'Book Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-dark">Book Details</h4>
    <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary rounded-pill">
        <i class="fas fa-arrow-left me-2"></i>Back to Books
    </a>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-4">
        <h5>{{ $book->title ?? 'Book Title' }}</h5>
        <p class="text-muted">Author: {{ $book->author ?? 'Author Name' }}</p>
        <p>{{ $book->description ?? 'No description available.' }}</p>
    </div>
</div>
@endsection
