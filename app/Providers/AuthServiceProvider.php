<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // AuthServiceProvider registrasikan kebijakan
        $this->registerPolicies();

        // Gerbang, denifisikan is_admin
        // $user berisi detail user yang login
        Gate::define('is_admin', function(User $user) {
            // jadi jika detail user yang login, value column is_admin nya adalah 1 maka dia adalah admin
            return $user->is_admin === 1;
        });
    }
}
