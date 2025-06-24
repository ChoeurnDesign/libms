<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Display settings dashboard
     */
    public function index()
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }

        $settings = $this->getAllSettings();

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }

        $request->validate([
            'library_name' => 'required|string|max:255',
            'library_email' => 'required|email|max:255',
            'library_phone' => 'nullable|string|max:20',
            'library_address' => 'nullable|string|max:500',
            'borrowing_period' => 'required|integer|min:1|max:365',
            'max_books_per_user' => 'required|integer|min:1|max:50',
            'renewal_period' => 'required|integer|min:1|max:30',
            'fine_per_day' => 'required|numeric|min:0|max:1000',
            'max_renewals' => 'required|integer|min:0|max:10',
            'library_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            // Handle logo upload
            if ($request->hasFile('library_logo')) {
                $logoPath = $request->file('library_logo')->store('library', 'public');
                $this->setSetting('library_logo', $logoPath);
            }

            // Save all settings
            $settings = [
                'library_name' => $request->library_name,
                'library_email' => $request->library_email,
                'library_phone' => $request->library_phone,
                'library_address' => $request->library_address,
                'library_description' => $request->library_description,
                'borrowing_period' => $request->borrowing_period,
                'max_books_per_user' => $request->max_books_per_user,
                'renewal_period' => $request->renewal_period,
                'fine_per_day' => $request->fine_per_day,
                'max_renewals' => $request->max_renewals,
                'email_notifications' => $request->has('email_notifications'),
                'overdue_notifications' => $request->has('overdue_notifications'),
                'return_confirmations' => $request->has('return_confirmations'),
                'date_format' => $request->date_format ?? 'Y-m-d',
                'timezone' => $request->timezone ?? 'UTC',
            ];

            foreach ($settings as $key => $value) {
                $this->setSetting($key, $value);
            }

            // Clear settings cache
            Cache::forget('library_settings');

            return redirect()->route('admin.settings.index')
                           ->with('success', 'Settings updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Failed to update settings. Please try again.')
                           ->withInput();
        }
    }

    /**
     * Reset settings to default
     */
    public function reset()
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }

        try {
            // Reset to default values
            $defaults = [
                'library_name' => 'ChoeurnDesign Library',
                'library_email' => 'admin@library.com',
                'library_phone' => '',
                'library_address' => '',
                'library_description' => 'Welcome to our library management system',
                'borrowing_period' => 14,
                'max_books_per_user' => 5,
                'renewal_period' => 7,
                'fine_per_day' => 1.00,
                'max_renewals' => 2,
                'email_notifications' => true,
                'overdue_notifications' => true,
                'return_confirmations' => false,
                'date_format' => 'Y-m-d',
                'timezone' => 'UTC',
            ];

            foreach ($defaults as $key => $value) {
                $this->setSetting($key, $value);
            }

            Cache::forget('library_settings');

            return redirect()->route('admin.settings.index')
                           ->with('success', 'Settings reset to default values!');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Failed to reset settings. Please try again.');
        }
    }

    /**
     * Get all settings
     */
    private function getAllSettings()
    {
        return Cache::remember('library_settings', 3600, function () {
            $settings = Setting::pluck('value', 'key')->toArray();

            // Add defaults for missing settings
            $defaults = [
                'library_name' => 'ChoeurnDesign Library',
                'library_email' => 'admin@library.com',
                'library_phone' => '',
                'library_address' => '',
                'library_description' => 'Welcome to our library management system',
                'library_logo' => '',
                'borrowing_period' => 14,
                'max_books_per_user' => 5,
                'renewal_period' => 7,
                'fine_per_day' => 1.00,
                'max_renewals' => 2,
                'email_notifications' => true,
                'overdue_notifications' => true,
                'return_confirmations' => false,
                'date_format' => 'Y-m-d',
                'timezone' => 'UTC',
            ];

            return array_merge($defaults, $settings);
        });
    }

    /**
     * Set a setting value
     */
    private function setSetting($key, $value)
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}
