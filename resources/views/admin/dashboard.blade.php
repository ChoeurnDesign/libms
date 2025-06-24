@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <!-- Total Books Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden text-white position-relative"
             style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); transition: transform 0.3s ease;"
             onmouseover="this.style.transform='translateY(-5px)'"
             onmouseout="this.style.transform='translateY(0)'">
            <div class="card-body p-4 position-relative" style="z-index: 2;">
                <i class="fas fa-book position-absolute end-0 top-0 mt-3 me-3 opacity-25" style="font-size: 3rem; z-index: 1;"></i>
                <div class="text-uppercase fw-bold opacity-75" style="font-size: 0.9rem; letter-spacing: 0.5px;">Total Books</div>
                <div class="display-4 fw-bold my-2" style="text-shadow: 0 2px 4px rgba(0,0,0,0.2);">{{ $stats['total_books'] ?? 156 }}</div>
                <div class="d-flex align-items-center justify-content-between mt-3" style="font-size: 0.8rem;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-arrow-up me-1"></i>
                        <span>More info</span>
                    </div>
                    <i class="fas fa-arrow-circle-right"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Students Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden text-white position-relative"
             style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); transition: transform 0.3s ease;"
             onmouseover="this.style.transform='translateY(-5px)'"
             onmouseout="this.style.transform='translateY(0)'">
            <div class="card-body p-4 position-relative" style="z-index: 2;">
                <i class="fas fa-users position-absolute end-0 top-0 mt-3 me-3 opacity-25" style="font-size: 3rem; z-index: 1;"></i>
                <div class="text-uppercase fw-bold opacity-75" style="font-size: 0.9rem; letter-spacing: 0.5px;">Total Students</div>
                <div class="display-4 fw-bold my-2" style="text-shadow: 0 2px 4px rgba(0,0,0,0.2);">{{ $stats['total_users'] ?? 24 }}</div>
                <div class="d-flex align-items-center justify-content-between mt-3" style="font-size: 0.8rem;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-arrow-up me-1"></i>
                        <span>More info</span>
                    </div>
                    <i class="fas fa-arrow-circle-right"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Borrowed Today Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden text-dark position-relative"
             style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); transition: transform 0.3s ease;"
             onmouseover="this.style.transform='translateY(-5px)'"
             onmouseout="this.style.transform='translateY(0)'">
            <div class="card-body p-4 position-relative" style="z-index: 2;">
                <i class="fas fa-exchange-alt position-absolute end-0 top-0 mt-3 me-3 opacity-25" style="font-size: 3rem; z-index: 1;"></i>
                <div class="text-uppercase fw-bold opacity-75" style="font-size: 0.9rem; letter-spacing: 0.5px;">Borrowed Today</div>
                <div class="display-4 fw-bold my-2">{{ $stats['borrowed_today'] ?? 8 }}</div>
                <div class="d-flex align-items-center justify-content-between mt-3" style="font-size: 0.8rem;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-arrow-up me-1"></i>
                        <span>More info</span>
                    </div>
                    <i class="fas fa-arrow-circle-right"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Returned Today Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden text-white position-relative"
             style="background: linear-gradient(135deg, #ff8a80 0%, #ff5722 100%); transition: transform 0.3s ease;"
             onmouseover="this.style.transform='translateY(-5px)'"
             onmouseout="this.style.transform='translateY(0)'">
            <div class="card-body p-4 position-relative" style="z-index: 2;">
                <i class="fas fa-undo position-absolute end-0 top-0 mt-3 me-3 opacity-25" style="font-size: 3rem; z-index: 1;"></i>
                <div class="text-uppercase fw-bold opacity-75" style="font-size: 0.9rem; letter-spacing: 0.5px;">Returned Today</div>
                <div class="display-4 fw-bold my-2" style="text-shadow: 0 2px 4px rgba(0,0,0,0.2);">{{ $stats['returned_today'] ?? 3 }}</div>
                <div class="d-flex align-items-center justify-content-between mt-3" style="font-size: 0.8rem;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-arrow-up me-1"></i>
                        <span>More info</span>
                    </div>
                    <i class="fas fa-arrow-circle-right"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-3 h-100"
             style="transition: all 0.3s ease;"
             onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(0,0,0,0.15)'"
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 0.125rem 0.25rem rgba(0,0,0,0.075)'">
            <div class="card-body p-4 text-center">
                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white mx-auto mb-3"
                     style="width: 70px; height: 70px; font-size: 1.8rem;">
                    <i class="fas fa-plus"></i>
                </div>
                <h5 class="card-title fw-bold">Add Book</h5>
                <p class="card-text text-muted">Add new books to library</p>
                <a href="{{ route('admin.books.create') }}" class="btn btn-primary rounded-pill px-4">Go to Add</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-3 h-100"
             style="transition: all 0.3s ease;"
             onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(0,0,0,0.15)'"
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 0.125rem 0.25rem rgba(0,0,0,0.075)'">
            <div class="card-body p-4 text-center">
                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center text-white mx-auto mb-3"
                     style="width: 70px; height: 70px; font-size: 1.8rem;">
                    <i class="fas fa-list"></i>
                </div>
                <h5 class="card-title fw-bold">Manage Books</h5>
                <p class="card-text text-muted">View and edit books</p>
                <a href="{{ route('admin.books.index') }}" class="btn btn-success rounded-pill px-4">Manage</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-3 h-100"
             style="transition: all 0.3s ease;"
             onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(0,0,0,0.15)'"
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 0.125rem 0.25rem rgba(0,0,0,0.075)'">
            <div class="card-body p-4 text-center">
                <div class="bg-info rounded-circle d-flex align-items-center justify-content-center text-white mx-auto mb-3"
                     style="width: 70px; height: 70px; font-size: 1.8rem;">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <h5 class="card-title fw-bold">Transactions</h5>
                <p class="card-text text-muted">View borrowing history</p>
                <a href="{{ route('admin.borrowings.index') }}" class="btn btn-info rounded-pill px-4">View All</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-3 h-100"
             style="transition: all 0.3s ease;"
             onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(0,0,0,0.15)'"
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 0.125rem 0.25rem rgba(0,0,0,0.075)'">
            <div class="card-body p-4 text-center">
                <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center text-white mx-auto mb-3"
                     style="width: 70px; height: 70px; font-size: 1.8rem;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h5 class="card-title fw-bold">Overdue</h5>
                <p class="card-text text-muted">Check overdue books</p>
                <a href="{{ route('admin.borrowings.overdue') }}" class="btn btn-warning rounded-pill px-4">Check Now</a>
            </div>
        </div>
    </div>
</div>

<!-- Chart and Activity Section -->
<div class="row g-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center p-4">
                <h6 class="mb-0 fw-bold">Monthly Transaction Report</h6>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle rounded-pill" type="button" data-bs-toggle="dropdown">
                        Select Year: 2025
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">2025</a></li>
                        <li><a class="dropdown-item" href="#">2024</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="bg-light rounded-3 d-flex align-items-center justify-content-center" style="height: 300px;">
                    <div class="text-center">
                        <i class="fas fa-chart-bar display-1 text-muted mb-3"></i>
                        <h5 class="text-muted">Transaction Chart</h5>
                        <p class="text-muted mb-0">Chart will be displayed here</p>
                    </div>
                </div>
                <div class="mt-4 d-flex justify-content-center gap-4">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-success me-2">■</span>
                        <small class="text-muted">Borrowed</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-primary me-2">■</span>
                        <small class="text-muted">Returned</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-transparent border-0 p-4">
                <h6 class="mb-0 fw-bold">Recent Activity</h6>
            </div>
            <div class="card-body p-4">
                @if(isset($recent_users) && $recent_users->count() > 0)
                    @foreach($recent_users->take(5) as $user)
                    <div class="d-flex align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="me-3">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white"
                                 style="width: 40px; height: 40px;">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold text-dark">{{ $user->name }}</div>
                            <small class="text-muted">{{ ucfirst($user->role) }} • {{ $user->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-users display-4 text-muted mb-3"></i>
                        <p class="text-muted mb-0">No recent activity</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
