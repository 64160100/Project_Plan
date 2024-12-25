<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $employee = EmployeeModel::with('department', 'permissions')
            ->where('email', $credentials['email'])
            ->first();

        if ($employee) {
            if (md5($credentials['password']) === $employee->Password) {
                Log::info('User logged in:', ['employee' => $employee]);
                try {
                    $token = JWTAuth::fromUser($employee);
                } catch (JWTException $e) {
                    Log::info('Error creating token: ', ['error' => $e->getMessage()]);
                    return redirect()->back()->withErrors(['error' => 'Could not create token. Please try again.']);
                }

                $permissions = $employee->permissions;
                $department = $employee->department;

                session([
                    'employee' => $employee,
                    'permissions' => $permissions,
                    'department' => $department
                ]);

                return redirect()->route('dashboard')->with('token', $token);
            } else {
                return redirect()->back()->withErrors(['error' => 'ข้อมูลรับรองไม่ถูกต้อง กรุณาตรวจสอบอีเมลและรหัสผ่านของคุณ']);
            }
        } else {
            return redirect()->back()->withErrors(['error' => 'ไม่พบผู้ใช้ กรุณาตรวจสอบที่อยู่อีเมลของคุณ']);
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