<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListProjectModel;
use Illuminate\Support\Facades\Log;

class PlanDLCController extends Controller
{
    public function report()
    {
        $lastestProject = ListProjectModel::join('Employee', 'Project.Employee_Id', '=', 'Employee.Id_Employee')
                            ->select('Project.*', 'Employee.Department_Name', 'Employee.Position_Name')
                            ->orderBy('Id_Project', 'DESC')
                            ->paginate(5);

        $data = [
            'lastestProject' => $lastestProject
        ];

        return view('PlanDLC.report', $data);
    }

    public function checkBudget(){
        $budgetProject = ListProjectModel::orderBy('Id_Project', 'DESC')->paginate(5);
        return view('PlanDLC.checkBudget', compact('budgetProject'));
    }

    public function editBudget(){
        $budgetProject = ListProjectModel::all();
        return view('PlanDLC.editBudget',compact('budgetProject'));
    }

    public function allProject(){
        $allProject = ListProjectModel::join('Employee', 'Project.Employee_Id', '=', 'Employee.Id_Employee')
                        ->select('Project.*', 'Employee.Department_Name', 'Employee.Position_Name')
                        ->orderBy('Id_Project', 'DESC')
                        ->paginate(5);

        $data = [
            'allProject' => $allProject
        ];

        return view('PlanDLC.allProject', $data);        
    }

    public function showProjectDepartment($Id_Department){
        $department = DepartmentModel::findOrFail($Id_Department);
        $projects = ListProjectModel::join('Employee', 'Project.Employee_Id', '=', 'Employee.Id_Employee')
                ->where('Employee.Department_Id', $Id_Department)
                ->with(['employee.department', 'employee.position'])
                ->get();

        $data = [      
            'projects' => $projects,
            'department' => $department
        ];
        return view('PlanDLC.showProjectDepartment', $data);
    }
}