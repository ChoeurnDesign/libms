@extends('layouts.app')

@section('title', 'My Borrowings')

@section('content')
@include('layouts.navbar')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    {{-- Changed route for "Back" button to use route helper --}}
                    <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary mb-3">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                    <h3 class="card-title">
                        <i class="fas fa-book-open text-primary"></i>
                        Currently Reading
                    </h3>
                    <div class="card-tools">
                        {{-- This route is already correct --}}
                        <a href="{{ route('user.borrowings.history') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-history"></i> Reading History
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <i class="fas fa-book text-primary me-3" style="font-size: 2rem;"></i>
                                <div>
                                    <h4 class="mb-0">{{ $stats['total_active'] }}</h4>
                                    <small class="text-muted">Currently Reading</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <i class="fas fa-clock text-warning me-3" style="font-size: 2rem;"></i>
                                <div>
                                    <h4 class="mb-0">{{ $stats['due_soon'] }}</h4>
                                    <small class="text-muted">Due Soon (3 days)</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <i class="fas fa-calendar-check text-info me-3" style="font-size: 2rem;"></i>
                                <div>
                                    <h4 class="mb-0">{{ $stats['needs_return'] ?? $stats['overdue'] }}</h4>
                                    <small class="text-muted">Needs Return</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <i class="fas fa-plus text-success me-3" style="font-size: 2rem;"></i>
                                <div>
                                    <h4 class="mb-0">{{ $stats['can_borrow_more'] ? 'Yes' : 'No' }}</h4>
                                    <small class="text-muted">Can Borrow More</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($activeBorrowings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Book</th>
                                        <th>Author</th>
                                        <th>Started Reading</th>
                                        <th>Return By</th>
                                        <th>Reading Days</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activeBorrowings as $borrowing)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($borrowing->book && $borrowing->book->cover_image)
                                                        <img src="{{ asset($borrowing->book->cover_url) }}" {{-- Changed to use cover_url appends --}}
                                                             class="me-2"
                                                             style="width: 40px; height: 50px; object-fit: cover;"
                                                             alt="{{ $borrowing->book_title }}">
                                                    @else
                                                        <div class="bg-light me-2 d-flex align-items-center justify-content-center"
                                                             style="width: 40px; height: 50px;">
                                                            <i class="fas fa-book text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <strong>{{ $borrowing->book_title ?? 'Unknown Title' }}</strong>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $borrowing->book_author ?? 'Unknown Author' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($borrowing->borrowed_date)->format('M d, Y') ?? 'N/A' }}</td> {{-- Formatted date --}}
                                            <td>
                                                @if(isset($borrowing->due_date))
                                                    {{ \Carbon\Carbon::parse($borrowing->due_date)->format('M d, Y') }} {{-- Formatted date --}}
                                                    @if(\Carbon\Carbon::parse($borrowing->due_date)->isPast()) {{-- Using Carbon methods --}}
                                                        <span class="badge bg-warning text-dark">Overdue</span>
                                                    @elseif(\Carbon\Carbon::parse($borrowing->due_date)->diffInDays(now()) <= 3) {{-- Using Carbon methods --}}
                                                        <span class="badge bg-info text-dark">Due Soon</span>
                                                    @endif
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($borrowing->borrowed_date))
                                                    @php
                                                        $borrowedDate = \Carbon\Carbon::parse($borrowing->borrowed_date);
                                                        $days = $borrowedDate->diffInDays(now());
                                                    @endphp
                                                    <span class="text-info">{{ $days }} days</span>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                @if($borrowing->status === 'borrowed')
                                                    <span class="badge bg-success text-white">Reading</span>
                                                @else
                                                    <span class="badge bg-secondary text-dark">{{ ucfirst($borrowing->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($borrowing->book_id))
                                                    <a href="{{ route('user.books.show', $borrowing->book_id) }}"
                                                       class="btn btn-sm btn-outline-primary"
                                                       title="View Book Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @endif

                                                @if($borrowing->status === 'borrowed')
                                                    <button class="btn btn-sm btn-outline-success"
                                                            onclick="extendReading({{ $borrowing->id }})"
                                                            title="Extend Reading Time">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                    {{-- Return Button --}}
                                                    <button class="btn btn-sm btn-outline-danger"
                                                            onclick="returnBook({{ $borrowing->id }})"
                                                            title="Return Book">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-info-circle text-info"></i>
                                        Reading Guidelines
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <ul class="mb-0">
                                                <li>📚 Borrow up to 5 books at once</li>
                                                <li>⏰ Standard reading period: 2 weeks</li>
                                                <li>🔄 Extend reading time if needed</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <ul class="mb-0">
                                                <li>📖 Take your time to enjoy reading</li>
                                                <li>⭐ Rate books after reading</li>
                                                <li>🏆 Build your reading achievements</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-book-open fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">No Books Currently Reading</h4>
                            <p class="text-muted">
                                Start your reading journey! Browse our collection and discover amazing books.
                            </p>
                            <div class="mt-4">
                                <a href="{{ route('user.books.index') }}" class="btn btn-primary btn-lg me-2">
                                    <i class="fas fa-search"></i>
                                    Discover Books
                                </a>
                                {{-- Changed route to user.categories.index --}}
                                <a href="{{ route('user.categories.index') }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-list"></i>
                                    Browse Categories
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function extendReading(borrowingId) {
    if (confirm('Would you like to extend your reading time by 2 weeks?')) {
        fetch(`{{ url('/user/borrowings') }}/${borrowingId}/renew`, { {{-- Using url() helper for direct path, or route() if available for API --}}
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Reading time extended successfully! Enjoy your book! 📚');
                window.location.reload();
            } else {
                alert(data.message || 'Unable to extend reading time');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error extending reading time. Please try again.');
        });
    }
}

// NEW: returnBook function
function returnBook(borrowingId) {
    if (confirm('Are you sure you want to return this book?')) {
        fetch(`{{ url('/user/borrowings') }}/${borrowingId}/return`, { {{-- Using url() helper for direct path --}}
            method: 'POST', // Or 'PUT', depending on your preference for RESTful actions
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Book returned successfully! Thank you for reading. 🎉');
                window.location.reload(); // Reload the page to update the list
            } else {
                alert(data.message || 'Unable to return book.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error returning book. Please try again.');
        });
    }
}
</script>
@endsection
