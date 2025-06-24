@extends('layouts.admin')

@section('page-title', 'Edit Student')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">
            <i class="fas fa-user-edit text-primary me-2"></i>Edit Student
        </h4>
        <p class="text-muted mb-0">Update student information</p>
    </div>
    <a href="{{ route('admin.students.show', $student) }}" class="btn btn-outline-secondary rounded-pill">
        <i class="fas fa-arrow-left me-2"></i>Back to Profile
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

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-transparent border-0 p-4">
                <h6 class="mb-0 fw-bold text-dark">
                    <i class="fas fa-info-circle text-primary me-2"></i>Student Details
                </h6>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.students.update', $student) }}">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark">
                            <i class="fas fa-user me-2 text-info"></i>Name *
                        </label>
                        <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror"
                               value="{{ old('name', $student->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark">
                            <i class="fas fa-envelope me-2 text-warning"></i>Email *
                        </label>
                        <input type="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                               value="{{ old('email', $student->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark">
                            <i class="fas fa-phone me-2 text-success"></i>Phone
                        </label>
                        <input type="text" name="phone" class="form-control"
                               value="{{ old('phone', $student->phone) }}">
                    </div>

                    <!-- Student ID -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark">
                            <i class="fas fa-id-card me-2 text-secondary"></i>Student ID
                        </label>
                        <input type="text" name="student_id" class="form-control @error('student_id') is-invalid @enderror"
                               value="{{ old('student_id', $student->student_id) }}">
                        @error('student_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark">
                            <i class="fas fa-map-marker-alt me-2 text-primary"></i>Address
                        </label>
                        <input type="text" name="address" class="form-control"
                               value="{{ old('address', $student->address) }}">
                    </div>

                    <!-- Password (Optional) -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark">
                            <i class="fas fa-lock me-2 text-danger"></i>New Password
                        </label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                               autocomplete="new-password" placeholder="Leave blank to keep current password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Leave blank if you don't want to change the password</small>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark">
                            <i class="fas fa-lock me-2 text-danger"></i>Confirm Password
                        </label>
                        <input type="password" name="password_confirmation" class="form-control"
                               autocomplete="new-password" placeholder="Repeat new password">
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end gap-3 mt-4 pt-3 border-top">
                        <a href="{{ route('admin.students.show', $student) }}" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            <i class="fas fa-save me-2"></i>Update Student
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
