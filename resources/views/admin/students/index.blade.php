@extends('layouts.admin')

@section('page-title', 'Students')

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">
            <i class="fas fa-users text-primary me-2"></i>Students Management
        </h4>
        <p class="text-muted mb-0">Manage library users and their borrowing activities</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary rounded-pill">
            <i class="fas fa-arrow-left me-2"></i>Dashboard
        </a>
        <a href="{{ route('admin.students.create') }}" class="btn btn-primary rounded-pill">
            <i class="fas fa-plus me-2"></i>Add Student
        </a>
    </div>
</div>

<!-- Quick Stats -->
<div class="row g-3 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-3 bg-primary bg-opacity-10">
            <div class="card-body text-center p-4">
                <div class="display-6 fw-bold text-primary">{{ $stats['total'] }}</div>
                <small class="text-dark">Total Students</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-3 bg-success bg-opacity-10">
            <div class="card-body text-center p-4">
                <div class="display-6 fw-bold text-success">{{ $stats['active_borrowers'] }}</div>
                <small class="text-dark">Active Borrowers</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-3 bg-danger bg-opacity-10">
            <div class="card-body text-center p-4">
                <div class="display-6 fw-bold text-danger">{{ $stats['overdue_borrowers'] }}</div>
                <small class="text-dark">Overdue Borrowers</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-3 bg-info bg-opacity-10">
            <div class="card-body text-center p-4">
                <div class="display-6 fw-bold text-info">{{ $stats['new_this_month'] }}</div>
                <small class="text-dark">New This Month</small>
            </div>
        </div>
    </div>
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

<!-- Filters & Search -->
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body p-4">
        <form method="GET" class="row g-3">
            <div class="col-md-6">
                <label class="form-label text-dark">Search Students</label>
                <input type="text" name="search" class="form-control"
                       placeholder="Search by name, email, phone, or student ID..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label text-dark">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Students</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active Borrowers</option>
                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-info d-block">
                    <i class="fas fa-search me-1"></i>Search
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Students Table -->
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        @if($students->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 fw-bold p-4 text-dark">Student</th>
                            <th class="border-0 fw-bold p-4 text-dark">Contact</th>
                            <th class="border-0 fw-bold p-4 text-dark">Borrowing Status</th>
                            <th class="border-0 fw-bold p-4 text-dark">Joined</th>
                            <th class="border-0 fw-bold p-4 text-dark">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr>
                            <td class="p-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center me-3"
                                         style="width: 40px; height: 40px;">
                                        <i class="fas fa-user text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $student->name }}</div>
                                        @if($student->student_id)
                                            <small class="text-muted">ID: {{ $student->student_id }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <div>
                                    <div class="text-dark">{{ $student->email }}</div>
                                    @if($student->phone)
                                        <small class="text-muted">{{ $student->phone }}</small>
                                    @endif
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="d-flex align-items-center gap-2">
                                    @if($student->active_borrowings_count > 0)
                                        <span class="badge bg-warning rounded-pill">
                                            {{ $student->active_borrowings_count }} borrowed
                                        </span>
                                    @else
                                        <span class="badge bg-light text-dark rounded-pill">No active loans</span>
                                    @endif

                                    @if($student->overdue_borrowings_count > 0)
                                        <span class="badge bg-danger rounded-pill">
                                            {{ $student->overdue_borrowings_count }} overdue
                                        </span>
                                    @endif
                                </div>
                                <small class="text-muted">{{ $student->borrowings_count }} total loans</small>
                            </td>
                            <td class="p-4">
                                <span class="text-dark">{{ $student->created_at->format('M d, Y') }}</span>
                            </td>
                            <td class="p-4">
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.students.show', $student) }}"
                                       class="btn btn-sm btn-info rounded-pill">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.students.edit', $student) }}"
                                       class="btn btn-sm btn-outline-primary rounded-pill">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($student->active_borrowings_count == 0)
                                        <button onclick="deleteStudent('{{ $student->name }}', '{{ route('admin.students.destroy', $student) }}')"
                                                class="btn btn-sm btn-outline-danger rounded-pill">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-outline-secondary rounded-pill" disabled title="Has active borrowings">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-top">
                {{ $students->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                     style="width: 80px; height: 80px;">
                    <i class="fas fa-users fa-2x text-muted"></i>
                </div>
                <h5 class="text-muted">No Students Found</h5>
                <p class="text-muted">Start by adding your first student to the library system.</p>
                <a href="{{ route('admin.students.create') }}" class="btn btn-primary rounded-pill">
                    <i class="fas fa-plus me-2"></i>Add First Student
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-3">
            <div class="modal-header">
                <h5 class="modal-title text-dark">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-dark">Delete student <strong id="studentName"></strong>?</p>
                <div class="alert alert-warning rounded-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    This action cannot be undone. All borrowing history will be preserved.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Student</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteStudent(name, url) {
    document.getElementById('studentName').textContent = name;
    document.getElementById('deleteForm').action = url;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endsection
