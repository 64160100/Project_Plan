<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListProjectModel;
use App\Models\DepartmentModel;
use App\Models\ProjectHasIndicatorsModel;
use App\Models\StrategicObjectivesModel;
use App\Models\KpiModel;
use App\Models\StrategyModel;
use App\Models\BudgetFormModel;
// use App\Models\ProjectHasBudgetSourceModel;
use App\Models\SubtopicBudgetHasBudgetFormModel;




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
            'department' => $department,
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

    //สรุปผล(modal)
    public function reportResult() {
        $strategicId = 1;
        $report = ListProjectModel::find(14);

        $strategicId = $report->Strategic_Id;
        $strategies = StrategyModel::with(['kpis', 'strategic'])
                    ->where('Strategic_Id', $strategicId)
                    ->get();

        $projectIndicator = ProjectHasIndicatorsModel::with('indicators')
                        ->where('Project_Id', 14)
                        ->get();

        $budgetProject = BudgetFormModel::where('Project_Id', 14)->first();

        $subBudgetProject = SubtopicBudgetHasBudgetFormModel::where('Subtopic_Budget_Id',1)
                        ->where('Budget_Form_Id', 1)
                        ->first();

        $data = [
            'report' => $report,
            'strategies' => $strategies,
            'projectIndicator' => $projectIndicator,
            'budgetProject' => $budgetProject,
            'subBudgetProject' => $subBudgetProject,
        ];
        return view('PlanDLC.reportResult', $data);
    }

    
}