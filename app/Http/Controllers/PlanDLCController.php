<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListProjectModel;
use App\Models\DepartmentModel;
use Illuminate\Support\Facades\Log;

class PlanDLCController extends Controller
{
    public function report(){
        $lastestProject = ListProjectModel::with(['employee.department', 'employee.position'])
                        ->orderBy('Id_Project', 'DESC')
                        ->paginate(5);
        $department = DepartmentModel::all();

        foreach ($department as $departments) {
            $departments->projects_count = ListProjectModel::join('Employee', 'Project.Employee_Id', '=', 'Employee.Id_Employee')
                ->where('Employee.Department_Id', $departments->Id_Department)
                ->count();
        }
        
    
        $data = [      
            'lastestProject' => $lastestProject,
            'department' => $department
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

        $allProject = ListProjectModel::with(['employee.department', 'employee.position'])
                        ->orderBy('Id_Project', 'DESC')
                        ->paginate(5);

        return view('PlanDLC.allProject', compact('allProject'));        
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