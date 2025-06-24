<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class AuthHelper
{
    /**
     * Check if current user is authenticated.
     */
    public static function isAuthenticated(): bool
    {
        return Auth::check();
    }

    /**
     * Get the authenticated user safely.
     */
    public static function getUser()
    {
        return Auth::user();
    }

    /**
     * Get the authenticated user ID safely.
     */
    public static function getUserId()
    {
        return Auth::id();
    }

    /**
     * Check if current user is admin.
     */
    public static function isAdmin(): bool
    {
        return Auth::check() && optional(Auth::user())->isAdmin();
    }

    /**
     * Check if current user is regular user.
     */
    public static function isUser(): bool
    {
        return Auth::check() && optional(Auth::user())->isUser();
    }

    /**
     * Check if user can access admin features.
     */
    public static function canAccessAdmin(): bool
    {
        return self::isAuthenticated() && self::isAdmin();
    }

    /**
     * Check if user can access user features.
     */
    public static function canAccessUser(): bool
    {
        return self::isAuthenticated() && self::isUser();
    }
}
