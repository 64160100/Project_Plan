<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\ListProjectModel;
use App\Models\StrategicModel;
use App\Models\StrategyModel;
use App\Models\FiscalYearQuarterModel;
use App\Models\ApproveModel;
use App\Models\RecordHistory;
use Carbon\Carbon;

class RequestApprovalController extends Controller
{
    public function showAllApprovals(Request $request)
    {
        $approvals = collect();
        $countApproved = 0;
        $employee = $request->session()->get('employee');
        $permissions = $request->session()->get('permissions');
    
        if (!$employee) {
            Log::warning('No employee data found in session.');
            return redirect()->route('home')->with('error', 'No employee data found in session.');
        }
    
        $request->session()->forget('pendingApprovalsCount');
        $pendingApprovals = collect();
    
        if ($employee->IsDirector === 'Y') {
            $pendingApprovals = ApproveModel::whereHas('project', function ($query) {
                $query->whereIn('Count_Steps', [1, 5, 8]);
            })->where('Status', 'I')->get();
        }
    
        $pendingApprovalsCount = $pendingApprovals->count();
        $request->session()->put('pendingApprovalsCount', $pendingApprovalsCount);
    
        $pendingProjectIds = $pendingApprovals->pluck('Project_Id');
        $request->session()->put('pendingProjectIds', $pendingProjectIds);
    
        if ($employee) {
            if ($employee->IsAdmin === 'Y') {
                $approvals = ApproveModel::with(['project.employee', 'recordHistory'])
                    ->whereHas('project', function ($query) {
                        $query->whereNotIn('Count_Steps', [0, 2, 6, 9]);
                    })->get();
            } elseif ($employee->IsManager === 'Y') {
                $approvals = ApproveModel::with(['project.employee', 'recordHistory'])
                    ->whereHas('project', function ($query) {
                        $query->whereIn('Count_Steps', [4, 7]);
                    })->get();
            } elseif ($employee->IsDirector === 'Y') {
                $approvals = ApproveModel::with(['project.employee', 'recordHistory'])
                    ->whereHas('project', function ($query) {
                        $query->whereIn('Count_Steps', [5, 8, 11]);
                    })->get();
            } elseif ($employee->IsFinance === 'Y') {
                $approvals = ApproveModel::with(['project.employee', 'recordHistory'])
                    ->whereHas('project', function ($query) {
                        $query->whereIn('Count_Steps', [3]);
                    })->get();
            } else {
                $approvals = ApproveModel::with(['project.employee', 'recordHistory'])
                    ->whereHas('project', function ($query) use ($employee) {
                        $query->whereIn('Count_Steps', [0, 2, 6, 9])
                            ->where('Employee_Id', $employee->Id_Employee);
                    })->get();
            }
    
            $countApproved = $approvals->where('Status', 'I')->count();
            $request->session()->put('countApproved', $countApproved);
    
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
    
            if ($employee->IsDirector === 'Y') {
                $strategics = StrategicModel::with(['projects' => function($query) {
                    $query->where('Count_Steps', 1)->orderBy('Name_Strategy');
                }, 'projects.approvals', 'projects.employee', 'quarterProjects.quarterProject'])->get();
    
                $quarterProjectsData = $strategics->pluck('quarterProjects')->flatten()->pluck('quarterProject')->unique(function ($item) {
                    return $item['Fiscal_Year'] . '-' . $item['Quarter'];
                });
    
                $logData = [];
                $quarters = $quarterProjectsData->sortBy('Fiscal_Year');
    
                foreach ($quarters as $quarter) {
                    $fiscalYear = $quarter->Fiscal_Year;
                    $quarterName = $quarter->Quarter;
    
                    $strategicsForQuarter = StrategicModel::whereHas('quarterProjects', function ($query) use ($quarter) {
                        $query->where('Quarter_Project_Id', $quarter->Id_Quarter_Project);
                    })->with(['strategies.projects' => function($query) {
                        $query->where('Count_Steps', 1);
                    }, 'strategies.projects.approvals'])->get();
    
                    $quarterLogData = [];
    
                    foreach ($strategicsForQuarter as $strategic) {
                        $strategicName = $strategic->Name_Strategic_Plan;
    
                        if ($strategic->strategies->isEmpty()) {
                            continue; // Skip if no strategies
                        }
    
                        $allStrategies = StrategyModel::where('Strategic_Id', $strategic->Id_Strategic)->get();
                        $createdStrategies = $strategic->strategies->pluck('Id_Strategy')->toArray();
                        $missingStrategies = $allStrategies->filter(function ($strategy) use ($createdStrategies) {
                            return !in_array($strategy->Id_Strategy, $createdStrategies);
                        });
    
                        foreach ($missingStrategies as $missingStrategy) {
                            $projectsWithStrategy = ListProjectModel::where('Strategy_Id', $missingStrategy->Id_Strategy)->exists();
                            if (!$projectsWithStrategy) {
                                continue; // Skip if no projects with this strategy
                            }
                        }
    
                        foreach ($strategic->strategies as $strategy) {
                            $strategyName = $strategy->Name_Strategy;
    
                            if ($strategy->projects->isEmpty()) {
                                continue; // Skip if no projects
                            }
    
                            foreach ($strategy->projects as $project) {
                                if ($project->Count_Steps != 1) {
                                    continue; // Skip if Count_Steps is not 1
                                }
                                $projectName = $project->Name_Project;
                                $projectId = $project->Id_Project;
                                $projectStatus = $project->approvals->first()->Status ?? 'Unknown';
                                $quarterLogData[] = "Project: $projectName, Project ID: $projectId, Strategy: $strategyName, Strategic: $strategicName, Fiscal Year: $fiscalYear, Quarter: $quarterName, Status: $projectStatus";
                            }
                        }
                    }
    
                    $logData[$fiscalYear . '-' . $quarterName] = !empty($quarterLogData) ? $quarterLogData : null;
                }
    
                if ($strategics) {
                    foreach ($strategics as $strategic) {
                        foreach ($strategic->quarterProjects as $quarterProject) {
                            $quarterProject->quarterProject->fiscalYear = $quarterProject->quarterProject->Fiscal_Year;
                            $quarterProject->quarterProject->quarter = $quarterProject->quarterProject->Quarter;
                        }
                    }
                }
            } else {
                $strategics = collect();
                $logData = collect();
            }

        } else {
            Log::warning('No employee data found in session.');
        }

        log::info($approvals);
    
        return view('requestApproval', compact('approvals', 'employee', 'strategics', 'logData'));
    }

    public function updateAllStatus(Request $request)
    {   
        $approvals = $request->input('approvals', []);
        $comment = $request->input('comment', '');
        $fiscalQuarter = $request->input('fiscalQuarter', '');

        foreach ($approvals as $approvalData) {
            $projectId = $approvalData['id'];
            $status = $approvalData['status'];

            $approval = ApproveModel::where('Project_Id', $projectId)->first();

            if ($approval) {
                Log::info('Approval found: ' . $approval->Id_Approve);
            } else {
                Log::warning('Approval not found for Project ID: ' . $projectId);
            }

            if ($approval) {
                $project = ListProjectModel::find($approval->Project_Id);

                if ($status === 'Y') {
                    if ($approval->Status === 'N') {
                        if ($project) {
                            $project->Count_Steps = 0;
                            $project->save();
                        }
                        continue;
                    }

                    $approval->Status = $status;
                    $approval->save();

                    $employee = $request->session()->get('employee');
                    $nameResponsible = $employee ? $employee->Firstname . ' ' . $employee->Lastname : 'Unknown';
                    $roleCreator = $employee ? $employee->Position_Name : 'Unknown';

                    switch ($project->Count_Steps) {
                        case 0:
                            $comment = "อนุมัติการเริ่มต้นโครงการตามที่เสนอ";
                            break;
                        case 1:
                            $comment = "เห็นชอบการดำเนินโครงการในขั้นต้น";
                            break;                       
                        default:
                            $comment = "เห็นชอบการดำเนินการตามที่เสนอ";
                    }

                    RecordHistory::create([
                        'Approve_Id' => $approval->Id_Approve,
                        'Approve_Project_Id' => $approval->Project_Id,
                        'Comment' => $comment,
                        'Time_Record' => Carbon::now('Asia/Bangkok'),
                        'Status_Record' => $approval->Status,
                        'Name_Record' => $nameResponsible,
                        'Permission_Record' => $roleCreator,
                    ]);

                    if ($project) {
                        switch ($project->Count_Steps) {
                            case 0:
                                $project->Count_Steps = 1;
                                break;
                            case 1:
                                $project->Count_Steps = 2;
                                break;
                            default:
                                break;
                        }
                        $project->save();

                        if ($project->Count_Steps <= 9) {
                            $approval->Status = 'Y';
                            $approval->save();
                        }
                    }
                } else {
                    if (empty($comment)) {
                        $comment = "ไม่อนุมัติโครงการ กรุณาแก้ไขตามคำแนะนำ";
                    }

                    if ($approval->Status === 'N') {
                        if ($project) {
                            $project->Count_Steps = 0;
                            $project->save();
                        }
                        continue; 
                    }

                    $approval->Status = $status;
                    $approval->save();

                    $employee = $request->session()->get('employee');                 
                    $nameResponsible = $employee ? $employee->Firstname . ' ' . $employee->Lastname : 'Unknown';
                    $roleCreator = $employee ? $employee->Position_Name : 'Unknown';

                    if ($project) {
                        $project->Count_Steps = 0;
                        $project->save();
                    }

                    RecordHistory::create([
                        'Approve_Id' => $approval->Id_Approve,
                        'Approve_Project_Id' => $approval->Project_Id,
                        'Comment' => $comment,
                        'Time_Record' => Carbon::now('Asia/Bangkok'),
                        'Status_Record' => $approval->Status,
                        'Name_Record' => $nameResponsible,
                        'Permission_Record' => $roleCreator,
                    ]);
                }
            }
        }

        return redirect()->route('requestApproval')->with('success', 'บันทึกการพิจารณาเรียบร้อยแล้วสำหรับ ' . $fiscalQuarter);
    }

    public function updateApprovalStatus(Request $request, $id, $status)
    {
        $approval = ApproveModel::find($id);

        if ($approval) {
            $approval->Status = $status;
            $approval->save();

            $employee = $request->session()->get('employee'); 
            $nameResponsible = $employee ? $employee->Firstname . ' ' . $employee->Lastname : 'Unknown';
            $roleCreator = $employee ? $employee->Position_Name : 'Unknown';

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
                'Permission_Record' => $roleCreator,
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
                            if ($project->Role_Creator === 'IsManager') {
                                $project->Count_Steps = 5;  
                            } else {
                                $project->Count_Steps = 4;
                            }
                            break;
                        case 4:
                            $project->Count_Steps = 5;
                            break;
                        case 5:
                            $project->Count_Steps = 6;
                            break;
                        case 6:
                            if ($project->Role_Creator === 'IsManager') {
                                $project->Count_Steps = 8;  
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
                        case 11:
                            $project->Count_Steps = 2;
                            $approval->Status = 'Y';
                            $approval->save();
                            break;
                        default:
                            break;
                    }
                    $project->save();
            
                    if ($project->Count_Steps <= 9 || $project->Count_Steps == 11) {
                        if ($project->Count_Steps == 2 && $approval->Status == 'Y') {
                        } else {
                            $approval->Status = 'I';
                            $approval->save();
                        }
                    }
                }
            }
        }

        return redirect()->route('requestApproval')->with('success', 'บันทึกการพิจารณาเรียบร้อยแล้ว');
    }

    public function disapproveAll(Request $request, $Strategic_Id)
    {       
        $projectIds = $request->input('project_ids', []);
        $comment = $request->input('comment', '');

        log::info($projectIds);
    
        foreach ($projectIds as $projectId) {
            $project = ListProjectModel::find($projectId);
            if ($project && $project->Count_Steps == 1) {
                $project->Count_Steps = 0;
                $project->save();
    
                $approval = $project->approvals->first();
                if ($approval) {
                    $approval->Status = 'N';
                    $approval->Comment = $comment;
                    $approval->save();
                }
            }
        }
    
        return redirect()->route('requestApproval')->with('success', 'โครงการทั้งหมดถูกไม่อนุมัติและรีเซ็ตเรียบร้อยแล้ว');
    }

    public function disapproveProject(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|max:255',
        ]);
    
        $approval = ApproveModel::where('Project_Id', $id)->first();
    
        log::info($approval);
    
        if (!$approval) {
            return redirect()->back()->with('error', 'Approval not found.');
        }
    
        $approval->Status = 'N';
        $approval->save();
    
        $employee = $request->session()->get('employee');    
        $nameResponsible = $employee ? $employee->Firstname . ' ' . $employee->Lastname : 'Unknown';
        $roleCreator = $employee ? $employee->Position_Name : 'Unknown';
    
        RecordHistory::create([
            'Approve_Id' => $approval->Id_Approve,
            'Approve_Project_Id' => $approval->Project_Id,
            'Comment' => $request->comment,
            'Time_Record' => Carbon::now('Asia/Bangkok'),
            'Status_Record' => $approval->Status,
            'Name_Record' => $nameResponsible,
            'Permission_Record' => $roleCreator,
        ]);
    
        return redirect()->back()->with('success', 'Project disapproved successfully.');
    }

    public function submitForAllApproval(Request $request)
    {       
        $fiscalYear = $request->input('fiscal_year');
        $quarter = $request->input('quarter');
        $projectIds = $request->input('project_ids', []);
    
        $projects = ListProjectModel::whereIn('Id_Project', $projectIds)
            ->where('Count_Steps', 0)
            ->get();
    
        foreach ($projects as $project) {
            $approval = ApproveModel::where('Project_Id', $project->Id_Project)->first();
    
            if ($approval && $approval->Status == 'I') {
                $approval->Status = 'Y';
                $approval->save();
    
                $employee = $request->session()->get('employee');           
                $nameResponsible = $employee ? $employee->Firstname. ' ' . $employee->Lastname : 'Unknown';
                $roleCreator = $employee ? $employee->Position_Name : 'Unknown';
    
                RecordHistory::create([
                    'Approve_Id' => $approval->Id_Approve,
                    'Approve_Project_Id' => $approval->Project_Id,
                    'Comment' => 'เสนอโครงการเพื่อขออนุมัติ',
                    'Time_Record' => Carbon::now('Asia/Bangkok'),
                    'Status_Record' => $approval->Status,
                    'Name_Record' => $nameResponsible,
                    'Permission_Record' => $roleCreator,
                ]);
    
                $project->Count_Steps = 1;
                $project->save();
    
                $approval->Status = 'I';
                $approval->save();
            }
        }
    
        return redirect()->back()->with('success', 'โครงการทั้งหมดถูกเสนอไปยังผู้อำนวยการเรียบร้อยแล้ว');
    }

}