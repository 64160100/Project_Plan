<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $employee = $request->session()->get('employee');
        
        log:info($employee);
    
        return view('dashboard', ['employee' => $employee]);
    }
}