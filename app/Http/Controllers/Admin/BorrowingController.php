<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowingController extends Controller
{
    /**
     * Display all borrowings
     */
    public function index(Request $request)
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }

        // Redirect to transactions instead (since they're the same thing)
        return redirect()->route('admin.transactions.index');
    }

    /**
     * Show form to create new borrowing
     */
    public function create()
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }

        // Redirect to transactions create
        return redirect()->route('admin.transactions.create');
    }

    /**
     * Store new borrowing
     */
    public function store(Request $request)
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }

        // Redirect to transactions store
        return app(TransactionController::class)->store($request);
    }

    /**
     * Show borrowing details
     */
    public function show(Borrowing $borrowing)
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }

        return view('admin.borrowings.show', compact('borrowing'));
    }

    /**
     * Show edit form
     */
    public function edit(Borrowing $borrowing)
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }

        return view('admin.borrowings.edit', compact('borrowing'));
    }

    /**
     * Update borrowing
     */
    public function update(Request $request, Borrowing $borrowing)
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }

        // Basic update logic here
        return redirect()->route('admin.borrowings.show', $borrowing)
                        ->with('success', 'Borrowing updated successfully!');
    }

    /**
     * Delete borrowing
     */
    public function destroy(Borrowing $borrowing)
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }

        $borrowing->delete();

        return redirect()->route('admin.borrowings.index')
                        ->with('success', 'Borrowing deleted successfully!');
    }

    /**
     * Process book return
     */
    public function returnBook(Borrowing $borrowing)
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }

        // Use the transaction controller method
        return app(TransactionController::class)->returnBook($borrowing);
    }

    /**
     * Show overdue borrowings
     */
    public function overdue()
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }

        // Redirect to transactions overdue
        return redirect()->route('admin.transactions.overdue');
    }
}
