@extends('layouts.admin')

@section('page-title', 'Borrowings Report')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">
            <i class="fas fa-exchange-alt text-success me-2"></i>Borrowings Report
        </h4>
        <p class="text-muted mb-0">All borrowing transactions</p>
    </div>
    <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary rounded-pill">
        <i class="fas fa-arrow-left me-2"></i>Back to Reports
    </a>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-transparent border-0 p-4">
        <h6 class="mb-0 fw-bold text-dark">
            <i class="fas fa-list-ul text-success me-2"></i>Borrowing History
        </h6>
    </div>
    <div class="card-body p-0">
        @if($borrowings->count())
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 p-3">Student</th>
                            <th class="border-0 p-3">Book</th>
                            <th class="border-0 p-3">Borrowed Date</th>
                            <th class="border-0 p-3">Due Date</th>
                            <th class="border-0 p-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($borrowings as $borrowing)
                        <tr>
                            <td class="p-3">{{ $borrowing->user->name }}</td>
                            <td class="p-3">{{ $borrowing->book->title }}</td>
                            <td class="p-3">{{ $borrowing->borrowed_date->format('Y-m-d') }}</td>
                            <td class="p-3">{{ $borrowing->due_date->format('Y-m-d') }}</td>
                            <td class="p-3">
                                @if($borrowing->returned_date)
                                    <span class="badge bg-success rounded-pill">Returned</span>
                                @else
                                    <span class="badge bg-warning rounded-pill">Active</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-top">
                {{ $borrowings->links() }}
            </div>
        @else
            <div class="text-center py-5 text-muted">
                <i class="fas fa-exchange-alt fa-2x mb-3"></i>
                <div>No borrowing records found.</div>
            </div>
        @endif
    </div>
</div>
@endsection
