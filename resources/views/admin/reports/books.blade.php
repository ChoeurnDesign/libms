@extends('layouts.admin')

@section('page-title', 'Books Report')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">
            <i class="fas fa-book text-primary me-2"></i>Books Report
        </h4>
        <p class="text-muted mb-0">Overview of all books in the library</p>
    </div>
    <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary rounded-pill">
        <i class="fas fa-arrow-left me-2"></i>Back to Reports
    </a>
</div>

<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-header bg-transparent border-0 p-4">
        <h6 class="mb-0 fw-bold text-dark">
            <i class="fas fa-list-ul text-primary me-2"></i>Book Inventory
        </h6>
    </div>
    <div class="card-body p-0">
        @if($books->count())
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 p-3">Title</th>
                            <th class="border-0 p-3">Author</th>
                            <th class="border-0 p-3">Category</th>
                            <th class="border-0 p-3">Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($books as $book)
                        <tr>
                            <td class="p-3">{{ $book->title }}</td>
                            <td class="p-3">{{ $book->author }}</td>
                            <td class="p-3">{{ $book->category->name ?? 'N/A' }}</td>
                            <td class="p-3">{{ $book->quantity }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-top">
                {{ $books->links() }}
            </div>
        @else
            <div class="text-center py-5 text-muted">
                <i class="fas fa-book fa-2x mb-3"></i>
                <div>No books found in the library.</div>
            </div>
        @endif
    </div>
</div>
@endsection
