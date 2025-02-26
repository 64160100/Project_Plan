<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use App\Models\ApproveModel;
use App\Models\RecordHistory;
use App\Models\ManagementPositionModel;
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
        $credentials = $request->only('username', 'password');

        $apiResponse = self::CheckUser($credentials['username']);
        
        $employee = EmployeeModel::where('username', $credentials['username'])->first();

        if (empty($apiResponse) || !$employee) {
            return redirect()->back()->withErrors(['error' => 'ไม่พบบัญชีผู้ใช้ในระบบ กรุณาตรวจสอบชื่อผู้ใช้ของคุณ']);
        }

        if ($employee && md5($credentials['password']) === $employee->Password) {
            Log::info('User logged in from database:', ['employee' => $employee]);

            try {
                $token = JWTAuth::fromUser($employee);
            } catch (JWTException $e) {
                Log::info('Error creating token: ', ['error' => $e->getMessage()]);
                return redirect()->back()->withErrors(['error' => 'Could not create token. Please try again.']);
            }

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
                        $query->whereIn('Count_Steps', [1, 5, 8]);
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

                    $pendingApprovalsCountForEmployee = $pendingApprovals->count();
                }
            }
            $pendingApprovalsCount = $pendingApprovals->count();

            if ($employee->IsAdmin === 'Y') {
                $recordHistories = RecordHistory::with('approvals.project')
                    ->whereHas('approvals', function ($query) {
                        $query->where('Status', '!=', 'Y');
                    })
                    ->orderBy('Id_Record_History', 'desc')
                    ->get();
            
                $statusNCount = RecordHistory::whereHas('approvals', function ($query) {
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
            
                $statusNCount = RecordHistory::whereHas('approvals', function ($query) {
                        $query->where('Status', 'N');
                    })
                    ->whereHas('approvals.project', function ($query) use ($employee) {
                        $query->where('Employee_Id', $employee->Id_Employee);
                    })
                    ->count();
            }

            session([
                'employee' => $employee,
                'pendingApprovalsCountForEmployee' => $pendingApprovalsCountForEmployee ?? 0,  
                'pendingApprovalsCount' => $pendingApprovalsCount,
                'recordHistories' => $recordHistories,  
                'statusNCount' => $statusNCount,
            ]);

            return redirect()->route('dashboard')->with('token', $token);
        }

        return redirect()->back()->withErrors(['error' => 'รหัสผ่านไม่ถูกต้อง กรุณาตรวจสอบรหัสผ่านของคุณ']);
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

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}