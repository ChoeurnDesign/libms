<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Book;
use App\Models\Category;
use App\Models\Borrowing;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }

        // Calculate statistics with try-catch for missing tables
        $stats = [
            'total_users' => $this->safeCount(User::class),
            'total_books' => $this->safeCount(Book::class),
            'total_categories' => $this->safeCount(Category::class),
            'active_borrowings' => $this->safeBorrowingCount('borrowed'),
            'overdue_books' => $this->safeOverdueCount(),
            'borrowed_today' => $this->safeTodayCount('created_at'),
            'returned_today' => $this->safeTodayCount('returned_at'),
        ];

        $recent_users = User::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_users'));
    }

    // REMOVE THE reports() METHOD - DON'T ADD IT

    private function safeCount($model)
    {
        try {
            return $model::count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function safeBorrowingCount($status)
    {
        try {
            return Borrowing::where('status', $status)->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function safeOverdueCount()
    {
        try {
            return Borrowing::where('status', 'borrowed')
                ->where('due_date', '<', now())
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function safeTodayCount($column)
    {
        try {
            return Borrowing::whereDate($column, today())->count();
        } catch (\Exception $e) {
            return 0;
        }
    }
}
