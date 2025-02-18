<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ListProjectModel;
use App\Models\StrategicModel;
use App\Models\StrategyModel;
use App\Models\FiscalYearQuarterModel;
use App\Models\ApproveModel;
use App\Models\RecordHistory;


class ProposeProjectController extends Controller
{
    public function proposeProject(Request $request)
    {
        $employee = $request->session()->get('employee');
        $permissions = $request->session()->get('permissions');
    
        if ($employee) {
            $projectsQuery = ListProjectModel::query();
    
            if ($employee->IsAdmin !== 'Y') {
                $projectsQuery->where('Employee_Id', $employee->Id_Employee);
            }
    
            $projects = $projectsQuery->whereIn('Count_Steps', [0, 1, 2, 6])
                ->with(['approvals.recordHistory', 'employee.department', 'employee'])
                ->get();
    
            $countStepsZero = $projects->where('Count_Steps', 0)->count();
    
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
            }
    
            $filteredStrategics = collect(); 
            $approvals = collect(); 
            $strategies = collect();
            $incompleteStrategies = [];
            $allStrategiesComplete = false;
            $quarterStyles = collect();
            $quarters = collect();
            $logData = [];
    
            if ($projects->where('Count_Steps', 0)->isNotEmpty()) {
                $approvals = ApproveModel::all();
                $strategies = StrategyModel::all();
    
                $quarters = FiscalYearQuarterModel::select('Id_Quarter_Project', 'Fiscal_Year', 'Quarter')
                    ->orderBy('Id_Quarter_Project', 'asc')
                    ->get();
    
                $query = StrategicModel::with('quarterProjects');
    
                if ($request->has('Fiscal_Year_Quarter') && $request->Fiscal_Year_Quarter != '') {
                    list($fiscalYear, $quarter) = explode('-', $request->Fiscal_Year_Quarter);
                    $query->whereHas('quarterProjects', function ($q) use ($fiscalYear, $quarter) {
                        $q->whereHas('quarterProject', function ($q2) use ($fiscalYear, $quarter) {
                            $q2->where('Fiscal_Year', $fiscalYear)
                            ->where('Quarter', $quarter);
                        });
                    });
                }
    
                $strategics = $query->get();
    
                $filteredStrategics = $strategics->filter(function($strategic) use ($approvals) {
                    foreach ($strategic->projects as $project) {
                        $projectApprovals = $approvals->where('Project_Id', $project->Id_Project);
                        $project->approvalStatuses = $projectApprovals->pluck('Status')->toArray();
                    }
                    return $strategic->projects->contains(function($project) {
                        return $project->Count_Steps == 0;
                    });
                });
    
                foreach ($filteredStrategics as $strategic) {
                    foreach ($strategic->projects as $project) {
                        $projectApprovals = $approvals->where('Project_Id', $project->Id_Project);
                        $project->approvalStatuses = $projectApprovals->pluck('Status')->toArray();
                    }
                }
    
                $quarters = $quarters->sortBy('Fiscal_Year');
    
                foreach ($quarters as $quarter) {
                    $fiscalYear = $quarter->Fiscal_Year;
                    $quarterName = $quarter->Quarter;
    
                    $strategics = StrategicModel::whereHas('quarterProjects', function ($query) use ($quarter) {
                        $query->where('Quarter_Project_Id', $quarter->Id_Quarter_Project);
                    })->with('strategies.projects.approvals')->get();
    
                    foreach ($strategics as $strategic) {
                        $strategicName = $strategic->Name_Strategic_Plan;
    
                        if ($strategic->strategies->isEmpty()) {
                            $logData[] = "Strategic: $strategicName, Fiscal Year: $fiscalYear, Quarter: $quarterName, Status: No strategies created";
                        }
    
                        $allStrategies = StrategyModel::where('Strategic_Id', $strategic->Id_Strategic)->get();
                        $createdStrategies = $strategic->strategies->pluck('Id_Strategy')->toArray();
                        $missingStrategies = $allStrategies->filter(function ($strategy) use ($createdStrategies) {
                            return !in_array($strategy->Id_Strategy, $createdStrategies);
                        });
    
                        foreach ($missingStrategies as $missingStrategy) {
                            $projectsWithStrategy = ListProjectModel::where('Strategy_Id', $missingStrategy->Id_Strategy)->exists();
                            if (!$projectsWithStrategy) {
                                $logData[] = "Missing Strategy: {$missingStrategy->Name_Strategy}, Strategic: $strategicName, Fiscal Year: $fiscalYear, Quarter: $quarterName";
                            }
                        }
    
                        foreach ($strategic->strategies as $strategy) {
                            $strategyName = $strategy->Name_Strategy;
    
                            if ($strategy->projects->isEmpty()) {
                                $logData[] = "Strategy: $strategyName, Strategic: $strategicName, Fiscal Year: $fiscalYear, Quarter: $quarterName, Status: No projects created";
                            }
    
                            foreach ($strategy->projects as $project) {
                                $projectName = $project->Name_Project;
                                $projectStatus = $project->approvals->first()->Status ?? 'Unknown';
                                $logData[] = "Project: $projectName, Strategy: $strategyName, Strategic: $strategicName, Fiscal Year: $fiscalYear, Quarter: $quarterName, Status: $projectStatus";
                            }
                        }
                    }
                }
    
                usort($incompleteStrategies, function($a, $b) {
                    return strcmp($a, $b);
                });
    
                $allStrategiesComplete = empty($incompleteStrategies);
            }
    
            $hasProjectsToPropose = false;
            $hasStatusN = false;
    
            $quartersByFiscalYear = $quarters->groupBy(function($quarter) {
                $calendarYear = intval(substr($quarter->Fiscal_Year, 0, 4));
                return $calendarYear;
            });
    
            foreach ($filteredStrategics as $Strategic) {
                if ($Strategic->projects->contains(function($project) {
                    return $project->Count_Steps == 0;
                })) {
                    $hasProjectsToPropose = true;
                    break;
                }
            }
    
            foreach ($filteredStrategics as $Strategic) {
                foreach ($Strategic->projects as $project) {
                    if (in_array('N', $project->approvalStatuses)) {
                        $hasStatusN = true;
                    }
                    if (in_array('I', $project->approvalStatuses)) {
                        $hasStatusN = false;
                        break 2;
                    }
                }
            }
    
            return view('proposeProject', compact('projects', 'countStepsZero', 'quarters', 'filteredStrategics', 'approvals', 'strategies', 'incompleteStrategies', 'allStrategiesComplete', 'hasProjectsToPropose', 'hasStatusN', 'quarterStyles', 'quartersByFiscalYear', 'strategicName', 'logData'));
        } else {
            return redirect()->back()->with('error', 'You are not authorized to view these projects.');
        }
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
    
                $project->Count_Steps = 1;
                $project->save();
    
                $approval->Status = 'I';
                $approval->save();
            }
        }
    
        return redirect()->back()->with('success', 'โครงการทั้งหมดถูกเสนอไปยังผู้อำนวยการเรียบร้อยแล้ว');
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
                    if ($project->Status_Budget == 'N') {
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
    
                if ($project->Count_Steps <= 9 || $project->Count_Steps == 11) {
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
