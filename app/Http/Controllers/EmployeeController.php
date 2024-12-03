<?php

namespace App\Http\Controllers;

use App\Models\MyModel;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function employee()
    {
        $employees = MyModel::all();
        return view('employee.employee', compact('employees'));
    }

    public function showemployee($Id_Employee)
    {
        $employee = MyModel::findOrFail($Id_Employee);
        return view('employee.showemployee', compact('employee'));
    }

    public function create()
    {
        return view('employee.createemployee');
    }

    public function store(Request $request)
    {
    MyModel::create([
        'Id_Employee' => $request->Id_Employee,
        'Name_Employee' => $request->Name_Employee,
        'Email' => $request->Email,
        'Password' => $request->Password,
    ]);

    return redirect()->route('employees.employee')->with('success', 'พนักงานถูกเพิ่มเรียบร้อยแล้ว');
}
}