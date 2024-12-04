<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use App\Models\PremissionModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AccountController extends Controller
{
    public function employee()
    {
        $employees = EmployeeModel::all();
        return view('account.employee', compact('employees'));
    }

    public function showemployee($Id_Employee)
    {
        $employee = EmployeeModel::findOrFail($Id_Employee);
        return view('account.showEmployee', compact('employee'));
    }

    public function create()
    {
        return view('account.createEmployee');
    }

    public function store(Request $request)
    {
        EmployeeModel::create([
        'Id_Employee' => $request->Id_Employee,
        'Name_Employee' => $request->Name_Employee,
        'Email' => $request->Email,
        'Password' => $request->Password,
    ]);
        return redirect()->route('account.employee')->with('success', 'พนักงานถูกเพิ่มเรียบร้อยแล้ว');
    }
    
    // user_account //

    public function userAccount()
    {
        $employees = EmployeeModel::with('permissions')->get();
        $permissions = PremissionModel::with('employees')->get();
        
    
        Log::info('Employees and Permissions Data:', [
            'Employees' => $employees->toArray(),
            'Permissions' => $permissions->toArray()
        ]);
    
        return view('account.user', compact('permissions', 'employees'));
    }

    public function editUser($Id_Employee)
    {
        $employee = EmployeeModel::with('permissions')->findOrFail($Id_Employee);
        $allPermissions = PremissionModel::all();

        $assignedPermissionIds = $employee->permissions->pluck('Id_Permission')->toArray();
        log::info('Unassigned ', ['Permissions' => $assignedPermissionIds]);

        $unassignedPermissions = $allPermissions->filter(function ($permission) use ($assignedPermissionIds) {
            return !in_array($permission->Id_Permission, $assignedPermissionIds);
    });

        return view('account.editUser', compact('employee', 'unassignedPermissions'));
    }

}