@extends('layouts.app')

@section('title', 'My Favorites')

@section('content')
@include('layouts.navbar')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary mb-3">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                    <h3 class="card-title">
                        <i class="fas fa-heart text-danger"></i>
                        My Favorite Books
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <i class="fas fa-heart text-danger me-3" style="font-size: 2rem;"></i>
                                <div>
                                    <h4 class="mb-0">{{ $stats['total_favorites'] }}</h4>
                                    <small class="text-muted">Total Favorites</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <i class="fas fa-check-circle text-success me-3" style="font-size: 2rem;"></i>
                                <div>
                                    <h4 class="mb-0">{{ $stats['available_favorites'] }}</h4>
                                    <small class="text-muted">Available</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <i class="fas fa-book text-warning me-3" style="font-size: 2rem;"></i>
                                <div>
                                    <h4 class="mb-0">{{ $stats['borrowed_favorites'] }}</h4>
                                    <small class="text-muted">Currently Borrowed</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($favoriteBooks->count() > 0)
                        <div class="row">
                            @foreach($favoriteBooks as $book)
                                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                    <div class="card h-100">
                                        @if(isset($book->book_cover) && $book->book_cover)
                                            <img src="{{ $book->book_cover }}" class="card-img-top"
                                            style="width: 100%; object-fit: contain; height: 250px;"
                                             alt="{{ $book->book_title }}">
                                        @else
                                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                                <i class="fas fa-book fa-3x text-muted"></i>
                                            </div>
                                        @endif

                                        <div class="card-body d-flex flex-column">
                                            <h6 class="card-title">{{ Str::limit($book->book_title ?? 'Unknown Title', 30) }}</h6>
                                            <p class="text-muted small">{{ $book->book_author ?? 'Unknown Author' }}</p>

                                            <div class="mt-auto">
                                                {{--  FIXED: Changed to user.books.show  --}}
                                                <a href="{{ route('user.books.show', $book->book_id) }}" class="btn btn-primary btn-sm">
                                                    View Details
                                                </a>

                                                {{-- Use favorite_id for delete --}}
                                                <form method="POST" action="{{ route('user.favorites.remove', $book->favorite_id) }}" class="d-inline" onsubmit="return confirm('Remove from favorites?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                                        <i class="fas fa-heart-broken"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-heart-broken fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">No Favorite Books Yet</h4>
                            <p class="text-muted">
                                Start adding books to your favorites by clicking the heart icon on any book.
                            </p>
                            {{--  FIXED: Changed to user.books.index  --}}
                            <a href="{{ route('user.books.index') }}" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                                Browse Books
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
