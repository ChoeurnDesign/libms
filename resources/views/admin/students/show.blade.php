@extends('layouts.admin')

@section('page-title', 'Student Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">
            <i class="fas fa-user text-primary me-2"></i>Student Profile
        </h4>
        <p class="text-muted mb-0">View details and borrowing history</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary rounded-pill">
            <i class="fas fa-arrow-left me-2"></i>Back to Students
        </a>
        <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-outline-primary rounded-pill">
            <i class="fas fa-edit me-2"></i>Edit Student
        </a>
    </div>
</div>

<!-- Profile Info -->
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-md-3 text-center">
                <div class="bg-primary bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; margin: auto;">
                    <i class="fas fa-user fa-3x text-primary"></i>
                </div>
                <div>
                    <span class="fw-bold text-dark">{{ $student->name }}</span>
                    @if($student->student_id)
                        <div class="text-muted">ID: {{ $student->student_id }}</div>
                    @endif
                </div>
            </div>
            <div class="col-md-9">
                <dl class="row mb-0">
                    <dt class="col-sm-3 text-dark">Email</dt>
                    <dd class="col-sm-9">{{ $student->email }}</dd>

                    <dt class="col-sm-3 text-dark">Phone</dt>
                    <dd class="col-sm-9">{{ $student->phone ?? '-' }}</dd>

                    <dt class="col-sm-3 text-dark">Address</dt>
                    <dd class="col-sm-9">{{ $student->address ?? '-' }}</dd>

                    <dt class="col-sm-3 text-dark">Joined</dt>
                    <dd class="col-sm-9">{{ $student->created_at->format('M d, Y') }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>

<!-- Borrowing History -->
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold text-dark">
            <i class="fas fa-book-open text-primary me-2"></i>Borrowing History
        </h6>
    </div>
    <div class="card-body p-0">
        @if($borrowings->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 p-3">Book</th>
                            <th class="border-0 p-3">Borrowed On</th>
                            <th class="border-0 p-3">Due Date</th>
                            <th class="border-0 p-3">Returned</th>
                            <th class="border-0 p-3">Status</th>
                            <th class="border-0 p-3">Fine</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($borrowings as $borrowing)
                        <tr>
                            <td class="p-3">
                                @if($borrowing->book)
                                    <strong>{{ $borrowing->book->title }}</strong>
                                    <div class="text-muted small">{{ $borrowing->book->author }}</div>
                                @else
                                    <span class="text-muted">Unknown Book</span>
                                @endif
                            </td>
                            <td class="p-3">
                                {{ $borrowing->borrowed_date ? \Carbon\Carbon::parse($borrowing->borrowed_date)->format('M d, Y') : '-' }}
                            </td>
                            <td class="p-3">
                                {{ $borrowing->due_date ? \Carbon\Carbon::parse($borrowing->due_date)->format('M d, Y') : '-' }}
                            </td>
                            <td class="p-3">
                                @if($borrowing->returned_date)
                                    {{ \Carbon\Carbon::parse($borrowing->returned_date)->format('M d, Y') }}
                                @else
                                    <span class="text-warning">Not returned</span>
                                @endif
                            </td>
                            <td class="p-3">
                                @if($borrowing->status === 'borrowed')
                                    <span class="badge bg-success">Borrowed</span>
                                @elseif($borrowing->status === 'returned')
                                    <span class="badge bg-secondary">Returned</span>
                                @else
                                    <span class="badge bg-light text-dark">{{ ucfirst($borrowing->status) }}</span>
                                @endif
                            </td>
                            <td class="p-3">
                                @if($borrowing->fine_amount > 0)
                                    <span class="badge bg-danger">${{ number_format($borrowing->fine_amount, 2) }}</span>
                                @else
                                    <span class="text-muted">$0.00</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3 border-top">
                {{ $borrowings->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                     style="width: 80px; height: 80px;">
                    <i class="fas fa-book-open fa-2x text-muted"></i>
                </div>
                <h5 class="text-muted">No Borrowings Found</h5>
                <p class="text-muted">This student has no borrowing records yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection
