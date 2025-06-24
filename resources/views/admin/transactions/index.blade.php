@extends('layouts.admin')

@section('page-title', 'Transactions')

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">
            <i class="fas fa-exchange-alt text-info me-2"></i>Library Transactions
        </h4>
        <p class="text-muted mb-0">Manage book borrowing and return transactions</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary rounded-pill">
            <i class="fas fa-arrow-left me-2"></i>Dashboard
        </a>
        <a href="{{ route('admin.transactions.create') }}" class="btn btn-primary rounded-pill">
            <i class="fas fa-plus me-2"></i>New Transaction
        </a>
    </div>
</div>

<!-- Quick Stats -->
@if(isset($stats))
<div class="row g-3 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-3 bg-primary bg-opacity-10">
            <div class="card-body text-center p-4">
                <div class="display-6 fw-bold text-primary">{{ $stats['total'] ?? 0 }}</div>
                <small class="text-dark">Total Transactions</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-3 bg-warning bg-opacity-10">
            <div class="card-body text-center p-4">
                <div class="display-6 fw-bold text-warning">{{ $stats['active'] ?? 0 }}</div>
                <small class="text-dark">Currently Borrowed</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-3 bg-success bg-opacity-10">
            <div class="card-body text-center p-4">
                <div class="display-6 fw-bold text-success">{{ $stats['returned'] ?? 0 }}</div>
                <small class="text-dark">Returned Books</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-3 bg-danger bg-opacity-10">
            <div class="card-body text-center p-4">
                <div class="display-6 fw-bold text-danger">{{ $stats['overdue'] ?? 0 }}</div>
                <small class="text-dark">Overdue Books</small>
            </div>
        </div>
    </div>
</div>
@endif

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

<!-- Filters & Search -->
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body p-4">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label text-dark">Search</label>
                <input type="text" name="search" class="form-control"
                       placeholder="Search by user or book..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label text-dark">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="borrowed" {{ request('status') == 'borrowed' ? 'selected' : '' }}>Currently Borrowed</option>
                    <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-info d-block">
                    <i class="fas fa-search me-1"></i>Filter
                </button>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <a href="{{ route('admin.transactions.overdue') }}" class="btn btn-warning d-block">
                    <i class="fas fa-clock me-1"></i>View Overdue
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Transactions Table -->
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        @if(isset($transactions) && $transactions->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 fw-bold p-4 text-dark">User</th>
                            <th class="border-0 fw-bold p-4 text-dark">Book</th>
                            <th class="border-0 fw-bold p-4 text-dark">Borrowed</th>
                            <th class="border-0 fw-bold p-4 text-dark">Due Date</th>
                            <th class="border-0 fw-bold p-4 text-dark">Status</th>
                            <th class="border-0 fw-bold p-4 text-dark">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                        <tr>
                            <td class="p-4">
                                <div>
                                    <div class="fw-bold text-dark">{{ $transaction->user->name }}</div>
                                    <small class="text-muted">{{ $transaction->user->email }}</small>
                                </div>
                            </td>
                            <td class="p-4">
                                <div>
                                    <div class="fw-bold text-dark">{{ $transaction->book->title }}</div>
                                    <small class="text-muted">by {{ $transaction->book->author }}</small>
                                </div>
                            </td>
                            <td class="p-4">
                                <span class="text-dark">{{ $transaction->borrowed_date->format('M d, Y') }}</span>
                                @if($transaction->renewed_at)
                                    {{-- Only display renewed_at if it exists and is a Carbon instance --}}
                                    <br><small class="text-info">Renewed {{ $transaction->renewed_at->format('M d') }}</small>
                                @endif
                            </td>
                            <td class="p-4">
                                <span class="text-dark">{{ $transaction->due_date->format('M d, Y') }}</span>
                                @if($transaction->isOverdue())
                                    <br><small class="text-danger fw-bold">{{ $transaction->getOverdueDays() }} days overdue!</small>
                                @endif
                            </td>
                            <td class="p-4">
                                @if($transaction->returned_date)
                                    <span class="badge bg-success rounded-pill">
                                        <i class="fas fa-check me-1"></i>Returned
                                    </span>
                                    <br><small class="text-muted">{{ $transaction->returned_date->format('M d, Y') }}</small>
                                @elseif($transaction->isOverdue())
                                    <span class="badge bg-danger rounded-pill">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Overdue
                                    </span>
                                @else
                                    <span class="badge bg-warning rounded-pill">
                                        <i class="fas fa-book me-1"></i>Borrowed
                                    </span>
                                @endif
                            </td>
                            <td class="p-4">
                                @if(!$transaction->returned_date)
                                    <div class="d-flex gap-1">
                                        <!-- Return Button -->
                                        <form method="POST" action="{{ route('admin.transactions.return', $transaction) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success rounded-pill"
                                                    onclick="return confirm('Mark this book as returned?')">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        </form>
                                        <!-- Renew Button (using a modal for date selection) -->
                                        <button type="button" class="btn btn-sm btn-info rounded-pill" data-bs-toggle="modal" data-bs-target="#renewModal{{ $transaction->id }}">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-success">
                                        <i class="fas fa-check-circle me-1"></i>Complete
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-top">
                {{ $transactions->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                     style="width: 80px; height: 80px;">
                    <i class="fas fa-exchange-alt fa-2x text-muted"></i>
                </div>
                <h5 class="text-muted">No Transactions Found</h5>
                <p class="text-muted">Start by creating your first borrowing transaction.</p>
                <a href="{{ route('admin.transactions.create') }}" class="btn btn-primary rounded-pill">
                    <i class="fas fa-plus me-2"></i>New Transaction
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Renew Modal for each transaction -->
@foreach($transactions as $transaction)
<div class="modal fade" id="renewModal{{ $transaction->id }}" tabindex="-1" aria-labelledby="renewModalLabel{{ $transaction->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-3 shadow">
            <div class="modal-header bg-info text-white border-0">
                <h5 class="modal-title" id="renewModalLabel{{ $transaction->id }}">Renew Borrowing</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.transactions.renew', $transaction) }}">
                @csrf
                <div class="modal-body p-4">
                    <p>Renewing <strong>{{ $transaction->book->title }}</strong> by <strong>{{ $transaction->user->name }}</strong>.</p>
                    <div class="mb-3">
                        <label for="new_due_date{{ $transaction->id }}" class="form-label">New Due Date</label>
                        <input type="date" class="form-control" id="new_due_date{{ $transaction->id }}" name="new_due_date" required min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info rounded-pill">Renew Book</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection
