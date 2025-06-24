@extends('layouts.admin')

@section('page-title', 'Settings')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">
            <i class="fas fa-cog text-primary me-2"></i>Library Settings
        </h4>
        <p class="text-muted mb-0">Configure your library management system</p>
    </div>
    <div>
        <button type="button" class="btn btn-outline-warning rounded-pill me-2" onclick="resetSettings()">
            <i class="fas fa-undo me-2"></i>Reset to Default
        </button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary rounded-pill">
            <i class="fas fa-arrow-left me-2"></i>Dashboard
        </a>
    </div>
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

<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <!-- Settings Tabs -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-transparent border-0 p-0">
            <ul class="nav nav-tabs nav-fill" id="settingsTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active border-0 rounded-0 py-3" id="library-tab" data-bs-toggle="tab"
                        data-bs-target="#library" type="button" role="tab">
                        <i class="fas fa-building me-2"></i>Library Info
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link border-0 rounded-0 py-3" id="borrowing-tab" data-bs-toggle="tab"
                        data-bs-target="#borrowing" type="button" role="tab">
                        <i class="fas fa-book me-2"></i>Borrowing Rules
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link border-0 rounded-0 py-3" id="notifications-tab" data-bs-toggle="tab"
                        data-bs-target="#notifications" type="button" role="tab">
                        <i class="fas fa-bell me-2"></i>Notifications
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link border-0 rounded-0 py-3" id="system-tab" data-bs-toggle="tab"
                        data-bs-target="#system" type="button" role="tab">
                        <i class="fas fa-wrench me-2"></i>System
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body p-4">
            <div class="tab-content" id="settingsTabContent">
                <!-- Library Information Tab -->
                <div class="tab-pane fade show active" id="library" role="tabpanel">
                    <h6 class="fw-bold text-dark mb-3">Library Information</h6>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="library_name" class="form-label fw-semibold">Library Name *</label>
                            <input type="text" class="form-control rounded-3" id="library_name" name="library_name"
                                value="{{ old('library_name', $settings['library_name']) }}" required>
                            @error('library_name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="library_email" class="form-label fw-semibold">Contact Email *</label>
                            <input type="email" class="form-control rounded-3" id="library_email" name="library_email"
                                value="{{ old('library_email', $settings['library_email']) }}" required>
                            @error('library_email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="library_phone" class="form-label fw-semibold">Phone Number</label>
                            <input type="text" class="form-control rounded-3" id="library_phone" name="library_phone"
                                value="{{ old('library_phone', $settings['library_phone']) }}">
                        </div>

                        <div class="col-md-6">
                            <label for="library_logo" class="form-label fw-semibold">Library Logo</label>
                            <input type="file" class="form-control rounded-3" id="library_logo" name="library_logo"
                                accept="image/*">
                            @if($settings['library_logo'])
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $settings['library_logo']) }}" alt="Current Logo"
                                    class="img-thumbnail" style="max-height: 60px;">
                            </div>
                            @endif
                        </div>

                        <div class="col-12">
                            <label for="library_address" class="form-label fw-semibold">Address</label>
                            <textarea class="form-control rounded-3" id="library_address" name="library_address"
                                rows="3">{{ old('library_address', $settings['library_address']) }}</textarea>
                        </div>

                        <div class="col-12">
                            <label for="library_description" class="form-label fw-semibold">Description</label>
                            <textarea class="form-control rounded-3" id="library_description" name="library_description"
                                rows="3">{{ old('library_description', $settings['library_description']) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Borrowing Rules Tab -->
                <div class="tab-pane fade" id="borrowing" role="tabpanel">
                    <h6 class="fw-bold text-dark mb-3">Borrowing Rules & Policies</h6>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="borrowing_period" class="form-label fw-semibold">Default Borrowing Period (Days)
                                *</label>
                            <input type="number" class="form-control rounded-3" id="borrowing_period"
                                name="borrowing_period"
                                value="{{ old('borrowing_period', $settings['borrowing_period']) }}" min="1" max="365"
                                required>
                            <div class="form-text">How many days can a book be borrowed?</div>
                        </div>

                        <div class="col-md-6">
                            <label for="max_books_per_user" class="form-label fw-semibold">Max Books Per User *</label>
                            <input type="number" class="form-control rounded-3" id="max_books_per_user"
                                name="max_books_per_user"
                                value="{{ old('max_books_per_user', $settings['max_books_per_user']) }}" min="1"
                                max="50" required>
                            <div class="form-text">Maximum books a user can borrow at once</div>
                        </div>

                        <div class="col-md-6">
                            <label for="renewal_period" class="form-label fw-semibold">Renewal Period (Days) *</label>
                            <input type="number" class="form-control rounded-3" id="renewal_period"
                                name="renewal_period" value="{{ old('renewal_period', $settings['renewal_period']) }}"
                                min="1" max="30" required>
                            <div class="form-text">How many days to extend when renewed?</div>
                        </div>

                        <div class="col-md-6">
                            <label for="max_renewals" class="form-label fw-semibold">Max Renewals *</label>
                            <input type="number" class="form-control rounded-3" id="max_renewals" name="max_renewals"
                                value="{{ old('max_renewals', $settings['max_renewals']) }}" min="0" max="10" required>
                            <div class="form-text">Maximum times a book can be renewed</div>
                        </div>

                        <div class="col-md-6">
                            <label for="fine_per_day" class="form-label fw-semibold">Fine Per Day ($) *</label>
                            <input type="number" class="form-control rounded-3" id="fine_per_day" name="fine_per_day"
                                value="{{ old('fine_per_day', $settings['fine_per_day']) }}" step="0.01" min="0"
                                max="1000" required>
                            <div class="form-text">Daily fine for overdue books</div>
                        </div>
                    </div>
                </div>

                <!-- Notifications Tab -->
                <div class="tab-pane fade" id="notifications" role="tabpanel">
                    <h6 class="fw-bold text-dark mb-3">Notification Settings</h6>

                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="email_notifications"
                                    name="email_notifications" style="cursor: pointer;"
                                    {{ $settings['email_notifications'] ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="email_notifications"
                                    style="cursor: pointer;">
                                    Enable Email Notifications
                                </label>
                                <div class="form-text">Send email notifications to users</div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="overdue_notifications"
                                    name="overdue_notifications" style="cursor: pointer;"
                                    {{ $settings['overdue_notifications'] ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="overdue_notifications"
                                    style="cursor: pointer;">
                                    Overdue Book Notifications
                                </label>
                                <div class="form-text">Notify users about overdue books</div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="return_confirmations"
                                    name="return_confirmations" style="cursor: pointer;"
                                    {{ $settings['return_confirmations'] ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="return_confirmations"
                                    style="cursor: pointer;">
                                    Return Confirmation Emails
                                </label>
                                <div class="form-text">Send email when books are returned</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Tab -->
                <div class="tab-pane fade" id="system" role="tabpanel">
                    <h6 class="fw-bold text-dark mb-3">System Preferences</h6>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="date_format" class="form-label fw-semibold">Date Format</label>
                            <select class="form-select rounded-3" id="date_format" name="date_format">
                                <option value="Y-m-d" {{ $settings['date_format'] == 'Y-m-d' ? 'selected' : '' }}>
                                    2024-12-25</option>
                                <option value="d/m/Y" {{ $settings['date_format'] == 'd/m/Y' ? 'selected' : '' }}>
                                    25/12/2024</option>
                                <option value="m/d/Y" {{ $settings['date_format'] == 'm/d/Y' ? 'selected' : '' }}>
                                    12/25/2024</option>
                                <option value="d-M-Y" {{ $settings['date_format'] == 'd-M-Y' ? 'selected' : '' }}>
                                    25-Dec-2024</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="timezone" class="form-label fw-semibold">Timezone</label>
                            <select class="form-select rounded-3" id="timezone" name="timezone">
                                <option value="UTC" {{ $settings['timezone'] == 'UTC' ? 'selected' : '' }}>UTC</option>
                                <option value="America/New_York"
                                    {{ $settings['timezone'] == 'America/New_York' ? 'selected' : '' }}>Eastern Time
                                </option>
                                <option value="America/Chicago"
                                    {{ $settings['timezone'] == 'America/Chicago' ? 'selected' : '' }}>Central Time
                                </option>
                                <option value="America/Denver"
                                    {{ $settings['timezone'] == 'America/Denver' ? 'selected' : '' }}>Mountain Time
                                </option>
                                <option value="America/Los_Angeles"
                                    {{ $settings['timezone'] == 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time
                                </option>
                                <option value="Asia/Phnom_Penh"
                                    {{ $settings['timezone'] == 'Asia/Phnom_Penh' ? 'selected' : '' }}>Cambodia Time
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer bg-transparent border-0 p-4">
            <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-outline-secondary rounded-pill" onclick="history.back()">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="submit" class="btn btn-primary rounded-pill">
                    <i class="fas fa-save me-2"></i>Save Settings
                </button>
            </div>
        </div>
    </div>
</form>

<!-- Reset Confirmation Modal -->
<div class="modal fade" id="resetModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-3">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Reset Settings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to reset all settings to default values? This action cannot be undone.</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.settings.reset') }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-warning rounded-pill">Reset Settings</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function resetSettings() {
        const modal = new bootstrap.Modal(document.getElementById('resetModal'));
        modal.show();
    }
</script>
@endsection
