<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TransactionController extends Controller
{
    /**
     * Check if user is admin
     */
    private function checkAdmin()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }
        return null;
    }

    /**
     * Display all transactions
     */
    public function index(Request $request)
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        $query = Borrowing::with(['user', 'book'])
            ->latest('borrowed_date'); // Back to borrowed_date

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'borrowed') {
                $query->whereNull('returned_date');
            } elseif ($request->status === 'returned') {
                $query->whereNotNull('returned_date');
            } elseif ($request->status === 'overdue') {
                $query->overdue();
            }
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                    ->orWhereHas('book', function ($bookQuery) use ($search) {
                        $bookQuery->where('title', 'like', "%{$search}%")
                            ->orWhere('author', 'like', "%{$search}%");
                    });
            });
        }

        $transactions = $query->paginate(15);

        $stats = [
            'total' => Borrowing::count(),
            'active' => Borrowing::whereNull('returned_date')->count(),
            'returned' => Borrowing::whereNotNull('returned_date')->count(),
            'overdue' => Borrowing::overdue()->count(),
        ];

        return view('admin.transactions.index', compact('transactions', 'stats'));
    }

    /**
     * Show form to create new transaction
     */
    public function create()
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        $users = User::where('role', 'user')->orderBy('name')->get();
        // FIXED: Use available_quantity instead of available_copies
        $books = Book::where('available_quantity', '>', 0)->orderBy('title')->get();

        return view('admin.transactions.create', compact('users', 'books'));
    }

    /**
     * Store new transaction
     */
    public function store(Request $request)
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'due_date' => 'required|date|after:today',
            'notes' => 'nullable|string|max:500',
        ]);

        // FIXED: Use available_quantity instead of available_copies
        $book = Book::findOrFail($request->book_id);
        if ($book->available_quantity <= 0) {
            return back()->withInput()->with('error', 'This book is not available for borrowing.');
        }

        // Check if user already has this book
        $existingBorrow = Borrowing::where('user_id', $request->user_id)
            ->where('book_id', $request->book_id)
            ->whereNull('returned_date')
            ->first();

        if ($existingBorrow) {
            return back()->withInput()->with('error', 'This user already has this book borrowed.');
        }

        try {
            Borrowing::create([
                'user_id' => $request->user_id,
                'book_id' => $request->book_id,
                'borrowed_date' => now(),
                'due_date' => $request->due_date,
                'status' => 'borrowed',
                'notes' => $request->notes,
                'fine_amount' => 0.00,
            ]);

            // FIXED: Decrement available_quantity
            $book->decrement('available_quantity');

            return redirect()->route('admin.transactions.index')
                ->with('success', 'Book borrowed successfully! Transaction created.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create transaction. Please try again.');
        }
    }

    /**
     * Show specific transaction
     */
    public function show(Borrowing $borrowing)
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        $borrowing->load(['user', 'book']);
        return view('admin.transactions.show', compact('borrowing'));
    }

    /**
     * Process book return
     */
    public function returnBook(Borrowing $borrowing)
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        if ($borrowing->returned_date) {
            return back()->with('error', 'Book is already returned.');
        }

        try {
            $fine = 0;
            if ($borrowing->isOverdue()) {
                $fine = $borrowing->calculateFine(1.00); // $1 per day fine
            }

            $borrowing->update([
                'returned_date' => now(),
                'status' => 'returned',
                'fine_amount' => $fine,
            ]);

            // FIXED: Increment available_quantity
            $borrowing->book->increment('available_quantity');

            $message = 'Book returned successfully!';
            if ($fine > 0) {
                $message .= " Fine applied: $" . number_format($fine, 2);
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to return book. Please try again.');
        }
    }

    /**
     * Renew borrowing
     */
    public function renew(Borrowing $borrowing, Request $request)
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        $request->validate([
            'new_due_date' => 'required|date|after:today',
        ]);

        if ($borrowing->returned_date) {
            return back()->with('error', 'Cannot renew returned book.');
        }

        try {
            $borrowing->update([
                'due_date' => $request->new_due_date,
            ]);

            return back()->with('success', 'Book renewed successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to renew book. Please try again.');
        }
    }

    /**
     * Show overdue transactions
     */
    public function overdue()
    {
        if ($redirect = $this->checkAdmin()) return $redirect;

        $overdueTransactions = Borrowing::with(['user', 'book'])
            ->overdue()
            ->orderBy('due_date')
            ->paginate(15);

        return view('admin.transactions.overdue', compact('overdueTransactions'));
    }
}
