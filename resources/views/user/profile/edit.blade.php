@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
@include('layouts.navbar')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <a href="{{ route('user.profile.show') }}" class="btn btn-outline-secondary mb-3">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                    <h3 class="card-title">
                        <i class="fas fa-user-edit"></i>
                        Edit Profile Information
                    </h3>
                </div>
                {{-- IMPORTANT: Add enctype="multipart/form-data" for file uploads --}}
                <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if($errors->has('update'))
                            <div class="alert alert-danger">
                                {{ $errors->first('update') }}
                            </div>
                        @endif

                        {{-- Profile Picture Upload Section --}}
                        <div class="form-group text-center mb-4">
                            {{-- Centered the label by moving it inside the .position-relative div and applying text-center to parent --}}
                            <div class="position-relative d-inline-block">
                                <label for="profile_picture" class="d-block mb-2 text-center">Profile Picture</label>
                                <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/default_avatar.png') }}"
                                     alt="Profile Picture" class="img-thumbnail rounded-circle" id="profilePicturePreview"
                                     style="width: 150px; height: 150px; object-fit: cover;">
                                <label for="profile_picture" class="btn btn-primary btn-sm rounded-circle position-absolute bottom-0 end-0"
                                       style="width: 40px; height: 40px; line-height: 25px; cursor: pointer;">
                                    <i class="fas fa-camera"></i>
                                </label>
                                <input type="file" class="form-control-file d-none @error('profile_picture') is-invalid @enderror"
                                       id="profile_picture" name="profile_picture" accept="image/*" onchange="previewProfilePicture(event)">
                            </div>
                            @error('profile_picture')
                                <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                            @enderror
                            {{-- Removed the text "Max 2MB, JPG, PNG, GIF" --}}
                        </div>
                        {{-- End Profile Picture Upload Section --}}

                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        @if(Schema::hasColumn('users', 'student_id'))
                        <div class="form-group">
                            <label for="student_id">Student ID</label>
                            <input type="text" class="form-control @error('student_id') is-invalid @enderror"
                                   id="student_id" name="student_id" value="{{ old('student_id', $user->student_id ?? '') }}">
                            @error('student_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        @endif

                        @if(Schema::hasColumn('users', 'phone'))
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                   id="phone" name="phone" value="{{ old('phone', $user->phone ?? '') }}">
                            @error('phone')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        @endif

                        @if(Schema::hasColumn('users', 'address'))
                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                       id="address" name="address" rows="3">{{ old('address', $user->address ?? '') }}</textarea>
                            @error('address')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                        <a href="{{ route('user.profile.show') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-lock"></i>
                        Change Password
                    </h3>
                </div>
                <form action="{{ route('user.profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        @if(session('password_success'))
                            <div class="alert alert-success">
                                {{ session('password_success') }}
                            </div>
                        @endif
                        @if($errors->has('password'))
                            <div class="alert alert-danger">
                                {{ $errors->first('password') }}
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                   id="current_password" name="current_password" required>
                            @error('current_password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">New Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password" required>
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirm New Password</label>
                            <input type="password" class="form-control"
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning">Change Password</button>
                        <a href="{{ route('user.profile.show') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function previewProfilePicture(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('profilePicturePreview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection
