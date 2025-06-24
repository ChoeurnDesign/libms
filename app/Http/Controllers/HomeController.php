<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Models\Book;
use App\Models\Category;
use Exception;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     */
    public function index()
    {
        if (optional(Auth::user())->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('user.dashboard');
        }
    }

    /**
     * Show the welcome page with featured books.
     */
    public function welcome()
    {
        try {
            $featuredBooks = Book::where('available_quantity', '>', 0)
                ->latest()
                ->take(8)
                ->get();

            $categories = Category::withCount('books')->take(6)->get();

            return view('welcome', compact('featuredBooks', 'categories'));
        } catch (Exception $e) {
            Log::error('Error loading welcome page: ' . $e->getMessage());
            return view('welcome', [
                'featuredBooks' => collect(),
                'categories' => collect()
            ]);
        }
    }

    /**
     * Show the user profile.
     */
    public function profile()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    /**
     * Update the user profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to continue.');
        }

        // Validate the request
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('users')->ignore($user->id),
                ],
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
                'current_password' => 'nullable|required_with:password',
                'password' => 'nullable|string|min:8|confirmed',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput($request->except('password', 'password_confirmation', 'current_password'));
        }

        // Prepare data for update
        $data = $request->only(['name', 'email', 'phone', 'address']);

        // Handle password update
        if ($request->filled('password')) {
            if (!$request->filled('current_password')) {
                return redirect()->back()
                    ->withErrors(['current_password' => 'Current password is required when setting a new password.'])
                    ->withInput($request->except('password', 'password_confirmation', 'current_password'));
            }

            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()
                    ->withErrors(['current_password' => 'The current password is incorrect.'])
                    ->withInput($request->except('password', 'password_confirmation', 'current_password'));
            }

            $data['password'] = Hash::make($request->password);
        }

        // Update user with better error handling
        try {
            // Check if user model exists and is valid
            if (!$user instanceof \App\Models\User) {
                throw new Exception('Invalid user model');
            }

            // Ensure the data array has valid keys
            $fillableFields = $user->getFillable();
            $filteredData = array_intersect_key($data, array_flip($fillableFields));

            // Log the update attempt for debugging
            Log::info('Attempting to update user profile', [
                'user_id' => $user->id,
                'data' => array_keys($filteredData),
                'updated_by' => 'ChoeurnDesign'
            ]);

            // Perform the update
            $updateResult = $user->update($filteredData);

            if (!$updateResult) {
                throw new Exception('User update returned false');
            }

            // Refresh the user model to get updated data
            $user->refresh();

            Log::info('User profile updated successfully', [
                'user_id' => $user->id,
                'updated_fields' => array_keys($filteredData)
            ]);

            return redirect()->back()->with('success', 'Profile updated successfully.');
        } catch (Exception $e) {
            // Log the specific error
            Log::error('Profile update failed for user: ' . $user->id, [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $data,
                'user_id' => $user->id
            ]);

            // Return with specific error message
            return redirect()->back()
                ->with('error', 'Failed to update profile: ' . $e->getMessage())
                ->withInput($request->except('password', 'password_confirmation', 'current_password'));
        }
    }
}
