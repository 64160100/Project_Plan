<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $employee = $request->session()->get('employee');
        $permissions = $request->session()->get('permissions');
    
        $message = "Hello";
    
        return view('dashboard', ['employee' => $employee, 'permissions' => $permissions, 'message' => $message]);
    }
}