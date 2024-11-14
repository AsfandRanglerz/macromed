<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait ChecksUserTypeTrait
{
    // Method to check if the user is an Admin or User
    protected function checkUserType()
    {
        // Check if the user is authenticated as an Admin
        if (Auth::guard('admin')->check()) {
            return Auth::guard('admin')->user(); // Return the authenticated Admin
        }

        // Check if the user is authenticated as a regular User
        if (Auth::guard('web')->check()) {
            return Auth::guard('web')->user(); // Return the authenticated User
        }

        // If neither Admin nor User is authenticated, return null
        return null;
    }
}
