<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    /**
     * Display all students
     */
    public function index(Request $request)
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }

        $query = User::where('role', 'user')
                    ->withCount(['borrowings', 'activeBorrowings', 'overdueBorrowings']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereHas('activeBorrowings');
            } elseif ($request->status === 'overdue') {
                $query->whereHas('overdueBorrowings');
            }
        }

        $students = $query->latest()->paginate(15);

        // Get quick stats
        $stats = [
            'total' => User::where('role', 'user')->count(),
            'active_borrowers' => User::whereHas('activeBorrowings')->count(),
            'overdue_borrowers' => User::whereHas('overdueBorrowings')->count(),
            'new_this_month' => User::where('role', 'user')
                                  ->whereMonth('created_at', now()->month)
                                  ->whereYear('created_at', now()->year)
                                  ->count(),
        ];

        return view('admin.students.index', compact('students', 'stats'));
    }

    /**
     * Show form to create new student
     */
    public function create()
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }

        return view('admin.students.create');
    }

    /**
     * Store new student
     */
    public function store(Request $request)
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'student_id' => 'nullable|string|unique:users,student_id',
            'password' => 'required|string|min:8|confirmed',
            'address' => 'nullable|string|max:500',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'student_id' => $request->student_id,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'address' => $request->address,
            'email_verified_at' => now(), // Auto-verify admin created accounts
        ]);

        return redirect()->route('admin.students.index')
                        ->with('success', 'Student created successfully!');
    }

    /**
     * Show student details
     */
    public function show(User $student)
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }

        if ($student->role !== 'user') {
            return redirect()->route('admin.students.index')
                           ->with('error', 'Invalid student record.');
        }

        $borrowings = $student->borrowings()
                            ->with('book')
                            ->latest('borrowed_date')
                            ->paginate(10);

        return view('admin.students.show', compact('student', 'borrowings'));
    }

    /**
     * Show edit form
     */
    public function edit(User $student)
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }

        if ($student->role !== 'user') {
            return redirect()->route('admin.students.index')
                           ->with('error', 'Invalid student record.');
        }

        return view('admin.students.edit', compact('student'));
    }

    /**
     * Update student
     */
    public function update(Request $request, User $student)
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($student->id)],
            'phone' => 'nullable|string|max:20',
            'student_id' => ['nullable', 'string', Rule::unique('users')->ignore($student->id)],
            'address' => 'nullable|string|max:500',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'student_id' => $request->student_id,
            'address' => $request->address,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $student->update($updateData);

        return redirect()->route('admin.students.show', $student)
                        ->with('success', 'Student updated successfully!');
    }

    /**
     * Delete student
     */
    public function destroy(User $student)
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }

        if ($student->role !== 'user') {
            return redirect()->route('admin.students.index')
                           ->with('error', 'Invalid student record.');
        }

        // Check if student has active borrowings
        if ($student->activeBorrowings()->count() > 0) {
            return back()->with('error', 'Cannot delete student with active borrowings.');
        }

        $student->delete();

        return redirect()->route('admin.students.index')
                        ->with('success', 'Student deleted successfully!');
    }
}
