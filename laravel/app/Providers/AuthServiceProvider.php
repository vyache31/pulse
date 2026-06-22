<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void {
    	Passport::tokensExpireIn(now()->addMinutes(15));
    	Passport::refreshTokensExpireIn(now()->addDays(30));
	}
}
