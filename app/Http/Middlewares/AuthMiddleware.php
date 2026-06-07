<?php

namespace App\Http\Middlewares;

use App\Modules\App\Models\AppModel;

class AuthMiddleware
{
    public function handle($request, $next)
    {
        if ($request->session()->get('login_st') != 1) {
            return redirect('app/auth/login')->with('flash_error', 'Sesi Anda telah berakhir, silakan login kembali.');
        }

        if (AppModel::getNav($request->query('n')) == null) {
            return redirect('dashboard?n=' . md5('000'));
        }

        return $next($request);
    }
}
