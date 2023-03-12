<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Dapatkan jalur yang harus diarahkan ke user saat mereka tidak autentikasi atau login.
     */
    // lindungi fungsi alihkan ke Permintaan $permintaan url
    protected function redirectTo(Request $request): ?string
    {
        // jika user sudah login maka kembalikkan null, jika belum login maka ke route login.index
        return $request->expectsJson() ? null : route('login.index');
    }
}
