<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{   

    public function employee(Request $request)
    {
        $employee = $request->session()->get('employee');
        
        if (!$employee) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบก่อนเข้าถึงหน้านี้');
        }
        
        if ($employee->IsAdmin !== 'Y') {
            return redirect()->route('dashboard')->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }
        
        $employees = EmployeeModel::all();
    
        Log::info('Employees Data:', $employees->toArray());
        
        return view('account.employee', compact('employees', 'employee'));
    }

    public function showEmployees($Id_Employee, Request $request)
    {
        $currentUser = $request->session()->get('employee');
        
        if (!$currentUser) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบก่อนเข้าถึงหน้านี้');
        }
        
        $viewEmployee = EmployeeModel::findOrFail($Id_Employee);
        
        $isOwnProfile = $currentUser->Id_Employee == $viewEmployee->Id_Employee;
        $isAdmin = $currentUser->IsAdmin == 'Y';
        
        if (!$isOwnProfile && !$isAdmin) {
            return redirect()->back()->with('error', 'คุณไม่มีสิทธิ์เข้าถึงข้อมูลนี้');
        }
        
        $employee = $viewEmployee; 
        
        return view('account.viewEmployee', compact('viewEmployee', 'currentUser', 'isOwnProfile', 'employee'));
    }

    public function create()
    {
        return view('account.createEmployee');
    }

    public function checkUsername(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
        ]);
    
        $username = $request->input('username');
        
        // ตรวจสอบก่อนว่ามีในฐานข้อมูลหรือไม่
        $existingEmployee = EmployeeModel::where('Username', $username)->first();
        
        if ($existingEmployee) {
            return response()->json([
                'success' => false,
                'message' => 'พนักงานนี้มีอยู่ในระบบแล้ว',
                'exists' => true,
            ]);
        }
        
        // เรียกใช้ฟังก์ชัน API เพื่อตรวจสอบข้อมูลผู้ใช้
        $userData = self::CheckUser($username);
        
        if (!$userData) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่พบข้อมูลผู้ใช้',
            ]);
        }
    
        Log::info('User data:', $userData);
        
        // ส่งข้อมูลกลับไปยังหน้าเว็บ
        return response()->json([
            'success' => true,
            'data' => $userData,
        ]);
    }

    public static function CheckUser($username)
    {   
        $curl = curl_init();
    
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://info.lib.buu.ac.th/apilib/Persons/CheckPersons/".base64_encode($username),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "postman-token: 8fc51128-3fac-8135-c258-b268c509f3e6",
            ),
        ));
    
        $response = curl_exec($curl);
        $err = curl_error($curl);
    
        curl_close($curl);
    
        if ($err) {
            Log::error('cURL Error:', ['error' => $err]);
            return null;
        } else {
            $someArray = (array) json_decode($response, true);
            if (!empty($someArray['ItemInfo'])) {
                Log::info('API response for username:', ['username' => $username, 'response' => $someArray['ItemInfo']]);
                return $someArray['ItemInfo'];
            } else {
                Log::info('API response for username:', ['username' => $username, 'response' => $someArray]);
                if (isset($someArray['status']) && $someArray['status'] === false) {
                    return null;
                }
                return $someArray;
            }
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'user_id' => 'required|string|max:255',
        ]);
        
        // ตรวจสอบว่ามีพนักงานในระบบแล้วหรือไม่
        $existingEmployee = EmployeeModel::where('Username', $request->input('user_id'))->first();
        
        if ($existingEmployee) {
            return redirect()->route('account.employee')
                ->with('error', 'พนักงานนี้มีอยู่ในระบบแล้ว');
        }
        
        $employee = new EmployeeModel();
        
        $latestId = EmployeeModel::max('Id_Employee') ?? 0;
        $newId = $latestId + 1;
        
        $employee->Username = $request->input('user_id'); 
        $employee->Password = '25d55ad283aa400af464c76d713c07ad'; 
        $employee->Prefix_Name = $request->input('prefix_name');
        $employee->Firstname = $request->input('firstname');
        $employee->Lastname = $request->input('lastname');
        $employee->Email = $request->input('email');
        $employee->Phone = $request->input('phone');
        $employee->Department_Name = $request->input('department');
        $employee->Position_Name = $request->input('position');
        $employee->TypePersons = $request->input('type_persons') ?? 'People';
        $employee->Agency = $request->input('agency');
        $employee->Status = $request->input('status') ?? 'Active';
        
        $employee->IsManager = $request->has('is_manager') ? 'Y' : 'N';
        $employee->IsDirector = $request->has('is_director') ? 'Y' : 'N';
        $employee->IsFinance = $request->has('is_finance') ? 'Y' : 'N';
        $employee->IsResponsible = $request->has('is_responsible') ? 'Y' : 'N';
        $employee->IsAdmin = $request->has('is_admin') ? 'Y' : 'N';
        $employee->IsGeneralEmployees = $request->has('is_general') ? 'Y' : 'N';
        
        $employee->save();
        
        $request->session()->put('employee', $employee);
        
        return redirect()->route('account.employee')
            ->with('success', 'เพิ่มพนักงานใหม่เรียบร้อยแล้ว');
    }

    public function editUser($Id_Employee, Request $request)
    {
        $currentUser = $request->session()->get('employee');
        
        if (!$currentUser) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบก่อนเข้าถึงหน้านี้');
        }
        
        $employee = EmployeeModel::findOrFail($Id_Employee);
        
        $isOwnProfile = $currentUser->Id_Employee == $employee->Id_Employee;
        $isAdmin = $currentUser->IsAdmin == 'Y';
        
        if (!$isOwnProfile && !$isAdmin) {
            return redirect()->back()->with('error', 'คุณไม่มีสิทธิ์เข้าถึงข้อมูลนี้');
        }
        
        return view('account.editUser', compact('employee', 'currentUser', 'isOwnProfile'));
    }

    public function updateUserPermissions(Request $request, $Id_Employee)
    {
        $currentUser = $request->session()->get('employee');
        
        if (!$currentUser || $currentUser->IsAdmin !== 'Y') {
            return response()->json([
                'success' => false, 
                'message' => 'คุณไม่มีสิทธิ์ดำเนินการนี้'
            ], 403);
        }
        
        $employee = EmployeeModel::findOrFail($Id_Employee);
    
        $field = $request->input('field');
        $value = $request->input('value') === 'true' ? 'Y' : 'N';
    
        if (in_array($field, ['IsManager', 'IsDirector', 'IsFinance', 'IsResponsible', 'IsAdmin', 'IsGeneralEmployees'])) {
            $employee->$field = $value;
            $employee->save();
        }
    
        return response()->json(['success' => true]);
    }

    public function updatePassword($Id_Employee, Request $request)
    {
        // Validate request
        $request->validate([
            'current_password' => 'required',
            'new_password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', // At least one uppercase, one lowercase, and one number
            ],
            'confirm_password' => 'required|same:new_password',
        ], [
            'new_password.regex' => 'รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร และประกอบด้วยตัวอักษรตัวพิมพ์ใหญ่ ตัวพิมพ์เล็ก และตัวเลข',
        ]);
    
        // Find the employee
        $employee = EmployeeModel::findOrFail($Id_Employee);
        
        // Check permissions (only allow user to change their own password or admin to change anyone's)
        $currentUser = $request->session()->get('employee');
        if (!$currentUser || ($currentUser->Id_Employee != $Id_Employee && $currentUser->IsAdmin !== 'Y')) {
            return response()->json(['message' => 'คุณไม่มีสิทธิ์เปลี่ยนรหัสผ่านนี้'], 403);
        }
    
        // Check if the password is already using bcrypt (starts with $2y$)
        $isBcrypt = strpos($employee->Password, '$2y$') === 0;
        
        // Verify current password differently based on the hashing algorithm
        if ($isBcrypt) {
            // Password uses Bcrypt
            if (!Hash::check($request->current_password, $employee->Password)) {
                return response()->json(['message' => 'รหัสผ่านปัจจุบันไม่ถูกต้อง'], 422);
            }
        } else {
            // Password uses MD5
            if (md5($request->current_password) !== $employee->Password) {
                return response()->json(['message' => 'รหัสผ่านปัจจุบันไม่ถูกต้อง'], 422);
            }
        }
    
        // Update password using MD5 (like the default format: 25d55ad283aa400af464c76d713c07ad)
        $employee->Password = md5($request->new_password);
        $employee->save();
    
        return response()->json(['message' => 'อัปเดตรหัสผ่านเรียบร้อยแล้ว']);
    }
}