<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class StaffController extends Controller
{
    public function list()
    {
        $users = User::where('role', User::ROLE_USER)
            ->get();

        return view('admin.staff.list', compact('users'));
    }
}
