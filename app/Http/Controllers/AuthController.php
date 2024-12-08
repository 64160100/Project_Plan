<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $employee = EmployeeModel::where('email', $credentials['email'])->where('password', $credentials['password'])->first();

        if ($employee) {
            $permissions = $employee->permissions;

            Log::info('Login', ['Employee' => $employee, 'Permissions' => $permissions]);

            return redirect()->route('dashboard')->with([
                'employee' => $employee,
                'permissions' => $permissions
            ]);
        } else {
            $employeeByEmail = EmployeeModel::where('email', $credentials['email'])->first();
            if ($employeeByEmail) {
                return back()->withErrors([
                    'password' => 'รหัสผ่านไม่ถูกต้อง',
                ])->withInput($request->only('email'));
            } else {
                return back()->withErrors([
                    'email' => 'อีเมลไม่ถูกต้อง',
                ])->withInput($request->only('email'));
            }
        }
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}