<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use App\Models\PermissionModel;
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
        'FirstName_Employee' => $request->FirstName_Employee,
        'Lastname_Employee' => $request->Lastname_Employee,
        'Email' => $request->Email,
        'Password' => $request->Password,
    ]);
        return redirect()->route('account.employee')->with('success', 'พนักงานถูกเพิ่มเรียบร้อยแล้ว');
    }
    
    // user_account //

    public function userAccount()
    {
        $employees = EmployeeModel::with('permissions')->get();
        $permissions = PermissionModel::with('employees')->get();
        
    
        Log::info('Employees and Permissions Data:', [
            'Employees' => $employees->toArray(),
            'Permissions' => $permissions->toArray()
        ]);
    
        return view('account.user', compact('permissions', 'employees'));
    }

    public function editUser($Id_Employee)
    {
        $employee = EmployeeModel::with('permissions')->findOrFail($Id_Employee);
        $allPermissions = PermissionModel::all();

        $assignedPermissionIds = $employee->permissions->pluck('Id_Permission')->toArray();

        $unassignedPermissions = $allPermissions->filter(function ($permission) use ($assignedPermissionIds) {
            return !in_array($permission->Id_Permission, $assignedPermissionIds);
        });

        return view('account.editUser', compact('employee', 'unassignedPermissions'));
    }

    public function updateUserPermissions(Request $request, $Id_Employee)
    {
        Log::info('Request Data', $request->all());

        if (!$request->has('Id_Permission') || !$request->Id_Permission) {
            return redirect()->back()->with('error', 'กรุณาเลือกสิทธิ์เพิ่มเติม');
        }

        $employee = EmployeeModel::findOrFail($request->Id_Employee);
        $permission = PermissionModel::findOrFail($request->Id_Permission);

        if ($employee->permissions()->where('Premission_Id_Permission', $permission->Id_Permission)->exists()) {
            return redirect()->back()->with('error', 'พนักงานมีสิทธิ์นี้อยู่แล้ว');
        }

        $employee->permissions()->attach($permission->Id_Permission);

        return redirect()->back()->with('success', 'สิทธิ์ถูกมอบเรียบร้อยแล้ว');
    }

    public function removePermission($Id_Employee, $Id_Permission)
    {
        $employee = EmployeeModel::findOrFail($Id_Employee);
        $permission = PermissionModel::findOrFail($Id_Permission);

        if ($employee->permissions()->where('Premission_Id_Permission', $permission->Id_Permission)->exists()) {
            $employee->permissions()->detach($permission->Id_Permission);
            return redirect()->back()->with('success', 'สิทธิ์ถูกลบเรียบร้อยแล้ว');
        }

        return redirect()->back()->with('error', 'ไม่สามารถลบสิทธิ์ได้');
    }

}