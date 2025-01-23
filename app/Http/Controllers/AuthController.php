<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        $employee = EmployeeModel::where('Email', $credentials['email'])->first();
    
        if ($employee && md5($credentials['password']) === $employee->Password) {
            Log::info('User logged in from database:', ['employee' => $employee]);
    
            try {
                $token = JWTAuth::fromUser($employee);
            } catch (JWTException $e) {
                Log::info('Error creating token: ', ['error' => $e->getMessage()]);
                return redirect()->back()->withErrors(['error' => 'Could not create token. Please try again.']);
            }
    
            $employee->load('permissions', 'department');
    
            session([
                'employee' => $employee,
                'permissions' => $employee->permissions,
                'department' => $employee->department
            ]);
    
            return redirect()->route('dashboard')->with('token', $token);
        }
    
        $encodedUsername = base64_encode($credentials['email']);
        $encodedPassword = base64_encode($credentials['password']);
    
        $response = Http::get("https://info.lib.buu.ac.th/apilib/Persons/CheckPersonsLogin/{$encodedUsername}/{$encodedPassword}");
    
        Log::info('API response:', ['response' => $response]);
    
        if ($response->successful() && $response['status'] === true) {
            $userData = $response['data'];
    
            $employee = EmployeeModel::firstOrCreate(
                ['Username' => $userData['Username']],
                [
                    'Prefix_Name' => $userData['Prefix_Name'],
                    'Firstname' => $userData['Firstname'],
                    'Lastname' => $userData['Lastname'],
                    'Email' => $userData['Email'],
                    'Phone' => $userData['Phone'],
                    'Department_Name' => $userData['Department_Name'],
                    'Position_Name' => $userData['Position_Name'],
                    'Status' => $userData['Status'],
                ]
            );
    
            Log::info('User logged in from API:', ['employee' => $employee]);
    
            try {
                $token = JWTAuth::fromUser($employee);
            } catch (JWTException $e) {
                Log::info('Error creating token: ', ['error' => $e->getMessage()]);
                return redirect()->back()->withErrors(['error' => 'Could not create token. Please try again.']);
            }
    
            $employee->load('permissions', 'department');
    
            session([
                'employee' => $employee,
                'permissions' => $employee->permissions,
                'department' => $employee->department
            ]);
    
            return redirect()->route('dashboard')->with('token', $token);
        } else {
            return redirect()->back()->withErrors(['error' => 'ข้อมูลรับรองไม่ถูกต้อง กรุณาตรวจสอบชื่อผู้ใช้และรหัสผ่านของคุณ']);
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