<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListProjectModel;
use App\Models\StorageFileModel;
use Illuminate\Support\Facades\Log;

class PlanDLCController extends Controller
{
    public function report()
    {
        $lastestProject = ListProjectModel::leftJoin('Employee', 'Project.Employee_Id', '=', 'Employee.Id_Employee')
            ->select('Project.*', 'Employee.Department_Name', 'Employee.Position_Name', 'Employee.Firstname', 'Employee.Lastname')
            ->orderBy('Id_Project', 'DESC')
            ->get();
        
        Log::info('Total projects found for report: ' . $lastestProject->count());
        
        $data = [
            'lastestProject' => $lastestProject,
            'totalProjects' => $lastestProject->count(),
            'lastUpdated' => now()->format('d/m/Y H:i')
        ];
    
        return view('PlanDLC.report', $data);
    }

    public function checkBudget(){
        $projects = ListProjectModel::where('Status_Budget', 'Y')
            ->with(['projectBudgetSources.budgetSource', 'projectBudgetSources.budgetSourceTotal'])
            ->orderBy('Id_Project', 'DESC')
            ->paginate(5);
        
        $budgetProject = $projects->getCollection()->map(function($project) {
            $totalProjectBudget = 0;
            $budgetTypes = [];
            
            if ($project->projectBudgetSources) {
                foreach ($project->projectBudgetSources as $projectBudget) {
                    if ($projectBudget->budgetSourceTotal) {
                        $totalProjectBudget += $projectBudget->budgetSourceTotal->Amount_Total;
                    }
                    
                    if ($projectBudget->budgetSource) {
                        $budgetTypes[] = $projectBudget->budgetSource->Name_Budget_Source;
                    }
                }
            }
            
            $project->totalBudget = $totalProjectBudget;
            $project->budgetTypes = array_unique($budgetTypes);
            
            return $project;
        });
        
        $projects->setCollection($budgetProject);
                
        return view('PlanDLC.checkBudget', compact('projects'));
    }

    public function editBudget(){
        $budgetProject = ListProjectModel::all();
        return view('PlanDLC.editBudget',compact('budgetProject'));
    }

    public function allProject()
    {
        $allProject = ListProjectModel::with(['employee', 'storageFiles' => function($query) {
                $query->orderBy('Storage_File.Id_Storage_File', 'desc');
            }])
            ->select('Project.*')
            ->orderBy('Id_Project', 'desc')
            ->paginate(10);
        
        // Process each project to get the latest file info and document count
        $allProject->getCollection()->transform(function($project) {
            // Get the count of storage files for this project
            $project->document_count = $project->storageFiles->count();
            
            // Get latest file if it exists
            if ($project->storageFiles->isNotEmpty()) {
                $latestFile = $project->storageFiles->first();
                $project->latest_file_date = \Carbon\Carbon::parse($latestFile->Created_At ?? now())->format('d/m/Y H:i');
                $project->latest_file_name = $latestFile->Name_Storage_File;
            } else {
                $project->latest_file_date = 'ยังไม่มีเอกสาร';
                $project->latest_file_name = '-';
            }
            
            return $project;
        });
        
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