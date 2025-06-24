@extends('layouts.admin')

@section('page-title', 'Overdue Transactions')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-danger mb-1">
            <i class="fas fa-exclamation-triangle me-2"></i>Overdue Transactions
        </h4>
        <p class="text-muted mb-0">All transactions with overdue books</p>
    </div>
    <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline-secondary rounded-pill">
        <i class="fas fa-arrow-left me-2"></i>Back to Transactions
    </a>
</div>

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

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        @if(isset($overdueTransactions) && $overdueTransactions->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 fw-bold p-4 text-dark">User</th>
                            <th class="border-0 fw-bold p-4 text-dark">Book</th>
                            <th class="border-0 fw-bold p-4 text-dark">Borrowed</th>
                            <th class="border-0 fw-bold p-4 text-dark">Due Date</th>
                            <th class="border-0 fw-bold p-4 text-dark">Days Overdue</th>
                            <th class="border-0 fw-bold p-4 text-dark">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($overdueTransactions as $transaction)
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
                            </td>
                            <td class="p-4">
                                <span class="text-dark">{{ $transaction->due_date->format('M d, Y') }}</span>
                            </td>
                            <td class="p-4">
                                <span class="badge bg-danger rounded-pill">
                                    {{ $transaction->days_overdue ?? $transaction->due_date->diffInDays(now()) }} days
                                </span>
                            </td>
                            <td class="p-4">
                                @if(!$transaction->returned_date)
                                    <form method="POST" action="{{ route('admin.transactions.return', $transaction) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success rounded-pill"
                                                onclick="return confirm('Mark this book as returned?')">
                                            <i class="fas fa-undo"></i> Return
                                        </button>
                                    </form>
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
                {{ $overdueTransactions->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                     style="width: 80px; height: 80px;">
                    <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                </div>
                <h5 class="text-muted">No Overdue Transactions</h5>
                <p class="text-muted">Great! There are currently no overdue books.</p>
                <a href="{{ route('admin.transactions.index') }}" class="btn btn-primary rounded-pill">
                    <i class="fas fa-arrow-left me-2"></i>Back to Transactions
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
