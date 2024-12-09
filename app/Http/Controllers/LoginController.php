<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EmployeeModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;


class LoginController extends Controller
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

            $token = Str::random(60);

            session([
                'employee' => $employee,
                'permissions' => $permissions,
                'token' => $token
            ]);

            Log::info('Session Data', session()->all());

            return redirect()->route('dashboard');
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