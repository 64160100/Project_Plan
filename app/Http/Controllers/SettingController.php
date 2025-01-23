<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function settings(Request $request)
    {   
        $employee = $request->session()->get('employee');
        $permissions = $request->session()->get('permissions');


        return view('setting', ['employee' => $employee, 'permissions' => $permissions]);
    }
}
