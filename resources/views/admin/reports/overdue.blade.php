@extends('layouts.admin')

@section('page-title', 'Overdue Report')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">
            <i class="fas fa-exclamation-triangle text-danger me-2"></i>Overdue Report
        </h4>
        <p class="text-muted mb-0">List of overdue books and borrowers</p>
    </div>
    <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary rounded-pill">
        <i class="fas fa-arrow-left me-2"></i>Back to Reports
    </a>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-transparent border-0 p-4">
        <h6 class="mb-0 fw-bold text-dark">
            <i class="fas fa-list-ul text-danger me-2"></i>Overdue Books
        </h6>
    </div>
    <div class="card-body p-0">
        @if($overdueBooks->count())
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 p-3">Student</th>
                            <th class="border-0 p-3">Book</th>
                            <th class="border-0 p-3">Borrowed Date</th>
                            <th class="border-0 p-3">Due Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($overdueBooks as $borrowing)
                        <tr>
                            <td class="p-3">{{ $borrowing->user->name }}</td>
                            <td class="p-3">{{ $borrowing->book->title }}</td>
                            <td class="p-3">{{ $borrowing->borrowed_date->format('Y-m-d') }}</td>
                            <td class="p-3 text-danger fw-bold">{{ $borrowing->due_date->format('Y-m-d') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5 text-muted">
                <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                <div>No overdue books found.</div>
            </div>
        @endif
    </div>
</div>
@endsection
