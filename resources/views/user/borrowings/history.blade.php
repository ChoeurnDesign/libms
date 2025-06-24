@extends('layouts.app')

@section('title', 'Borrowing History')

@section('content')
@include('layouts.navbar')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history text-info"></i>
                        Borrowing History
                    </h3>
                    <div class="card-tools">
                        {{-- FIX: Changed route to user.borrowings.index for consistency --}}
                        <a href="{{ route('user.borrowings.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Active
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Stats Row -->
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <i class="fas fa-book text-info me-3" style="font-size: 2rem;"></i>
                                <div>
                                    <h4 class="mb-0">{{ $historyStats['total_borrowed'] ?? 0 }}</h4>
                                    <small class="text-muted">Total Borrowed</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <i class="fas fa-check text-success me-3" style="font-size: 2rem;"></i>
                                <div>
                                    <h4 class="mb-0">{{ $historyStats['total_returned'] ?? 0 }}</h4>
                                    <small class="text-muted">Books Completed</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <i class="fas fa-star text-warning me-3" style="font-size: 2rem;"></i>
                                <div>
                                    <h4 class="mb-0">{{ round((($historyStats['total_returned'] ?? 0) / max(($historyStats['total_borrowed'] ?? 1), 1)) * 100) }}%</h4>
                                    <small class="text-muted">Reading Score</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(isset($borrowingHistory) && $borrowingHistory->count() > 0)
                        <!-- History Table -->
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Book</th>
                                        <th>Author</th>
                                        <th>Borrowed Date</th>
                                        <th>Due Date</th>
                                        <th>Returned Date</th>
                                        <th>Status</th>
                                        <th>Reading Days</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($borrowingHistory as $borrowing)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($borrowing->book_cover)
                                                    <img src="{{ asset($borrowing->book_cover) }}"
                                                         class="me-2"
                                                         style="width: 40px; height: 50px; object-fit: cover;"
                                                         alt="{{ $borrowing->book_title }}">
                                                @else
                                                    <div class="bg-light me-2 d-flex align-items-center justify-content-center"
                                                         style="width: 40px; height: 50px;">
                                                        <i class="fas fa-book text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $borrowing->book_title ?? $borrowing->title ?? 'Unknown Title' }}</strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $borrowing->book_author ?? $borrowing->author ?? 'Unknown Author' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($borrowing->borrowed_date)->format('M d, Y') ?? 'N/A' }}</td>
                                        <td>
                                            @if(isset($borrowing->due_date))
                                                {{ \Carbon\Carbon::parse($borrowing->due_date)->format('M d, Y') }}
                                                @if(\Carbon\Carbon::parse($borrowing->due_date)->isPast())
                                                    <span class="badge bg-warning text-dark">Overdue</span>
                                                @elseif(\Carbon\Carbon::parse($borrowing->due_date)->diffInDays(now()) <= 3)
                                                    <span class="badge bg-info text-dark">Due Soon</span>
                                                @endif
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            {{ $borrowing->returned_date ? \Carbon\Carbon::parse($borrowing->returned_date)->format('M d, Y') : ($borrowing->status === 'returned' ? 'Returned' : 'Still Reading') }}
                                        </td>
                                        <td>
                                            @if(isset($borrowing->borrowed_date) && isset($borrowing->returned_date))
                                                @php
                                                    $days = \Carbon\Carbon::parse($borrowing->borrowed_date)->diffInDays(\Carbon\Carbon::parse($borrowing->returned_date));
                                                @endphp
                                                <span class="text-info">{{ $days }} days</span>
                                            @elseif($borrowing->status === 'borrowed' && isset($borrowing->borrowed_date))
                                                @php
                                                    $days = \Carbon\Carbon::parse($borrowing->borrowed_date)->diffInDays(now());
                                                @endphp
                                                <span class="text-primary">{{ $days }} days (reading)</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Reading Achievement Section -->
                        <div class="mt-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-trophy text-warning"></i>
                                        Reading Achievements
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <ul class="mb-0">
                                                <li>📚 Books Read: {{ $historyStats['total_returned'] ?? 0 }}</li>
                                                <li>⭐ Reading Progress: {{ round((($historyStats['total_returned'] ?? 0) / max(($historyStats['total_borrowed'] ?? 1), 1)) * 100) }}%</li>
                                                <li>🎯 Favorite Activity: Reading</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <ul class="mb-0">
                                                <li>📖 Currently Reading: {{ ($historyStats['total_borrowed'] ?? 0) - ($historyStats['total_returned'] ?? 0) }}</li>
                                                <li>🏆 Reading Level:
                                                    @if(($historyStats['total_returned'] ?? 0) >= 20)
                                                        Master Reader
                                                    @elseif(($historyStats['total_returned'] ?? 0) >= 10)
                                                        Advanced Reader
                                                    @elseif(($historyStats['total_returned'] ?? 0) >= 5)
                                                        Regular Reader
                                                    @else
                                                        New Reader
                                                    @endif
                                                </li>
                                                <li>💫 Keep Reading!</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-5">
                            <i class="fas fa-history fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">No Borrowing History</h4>
                            <p class="text-muted">
                                Your reading journey will appear here once you start borrowing books.<br>
                                Build your reading history and discover amazing stories!
                            </p>
                            <div class="mt-4">
                                <a href="{{ route('user.books.index') }}" class="btn btn-primary btn-lg me-2">
                                    <i class="fas fa-search"></i>
                                    Start Reading
                                </a>
                                <a href="{{ route('user.categories.index') }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-list"></i>
                                    Browse Categories
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
