<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        return $request->user()->isAdmin()
            ? redirect()->intended('/admin/attendance/list')
            : redirect()->intended('/attendance');
    }
}
