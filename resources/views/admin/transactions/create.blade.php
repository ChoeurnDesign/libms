@extends('layouts.admin')

@section('page-title', 'New Transaction')

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">
            <i class="fas fa-plus text-success me-2"></i>New Transaction
        </h4>
        <p class="text-muted mb-0">Issue a book to a user</p>
    </div>
    <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline-secondary rounded-pill">
        <i class="fas fa-arrow-left me-2"></i>Back to Transactions
    </a>
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

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-transparent border-0 p-4">
                <h6 class="mb-0 fw-bold text-dark">
                    <i class="fas fa-book-open text-primary me-2"></i>Transaction Details
                </h6>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.transactions.store') }}">
                    @csrf

                    <!-- User Selection -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark">
                            <i class="fas fa-user me-2 text-info"></i>Select User *
                        </label>
                        <select name="user_id" class="form-select form-select-lg @error('user_id') is-invalid @enderror" required>
                            <option value="">Choose a user...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Select which user is borrowing the book</small>
                    </div>

                    <!-- Book Selection -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark">
                            <i class="fas fa-book me-2 text-warning"></i>Select Book *
                        </label>
                        <select name="book_id" id="bookSelect" class="form-select form-select-lg @error('book_id') is-invalid @enderror" required>
                            <option value="">Choose a book...</option>
                            @foreach($books as $book)
                                <option value="{{ $book->id }}"
                                        data-available="{{ $book->available_quantity }}"
                                        {{ old('book_id') == $book->id ? 'selected' : '' }}>
                                    {{ $book->title }} by {{ $book->author }}
                                    ({{ $book->available_quantity }} available)
                                </option>
                            @endforeach
                        </select>
                        @error('book_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Select which book to lend</small>

                        <!-- Book Availability Info -->
                        <div id="bookInfo" class="mt-2 d-none">
                            <div class="alert alert-info rounded-3 py-2">
                                <i class="fas fa-info-circle me-2"></i>
                                <span id="availabilityText"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Due Date -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark">
                            <i class="fas fa-calendar-alt me-2 text-danger"></i>Due Date *
                        </label>
                        <input type="date"
                               name="due_date"
                               class="form-control form-control-lg @error('due_date') is-invalid @enderror"
                               value="{{ old('due_date', now()->addWeeks(2)->format('Y-m-d')) }}"
                               min="{{ now()->addDay()->format('Y-m-d') }}"
                               required>
                        @error('due_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">When should the book be returned? (Default: 2 weeks from today)</small>
                    </div>

                    <!-- Quick Date Buttons -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark">Quick Select:</label>
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="button" class="btn btn-outline-info btn-sm rounded-pill"
                                    onclick="setDueDate(7)">1 Week</button>
                            <button type="button" class="btn btn-outline-info btn-sm rounded-pill"
                                    onclick="setDueDate(14)">2 Weeks</button>
                            <button type="button" class="btn btn-outline-info btn-sm rounded-pill"
                                    onclick="setDueDate(30)">1 Month</button>
                            <button type="button" class="btn btn-outline-info btn-sm rounded-pill"
                                    onclick="setDueDate(60)">2 Months</button>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark">
                            <i class="fas fa-sticky-note me-2 text-secondary"></i>Notes (Optional)
                        </label>
                        <textarea name="notes"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Add any special notes about this transaction...">{{ old('notes') }}</textarea>
                        <small class="text-muted">Any special instructions or notes about this borrowing</small>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end gap-3 mt-4 pt-3 border-top">
                        <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-success rounded-pill px-4">
                            <i class="fas fa-check me-2"></i>Create Transaction
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row mt-4 g-3">
            <div class="col-md-4">
                <div class="card border-0 bg-info bg-opacity-10 text-center p-3">
                    <h5 class="text-info mb-1">{{ $users->count() }}</h5>
                    <small class="text-dark">Active Users</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 bg-success bg-opacity-10 text-center p-3">
                    <h5 class="text-success mb-1">{{ $books->count() }}</h5>
                    <small class="text-dark">Available Books</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 bg-warning bg-opacity-10 text-center p-3">
                    <h5 class="text-warning mb-1">{{ $books->sum('available_quantity') }}</h5>
                    <small class="text-dark">Total Copies</small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Set due date quickly
function setDueDate(days) {
    const today = new Date();
    const dueDate = new Date(today.getTime() + (days * 24 * 60 * 60 * 1000));
    const formattedDate = dueDate.toISOString().split('T')[0];
    document.querySelector('input[name="due_date"]').value = formattedDate;
}

// Show book availability info
document.getElementById('bookSelect').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const bookInfo = document.getElementById('bookInfo');
    const availabilityText = document.getElementById('availabilityText');

    if (selectedOption.value) {
        const available = selectedOption.dataset.available;
        bookInfo.classList.remove('d-none');

        if (available > 0) {
            availabilityText.innerHTML = `<strong>${available}</strong> copies available for borrowing`;
            bookInfo.querySelector('.alert').className = 'alert alert-success rounded-3 py-2';
        } else {
            availabilityText.innerHTML = 'This book is currently <strong>not available</strong>';
            bookInfo.querySelector('.alert').className = 'alert alert-danger rounded-3 py-2';
        }
    } else {
        bookInfo.classList.add('d-none');
    }
});

// Initialize form
document.addEventListener('DOMContentLoaded', function() {
    // Set default due date to 2 weeks from today
    const today = new Date();
    const twoWeeksLater = new Date(today.getTime() + (14 * 24 * 60 * 60 * 1000));
    const defaultDate = twoWeeksLater.toISOString().split('T')[0];

    const dueDateInput = document.querySelector('input[name="due_date"]');
    if (!dueDateInput.value) {
        dueDateInput.value = defaultDate;
    }
});
</script>
@endsection
