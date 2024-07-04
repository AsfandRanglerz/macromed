<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('exists_in_users_or_admins', function ($attribute, $value, $parameters, $validator) {
            // Check if the email exists in either users or admins table
            $userExists = DB::table('users')->where('email', $value)->exists();
            $adminExists = DB::table('admins')->where('email', $value)->exists();
            return $userExists || $adminExists;
        });
    }
}
