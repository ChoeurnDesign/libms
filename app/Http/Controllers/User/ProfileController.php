<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User; // Ensure User model is imported
use App\Models\Borrowing; // Ensure Borrowing model is imported for stats
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema; // Already present, but good to ensure
use Illuminate\Support\Facades\Storage; // Import Storage facade for file handling
use Illuminate\Validation\Rule;
use Carbon\Carbon; // Import Carbon for date handling

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $user = Auth::user();

        // Initialize stats with defaults
        $stats = [
            'total_borrowed' => 0,
            'currently_borrow_ed' => 0,
            'books_returned' => 0,
            'overdue_books' => 0,
            'total_fines' => 0,
        ];

        // Get borrowing stats using Eloquent if borrowings table exists
        if (Schema::hasTable('borrowings')) {
            try {
                // Fetch all borrowings for the user using the Borrowing model
                $borrowings = Borrowing::where('user_id', $user->id)->get();

                $stats['total_borrowed'] = $borrowings->count();
                $stats['currently_borrowed'] = $borrowings->where('status', 'borrowed')->count();
                $stats['books_returned'] = $borrowings->where('status', 'returned')->count();

                // Calculate overdue books using the isOverdue method from the Borrowing model
                $stats['overdue_books'] = $borrowings->filter(function ($borrowing) {
                    return $borrowing->isOverdue();
                })->count();

                // Calculate total fines (sum of fine_amount from all borrowings)
                $stats['total_fines'] = $borrowings->sum('fine_amount');

            } catch (\Exception $e) {
                // Keep default stats if something goes wrong
                // \Log::error("Error fetching borrowing stats for user " . $user->id . ": " . $e->getMessage()); // Removed Log
            }
        }

        return view('user.profile.show', compact('user', 'stats'));
    }

    /**
     * Show edit profile form
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $user = Auth::user();
        return view('user.profile.edit', compact('user'));
    }

    /**
     * Update user profile
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Define base validation rules
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Max 2MB
        ];

        // Conditionally add rules for optional fields based on column existence
        if (Schema::hasColumn('users', 'student_id')) {
            $rules['student_id'] = ['nullable', 'string', 'max:50', Rule::unique('users')->ignore($user->id)];
        }
        if (Schema::hasColumn('users', 'phone')) {
            $rules['phone'] = ['nullable', 'string', 'max:20'];
        }
        if (Schema::hasColumn('users', 'address')) {
            $rules['address'] = ['nullable', 'string', 'max:500'];
        }

        // Validate the request
        $validated = $request->validate($rules);

        try {
            // Update core user data
            $user->name = $validated['name'];
            $user->email = $validated['email'];

            // Handle profile picture upload
            if ($request->hasFile('profile_picture')) {
                // Delete old profile picture if it exists
                if ($user->profile_picture) {
                    Storage::disk('public')->delete($user->profile_picture);
                }
                // Store the new profile picture
                $path = $request->file('profile_picture')->store('profile_pictures', 'public');
                $user->profile_picture = $path;
            }

            // Update optional fields if they exist and are present in validated data
            if (Schema::hasColumn('users', 'student_id') && isset($validated['student_id'])) {
                $user->student_id = $validated['student_id'];
            }
            if (Schema::hasColumn('users', 'phone') && isset($validated['phone'])) {
                $user->phone = $validated['phone'];
            }
            if (Schema::hasColumn('users', 'address') && isset($validated['address'])) {
                $user->address = $validated['address'];
            }

            // Save all changes to the user model
            $user->save();

            return redirect()->route('user.profile.edit')->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            // \Log::error("Error updating user profile for user " . $user->id . ": " . $e->getMessage()); // Removed Log
            return back()->withErrors(['update' => 'Failed to update profile. Please try again.'])->withInput();
        }
    }

    /**
     * Update user password
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        try {
            // Update password using Eloquent model
            $user->password = Hash::make($request->password);
            $user->save();

            return redirect()->route('user.profile.edit')->with('password_success', 'Password updated successfully!');

        } catch (\Exception $e) {
            // \Log::error("Error updating password for user " . $user->id . ": " . $e->getMessage()); // Removed Log
            return back()->withErrors(['password' => 'Failed to update password. Please try again.']);
        }
    }
}
