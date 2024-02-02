<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    // Dashboard
    public function index()
    {
        return view('admin.dashboard.index');
    }

    // Logout
    public function logout()
    {
        // Your existing logout logic
        return redirect()->route('admin.login.view');
    }
}
