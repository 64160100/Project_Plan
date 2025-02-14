<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator; 
use App\Models\ListProjectModel;
use App\Models\StrategicModel;
use App\Models\StrategyModel;
use App\Models\ApproveModel;
use App\Models\EmployeeModel;
use App\Models\RecordHistory;
use App\Models\SDGsModel;
use App\Models\IntegrationModel;
use App\Models\SupProjectModel;
use App\Models\PlatformModel;
use App\Models\ProgramModel;
use App\Models\Kpi_ProgramModel;
use App\Models\ProjectHasSDGModel;
use App\Models\ProjectHasIntegrationCategoryModel;
use App\Models\TargetModel;
use App\Models\TargetDetailsModel;
use App\Models\LocationModel;
use App\Models\IndicatorsModel;
use App\Models\ProjectHasIndicatorsModel;
use App\Models\MonthsModel;
use App\Models\PdcaModel;
use App\Models\PdcaDetailsModel;
use App\Models\MonthlyPlansModel;
use App\Models\ShortProjectModel;
use App\Models\BudgetSourceModel;
use App\Models\SubtopBudgetModel;
use App\Models\ProjectHasBudgetSourceModel;
use App\Models\BudgetFormModel;
use App\Models\SubtopicBudgetHasBudgetFormModel;
use App\Models\ExpectedResultsModel;
use App\Models\OutcomeModel;
use App\Models\OutputModel;
use App\Models\StrategicObjectivesModel;
use App\Models\KpiModel;
use App\Models\ProjectBatchHasProjectModel;
use App\Models\BatchProjectModel;
use App\Models\FiscalYearQuarterModel;
use Carbon\Carbon;

class ListProjectController extends Controller
{
    public function project()
    {
        $quarters = FiscalYearQuarterModel::all();
        $strategics = StrategicModel::with(['projects', 'quarterProjects'])->get();
        $strategics->each(function ($strategics) {
            $strategics->project_count = $strategics->projects->count();
        });

        return view('Project.listProject', compact('strategics', 'quarters')); 
    }

    public function viewProject($projectId)
    {
        $project = ListProjectModel::with([
            'subProjects',
            'platforms.programs.kpis',
            'sdgs',
            'projectHasIntegrationCategories',
            'targets.targetDetails',
            'locations',
            'projectHasIndicators'
        ])->findOrFail($projectId);
    
        $strategics = StrategicModel::with(['strategies'])->findOrFail($project->Strategic_Id);
        $strategies = $strategics->strategies;
        $employees = EmployeeModel::all();
    
        $months = MonthsModel::orderBy('Id_Months', 'asc')->pluck('Name_Month', 'Id_Months');
        $pdcaStages = PdcaModel::all();
        $pdcaDetails = PdcaDetailsModel::where('Project_Id', $projectId)->get();
        $monthlyPlans = MonthlyPlansModel::where('Project_id', $projectId)->get();
        $shortProjects = ShortProjectModel::where('Project_Id', $projectId)->get();
    
        $budgetSources = BudgetSourceModel::all();
        $subtopBudgets = SubtopBudgetModel::all();
        $projectBudgetSources = ProjectHasBudgetSourceModel::where('Project_Id', $projectId)->get();
        $budgetForms = BudgetFormModel::where('Project_Id', $projectId)->get();
        $subtopicBudgetForms = SubtopicBudgetHasBudgetFormModel::whereIn('Budget_Form_Id', $budgetForms->pluck('Id_Budget_Form'))->get();
    
        $expectedResults = ExpectedResultsModel::where('Project_Id', $projectId)->get();
        $outcomes = OutcomeModel::where('Project_Id', $projectId)->get();
        $outputs = OutputModel::where('Project_Id', $projectId)->get();
    
        $formatDateToThai = function($date) use ($months) {
            if (!$date) {
                return '-';
            }
            $year = date('Y', strtotime($date)) + 543;
            $monthKey = date('m', strtotime($date));
            $month = $months[$monthKey] ?? null;
            $day = date('d', strtotime($date));
            return $month ? "วันที่ $day $month พ.ศ. $year" : "วันที่ $day พ.ศ. $year";
        };
    
        $firstTime = $formatDateToThai($project->First_Time ?? null);
        $endTime = $formatDateToThai($project->End_Time ?? null);
    
        return view('Project.viewProject', compact(
            'project', 'strategics', 'strategies', 'employees', 'firstTime', 'endTime', 'months', 'pdcaStages', 'pdcaDetails', 'monthlyPlans', 'shortProjects', 'budgetSources', 'subtopBudgets', 'projectBudgetSources', 'budgetForms', 'subtopicBudgetForms', 'expectedResults', 'outcomes', 'outputs'
        ));
    }

    public function showCreateFirstForm($Strategic_Id)
    {
        $strategics = StrategicModel::with(['strategies.kpis', 'strategies.strategicObjectives', 'projects'])->findOrFail($Strategic_Id);
        $strategies = $strategics->strategies;
        $projects = $strategics->projects; 
        $employees = EmployeeModel::all();
        $sdgs = SDGsModel::all(); 
        $nameStrategicPlan = $strategics->Name_Strategic_Plan;
        $integrationCategories = IntegrationModel::orderBy('Id_Integration_Category', 'asc')->get();
        $months = MonthsModel::orderBy('Id_Months', 'asc')->pluck('Name_Month', 'Id_Months');
        $pdcaStages = PdcaModel::all();
        $budgetSources = BudgetSourceModel::all();
        $subtopBudgets = SubtopBudgetModel::all();
        $kpis = KpiModel::all();
        $strategicObjectives = StrategicObjectivesModel::all();

        return view('Project.createFirstForm', compact('strategics', 'strategies', 'projects', 'employees', 'sdgs', 'nameStrategicPlan', 'integrationCategories', 'months', 'pdcaStages', 'budgetSources', 'subtopBudgets', 'kpis', 'strategicObjectives'));
    }

    public function showCreateForm($Strategic_Id)
    {
        $strategics = StrategicModel::with(['strategies.kpis', 'strategies.strategicObjectives', 'projects'])->findOrFail($Strategic_Id);
        $strategies = $strategics->strategies;
        $projects = $strategics->projects; 
        $employees = EmployeeModel::all();
        $sdgs = SDGsModel::all(); 
        $nameStrategicPlan = $strategics->Name_Strategic_Plan;
        $integrationCategories = IntegrationModel::orderBy('Id_Integration_Category', 'asc')->get();
        $months = MonthsModel::orderBy('Id_Months', 'asc')->pluck('Name_Month', 'Id_Months');
        $pdcaStages = PdcaModel::all();
        $budgetSources = BudgetSourceModel::all();
        $subtopBudgets = SubtopBudgetModel::all();
        
        return view('Project.createProject', compact('strategics', 'strategies', 'projects', 'employees', 'sdgs', 'nameStrategicPlan', 'integrationCategories', 'months', 'pdcaStages', 'budgetSources', 'subtopBudgets'));
    }

    public function createProject(Request $request, $Strategic_Id)
    {
        $strategics = StrategicModel::with(['strategies', 'projects'])->findOrFail($Strategic_Id);
        $strategies = $strategics->strategies;
    
        $request->validate([
            'Name_Strategy' => 'required',
        ]);

        $strategy = StrategyModel::find($request->input('Name_Strategy'));
            
        $nameStrategy = $strategy ? $strategy->Name_Strategy : null;
        $strategyId = $strategy ? $strategy->Id_Strategy : null;

        $employee = $request->session()->get('employee');
        $nameCreator = $employee ? $employee->Firstname_Employee . ' ' . $employee->Lastname_Employee : 'Unknown';
        $permissions = $request->session()->get('permissions');
        $roleCreator = $permissions->first()->Name_Permission ?? 'Unknown';

        $project = new ListProjectModel;
        $project->Strategic_Id = $request->Strategic_Id;
        $project->Strategy_Id = $strategyId ?? null;
        $project->Name_Strategy = $nameStrategy ?? null;
        $project->Name_Project = $request->Name_Project;
        $project->Description_Project = $request->Description_Project ?? null;
        $project->Employee_Id = $request->employee_id ?? null;
        $project->Objective_Project = $request->Objective_Project ?? $request->Objective_Project_Other ?? null;
        $project->Principles_Reasons = $request->Principles_Reasons ?? null;
        $project->Success_Indicators = $request->Success_Indicators ?? $request->Success_Indicators_Other ?? null;
        $project->Value_Target = $request->Value_Target ?? $request->Value_Target_Other ?? null;
        $project->Project_type = $request->Project_Type ?? null;
        $project->Status_Budget = $request->Status_Budget ?? null;
        $project->First_Time = $request->First_Time ?? null;
        $project->End_Time = $request->End_Time ?? null;
        $project->Name_Creator = $nameCreator;
        $project->Role_Creator = $roleCreator;
        $project->save();
    
        if ($project->Id_Project) {
            $approval = new ApproveModel;
            $approval->Status = 'I';
            $approval->Project_Id = $project->Id_Project;
            $approval->save();
            // Log::info('Approval record created for project ID: ' . $project->Id_Project);
    
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
                    if (is_array($request->target_details)) {
                        $targetDetails->Details_Target = implode(' ', $request->target_details);
                    } else {
                        $targetDetails->Details_Target = $request->target_details;
                    }
                    $targetDetails->Target_Project_Id = $latestTargetId;
                    $targetDetails->save();
                    Log::info('Target details created for target ID: ' . $latestTargetId);
                }
            }
    
            if ($request->has('location')) {
                foreach ($request->location as $locationName) {
                    if (!empty($locationName)) {
                        $location = new LocationModel;
                        $location->Name_Location = $locationName;
                        $location->Project_Id = $project->Id_Project;
                        $location->save();
                        Log::info('Location created with name: ' . $locationName);
                    }
                }
            }
    
            if ($request->has('goal')) {
                foreach ($request->goal as $goalType) {
                    if ($goalType == '1' && $request->has('quantitative')) {
                        foreach ($request->quantitative as $quantitativeDetail) {
                            $indicator = new IndicatorsModel;
                            $indicator->Type_Indicators = 'Quantitative';
                            $indicator->save();
    
                            $projectIndicator = new ProjectHasIndicatorsModel;
                            $projectIndicator->Project_Id = $project->Id_Project;
                            $projectIndicator->Indicators_Id = $indicator->Id_Indicators;
                            $projectIndicator->Details_Indicators = $quantitativeDetail;
                            $projectIndicator->save();
                            Log::info('Quantitative indicator created with detail: ' . $quantitativeDetail);
                        }
                    }
    
                    if ($goalType == '2' && $request->has('qualitative')) {
                        foreach ($request->qualitative as $qualitativeDetail) {
                            $indicator = new IndicatorsModel;
                            $indicator->Type_Indicators = 'Qualitative';
                            $indicator->save();
    
                            $projectIndicator = new ProjectHasIndicatorsModel;
                            $projectIndicator->Project_Id = $project->Id_Project;
                            $projectIndicator->Indicators_Id = $indicator->Id_Indicators;
                            $projectIndicator->Details_Indicators = $qualitativeDetail;
                            $projectIndicator->save();
                            Log::info('Qualitative indicator created with detail: ' . $qualitativeDetail);
                        }
                    }
                }
            }
    
            if ($request->has('pdca')) {
                foreach ($request->pdca as $stageId => $details) {
                    $pdcaDetail = new PdcaDetailsModel;
                    $pdcaDetail->PDCA_Stages_Id = $stageId;
                    $pdcaDetail->Details_PDCA = $details['detail'];
                    $pdcaDetail->Project_Id = $project->Id_Project;
                    $pdcaDetail->save();
                    Log::info('PDCA stage created with ID: ' . $stageId);
    
                    if (isset($details['months']) && is_array($details['months'])) {
                        foreach ($details['months'] as $month) {
                            $monthlyPlan = new MonthlyPlansModel;
                            $monthlyPlan->Project_id = $project->Id_Project;
                            $monthlyPlan->Months_id = $month;
                            $monthlyPlan->PDCA_Stages_Id = $stageId;
    
                            $currentYear = date('Y');
                            $fiscalYearStartMonth = 10;
                            $fiscalYear = ($month >= $fiscalYearStartMonth) ? $currentYear + 1 : $currentYear;
    
                            $monthlyPlan->Fiscal_year = $fiscalYear;
                            $monthlyPlan->save();
                            Log::info('PDCA stage month saved: ' . $month . ' for fiscal year: ' . $fiscalYear);
                        }
                    }
                }
            }
    
            if ($request->Project_Type == 'S') {
                foreach ($request->Details_Short_Project as $detail) {
                    $shortProject = new ShortProjectModel;
                    $shortProject->Project_Id = $project->Id_Project;
                    $shortProject->Details_Short_Project = $detail;
                    $shortProject->save();
                    Log::info('Short project created with detail: ' . $detail);
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
    
            if ($request->has('expected_results')) {
                foreach ($request->expected_results as $expectedResult) {
                    $expectedResultModel = new ExpectedResultsModel;
                    $expectedResultModel->Name_Expected_Results = $expectedResult;
                    $expectedResultModel->Project_Id = $project->Id_Project;
                    $expectedResultModel->save();
                    Log::info('Expected result created with name: ' . $expectedResult);
                }
            }
            
            if ($request->has('outcomes')) {
                foreach ($request->outcomes as $outcome) {
                    $outcomeModel = new OutcomeModel;
                    $outcomeModel->Name_Outcome = $outcome;
                    $outcomeModel->Project_Id = $project->Id_Project;
                    $outcomeModel->save();
                    Log::info('Outcome created with name: ' . $outcome);
                }
            }
            
            if ($request->has('outputs')) {
                foreach ($request->outputs as $output) {
                    $outputModel = new OutputModel;
                    $outputModel->Name_Output = $output;
                    $outputModel->Project_Id = $project->Id_Project;
                    $outputModel->save();
                    Log::info('Output created with name: ' . $output);
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

            $strategics = StrategicModel::with(['projects' => function($query) {
                $query->where('Count_Steps', 1)->orderBy('Name_Strategy');
            }, 'projects.approvals'])->get();
                        
        } else {
            Log::warning('No employee data found in session.');
        }
    
        return view('requestApproval', compact('approvals', 'employee', 'strategics'));
    }

    public function updateAllStatus(Request $request)
    {
        $approvals = $request->input('approvals', []);

        foreach ($approvals as $approvalData) {
            $id = $approvalData['id'];
            $status = $approvalData['status'];

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
        }

        return redirect()->route('requestApproval')->with('success', 'บันทึกการพิจารณาเรียบร้อยแล้ว');
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
    
                $incompleteStrategies = [];
                foreach ($strategies as $strategy) {
                    $strategyProjects = $projects->where('Count_Steps', 0)
                                                ->where('Strategy_Id', $strategy->Id_Strategy)
                                                ->filter(function($project) {
                                                    return $project->approvals->contains('Status', 'I');
                                                });
    
                    if ($strategyProjects->isEmpty()) {
                        $incompleteStrategies[] = $strategy->Name_Strategy;
                    }
                }
    
                usort($incompleteStrategies, function($a, $b) {
                    return strcmp($a, $b);
                });
    
                $allStrategiesComplete = empty($incompleteStrategies);
            }
    
            $hasProjectsToPropose = false;
            $hasStatusN = false;
    
            $quarterStyles = [
                1 => 'border-blue-200 bg-blue-50',
                2 => 'border-green-200 bg-green-50',
                3 => 'border-yellow-200 bg-yellow-50',
                4 => 'border-purple-200 bg-purple-50'
            ];
    
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
    
            return view('proposeProject', compact('projects', 'countStepsZero', 'quarters', 'filteredStrategics', 'approvals', 'strategies', 'incompleteStrategies', 'allStrategiesComplete', 'strategics', 'hasProjectsToPropose', 'hasStatusN', 'quarterStyles', 'quartersByFiscalYear'));
        } else {
            return redirect()->back()->with('error', 'You are not authorized to view these projects.');
        }
    }

    public function submitForAllApproval(Request $request)
    {
        $projects = ListProjectModel::where('Count_Steps', 0)->get();
    
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

    // public function edit($id, Request $request)
    // {
    //     $project = ListProjectModel::with('supProjects')->findOrFail($id);
    //     $strategies = StrategyModel::all();
    //     $sdgs = SustainableDevelopmentGoalsModel::all();
    //     return view('Project.editProject', compact('project', 'strategies', 'sdgs'));
    // }

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
        $permissions = $employee ? $employee->permissions : collect();
    
        $nameResponsible = $employee ? $employee->Firstname_Employee . ' ' . $employee->Lastname_Employee : 'Unknown';
        $permissionName = $permissions->first()->Name_Permission ?? 'Unknown';
    
        RecordHistory::create([
            'Approve_Id' => $approval->Id_Approve,
            'Approve_Project_Id' => $approval->Project_Id,
            'Comment' => $request->comment,
            'Time_Record' => Carbon::now('Asia/Bangkok'),
            'Status_Record' => $approval->Status,
            'Name_Record' => $nameResponsible,
            'Permission_Record' => $permissionName,
        ]);
    
        return redirect()->back()->with('success', 'Project disapproved successfully.');
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

}