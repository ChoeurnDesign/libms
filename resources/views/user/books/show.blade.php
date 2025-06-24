@extends('layouts.app')

@section('title', $book->title)

@section('content')
@include('layouts.navbar')

<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('books.index') }}">Browse Books</a></li>
            <li class="breadcrumb-item active">{{ $book->title }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Book Cover -->
        <div class="col-md-4 mb-4">
            <div class="card">
                @if($book->cover_url)
                    <img src="{{ $book->cover_url }}"
                         class="card-img-top"
                         alt="{{ $book->title }}"
                         style="width: 100%; object-fit: contain;">
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                         style="height: 400px;">
                        <i class="fas fa-book fa-5x text-muted"></i>
                    </div>
                @endif
            </div>
        </div>

        <!-- Book Details -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title h3">{{ $book->title }}</h1>
                    <p class="lead text-muted">by {{ $book->author }}</p>

                    @if($book->category)
                        <p class="mb-2">
                            <span class="badge bg-primary">
                                <i class="fas fa-tag me-1"></i>{{ $book->category->name }}
                            </span>
                        </p>
                    @endif

                    <!-- Availability Status -->
                    <div class="mb-3">
                        @if($book->is_available)
                            <span class="badge bg-success fs-6">
                                <i class="fas fa-check-circle me-1"></i>Available
                            </span>
                        @else
                            <span class="badge bg-warning fs-6">
                                <i class="fas fa-exclamation-circle me-1"></i>Borrowed
                            </span>
                        @endif

                        @if($hasActiveBorrowing)
                            <span class="badge bg-info fs-6 ms-2">
                                <i class="fas fa-user-check me-1"></i>You borrowed this
                            </span>
                        @endif
                    </div>

                    <!-- Book Info -->
                    <div class="row mb-4">
                        @if($book->isbn)
                            <div class="col-sm-6 mb-2">
                                <strong>ISBN:</strong> {{ $book->isbn }}
                            </div>
                        @endif
                        <div class="col-sm-6 mb-2">
                            <strong>Copies:</strong> {{ $book->available_quantity }} of {{ $book->quantity }} available
                        </div>
                        @if($book->location)
                            <div class="col-sm-6 mb-2">
                                <strong>Location:</strong> {{ $book->location }}
                            </div>
                        @endif
                    </div>

                    <!-- Description -->
                    @if($book->description)
                        <div class="mb-4">
                            <h5>Description</h5>
                            <p class="text-muted">{{ $book->description }}</p>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="d-flex gap-3 mb-4">
                        @auth
                            @if($canBorrow)
                                <button type="button" class="btn btn-success btn-lg rounded-pill px-4"
                                        onclick="borrowBook({{ $book->id }})">
                                    <i class="fas fa-book me-2"></i>Borrow Book
                                </button>
                            @else
                                <button type="button" class="btn btn-secondary btn-lg rounded-pill px-4" disabled>
                                    <i class="fas fa-times me-2"></i>{{ $borrowMessage }}
                                </button>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg rounded-pill px-4">
                                <i class="fas fa-sign-in-alt me-2"></i>Login to Borrow
                            </a>
                        @endauth
                    </div>

                    <!-- User's Borrowing Info -->
                    @auth
                        <div class="border-top pt-3">
                            <small class="text-muted">
                                You have {{ $activeBorrowingsCount }} active borrowing(s) out of 5 maximum.
                            </small>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="row mt-4">
        <div class="col-12">
            <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Browse Books
            </a>
        </div>
    </div>
</div>

<!-- CSRF Token meta for AJAX -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
function borrowBook(bookId) {
    if (confirm('Do you want to borrow this book for 14 days?')) {
        fetch(`/books/${bookId}/borrow`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ ' + data.message);
                window.location.reload();
            } else {
                alert('❌ ' + (data.message || 'Error borrowing book'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Error borrowing book. Please try again.');
        });
    }
}
</script>
@endsection
