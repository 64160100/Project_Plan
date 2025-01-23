<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\ListProjectModel;
use App\Models\StrategicModel;
use App\Models\ApproveModel;
use App\Models\EmployeeModel;
use App\Models\RecordHistory;
use App\Models\Sdg;
use Carbon\Carbon;

class ListProjectController extends Controller
{
    public function project()
    {
        $strategics = StrategicModel::with('projects')->get();
        $strategics->each(function ($strategics) {
            $strategics->project_count = $strategics->projects->count();
        });

        return view('Project.listProject', compact('strategics')); 
    }

    public function showCreateForm($Strategic_Id)
    {
        $strategics = StrategicModel::with('strategies')->find($Strategic_Id); 
        $strategics = StrategicModel::with('projects')->find($Strategic_Id); 
        $strategies = $strategics->strategies;
        $employees = EmployeeModel::all();
        $sdgs = Sdg::all();
    
        return view('Project.createProject', compact('strategics', 'strategies', 'employees', 'sdgs'));
    }

    public function createProject(Request $request, $Strategic_Id)
    {
        $strategics = StrategicModel::with(['strategies', 'projects'])->findOrFail($Strategic_Id);
        $strategies = $strategics->strategies;

        $project = new ListProjectModel;
        $project->Strategic_Id = $request->Strategic_Id;
        $project->Name_Project = $request->Name_Project;
        $project->Employee_Id = $request->employee_id;
        $project->Objective_Project = $request->Objective_Project;
        $project->Indicators_Project = $request->Indicators_Project;
        $project->Target_Project = $request->Target_Project;
        $project->First_Time = $request->First_Time;
        $project->End_Time = $request->End_Time;
        $project->save();

        Log::info('Project created with ID: ' . $project->Id_Project);

        if ($project->Id_Project) {
            $approval = new ApproveModel;
            $approval->Status = 'I';
            $approval->Project_Id = $project->Id_Project;
            $approval->save();
            Log::info('Approval record created for project ID: ' . $project->Id_Project);
        } else {
            Log::error('Failed to create project.');
        }

        if ($request->has('sdgs')) {
            $selectedSDGs = $request->sdgs;
            $project->sdgs()->sync($selectedSDGs);
        }

        if ($request->has('Name_Sup_Project')) {
            $this->createSupProjects($project->Id_Project, $request->Name_Sup_Project);
        }

        if ($request->has('Objective_Project')) {
            $this->createObjProjects($project->Id_Project, $request->Objective_Project);
        }

        if ($request->has('Indicators_Project')) {
            $this->createIndicatorsProjects($project->Id_Project, $request->Indicators_Project);
        }

        if ($request->has('Target_Project')) {
            $this->createTargetProjects($project->Id_Project, $request->Target_Project);
        }

        return redirect()->route('project', ['Strategic_Id' => $Strategic_Id])->with('success', 'โครงการถูกสร้างเรียบร้อยแล้ว');
    }
    
    public function showAllApprovals(Request $request)
    {
        $approvals = collect();
    
        $employee = $request->session()->get('employee');
        $permissions = $request->session()->get('permissions');
    
        if ($employee) {
            if ($employee->IsAdmin === 'Y') {
                $approvals = ApproveModel::with(['project.employee.department', 'recordHistory'])
                    ->whereHas('project', function ($query) {
                        $query->whereNotIn('Count_Steps', [0, 2, 6, 9]);
                    })->get();
            } elseif ($employee->IsManager === 'Y') {
                $approvals = ApproveModel::with(['project.employee.department', 'recordHistory'])
                    ->whereHas('project', function ($query) {
                        $query->whereIn('Count_Steps', [4, 7]);
                    })->get();
            } elseif ($employee->IsDirector === 'Y') {
                $approvals = ApproveModel::with(['project.employee.department', 'recordHistory'])
                    ->whereHas('project', function ($query) {
                        $query->whereIn('Count_Steps', [1, 5, 8]);
                    })->get();
            } elseif ($employee->IsFinance === 'Y') {
                $approvals = ApproveModel::with(['project.employee.department', 'recordHistory'])
                    ->whereHas('project', function ($query) {
                        $query->whereIn('Count_Steps', [3]);
                    })->get();
            } else {
                $approvals = ApproveModel::with(['project.employee.department', 'recordHistory'])
                    ->whereHas('project', function ($query) use ($employee) {
                        $query->whereIn('Count_Steps', [0, 2, 6, 9])
                            ->where('Employee_Id', $employee->Id_Employee);
                    })->get();
            }
    
            foreach ($approvals as $approval) {
                $project = $approval->project;
                if ($project && $project->First_Time) {
                    $firstTime = \Carbon\Carbon::parse($project->First_Time);
                    $thaiMonths = [
                        1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน',
                        5 => 'พฤษภาคม', 6 => 'มิถุนายน', 7 => 'กรกฎาคม', 8 => 'สิงหาคม',
                        9 => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม'
                    ];
                    $day = $firstTime->format('j');
                    $month = $thaiMonths[(int)$firstTime->format('m')];
                    $year = $firstTime->year + 543;
                    $project->formattedFirstTime = "วันที่ $day $month พ.ศ $year";
                }
    
                foreach ($approval->recordHistory as $history) {
                    $timeRecord = $history->Time_Record;
                    $date = \Carbon\Carbon::parse($timeRecord);
                    $currentDate = \Carbon\Carbon::now()->setTimezone('Asia/Bangkok');
                    $history->daysSinceTimeRecord = max(ceil($date->diffInDays($currentDate, false)), 0);
    
                    if ($history->daysSinceTimeRecord == -0) {
                        $history->daysSinceTimeRecord = 0;
                    }
                }
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
    
            $comment = $status === 'Y' ? 'เห็นควรอนุมัติโครงการตามที่เสนอ' : $request->input('comment');
            $employee = $request->session()->get('employee');
            $permissions = $employee ? $employee->permissions : collect();
    
            $nameResponsible = $employee ? $employee->Firstname_Employee . ' ' . $employee->Lastname_Employee : 'Unknown';
            $permissionName = $permissions->first()->Name_Permission ?? 'Unknown';
    
            RecordHistory::create([
                'Approve_Id' => $approval->Id_Approve,
                'Approve_Project_Id' => $approval->Project_Id,
                'Comment' => $comment,
                'Time_Record' => Carbon::now('Asia/Bangkok'),
                'Status_Record' => $approval->Status,
                'Name_Record' => $nameResponsible,
                'Permission_Record' => $permissionName,
            ]);
    
            if ($status === 'Y') {
                $project = ListProjectModel::find($approval->Project_Id);
                if ($project) {
                    switch ($project->Count_Steps) {
                        case 0:
                            $project->Count_Steps = 1;
                            break;
                        case 1:
                            $project->Count_Steps = 2;
                            break;
                        case 2:
                            $project->Count_Steps = 3;
                            break;
                        case 3:
                            $project->Count_Steps = 4;
                            break;
                        case 4:
                            $project->Count_Steps = 5;
                            break;
                        case 5:
                            $project->Count_Steps = 6;
                            break;
                        case 6:
                            $project->Count_Steps = 7;
                            break;
                        case 7:
                            $project->Count_Steps = 8;
                            break;
                        case 8:
                            $project->Count_Steps = 9;
                            break;
                        default:
                            break;
                    }
                    $project->save();
    
                    if ($project->Count_Steps <= 9) {
                        $approval->Status = 'I';
                        $approval->save();
                    }
                }
            }
        }
    
        return redirect()->route('requestApproval');
    }

    public function proposeProject(Request $request)
    {
        $employee = $request->session()->get('employee');
        $permissions = $request->session()->get('permissions');
    
        if ($employee) {
            $projectsQuery = ListProjectModel::query();
    
            if ($employee->IsAdmin !== 'Y') {
                $projectsQuery->where('Employee_Id', $employee->Id_Employee);
            }
    
            $projects = $projectsQuery->whereHas('approvals', function($query) {
                    $query->whereIn('Status', ['I', 'N']);
                })
                ->with(['approvals.recordHistory', 'employee.department', 'employee'])
                ->get();
    
            foreach ($projects as $project) {
                $employeeData = $project->employee;
                $project->employeeName = $employeeData ? $employeeData->Firstname_Employee . ' ' . $employeeData->Lastname_Employee : '-';
                $project->departmentName = $employeeData->department->Name_Department ?? 'No Department';
    
                if ($project->First_Time) {
                    $date = new \DateTime($project->First_Time);
                    $thaiMonths = [
                        1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน',
                        5 => 'พฤษภาคม', 6 => 'มิถุนายน', 7 => 'กรกฎาคม', 8 => 'สิงหาคม',
                        9 => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม'
                    ];
                    $day = $date->format('d');
                    $month = $thaiMonths[(int)$date->format('m')];
                    $year = (int)$date->format('Y') + 543; 
                    $project->formattedFirstTime = "วันที่ $day $month พ.ศ $year";
                } else {
                    $project->formattedFirstTime = '-';
                }
    
                foreach ($project->approvals as $approval) {
                    foreach ($approval->recordHistory as $history) {
                        $timeRecord = $history->Time_Record;
                        $date = \Carbon\Carbon::parse($timeRecord);
                        $thaiMonths = [
                            1 => 'ม.ค', 2 => 'ก.พ', 3 => 'มี.ค', 4 => 'เม.ย',
                            5 => 'พ.ค', 6 => 'มิ.ย', 7 => 'ก.ค', 8 => 'ส.ค',
                            9 => 'ก.ย', 10 => 'ต.ค', 11 => 'พ.ย', 12 => 'ธ.ค'
                        ];
                        $history->formattedTimeRecord = $date->format('j') . ' ' . $thaiMonths[(int)$date->format('m')] . ' ' . ($date->year + 543);
                    }
                }
    
                foreach ($project->approvals as $approval) {
                    foreach ($approval->recordHistory as $history) {
                        $timeRecord = $history->Time_Record;
                        $date = \Carbon\Carbon::parse($timeRecord);
                        $thaiMonths = [
                            1 => 'ม.ค', 2 => 'ก.พ', 3 => 'มี.ค', 4 => 'เม.ย',
                            5 => 'พ.ค', 6 => 'มิ.ย', 7 => 'ก.ค', 8 => 'ส.ค',
                            9 => 'ก.ย', 10 => 'ต.ค', 11 => 'พ.ย', 12 => 'ธ.ค'
                        ];
                        $day = $date->format('j');
                        $month = $thaiMonths[(int)$date->format('m')];
                        $year = $date->year + 543;
                        $time = $date->format('H:i น.');
                        $history->formattedDateTime = "$day $month $year $time";
                    }
                }
    
                if ($project->Count_Steps === 9) {
                    $project->status = 'สิ้นสุด';
                    $project->buttonText = 'สิ้นสุดโครงการ';
                }
            }
    
            return view('proposeProject', compact('projects'));
        } else {
            return redirect()->back()->with('error', 'You are not authorized to view these projects.');
        }
    }
    
    public function submitForApproval(Request $request, $id)
    {
        $project = ListProjectModel::find($id);
    
        if ($project) {
            $approval = ApproveModel::where('Project_Id', $project->Id_Project)->first();
    
            if ($approval) {
                $approval->Status = 'Y';
                $approval->save();
    
                $employee = $request->session()->get('employee');
                $permissions = $employee ? $employee->permissions : collect();
    
                $nameResponsible = $employee ? $employee->Firstname_Employee . ' ' . $employee->Lastname_Employee : 'Unknown';
                $permissionName = $permissions->first()->Name_Permission ?? 'Unknown';
    
                RecordHistory::create([
                    'Approve_Id' => $approval->Id_Approve,
                    'Approve_Project_Id' => $approval->Project_Id,
                    'Comment' => 'เสนอโครงการเพื่อขออนุมัติ',
                    'Time_Record' => Carbon::now('Asia/Bangkok'),
                    'Status_Record' => $approval->Status,
                    'Name_Record' => $nameResponsible,
                    'Permission_Record' => $permissionName,
                ]);
    
                switch ($project->Count_Steps) {
                    case 0:
                        $project->Count_Steps = 1;
                        break;
                    case 1:
                        $project->Count_Steps = 2;
                        break;
                    case 2:
                        $project->Count_Steps = 3;
                        break;
                    case 3:
                        $project->Count_Steps = 4;
                        break;
                    case 4:
                        $project->Count_Steps = 5;
                        break;
                    case 5:
                        $project->Count_Steps = 6;
                        break;
                    case 6:
                        $project->Count_Steps = 7;
                        break;
                    case 7:
                        $project->Count_Steps = 8;
                        break;
                    case 8:
                        $project->Count_Steps = 9;
                        break;
                    case 9:
                        $project->Count_Steps = 10;
                        $approval->Status = 'Y';
                        $approval->save();
                        break;
                    default:
                        break;
                }
    
                $project->save();
    
                if ($project->Count_Steps <= 9) {
                    $approval->Status = 'I';
                    $approval->save();
                }
    
                return redirect()->route('proposeProject')->with('success', 'Project submitted for approval.');
            } else {
                return redirect()->route('proposeProject')->with('error', 'Approval record not found.');
            }
        }
    
        return redirect()->route('proposeProject')->with('error', 'Project not found.');
    }

}