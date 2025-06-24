@extends('layouts.admin')

@section('page-title', 'Students Report')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">
            <i class="fas fa-users text-info me-2"></i>Students Report
        </h4>
        <p class="text-muted mb-0">List of all registered students</p>
    </div>
    <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary rounded-pill">
        <i class="fas fa-arrow-left me-2"></i>Back to Reports
    </a>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-transparent border-0 p-4">
        <h6 class="mb-0 fw-bold text-dark">
            <i class="fas fa-list-ul text-info me-2"></i>Students
        </h6>
    </div>
    <div class="card-body p-0">
        @if($students->count())
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 p-3">Name</th>
                            <th class="border-0 p-3">Email</th>
                            <th class="border-0 p-3">Phone</th>
                            <th class="border-0 p-3">Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr>
                            <td class="p-3">{{ $student->name }}</td>
                            <td class="p-3">{{ $student->email }}</td>
                            <td class="p-3">{{ $student->phone }}</td>
                            <td class="p-3">{{ $student->created_at->format('Y-m-d') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-top">
                {{ $students->links() }}
            </div>
        @else
            <div class="text-center py-5 text-muted">
                <i class="fas fa-users fa-2x mb-3"></i>
                <div>No students found.</div>
            </div>
        @endif
    </div>
</div>
@endsection
