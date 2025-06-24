@extends('layouts.admin')

@section('page-title', 'Reports')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">
            <i class="fas fa-chart-bar text-success me-2"></i>Reports Dashboard
        </h4>
        <p class="text-muted mb-0">Library analytics and insights</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary rounded-pill">
        <i class="fas fa-arrow-left me-2"></i>Dashboard
    </a>
</div>

<!-- Quick Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-3 bg-primary bg-opacity-10">
            <div class="card-body text-center p-4">
                <div class="display-6 fw-bold text-primary">{{ $stats['total_books'] }}</div>
                <small class="text-dark">Total Books</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-3 bg-info bg-opacity-10">
            <div class="card-body text-center p-4">
                <div class="display-6 fw-bold text-info">{{ $stats['total_students'] }}</div>
                <small class="text-dark">Students</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-3 bg-warning bg-opacity-10">
            <div class="card-body text-center p-4">
                <div class="display-6 fw-bold text-warning">{{ $stats['active_borrowings'] }}</div>
                <small class="text-dark">Active Loans</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-3 bg-danger bg-opacity-10">
            <div class="card-body text-center p-4">
                <div class="display-6 fw-bold text-danger">{{ $stats['overdue_books'] }}</div>
                <small class="text-dark">Overdue</small>
            </div>
        </div>
    </div>
</div>

<!-- Report Categories -->
<div class="row g-4">
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body p-4 text-center">
                <div class="bg-primary bg-opacity-20 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                     style="width: 60px; height: 60px;">
                    <i class="fas fa-book fa-2x text-white"></i>
                </div>
                <h6 class="fw-bold text-dark mb-2">Books Report</h6>
                <p class="text-muted small mb-3">View all books and inventory</p>
                <a href="{{ route('admin.reports.books') }}" class="btn btn-primary rounded-pill">
                    <i class="fas fa-chart-line me-2"></i>View Report
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body p-4 text-center">
                <div class="bg-info bg-opacity-20 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                     style="width: 60px; height: 60px;">
                    <i class="fas fa-users fa-2x text-success"></i>
                </div>
                <h6 class="fw-bold text-dark mb-2">Students Report</h6>
                <p class="text-muted small mb-3">View all registered students</p>
                <a href="{{ route('admin.reports.students') }}" class="btn btn-info rounded-pill">
                    <i class="fas fa-chart-pie me-2"></i>View Report
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body p-4 text-center">
                <div class="bg-success bg-opacity-20 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                     style="width: 60px; height: 60px;">
                    <i class="fas fa-exchange-alt fa-2x text-info"></i>
                </div>
                <h6 class="fw-bold text-dark mb-2">Borrowings Report</h6>
                <p class="text-muted small mb-3">View borrowing transactions</p>
                <a href="{{ route('admin.reports.borrowings') }}" class="btn btn-success rounded-pill">
                    <i class="fas fa-chart-bar me-2"></i>View Report
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body p-4 text-center">
                <div class="bg-danger bg-opacity-20 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                     style="width: 60px; height: 60px;">
                    <i class="fas fa-exclamation-triangle fa-2x text-gray"></i>
                </div>
                <h6 class="fw-bold text-dark mb-2">Overdue Report</h6>
                <p class="text-muted small mb-3">Track overdue books</p>
                <a href="{{ route('admin.reports.overdue') }}" class="btn btn-danger rounded-pill">
                    <i class="fas fa-clock me-2"></i>View Report
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Export Section -->
<div class="card border-0 shadow-sm rounded-3 mt-4">
    <div class="card-header bg-transparent border-0 p-4">
        <h6 class="mb-0 fw-bold text-dark">
            <i class="fas fa-download text-success me-2"></i>Export Data
        </h6>
    </div>
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-4">
                <a href="{{ route('admin.reports.export', 'books') }}" class="btn btn-outline-primary d-block">
                    <i class="fas fa-file-csv me-2"></i>Export Books
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('admin.reports.export', 'students') }}" class="btn btn-outline-info d-block">
                    <i class="fas fa-file-csv me-2"></i>Export Students
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('admin.reports.export', 'borrowings') }}" class="btn btn-outline-success d-block">
                    <i class="fas fa-file-csv me-2"></i>Export Borrowings
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
