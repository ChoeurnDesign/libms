<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Book;
use App\Models\Category;
use App\Models\Borrowing;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }

        // Get selected year from query, or use current year by default
        $year = $request->query('year', now()->year);

        // Calculate statistics with try-catch for missing tables
        $stats = [
            'total_users' => $this->safeCount(User::class),
            'total_books' => $this->safeCount(Book::class),
            'total_categories' => $this->safeCount(Category::class),
            'active_borrowings' => $this->safeBorrowingCount('borrowed'),
            'overdue_books' => $this->safeOverdueCount(),
            'borrowed_today' => $this->safeTodayCount('borrowed_date'),
            'returned_today' => $this->safeTodayCount('returned_date'),
        ];

        $recent_users = User::latest()->take(5)->get();

        // Transaction chart data for the selected year (Jan to Dec)
        $months = [];
        $borrowed = [];
        $returned = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[] = date('M', mktime(0, 0, 0, $i, 10));
            $borrowed[] = $this->safeMonthlyCountByYear('borrowed_date', $i, $year);
            $returned[] = $this->safeMonthlyCountByYear('returned_date', $i, $year, true);
        }

        return view('admin.dashboard', compact('stats', 'recent_users', 'months', 'borrowed', 'returned', 'year'));
    }

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

    private function safeMonthlyCountByYear($column, $month, $year, $notNull = false)
    {
        try {
            $query = Borrowing::whereMonth($column, $month)
                ->whereYear($column, $year);
            if ($notNull) {
                $query->whereNotNull($column);
            }
            return $query->count();
        } catch (\Exception $e) {
            return 0;
        }
    }
}
