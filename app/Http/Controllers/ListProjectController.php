<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\ListProjectModel;
use App\Models\StrategicModel;
use App\Models\StrategyModel;
use App\Models\ApproveModel;
use App\Models\EmployeeModel;
use App\Models\RecordHistory;
use App\Models\SDGsModel;
use App\Models\BudgetModel;
use App\Models\IntegrationModel;
use App\Models\SupProjectModel;
use App\Models\PlatformModel;
use App\Models\ProgramModel;
use App\Models\Kpi_ProgramModel;
use App\Models\ProjectHasSDGModel;
use App\Models\ProjectHasIntegrationCategoryModel;
use App\Models\TargetModel;
use App\Models\TargetDetailsModel;
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

    public function viewProject($projectId)
    {
        $project = ListProjectModel::with([
            'subProjects',
            'platforms.programs.kpis',
            'sdgs',
            'projectHasIntegrationCategories',
            'targets.targetDetails'
        ])->findOrFail($projectId);

        $strategics = StrategicModel::with(['strategies'])->findOrFail($project->Strategic_Id);
        $strategies = $strategics->strategies;
        $employees = EmployeeModel::all();

        Log::info('Project data: ', $project->toArray());

        return view('Project.viewProject', compact('project', 'strategics', 'strategies', 'employees'));
    }

    public function showCreateForm($Strategic_Id)
    {
        $strategics = StrategicModel::with(['strategies', 'projects'])->find($Strategic_Id);
        $strategies = $strategics->strategies;
        $projects = $strategics->projects; 
        $employees = EmployeeModel::all();
        $sdgs = SDGsModel::all();
        $nameStrategicPlan = $strategics->Name_Strategic_Plan;
        $integrationCategories = IntegrationModel::orderBy('Id_Integration_Category', 'asc')->get();
        $months = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
    
        return view('Project.createProject', compact('strategics', 'strategies', 'projects', 'employees', 'sdgs', 'nameStrategicPlan', 'integrationCategories', 'months'));
    }

    public function createProject(Request $request, $Strategic_Id)
    {
        $strategics = StrategicModel::with(['strategies', 'projects'])->findOrFail($Strategic_Id);
        $strategies = $strategics->strategies;

        $project = new ListProjectModel;
        $project->Strategic_Id = $Strategic_Id;
        $project->Name_Strategy = $request->Name_Strategy ?? null;
        $project->Name_Project = $request->Name_Project;
        $project->Employee_Id = $request->employee_id ?? null;
        $project->Objective_Project = $request->Objective_Project ?? null;
        $project->Principles_Reasons = $request->Principles_Reasons ?? null;
        $project->First_Time = $request->First_Time ?? null;
        $project->End_Time = $request->End_Time ?? null;
        $project->save();
        
        if ($project->Id_Project) {
            $approval = new ApproveModel;
            $approval->Status = 'I';
            $approval->Project_Id = $project->Id_Project;
            $approval->save();
            Log::info('Approval record created for project ID: ' . $project->Id_Project);

            $budget = new BudgetModel;
            $budget->Status_Budget = $request->Status_Budget ?? null;
            $budget->Project_Id = $project->Id_Project;
            $budget->save();
            Log::info('Budget record created for project ID: ' . $project->Id_Project);

            if ($request->has('Name_Sup_Project')) {
                foreach ($request->Name_Sup_Project as $supProjectName) {
                    $supProject = new SupProjectModel;
                    $supProject->Project_Id = $project->Id_Project;
                    $supProject->Name_Sup_Project = $supProjectName;
                    $supProject->save();
                    Log::info('Sub-project created with name: ' . $supProjectName);
                }
            }

            if ($request->has('platforms')) {
                foreach ($request->platforms as $platformData) {
                    if (!empty($platformData['name'])) {
                        $platform = new PlatformModel;
                        $platform->Name_Platform = $platformData['name'];
                        $platform->Project_Id = $project->Id_Project;
                        $platform->save();
                        Log::info('Platform created with name: ' . $platformData['name']);

                        if (!empty($platformData['program'])) {
                            $program = new ProgramModel;
                            $program->Name_Program = $platformData['program'];
                            $program->Platform_Id = $platform->Id_Platform;
                            $program->save();
                            Log::info('Program created with name: ' . $platformData['program']);

                            if (isset($platformData['kpis']) && is_array($platformData['kpis'])) {
                                foreach ($platformData['kpis'] as $kpiName) {
                                    if (!empty($kpiName)) {
                                        $kpi = new Kpi_ProgramModel;
                                        $kpi->Name_KPI = $kpiName;
                                        $kpi->Program_Id = $program->Id_Program;
                                        $kpi->save();
                                        Log::info('KPI created with name: ' . $kpiName);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if ($request->has('sdgs')) {
                foreach ($request->sdgs as $sdgId) {
                    $projectSdg = new ProjectHasSDGModel;
                    $projectSdg->Project_Id = $project->Id_Project;
                    $projectSdg->SDGs_Id = $sdgId;    
                    $projectSdg->save();
                    Log::info('Project SDG created with SDG ID: ' . $sdgId);
                }
            }

            if ($request->has('integrationCategories')) {
                foreach ($request->integrationCategories as $categoryId => $categoryData) {
                    if (isset($categoryData['checked'])) {
                        $additionalInfo = $categoryData['details'] ?? null;
                        $projectIntegration = new ProjectHasIntegrationCategoryModel;
                        $projectIntegration->Project_Id = $project->Id_Project;
                        $projectIntegration->Integration_Category_Id = $categoryId;
                        $projectIntegration->Integration_Details = $additionalInfo;
                        $projectIntegration->save();
                        Log::info('Project Integration Category created with category ID: ' . $categoryId);
                    }
                }
            }

            if ($request->has('target_group')) {
                log::info('Target group: ', $request->target_group);
                $latestTargetId = null;
            
                foreach ($request->target_group as $index => $targetGroupName) {
                    $target = new TargetModel;
                    $target->Name_Target = $targetGroupName;
                    $target->Quantity_Target = $request->target_count[$index];
                    $target->Unit_Target = $request->target_unit[$index];
                    $target->Project_Id = $project->Id_Project;
                    $target->save();
                    Log::info('Target created with name: ' . $targetGroupName);
            
                    $latestTargetId = $target->Id_Target_Project;
                }
            
                if ($latestTargetId && $request->has('target_details')) {
                    $targetDetails = new TargetDetailsModel;
                    $targetDetails->Details_Target = $request->target_details; 
                    $targetDetails->Target_Project_Id = $latestTargetId;
                    $targetDetails->save();
                    Log::info('Target details created for target ID: ' . $latestTargetId);
                }
            }
        } else {
            Log::error('Failed to create project.');
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
                        $query->whereIn('Count_Steps', [1, 5, 8, 11]);
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

            $employee = $request->session()->get('employee');
            $permissions = $employee ? $employee->permissions : collect();
            $nameResponsible = $employee ? $employee->Firstname_Employee . ' ' . $employee->Lastname_Employee : 'Unknown';
            $permissionName = $permissions->first()->Name_Permission ?? 'Unknown';

            $project = ListProjectModel::find($approval->Project_Id);
            
            if ($status === 'Y') {
                switch ($project->Count_Steps) {
                    case 0:
                        $comment = "อนุมัติการเริ่มต้นโครงการตามที่เสนอ";
                        break;
                    case 1:
                        $comment = "เห็นชอบการดำเนินโครงการในขั้นต้น";
                        break;
                    case 2:
                        $comment = "อนุมัติการจัดสรรงบประมาณตามที่เสนอ";
                        break;
                    case 3:
                        $comment = "ยืนยันความเหมาะสมของงบประมาณโครงการ";
                        break;
                    case 4:
                        $comment = "เห็นชอบการดำเนินงานในส่วนของฝ่าย";
                        break;
                    case 5:
                        $comment = "อนุมัติการดำเนินโครงการอย่างเป็นทางการ";
                        break;
                    case 6:
                        $comment = "รับทราบรายงานความคืบหน้าการดำเนินโครงการ";
                        break;
                    case 7:
                        $comment = "รับรองผลการดำเนินงานระดับฝ่าย";
                        break;
                    case 8:
                        $comment = "รับรองผลการดำเนินโครงการเสร็จสมบูรณ์";
                        break;
                    case 11:
                        $comment = "อนุมัติการดำเนินการแก้ไขความล่าช้าของโครงการ";
                        break;
                    default:
                        $comment = "เห็นชอบการดำเนินการตามที่เสนอ";
                }
            } else {
                $comment = $request->input('comment');
                if (empty($comment)) {
                    $comment = "ไม่อนุมัติโครงการ กรุณาแก้ไขตามคำแนะนำ";
                }
            }

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
                        case 11:
                            $project->Count_Steps = 2; 
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

        return redirect()->route('requestApproval')->with('success', 'บันทึกการพิจารณาเรียบร้อยแล้ว');
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
                ->with(['approvals.recordHistory', 'employee.department', 'employee', 'budgets'])
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
    
                $project->budgetStatus = $project->budgets->first()->Status_Budget ?? 'N/A';
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

                if ($project->Count_Steps == 2) {
                    $budget = BudgetModel::where('Project_Id', $project->Id_Project)->first();
                    if ($budget && $budget->Status_Budget == 'N') {
                        $project->Count_Steps = 4; 
                    } else {
                        $project->Count_Steps = 3; 
                    }
                } else {
                    switch ($project->Count_Steps) {
                        case 0:
                            $project->Count_Steps = 1;
                            break;
                        case 1:
                            $project->Count_Steps = 2;
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
                            if (\Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($project->End_Time))) {
                                $project->Count_Steps = 11; 
                            } else {
                                $project->Count_Steps = 7;
                            }
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
                        case 11:
                            $project->Count_Steps = 2;
                            break;
                        default:
                            break;
                    }
                }

                $project->save();

                if ($project->Count_Steps <= 11) {
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

    public function edit($id, Request $request)
    {
        $project = ListProjectModel::with('supProjects')->findOrFail($id);
        $strategies = StrategyModel::all();
        $sdgs = SustainableDevelopmentGoalsModel::all();
        return view('Project.editProject', compact('project', 'strategies', 'sdgs'));
    }
    
    public function update(Request $request, $id)
    {   
        Log::info($request->all()); 
    
        $request->validate([
            'Strategic_Id' => 'required|integer',
            'Name_Project' => 'required|string|max:255',
            'Count_Steps' => 'nullable|integer',
            'Employee_Id' => 'required|integer',
        ]);
    
        $project = ListProjectModel::findOrFail($id);
    
        $project->update($request->only([
            'Strategic_Id',
            'Name_Project',
            'Count_Steps',
            'Employee_Id'
        ]));
    
        if (in_array($project->Count_Steps, [3, 4, 5])) {
            $project->Count_Steps = 2;
            $project->save();
        }
    
        if (in_array($project->Count_Steps, [7, 8])) {
            $project->Count_Steps = 6;
            $project->save();
        }
    
        $approve = ApproveModel::where('Project_Id', $project->Id_Project)->first();
        if ($approve) {
            $approve->Status = 'I';
            $approve->save();
        }
    
        Log::info('Project after update: ', $project->toArray());
    
        return redirect()->route('proposeProject')->with('success', 'Project updated successfully');
    }

}