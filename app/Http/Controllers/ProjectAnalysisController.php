<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListProjectModel;
use App\Models\DepartmentModel;

class ProjectAnalysisController extends Controller
{
    
    public function report(){
        $lastestProject = ListProjectModel::with(['employee.department', 'employee.position'])
                        ->orderBy('Id_Project', 'DESC')
                        ->paginate(5);
        // $department = DepartmentModel::orderBy('Id_Department', 'ASC');
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
        
        return view('ProjectAnalysis.report', $data);
    }

    public function checkBudget(){
        $budgetProject = ListProjectModel::orderBy('Id_Project', 'DESC')->paginate(5);
        return view('ProjectAnalysis.checkBudget', compact('budgetProject'));
    }

    public function editBudget(){
        $budgetProject = ListProjectModel::all();
        return view('ProjectAnalysis.editBudget',compact('budgetProject'));
    }

    public function allProject(){
        // $allProject = ListProjectModel::orderBy('Id_Project', 'DESC')->paginate(5);

        $allProject = ListProjectModel::with(['employee.department', 'employee.position'])
                        ->orderBy('Id_Project', 'DESC')
                        ->paginate(5);
        return view('ProjectAnalysis.allProject', compact('allProject'));        
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
        return view('ProjectAnalysis.showProjectDepartment', $data);
    }
}
