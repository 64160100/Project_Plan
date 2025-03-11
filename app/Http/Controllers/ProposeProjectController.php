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
use App\Models\SDGsModel;
use App\Models\StrategicObjectivesModel;
use App\Models\EmployeeModel;
use Carbon\Carbon;

class ProposeProjectController extends Controller
{
    public function proposeProject(Request $request)
    {
        $employee = $request->session()->get('employee');
    
        if ($employee) {
            $projectsQuery = ListProjectModel::query();
    
            if ($employee->IsAdmin !== 'Y') {
                $projectsQuery->where('Employee_Id', $employee->Id_Employee);
            }
    
            $projects = $projectsQuery->whereIn('Count_Steps', [0, 1, 2, 3, 4, 5, 6, 7, 8, 9])
                ->with(['approvals.recordHistory', 'employee'])
                ->get();
    
            $countStepsZero = $projects->where('Count_Steps', 0)->count();

            $employees = EmployeeModel::all(); 
    
            foreach ($projects as $project) {
                $employeeData = $project->employee;
                $project->employeeName = $employeeData ? $employeeData->Firstname . ' ' . $employeeData->Lastname : '-';
                // Update this line to use Department_Name directly from the employee model
                $project->departmentName = $employeeData ? $employeeData->Department_Name : 'ยังไม่มีผู้รับผิดชอบโครงการ';
    
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
                
                if ($project->End_Time) {
                    $date = new \DateTime($project->End_Time);
                    $thaiMonths = [
                        1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน',
                        5 => 'พฤษภาคม', 6 => 'มิถุนายน', 7 => 'กรกฎาคม', 8 => 'สิงหาคม',
                        9 => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม'
                    ];
                    $day = $date->format('d');
                    $month = $thaiMonths[(int)$date->format('m')];
                    $year = (int)$date->format('Y') + 543;
                    $project->formattedEndTime = "วันที่ $day $month พ.ศ. $year";
                } else {
                    $project->formattedEndTime = '-';
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
            $strategicName = collect();
            $logData = [];
            $strategics = collect();
            
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
            
                        if (is_iterable($strategic->strategies) && $strategic->strategies->isEmpty()) {
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
            
                        if (is_iterable($strategic->strategies)) {
                            foreach ($strategic->strategies as $strategy) {
                                $strategyName = $strategy->Name_Strategy;
            
                                if (is_iterable($strategy->projects) && $strategy->projects->isEmpty()) {
                                    $logData[] = "Strategy: $strategyName, Strategic: $strategicName, Fiscal Year: $fiscalYear, Quarter: $quarterName, Status: No projects created";
                                }
            
                                if (is_iterable($strategy->projects)) {
                                    foreach ($strategy->projects as $project) {
                                        $projectName = $project->Name_Project;
                                        $projectStatus = $project->approvals->first()->Status ?? 'Unknown';
                                        $logData[] = "Project: $projectName, Strategy: $strategyName, Strategic: $strategicName, Fiscal Year: $fiscalYear, Quarter: $quarterName, Status: $projectStatus";
                                    }
                                }
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
            
            foreach ($strategics as $strategic) {
                $strategicName = $strategic->Name_Strategic_Plan;
            
                if (is_iterable($strategic->strategies)) {
                    foreach ($strategic->strategies as $strategy) {
                        $strategyName = $strategy->Name_Strategy;
            
                        if (is_iterable($strategy->projects) && $strategy->projects->isEmpty()) {
                            $logData[] = "Strategy: $strategyName, Strategic: $strategicName, Fiscal Year: $fiscalYear, Quarter: $quarterName, Status: No projects created";
                        }
            
                        if (is_iterable($strategy->projects)) {
                            foreach ($strategy->projects as $project) {
                                $projectName = $project->Name_Project;
                                $projectStatus = $project->approvals->first()->Status ?? 'Unknown';
                                $logData[] = "Project: $projectName, Strategy: $strategyName, Strategic: $strategicName, Fiscal Year: $fiscalYear, Quarter: $quarterName, Status: $projectStatus";
                            }
                        }
                    }
                }
            }
    
            return view('proposeProject', compact('projects', 'countStepsZero', 'quarters', 'filteredStrategics', 'approvals', 'strategies', 'incompleteStrategies', 'allStrategiesComplete', 'hasProjectsToPropose', 'hasStatusN', 'quarterStyles', 'quartersByFiscalYear', 'strategicName', 'logData', 'employees'));
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
                $nameResponsible = $employee ? $employee->Firstname . ' ' . $employee->Lastname : 'Unknown';
                $roleCreator = $employee ? $employee->Position_Name : 'Unknown';
                
                log::info($roleCreator);

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
    
    public function submitForApproval(Request $request, $id)
    {
        $project = ListProjectModel::find($id);

        if ($project) {
            $approval = ApproveModel::where('Project_Id', $project->Id_Project)->first();

            if ($approval) {
                $approval->Status = 'Y';
                $approval->save();

                $employee = $request->session()->get('employee');
                $nameResponsible = $employee ? $employee->Firstname . ' ' . $employee->Lastname : 'Unknown';
                $roleCreator = $employee ? ($employee->IsManager === 'Y' ? 'IsManager' : $employee->Position_Name) : 'Unknown';

                RecordHistory::create([
                    'Approve_Id' => $approval->Id_Approve,
                    'Approve_Project_Id' => $approval->Project_Id,
                    'Comment' => 'เสนอโครงการเพื่อขออนุมัติ',
                    'Time_Record' => Carbon::now('Asia/Bangkok'),
                    'Status_Record' => $approval->Status,
                    'Name_Record' => $nameResponsible,
                    'Permission_Record' => $roleCreator,
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

    public function editProject(Request $request, $Id_Project)
    {
        $project = ListProjectModel::findOrFail($Id_Project);
        $strategics = StrategicModel::all();
        $strategies = StrategyModel::where('Strategic_Id', $project->Strategic_Id)->get();
        $employees = EmployeeModel::all();
        $budgetSources = BudgetSourceModel::all();
        $subtopBudgets = SubtopBudgetModel::all();
        $strategicObjectives = StrategicObjectivesModel::all();
        $kpis = KpiModel::all();
        $sourcePage = $request->input('sourcePage', 'listProject');
    
        return view('Project.editProject', compact('project', 'strategics', 'strategies', 'employees', 'budgetSources', 'subtopBudgets', 'strategicObjectives', 'kpis', 'sourcePage'));
    }
    
    public function updateProject(Request $request, $id)
    {
        $sourcePage = $request->input('sourcePage', 'proposeProject');

        $request->validate([
            'Name_Strategy' => 'required',
        ]);

        $strategy = StrategyModel::find($request->input('Name_Strategy'));
        
        $nameStrategy = $strategy ? $strategy->Name_Strategy : null;
        $strategyId = $strategy ? $strategy->Id_Strategy : null;

        $project = ListProjectModel::findOrFail($id);

        $project->Strategic_Id = $request->Strategic_Id ?? $project->Strategic_Id;
        $project->Strategy_Id = $strategyId; 
        $project->Name_Strategy = $nameStrategy;
        $project->Name_Project = $request->Name_Project ?? $project->Name_Project;
        $project->Employee_Id = $request->Employee_Id ?? $project->Employee_Id;
        $project->Objective_Project = $request->Objective_Project ?? $project->Objective_Project;
        $project->Principles_Reasons = $request->Principles_Reasons ?? $project->Principles_Reasons;
        $project->Success_Indicators = $request->Success_Indicators ?? $project->Success_Indicators;
        $project->Value_Target = $request->Value_Target ?? $project->Value_Target;
        $project->Project_Type = $request->Project_Type ?? $project->Project_Type;
        $project->Status_Budget = $request->Status_Budget ?? $project->Status_Budget;
        $project->First_Time = $request->First_Time ?? $project->First_Time;
        $project->End_Time = $request->End_Time ?? $project->End_Time;
        $project->Count_Steps = $request->Count_Steps ?? $project->Count_Steps;

        $project->save();

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

        if ($request->has('Name_Sup_Project')) {
            $project->supProjects()->delete();
            foreach ($request->Name_Sup_Project as $supProjectName) {
                $supProject = new SupProjectModel;
                $supProject->Project_Id = $project->Id_Project ?? null;
                $supProject->Name_Sup_Project = $supProjectName ?? null ; 
                $supProject->save();
            }
        }

        if ($request->Status_Budget !== 'N') {
            if ($request->has('budget_source')) {
                $projectBudgetSource = new ProjectHasBudgetSourceModel;
                $projectBudgetSource->Project_Id = $project->Id_Project;
                $projectBudgetSource->Budget_Source_Id = $request->budget_source;
                $projectBudgetSource->Amount_Total = $request->input('amount_' . $request->budget_source) ?? null;
                $projectBudgetSource->Details_Expense = $request->source_detail ?? null;
                $projectBudgetSource->save();
                Log::info('Project budget source created with ID: ' . $request->budget_source);
            }
        
            if ($request->has('activity')) {
                foreach ($request->activity as $index => $activity) {
                    $budgetForm = new BudgetFormModel;
                    $budgetForm->Budget_Source_Id = $request->budget_source;
                    $budgetForm->Project_Id = $project->Id_Project;
                    $budgetForm->Big_Topic = $activity ?? null;
                    $budgetForm->Amount_Big = $request->total_amount[$index] ?? null;
                    $budgetForm->save();
                    Log::info('Budget form created with big topic: ' . $activity);
        
                    if ($request->has('subActivity')) {
                        foreach ($request->subActivity[$index] as $subIndex => $subActivity) {
                            $subtopicBudgetForm = new SubtopicBudgetHasBudgetFormModel;
                            $subtopicBudgetForm->Subtopic_Budget_Id = $subActivity ?? null;
                            $subtopicBudgetForm->Budget_Form_Id = $budgetForm->Id_Budget_Form;
                            $subtopicBudgetForm->Details_Subtopic_Form = $request->description[$index][$subIndex] ?? null;
                            $subtopicBudgetForm->Amount_Sub = $request->amount[$index][$subIndex] ?? null;
                            $subtopicBudgetForm->save();
                            Log::info('Subtopic budget form created with subtopic ID: ' . $subActivity);
                        }
                    }
                }
            }
        }

        if ($sourcePage === 'proposeProject') {
            return redirect()->route('proposeProject')->with('success', 'Project updated successfully');
        } else {
            return redirect()->route('project')->with('success', 'Project updated successfully');
        }
    }

    public function updateStatus($id)
    {
        $approval = ApproveModel::where('Project_Id', $id)->first();

        if (!$approval) {
            return redirect()->back()->with('error', 'Approval record not found.');
        }

        $approval->Status = 'N';
        $approval->save();

        return redirect()->route('proposeProject')->with('success', 'Project status updated successfully.');
    }

    public function updateEmployee(Request $request, $id)
    {
        $project = ListProjectModel::findOrFail($id);
        $project->Employee_Id = $request->input('employee_id');
        $project->save();
    
        return redirect()->back()->with('success', 'อัปเดตผู้รับผิดชอบเรียบร้อยแล้ว');
    }


}