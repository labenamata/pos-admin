<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Definisikan gate untuk role admin
        Gate::define('admin', function (User $user) {
            return $user->role === 'admin';
        });
        
        // Definisikan gate untuk role kasir
        Gate::define('kasir', function (User $user) {
            return $user->role === 'kasir';
        });
    }
}
