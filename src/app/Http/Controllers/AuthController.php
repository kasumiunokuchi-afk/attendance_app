<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function store(RegisterRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'role' => User::ROLE_USER,
        ]);

        event(new Registered($user));

        auth()->login($user);

        // TODO : メール認証機能は余力で実装
        return redirect()->route('attendance.index');
    }

    public function showLogin(Request $request)
    {
        $type = $request->routeIs('admin.login') ? User::ROLE_ADMIN : User::ROLE_USER;

        return view('auth.login', compact('type'));
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->isAdmin()) {
                return redirect('/admin/attendance/list');
            }
            return redirect('/attendance');
        }

        return back()->withErrors([
            'email' => 'ログイン情報が登録されていません',
        ]);
    }

    public function logout()
    {
        $user = Auth::user();
        $route = 'login';
        if ($user && $user->isAdmin()) {
            $route = 'admin.login';
        }

        Auth::logout();
        return redirect()->route($route);
    }
}
