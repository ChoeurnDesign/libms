<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;
use App\Models\Borrowing;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Reports Dashboard
     */
    public function index()
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }

        $stats = [
            'total_books' => Book::count(),
            'total_students' => User::where('role', 'user')->count(),
            'active_borrowings' => Borrowing::whereNull('returned_date')->count(),
            'overdue_books' => Borrowing::whereNull('returned_date')->where('due_date', '<', now())->count(),
        ];

        return view('admin.reports.index', compact('stats'));
    }

    /**
     * Books Report
     */
    public function books()
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }

        $books = Book::with('category')->paginate(20);
        $categories = Category::all();

        return view('admin.reports.books', compact('books', 'categories'));
    }

    /**
     * Students Report
     */
    public function students()
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }

        $students = User::where('role', 'user')->paginate(20);

        return view('admin.reports.students', compact('students'));
    }

    /**
     * Borrowings Report
     */
    public function borrowings()
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }

        $borrowings = Borrowing::with(['user', 'book'])->latest()->paginate(20);

        return view('admin.reports.borrowings', compact('borrowings'));
    }

    /**
     * Overdue Report
     */
    public function overdue()
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }

        $overdueBooks = Borrowing::with(['user', 'book'])
            ->whereNull('returned_date')
            ->where('due_date', '<', now())
            ->orderBy('due_date')
            ->get();

        return view('admin.reports.overdue', compact('overdueBooks'));
    }

    /**
     * Export CSV
     */
    public function export($type)
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }

        $filename = $type . '_report_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($type) {
            $file = fopen('php://output', 'w');

            if ($type === 'books') {
                fputcsv($file, ['Title', 'Author', 'Category', 'Quantity']);
                foreach (Book::with('category')->get() as $book) {
                    fputcsv($file, [
                        $book->title,
                        $book->author,
                        $book->category->name ?? 'N/A',
                        $book->quantity
                    ]);
                }
            } elseif ($type === 'students') {
                fputcsv($file, ['Name', 'Email', 'Phone']);
                foreach (User::where('role', 'user')->get() as $student) {
                    fputcsv($file, [$student->name, $student->email, $student->phone]);
                }
            } elseif ($type === 'borrowings') {
                fputcsv($file, ['Student', 'Book', 'Borrowed Date', 'Due Date', 'Status']);
                foreach (Borrowing::with(['user', 'book'])->get() as $borrowing) {
                    fputcsv($file, [
                        $borrowing->user->name,
                        $borrowing->book->title,
                        $borrowing->borrowed_date->format('Y-m-d'),
                        $borrowing->due_date->format('Y-m-d'),
                        $borrowing->returned_date ? 'Returned' : 'Active'
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
