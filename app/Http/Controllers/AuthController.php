<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use App\Models\ApproveModel;
use App\Models\RecordHistory;
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

            $pendingApprovals = collect();
            if ($employee) {
                if ($employee->IsAdmin === 'Y') {
                    $pendingApprovals = ApproveModel::whereHas('project', function ($query) {
                        $query->whereNotIn('Count_Steps', [0, 2, 6, 9]);
                    })->where('Status', 'I')->get();
                } elseif ($employee->IsManager === 'Y') {
                    $pendingApprovals = ApproveModel::whereHas('project', function ($query) {
                        $query->whereIn('Count_Steps', [4, 7]);
                    })->where('Status', 'I')->get();
                } elseif ($employee->IsDirector === 'Y') {
                    $pendingApprovals = ApproveModel::whereHas('project', function ($query) {
                        // $query->whereIn('Count_Steps', [1, 5, 8]);
                        $query->whereIn('Count_Steps', [5, 8, 11]);
                    })->where('Status', 'I')->get();
                } elseif ($employee->IsFinance === 'Y') {
                    $pendingApprovals = ApproveModel::whereHas('project', function ($query) {
                        $query->whereIn('Count_Steps', [3]);
                    })->where('Status', 'I')->get();
                } else {
                    $pendingApprovals = ApproveModel::whereHas('project', function ($query) use ($employee) {
                        $query->whereIn('Count_Steps', [0, 2, 6, 9])
                            ->where('Employee_Id', $employee->Id_Employee);
                    })->where('Status', 'I')->get();

                    // $pendingApprovalsCountForEmployee = $pendingApprovals->count();
                }

                $projectNames = $pendingApprovals->map(function ($approval) {
                    return $approval->project->Name_Project;
                });

            }
            $pendingApprovalsCount = $pendingApprovals->count();
            $projectIds = $pendingApprovals->pluck('Project_Id');

            if ($employee->IsAdmin === 'Y') {
                $recordHistories = RecordHistory::with('approvals.project')
                    ->whereHas('approvals', function ($query) {
                        $query->where('Status', '!=', 'Y');
                    })
                    ->orderBy('Id_Record_History', 'desc')
                    ->get();
            
                $statusNCount = RecordHistory::where('Status_Record', 'N')
                    ->whereHas('approvals', function ($query) {
                        $query->where('Status', 'N');
                    })
                    ->count();
            } else {
                $recordHistories = RecordHistory::whereHas('approvals', function ($query) {
                        $query->where('Status', '!=', 'Y');
                    })
                    ->whereHas('approvals.project', function ($query) use ($employee) {
                        $query->where('Employee_Id', $employee->Id_Employee);
                    })
                    ->with('approvals.project')
                    ->orderBy('Id_Record_History', 'desc')
                    ->get();
            
                $statusNCount = RecordHistory::where('Status_Record', 'N')
                    ->whereHas('approvals', function ($query) {
                        $query->where('Status', 'N');
                    })
                    ->whereHas('approvals.project', function ($query) use ($employee) {
                        $query->where('Employee_Id', $employee->Id_Employee);
                    })
                    ->count();
            }
    
            session([
                'employee' => $employee,
                'permissions' => $employee->permissions,
                'department' => $employee->department,
                'pendingApprovalsCount' => $pendingApprovalsCount,
                // 'pendingApprovalsCountForEmployee' => $pendingApprovalsCountForEmployee ?? 0,
                'recordHistories' => $recordHistories,
                'statusNCount' => $statusNCount,
                'projectIds' => $projectIds,
                'projectNames' => $projectNames, 
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