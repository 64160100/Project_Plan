<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\ListProjectModel;
use App\Models\StrategicModel;
use App\Models\ApproveModel;
use App\Models\EmployeeModel;
use App\Models\Record1Model; 
use App\Models\Record2Model; 

class ListProjectController extends Controller
{
    public function project()
    {
        $strategics = StrategicModel::with('projects')->get();        
        return view('Project.listProject', compact('strategics'));
    }

    public function showCreateForm()
    {
        $strategics = StrategicModel::with('projects')->get();
        $employees = EmployeeModel::all(); // Fetch all employees
    
        return view('Project.createProject', compact('strategics', 'employees'));
    }

    public function createProject(Request $request)
    {
        $project = new ListProjectModel;
        $project->strategic_Id = $request->strategic_id;
        $project->Name_Project = $request->project_name;
        $project->Employee_Id = $request->employee_id; // Set the Employee_Id
        $project->save();

        Log::info('Project created with ID: '. $project->Id_Project);

        if ($project->Id_Project) {
            Log::info('Project created with ID: ' . $project->Id_Project);
            $approval = new ApproveModel;
            $approval->Status = 'I'; 
            $approval->Project_Id = $project->Id_Project; 
            $approval->save();
            Log::info('Approval record created for project ID: ' . $project->Id_Project);
        } else {
            Log::error('Failed to create project.');
        }

        return redirect()->route('project')->with('success', 'Project created successfully and approval record created.');
    }
    
    public function showAllApprovals(Request $request)
    {
        $approvals = collect();

        $employee = $request->session()->get('employee');
        $permissions = $request->session()->get('permissions');

        if ($employee) {
            Log::info('Employee ' . $employee->name . ' is requesting approval records.');

            if ($employee->IsManager === 'Y') {
                $approvals = ApproveModel::whereHas('project', function ($query) {
                    $query->where('Count_Steps', 1);
                })->get();
            } elseif ($employee->IsDirector === 'Y') {
                $approvals = ApproveModel::whereHas('project', function ($query) {
                    $query->where('Count_Steps', 2);
                })->get();
            } else {
                $approvals = ApproveModel::whereHas('project', function ($query) use ($employee) {
                    $query->where('Count_Steps', 0)
                        ->where('Employee_Id', $employee->Id_Employee);
                })->get();
            }
        } else {
            Log::warning('No employee data found in session.');
        }

        return view('requestApproval', compact('approvals'));
    }

    public function updateApprovalStatus(Request $request, $id, $status)
    {
        $approval = ApproveModel::find($id);

        if ($approval) {
            $approval->Status = $status;
            $approval->save();

            if ($status === 'Y') {
                $record1 = Record1Model::find($approval->Id_Approve);
                Log::info('Record1 updated for approval ID: '. $approval->Id_Approve);
                if ($record1) {
                    $record1->Comment1 = 'This is a comment for approval Y'; 
                    $record1->Approve_Id = $approval->Id_Approve; 
                    $record1->save();
                } else {
                    $record1 = new Record1Model();
                    $record1->Comment1 = 'This is a comment for approval Y'; 
                    $record1->Approve_Id = $approval->Id_Approve; 
                    $record1->save();
                }

                $project = ListProjectModel::find($approval->Project_Id);
                if ($project) {
                    if ($project->Count_Steps === 0) {
                        $project->Count_Steps = 1; 
                    } elseif ($project->Count_Steps === 1) {
                        $project->Count_Steps = 2; 
                    } elseif ($project->Count_Steps === 2) {
                        $project->Count_Steps = 3; // Update to step 3 for executives
                    }
                    $project->save();

                    if ($project->Count_Steps === 1 || $project->Count_Steps === 2) {
                        $approval->Status = 'I';
                        $approval->save();
                    }
                }
            } elseif ($status === 'N') {
                $record2 = Record2Model::find($approval->Id_Approve);
                Log::info('Record2 updated for approval ID: '. $approval->Id_Approve);
                $comment2 = $request->input('comment2'); // Get the comment from the request
                if ($record2) {
                    $record2->Comment2 = $comment2;
                    $record2->Approve_Id = $approval->Id_Approve; 
                    $record2->save();
                } else {
                    $record2 = new Record2Model();
                    $record2->Comment2 = $comment2;
                    $record2->Approve_Id = $approval->Id_Approve; 
                    $record2->save();
                }
            }
        }

        return redirect()->route('requestApproval');
    }

    public function proposeProject()
    {
        $projects = ListProjectModel::whereHas('approvals', function($query) {
            $query->where('Status', 'I');
        })->get();

        Log::info('Employee is proposing projects.'. $projects);

        return view('proposeProject', compact('projects'));
    }

    public function submitForApproval(Request $request, $id)
    {
        $project = ListProjectModel::find($id);
    
        if ($project) {
            // Check if the project is at the initial step
            if ($project->Count_Steps === 0) {
                // Find the existing approval record
                $approval = ApproveModel::where('Project_Id', $project->Id_Project)->first();
    
                if ($approval) {
                    // Update the approval status to 'Y' initially
                    $approval->Status = 'Y';
                    $approval->save();
    
                    // Update the project's Count_Steps to 1
                    $project->Count_Steps = 1;
                    $project->save();
    
                    // Change the approval status back to 'I'
                    $approval->Status = 'I';
                    $approval->save();
    
                    return redirect()->route('proposeProject')->with('success', 'Project submitted for approval.');
                } else {
                    return redirect()->route('proposeProject')->with('error', 'Approval record not found.');
                }
            } else {
                return redirect()->route('proposeProject')->with('error', 'Project is not at the initial step.');
            }
        }
    
        return redirect()->route('proposeProject')->with('error', 'Project not found.');
    }

    
}