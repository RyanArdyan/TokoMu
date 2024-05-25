<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Model untuk pemetaan kebijakan untuk aplikasi.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Registrasi autentikasi apapun atau autorisasi layanan
     */
    // publik fungsi boot(): ruang kosong
    public function boot(): void
    {
        // AuthServiceProvider atau penyedia layanan aplikasi registrasikan kebijakan
        $this->registerPolicies();

        // Gerbang, denifisikan is_admin_dan_is_pembeli
        // $user berisi detail user yang login
        Gate::define('is_admin_dan_is_pembeli', function(User $user) {
            // jadi jika detail user yang login, value column is_admin nya adalah 1 atau 2 maka, 1 berarti adalah admin, 2 berarti adalah pembeli
            return $user->is_admin === 1 || $user->is_admin === 2;
        });
    }
}
