<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    //Login View
    public function index()
    {

        return view('admin.auth.login');
    }

    //Login Submit
    public function login(Request $request)
    {

        $validate = $request->validate
        ([
                'email' => 'required|exists:admins,email',
                'password' => 'required',
            ]);

        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], true)) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->back()->withErrors(['error' => 'Invalid Email id or Password']);
        }
    }
}