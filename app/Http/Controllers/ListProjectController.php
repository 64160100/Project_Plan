<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PDF;
use Illuminate\Support\Facades\Storage;
use App\Models\ListProjectModel;
use App\Models\StrategicModel;
use App\Models\StrategyModel;
use App\Models\ApproveModel;
use App\Models\EmployeeModel;
use App\Models\SDGsModel;
use App\Models\IntegrationModel;
use App\Models\SubProjectModel;
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
use App\Models\FiscalYearQuarterModel;
use App\Models\StorageFileModel;
use App\Models\BudgetSourceTotalModel;
use App\Models\ExpenseTypesModel;
use App\Models\ExpenseModel;
use App\Models\ExpenHasSubtopicBudgetModel;
use App\Models\SuccessIndicatorsModel;
use App\Models\ValueTargetModel;
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
        
        $subtopBudgets = SubtopBudgetModel::orderBy('Name_Subtopic_Budget', 'asc')->get();
        $kpis = KpiModel::all();
        $strategicObjectives = StrategicObjectivesModel::all();

        $mainCategories = [];
        $categoryIds = [];
        
        foreach ($subtopBudgets as $subtop) {
            $parts = explode(' / ', $subtop->Name_Subtopic_Budget);
            $mainCategory = trim($parts[0]);
            
            if (!isset($categoryIds[$mainCategory])) {
                $categoryIds[$mainCategory] = $subtop->Id_Subtopic_Budget;
                $mainCategories[$subtop->Id_Subtopic_Budget] = $mainCategory;
            }
        }

        return view('Project.createFirstForm', compact(
            'strategics', 'strategies', 'projects', 'employees', 'sdgs', 
            'nameStrategicPlan', 'integrationCategories', 'months', 'pdcaStages', 
            'budgetSources', 'subtopBudgets', 'kpis', 'strategicObjectives', 
            'mainCategories',
        ));
    }

    public function searchProjects(Request $request)
    {
        $query = $request->input('query');
        $strategicId = $request->input('strategic_id');
        
        $projects = ListProjectModel::where('Strategic_Id', $strategicId)
            ->where('Name_Project', 'LIKE', "%{$query}%")
            ->with([
                'successIndicators',  // ดึงข้อมูลตัวชี้วัด
                'valueTargets',       // ดึงข้อมูลค่าเป้าหมาย
                'employee',           // ดึงข้อมูลผู้รับผิดชอบ
                'strategy'            // ดึงข้อมูลกลยุทธ์
            ])
            ->select([
                'Id_Project', 
                'Name_Project', 
                'Strategy_Id', 
                'Employee_Id',
                'Objective_Project', 
                'Principles_Reasons'  // เพิ่มข้อมูลที่ต้องการดึงเพิ่มเติม
            ])
            ->get();
        
        // บันทึก Log รายละเอียดของผลการค้นหา
        Log::info('Search results returning to view:', [
            'count' => $projects->count(),
            'project_details' => $projects->map(function($project) {
                return [
                    'id' => $project->Id_Project,
                    'name' => $project->Name_Project,
                    'employee' => $project->employee ? ($project->employee->Firstname . ' ' . $project->employee->Lastname) : 'ไม่ระบุผู้รับผิดชอบ',
                    'indicators_count' => $project->successIndicators->count(),
                    'indicators_data' => $project->successIndicators->map(function($indicator) {
                        return [
                            'id' => $indicator->Id_Indicator,
                            'description' => $indicator->Description_Indicators
                        ];
                    }),
                    'targets_count' => $project->valueTargets->count(),
                    'targets_data' => $project->valueTargets->map(function($target) {
                        return [
                            'id' => $target->Id_Value_Target,
                            'value' => $target->Value_Target,
                            'indicator_id' => $target->Indicator_Id
                        ];
                    })
                ];
            })
        ]);
        
        return response()->json($projects);
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

        log::info($request);

        $strategics = StrategicModel::with(['strategies', 'projects'])->findOrFail($Strategic_Id);
        $strategies = $strategics->strategies;
    
        $request->validate([
            'Name_Strategy' => 'required',
            'Name_Project' => 'required|string|max:255',
        ], [
            'Name_Strategy.required' => 'กรุณาเลือกกลยุทธ์',
            'Name_Project.required' => 'กรุณากรอกชื่อของโครงการ'
        ]);

        $strategy = StrategyModel::find($request->input('Name_Strategy'));
            
        $nameStrategy = $strategy ? $strategy->Name_Strategy : null;
        $strategyId = $strategy ? $strategy->Id_Strategy : null;

        $employee = $request->session()->get('employee');
        $nameCreator = $employee ? $employee->Firstname . ' ' . $employee->Lastname : 'Unknown';
        $roleCreator = $employee ? ($employee->IsManager === 'Y' ? 'IsManager' : $employee->Position_Name) : 'Unknown';

        $project = new ListProjectModel;
        $project->Strategic_Id = $request->Strategic_Id;
        $project->Strategy_Id = $strategyId ?? null;
        $project->Name_Strategy = $nameStrategy ?? null;
        $project->Name_Project = $request->Name_Project;
        $project->Description_Project = $request->Description_Project ?? null;
        $project->Employee_Id = $request->employee_id ?? null;
        $project->Objective_Project = $request->Objective_Project ?? $request->Objective_Project_Other ?? null;
        $project->Principles_Reasons = $request->Principles_Reasons ?? null;
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
    
            if ($request->has('Name_Sub_Project')) {
                foreach ($request->Name_Sub_Project as $subProjectName) {
                    $Sub_Project = new SubProjectModel;
                    $Sub_Project->Project_Id = $project->Id_Project;
                    $Sub_Project->Name_Sub_Project = $subProjectName;
                    $Sub_Project->save();
                    Log::info('Sub-project created with name: ' . $subProjectName);
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

            if ($request->has('indicators') && is_array($request->indicators) && 
                $request->has('targets') && is_array($request->targets) && 
                $request->has('target_types') && is_array($request->target_types)) {
                
                foreach ($request->indicators as $index => $indicatorText) {
                    if (!empty($indicatorText) && isset($request->targets[$index]) && !empty($request->targets[$index]) && isset($request->target_types[$index])) {
                        // สร้างตัวชี้วัด
                        $indicator = new SuccessIndicatorsModel();
                        $indicator->Project_Id = $project->Id_Project;
                        $indicator->Description_Indicators = $indicatorText;
                        $indicator->Type_Indicators = $request->target_types[$index];
                        $indicator->save();
                        
                        // สร้างค่าเป้าหมาย
                        $target = new ValueTargetModel();
                        $target->Project_Id = $project->Id_Project;
                        $target->Value_Target = $request->targets[$index];
                        $target->Type_Value_Target = $request->target_types[$index];
                        $target->save();
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
                    foreach ($request->budget_source as $sourceId) {
                        $projectBudgetSource = new ProjectHasBudgetSourceModel;
                        $projectBudgetSource->Project_Id = $project->Id_Project;
                        $projectBudgetSource->Budget_Source_Id = $sourceId;
                        $projectBudgetSource->Details_Expense = $request->source_detail ?? null;
                        $projectBudgetSource->save();
                        
                        $amountKey = 'amount_' . $sourceId;
                        $amount = $request->input($amountKey);
                        
                        if ($amount) {
                            $budgetSourceTotal = new BudgetSourceTotalModel;
                            $budgetSourceTotal->Amount_Total = $amount;
                            $budgetSourceTotal->Project_has_Budget_Source_Id = $projectBudgetSource->Id_Project_has_Budget_Source;
                            $budgetSourceTotal->save();
                        }
                    }
                    
                    $expenseType = new ExpenseTypesModel;
                    $expenseType->Project_Id = $project->Id_Project;
                    $expenseType->Expense_Status = $request->date_type; 
                    $expenseType->save();
                    
                    Log::info('Expense type created', [
                        'project_id' => $project->Id_Project,
                        'expense_status' => $expenseType->Expense_Status
                    ]);
                    
                    if ($request->has('date')) {
                        foreach ($request->date as $index => $date) {
                            if (!empty($date)) {
                                $expense = new ExpenseModel;
                                $expense->Expense_Types_Id = $expenseType->Id_Expense_Types;
                                $expense->Date_Expense = $date;
                                $expense->Details_Expense = $request->budget_details[$index] ?? null;
                                $expense->save();
                    
                                Log::info('Expense created', [
                                    'expense_id' => $expense->Id_Expense,
                                    'date' => $date,
                                    'details' => $expense->Details_Expense
                                ]);
                                
                                // STANDARD DAY PROCESSING (using budget_category, item_desc, item_amount)
                                if (isset($request->budget_category[$index]) && is_array($request->budget_category[$index])) {
                                    Log::info('Budget categories for date ' . $date . ':', $request->budget_category[$index]);
                                    
                                    foreach ($request->budget_category[$index] as $subIndex => $categoryId) {
                                        Log::info('Processing Category ID: ' . $categoryId . ' at subIndex: ' . $subIndex . ' for day: ' . $index);
                                        
                                        // Initialize variables
                                        $descriptions = null;
                                        $amounts = null;
                                        
                                        // Try to find descriptions in various possible locations
                                        if (isset($request->item_desc[$categoryId][$subIndex])) {
                                            $descriptions = $request->item_desc[$categoryId][$subIndex];
                                            Log::info('Found descriptions in structure 1');
                                        } else if (isset($request->item_desc[$subIndex][$categoryId])) {
                                            $descriptions = $request->item_desc[$subIndex][$categoryId];
                                            Log::info('Found descriptions in structure 2');
                                        } else if (isset($request->item_desc[$index][$categoryId])) {
                                            $descriptions = $request->item_desc[$index][$categoryId];
                                            Log::info('Found descriptions in structure 3');
                                        } else {
                                            // If we didn't find descriptions in standard format, check if there's an indexed version
                                            $itemDescKey = "item_desc_{$index}";
                                            if ($request->has($itemDescKey) && isset($request->$itemDescKey[$categoryId])) {
                                                $descriptions = $request->$itemDescKey[$categoryId];
                                                Log::info("Found descriptions in indexed structure: {$itemDescKey}");
                                            } else {
                                                Log::warning('No descriptions found for category ID: ' . $categoryId);
                                                continue; // Skip this category if no descriptions found
                                            }
                                        }
                                        
                                        // Try to find amounts in various possible locations
                                        if (isset($request->item_amount[$categoryId][$subIndex])) {
                                            $amounts = $request->item_amount[$categoryId][$subIndex];
                                            Log::info('Found amounts in structure 1');
                                        } else if (isset($request->item_amount[$subIndex][$categoryId])) {
                                            $amounts = $request->item_amount[$subIndex][$categoryId];
                                            Log::info('Found amounts in structure 2');
                                        } else if (isset($request->item_amount[$index][$categoryId])) {
                                            $amounts = $request->item_amount[$index][$categoryId];
                                            Log::info('Found amounts in structure 3');
                                        } else {
                                            // If we didn't find amounts in standard format, check if there's an indexed version
                                            $itemAmountKey = "item_amount_{$index}";
                                            if ($request->has($itemAmountKey) && isset($request->$itemAmountKey[$categoryId])) {
                                                $amounts = $request->$itemAmountKey[$categoryId];
                                                Log::info("Found amounts in indexed structure: {$itemAmountKey}");
                                            } else {
                                                Log::warning('No amounts found for category ID: ' . $categoryId);
                                                continue; // Skip this category if no amounts found
                                            }
                                        }
                    
                                        if ($categoryId !== null && $categoryId !== '' && is_numeric($categoryId)) {
                                            // Handle nested descriptions and amounts
                                            $processedDescriptions = [];
                                            $processedAmounts = [];
                                            
                                            // Process descriptions - handle up to 3 levels of nesting
                                            if (is_array($descriptions)) {
                                                foreach ($descriptions as $k1 => $v1) {
                                                    if (is_array($v1)) {
                                                        foreach ($v1 as $k2 => $v2) {
                                                            if (is_array($v2)) {
                                                                foreach ($v2 as $v3) {
                                                                    $processedDescriptions[] = $v3;
                                                                }
                                                            } else {
                                                                $processedDescriptions[] = $v2;
                                                            }
                                                        }
                                                    } else {
                                                        $processedDescriptions[] = $v1;
                                                    }
                                                }
                                            } else {
                                                $processedDescriptions[] = $descriptions;
                                            }
                                            
                                            // Process amounts - handle up to 3 levels of nesting
                                            if (is_array($amounts)) {
                                                foreach ($amounts as $k1 => $v1) {
                                                    if (is_array($v1)) {
                                                        foreach ($v1 as $k2 => $v2) {
                                                            if (is_array($v2)) {
                                                                foreach ($v2 as $v3) {
                                                                    $processedAmounts[] = $v3;
                                                                }
                                                            } else {
                                                                $processedAmounts[] = $v2;
                                                            }
                                                        }
                                                    } else {
                                                        $processedAmounts[] = $v1;
                                                    }
                                                }
                                            } else {
                                                $processedAmounts[] = $amounts;
                                            }
                                            
                                            // Now process each description with its corresponding amount
                                            foreach ($processedDescriptions as $descIndex => $description) {
                                                $amount = isset($processedAmounts[$descIndex]) ? $processedAmounts[$descIndex] : 0;
                                                
                                                // Convert to string safely
                                                $descriptionStr = is_array($description) ? json_encode($description) : (string)$description;
                                                $amountVal = is_array($amount) ? array_sum($amount) : (float)$amount;
                                                
                                                Log::info('Processing item:', [
                                                    'category_id' => $categoryId,
                                                    'description' => $descriptionStr,
                                                    'amount' => $amountVal
                                                ]);
                                                
                                                // Skip empty descriptions
                                                if (empty($descriptionStr)) continue;
                                                
                                                // Check if record already exists
                                                $existingRecord = ExpenHasSubtopicBudgetModel::where('Subtopic_Budget_Id', $categoryId)
                                                    ->where('Expense_Id', $expense->Id_Expense)
                                                    ->where('Details_Expense_Budget', $descriptionStr)
                                                    ->first();
                                                
                                                if (!$existingRecord) {
                                                    $expenseSubtopic = new ExpenHasSubtopicBudgetModel;
                                                    $expenseSubtopic->Subtopic_Budget_Id = (int)$categoryId;
                                                    $expenseSubtopic->Expense_Id = $expense->Id_Expense;
                                                    $expenseSubtopic->Details_Expense_Budget = $descriptionStr;
                                                    $expenseSubtopic->Amount_Expense_Budget = $amountVal;
                                                    
                                                    $expenseSubtopic->save();
                                                    
                                                    Log::info('Expense subtopic created', [
                                                        'expense_id' => $expense->Id_Expense,
                                                        'subtopic_id' => $categoryId,
                                                        'description' => $descriptionStr,
                                                        'amount' => $amountVal
                                                    ]);
                                                } else {
                                                    Log::warning('Duplicate entry skipped', [
                                                        'subtopic_id' => $categoryId,
                                                        'expense_id' => $expense->Id_Expense,
                                                        'description' => $descriptionStr
                                                    ]);
                                                }
                                            }
                                        } else {
                                            Log::warning('Invalid category ID: ' . $categoryId);
                                        }
                                    }
                                } else {
                                    Log::warning('No budget categories found for expense ID: ' . $expense->Id_Expense);
                                }
                                
                                // ADDITIONAL DAY PROCESSING (using category_N, item_desc_N, item_amount_N)
                                // Only process this for indices greater than 0 (additional days)
                                if ($index > 0) {
                                    $categoryKey = "category_{$index}";
                                    
                                    if ($request->has($categoryKey) && is_array($request->$categoryKey)) {
                                        Log::info("Processing additional data for day {$index} with {$categoryKey}");
                                        
                                        foreach ($request->$categoryKey as $catIndex => $categoryId) {
                                            Log::info("Processing day {$index}, category {$categoryId} from {$categoryKey}");
                                            
                                            if ($categoryId !== null && $categoryId !== '' && is_numeric($categoryId)) {
                                                // Get additional descriptions and amounts
                                                $descKey = "item_desc_{$index}";
                                                $amountKey = "item_amount_{$index}";
                                                
                                                if ($request->has($descKey) && isset($request->$descKey[$categoryId])) {
                                                    $additionalDesc = $request->$descKey[$categoryId];
                                                    $additionalAmount = $request->has($amountKey) && isset($request->$amountKey[$categoryId]) 
                                                        ? $request->$amountKey[$categoryId] 
                                                        : [];
                                                        
                                                    Log::info("Found additional data in {$descKey} and {$amountKey} for category {$categoryId}");
                                                    
                                                    // Handle nested descriptions and amounts for additional days
                                                    $processedDescriptions = [];
                                                    $processedAmounts = [];
                                                    
                                                    // Process descriptions - handle up to 3 levels of nesting
                                                    if (is_array($additionalDesc)) {
                                                        foreach ($additionalDesc as $k1 => $v1) {
                                                            if (is_array($v1)) {
                                                                foreach ($v1 as $k2 => $v2) {
                                                                    if (is_array($v2)) {
                                                                        foreach ($v2 as $v3) {
                                                                            $processedDescriptions[] = $v3;
                                                                        }
                                                                    } else {
                                                                        $processedDescriptions[] = $v2;
                                                                    }
                                                                }
                                                            } else {
                                                                $processedDescriptions[] = $v1;
                                                            }
                                                        }
                                                    } else {
                                                        $processedDescriptions[] = $additionalDesc;
                                                    }
                                                    
                                                    // Process amounts - handle up to 3 levels of nesting
                                                    if (is_array($additionalAmount)) {
                                                        foreach ($additionalAmount as $k1 => $v1) {
                                                            if (is_array($v1)) {
                                                                foreach ($v1 as $k2 => $v2) {
                                                                    if (is_array($v2)) {
                                                                        foreach ($v2 as $v3) {
                                                                            $processedAmounts[] = $v3;
                                                                        }
                                                                    } else {
                                                                        $processedAmounts[] = $v2;
                                                                    }
                                                                }
                                                            } else {
                                                                $processedAmounts[] = $v1;
                                                            }
                                                        }
                                                    } else {
                                                        $processedAmounts[] = $additionalAmount;
                                                    }
                                                    
                                                    // Process each description with its amount for this additional day
                                                    foreach ($processedDescriptions as $descIndex => $description) {
                                                        $amount = isset($processedAmounts[$descIndex]) ? $processedAmounts[$descIndex] : 0;
                                                        
                                                        // Convert to string safely
                                                        $descriptionStr = is_array($description) ? json_encode($description) : (string)$description;
                                                        $amountVal = is_array($amount) ? array_sum($amount) : (float)$amount;
                                                        
                                                        // Skip empty descriptions
                                                        if (empty($descriptionStr)) continue;
                                                        
                                                        Log::info("Processing additional day item:", [
                                                            'day' => $index,
                                                            'category_id' => $categoryId,
                                                            'description' => $descriptionStr,
                                                            'amount' => $amountVal
                                                        ]);
                                                        
                                                        // Check if record already exists
                                                        $existingRecord = ExpenHasSubtopicBudgetModel::where('Subtopic_Budget_Id', $categoryId)
                                                            ->where('Expense_Id', $expense->Id_Expense)
                                                            ->where('Details_Expense_Budget', $descriptionStr)
                                                            ->first();
                                                        
                                                        if (!$existingRecord) {
                                                            $expenseSubtopic = new ExpenHasSubtopicBudgetModel;
                                                            $expenseSubtopic->Subtopic_Budget_Id = (int)$categoryId;
                                                            $expenseSubtopic->Expense_Id = $expense->Id_Expense;
                                                            $expenseSubtopic->Details_Expense_Budget = $descriptionStr;
                                                            $expenseSubtopic->Amount_Expense_Budget = $amountVal;
                                                            
                                                            $expenseSubtopic->save();
                                                            
                                                            Log::info('Additional day expense subtopic created', [
                                                                'day' => $index,
                                                                'expense_id' => $expense->Id_Expense,
                                                                'subtopic_id' => $categoryId,
                                                                'description' => $descriptionStr,
                                                                'amount' => $amountVal
                                                            ]);
                                                        } else {
                                                            Log::warning('Additional day duplicate entry skipped', [
                                                                'day' => $index,
                                                                'subtopic_id' => $categoryId,
                                                                'expense_id' => $expense->Id_Expense,
                                                                'description' => $descriptionStr
                                                            ]);
                                                        }
                                                    }
                                                } else {
                                                    Log::warning("No descriptions found in {$descKey} for category {$categoryId}");
                                                }
                                            } else {
                                                Log::warning("Invalid category ID in {$categoryKey}: {$categoryId}");
                                            }
                                        }
                                    } else {
                                        Log::info("No {$categoryKey} array found for day {$index}");
                                    }
                                }
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

            try {
                $maxFileSizeInMB = config('filesystems.max_upload_size', 20);
                $maxFileSizeInBytes = $maxFileSizeInMB * 1024 * 1024;
            
                $baseDir = storage_path('app/public/uploads');
                if (!file_exists($baseDir)) {
                    mkdir($baseDir, 0755, true);
                }
            
                $folderPath = 'uploads/project_' . $project->Id_Project;
            
                if (!Storage::disk('public')->exists($folderPath)) {
                    Storage::disk('public')->makeDirectory($folderPath);
                    // Log::info('Project directory created', ['path' => $folderPath]);
                }
            
                // Calculate total budget from Budget_Source_Total
                $totalBudget = 0;
                $projectBudgetSources = $project->projectBudgetSources;
                
                // Load budget source totals for each project budget source
                foreach ($projectBudgetSources as $pbs) {
                    // Get the budget source total related to this project_has_budget_source
                    $budgetSourceTotal = BudgetSourceTotalModel::where('Project_has_Budget_Source_Id', $pbs->Id_Project_has_Budget_Source)->first();
                    
                    // Add to total if budget source total exists
                    if ($budgetSourceTotal) {
                        $totalBudget += (float)$budgetSourceTotal->Amount_Total;
                    }
                }
                
                // Log the calculated total budget
                // Log::info('Total budget calculated for project', [
                //     'project_id' => $project->Id_Project,
                //     'total_budget' => $totalBudget
                // ]);
            
                // โหลดข้อมูลโครงการพร้อมกับความสัมพันธ์ทั้งหมด รวมถึง successIndicators และ valueTargets
                $pdfData = [
                    'project' => $project->load([
                        'subProjects',
                        'projectBudgetSources.budget_source',
                        'projectBudgetSources.budgetSourceTotal',
                        'strategic',
                        'strategy',
                        'employee',
                        'targets',
                        'locations',
                        'pdca',
                        'platforms',
                        'projectHasSDGs.sdgs',
                        'projectHasIntegrationCategories.integrationCategory',
                        'projectHasIndicators.indicators',
                        'successIndicators', // เพิ่มความสัมพันธ์ตัวชี้วัดความสำเร็จ
                        'valueTargets' // เพิ่มความสัมพันธ์ค่าเป้าหมาย
                    ]),
                    'strategy' => $strategy,
                    'nameCreator' => $nameCreator,
                    'roleCreator' => $roleCreator,
                    'projectBudgetSources' => $projectBudgetSources,
                    'totalBudget' => $totalBudget // Pass the calculated total budget to the view
                ];
            
                $pdf = PDF::loadView('PDF.PDFFirstForm', $pdfData);
                $pdf->setPaper('A4');
            
                $pdf->getDomPDF()->set_option("fontDir", public_path('fonts/'));
                $pdf->getDomPDF()->set_option("font_cache", public_path('fonts/'));
                $pdf->getDomPDF()->set_option("defaultFont", "THSarabunNew");
                $pdf->getDomPDF()->set_option("isRemoteEnabled", true);
            
                $filename = 'project_' . $project->Id_Project . '_summary_' . date('Y-m-d') . '.pdf';
                $filePath = $folderPath . '/' . $filename;
            
                $pdfContent = $pdf->output();
            
                $pdfSize = strlen($pdfContent);
                $pdfSizeInMB = round($pdfSize / (1024 * 1024), 2);
            
                if ($pdfSizeInMB > $maxFileSizeInMB) {
                    throw new \Exception("PDF size ({$pdfSizeInMB} MB) exceeds the limit of {$maxFileSizeInMB} MB");
                }
            
                if (Storage::disk('public')->put($filePath, $pdfContent)) {
                    $storageFile = StorageFileModel::create([
                        'Name_Storage_File' => $filename,
                        'Path_Storage_File' => $filePath,
                        'Type_Storage_File' => 'application/pdf',
                        'Size' => $pdfSize,
                        'Project_Id' => $project->Id_Project
                    ]);
            
                    if (!$storageFile) {
                        throw new \Exception('Failed to create storage file record');
                    }
            
                    // Log::info('PDF generated and saved successfully', [
                    //     'project_id' => $project->Id_Project,
                    //     'file_name' => $filename,
                    //     'path' => $filePath,
                    //     'size' => $storageFile->getSizeInMB() . 'MB'
                    // ]);
            
                } else {
                    throw new \Exception('Failed to save PDF file');
                }
            
            } catch (\Exception $e) {
                Log::error('Failed to generate PDF', [
                    'project_id' => $project->Id_Project,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
            
        } else {
            Log::error('Failed to create project.');
        }
        
        return redirect()->route('project', ['Strategic_Id' => $Strategic_Id])->with('success', 'โครงการถูกสร้างเรียบร้อยแล้ว');
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

        if ($request->has('Name_Sub_Project')) {
            $project->subProjects()->delete();
            foreach ($request->Name_Sub_Project as $subProjectName) {
                $subProject = new SubProjectModel;
                $subProject->Project_Id = $project->Id_Project ?? null;
                $subProject->Name_Sub_Project = $subProjectName ?? null ; 
                $subProject->save();
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

    public function editAllProject($Id_Project, Request $request)
    {
        $project = ListProjectModel::findOrFail($Id_Project);
        $strategics = StrategicModel::with(['strategies.kpis', 'strategies.strategicObjectives', 'projects'])->findOrFail($project->Strategic_Id);
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
        $sourcePage = $request->input('sourcePage', 'listProject');

        log::info($strategicObjectives);
            
        return view('Project.editBigFormProject', compact('project', 'strategics', 'strategies', 'projects', 'employees', 'sdgs', 'nameStrategicPlan', 'integrationCategories', 'months', 'pdcaStages', 'budgetSources', 'subtopBudgets', 'kpis', 'strategicObjectives', 'sourcePage'));
    }

    public function resetStatus($id)
    {
        $project = ListProjectModel::findOrFail($id);
        $project->approvals->first()->Status = 'I';
        $project->approvals->first()->save();
    
        $project->Count_Steps = 2;
        $project->save();
    
        return redirect()->route('proposeProject')->with('success', 'Project status reset to I and Count_Steps reset to 2 successfully.');
    }

    public function updateField(Request $request)
    {
        $project = ListProjectModel::findOrFail($request->id);
        $field = $request->field;
        $value = $request->value;

        $project->$field = $value;
        $project->save();

        return response()->json(['success' => true]);
    }

}