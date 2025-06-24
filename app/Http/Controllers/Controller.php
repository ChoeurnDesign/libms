<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Get the authenticated user safely.
     */
    protected function getAuthUser()
    {
        return Auth::user();
    }

    /**
     * Get the authenticated user ID safely.
     */
    protected function getAuthUserId()
    {
        return Auth::id();
    }

    /**
     * Check if current user is admin.
     */
    protected function isCurrentUserAdmin()
    {
        return Auth::check() && optional(Auth::user())->isAdmin();
    }

    /**
     * Check if current user is regular user.
     */
    protected function isCurrentUserRegular()
    {
        return Auth::check() && optional(Auth::user())->isUser();
    }
}
