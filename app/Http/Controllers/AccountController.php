<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AccountController extends Controller
{
    public function employee(Request $request)
    {
        $employee = $request->session()->get('employee');
    
        $employees = EmployeeModel::all();

        Log::info('Employees Data:', $employees->toArray());
    
        return view('account.employee', compact('employees', 'employee'));
    }

    public function showEmployees($Id_Employee)
    {
        $employee = EmployeeModel::findOrFail($Id_Employee);
    
        return view('account.viewEmployee', compact('employee'));
    }

    public function create()
    {
        return view('account.createEmployee');
    }

    public function store(Request $request)
    {
        EmployeeModel::create([
            'Id_Employee' => $request->Id_Employee,
            'Firstname_Employee' => $request->Firstname_Employee,
            'Lastname_Employee' => $request->Lastname_Employee,
            'Email' => $request->Email,
            'Password' => $request->Password,
        ]);
        return redirect()->route('account.employee')->with('success', 'พนักงานถูกเพิ่มเรียบร้อยแล้ว');
    } 

    public function editUser($Id_Employee)
    {
        $employee = EmployeeModel::findOrFail($Id_Employee);

        return view('account.editUser', compact('employee'));
    }

    public function updateUserPermissions(Request $request, $Id_Employee)
    {
        $employee = EmployeeModel::findOrFail($Id_Employee);

        $field = $request->input('field');
        $value = $request->input('value') === 'true' ? 'Y' : 'N';

        if (in_array($field, ['IsManager', 'IsDirector', 'IsFinance', 'IsResponsible', 'IsAdmin'])) {
            $employee->$field = $value;
            $employee->save();
        }

        return response()->json(['success' => true]);
    }
}