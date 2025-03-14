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
use App\Models\ObjectiveProjectModel;
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
                'successIndicators',  
                'valueTargets',       
                'employee',           
                'strategy'            
            ])
            ->select([
                'Id_Project', 
                'Name_Project', 
                'Strategy_Id', 
                'Employee_Id',
                'Objective_Project', 
                'Principles_Reasons' 
            ])
            ->get();
        
    
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
                    $target->Success_Indicators_Id = $indicator->Id_Success_Indicators; 
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
        $project = ListProjectModel::with([
            'subProjects', 
            'successIndicators.valueTargets', 
            'projectBudgetSources.budgetSource', 
            'projectBudgetSources.budgetSourceTotal',
            'expenseTypes.expenses.expenHasSubtopicBudgets',
            'objectives'  
        ])->findOrFail($Id_Project);

        $expenseStatus = $project->expenseTypes->first()->Expense_Status ?? 'O';
        $expenses = $project->expenseTypes->first()->expenses ?? [];

        $expenseSubtopics = [];
        foreach ($expenses as $expense) {
            if (isset($expense->expenHasSubtopicBudgets) && count($expense->expenHasSubtopicBudgets) > 0) {
                $expenseSubtopics[$expense->Id_Expense] = $expense->expenHasSubtopicBudgets;
                Log::info('Expense ID: ' . $expense->Id_Expense . ' has ' . count($expense->expenHasSubtopicBudgets) . ' subtopics');
            } else {
                $expenseSubtopics[$expense->Id_Expense] = [];
                Log::info('Expense ID: ' . $expense->Id_Expense . ' has no subtopics');
            }
        }

        $strategics = StrategicModel::with(['strategies.kpis', 'strategies.strategicObjectives', 'projects'])->get();
        $strategies = StrategyModel::where('Strategic_Id', $project->Strategic_Id)->get();
        
        // Get all projects for the current strategic plan
        $projects = ListProjectModel::where('Strategic_Id', $project->Strategic_Id)->get();
        
        $employees = EmployeeModel::all();
        $budgetSources = BudgetSourceModel::all();
        $subtopBudgets = SubtopBudgetModel::orderBy('Name_Subtopic_Budget', 'asc')->get();
        $strategicObjectives = StrategicObjectivesModel::all();
        $kpis = KpiModel::all();
        $sdgs = SDGsModel::all();
        
        // Get selected SDGs for this project
        $selectedSdgs = ProjectHasSDGModel::where('Project_Id', $Id_Project)
            ->pluck('SDGs_Id')
            ->toArray();
        
        // Get selected integration categories for this project
        $selectedIntegrations = ProjectHasIntegrationCategoryModel::where('Project_Id', $Id_Project)
            ->pluck('Integration_Category_Id')
            ->toArray();
        
        // Get integration details
        $integrationDetails = [];
        $projectIntegrations = ProjectHasIntegrationCategoryModel::where('Project_Id', $Id_Project)->get();
        foreach ($projectIntegrations as $integration) {
            $integrationDetails[$integration->Integration_Category_Id] = $integration->Integration_Details;
        }
        
        $integrationCategories = IntegrationModel::orderBy('Id_Integration_Category', 'asc')->get();
        $months = MonthsModel::orderBy('Id_Months', 'asc')->pluck('Name_Month', 'Id_Months');
        $pdcaStages = PdcaModel::all();
        $nameStrategicPlan = $strategics->first()->Name_Strategic_Plan ?? '';
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

        $sourcePage = $request->input('sourcePage', 'listProject');

        return view('Project.editProject', compact(
            'project', 'strategics', 'strategies', 'projects', 'employees', 'sdgs', 
            'selectedSdgs', 'nameStrategicPlan', 'integrationCategories', 'selectedIntegrations',
            'integrationDetails', 'months', 'pdcaStages', 'budgetSources', 'subtopBudgets', 
            'kpis', 'strategicObjectives', 'mainCategories', 'expenseSubtopics', 'expenseStatus',
            'expenses', 'sourcePage'
        ));
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
        
        // Ensure values are strings, not arrays
        if (isset($request->Principles_Reasons)) {
            $project->Principles_Reasons = is_array($request->Principles_Reasons) ? 
                implode(', ', $request->Principles_Reasons) : $request->Principles_Reasons;
        }
        
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
    
        if ($request->has('Name_Sub_Project') && is_array($request->Name_Sub_Project)) {
            $project->subProjects()->delete();
            foreach ($request->Name_Sub_Project as $subProjectName) {
                if (is_string($subProjectName)) { // Ensure it's a string
                    $subProject = new SubProjectModel;
                    $subProject->Project_Id = $project->Id_Project ?? null;
                    $subProject->Name_Sub_Project = $subProjectName ?? null; 
                    $subProject->save();
                }
            }
        }
    
        if ($request->Status_Budget !== 'N') {
            if ($request->has('budget_source')) {
                $budget_source = is_array($request->budget_source) ? 
                    $request->budget_source[0] : $request->budget_source;
                    
                $projectBudgetSource = new ProjectHasBudgetSourceModel;
                $projectBudgetSource->Project_Id = $project->Id_Project;
                $projectBudgetSource->Budget_Source_Id = $budget_source;
                
                // Handle source_detail value
                $source_detail = $request->source_detail ?? null;
                if (is_array($source_detail)) {
                    $source_detail = implode(', ', $source_detail);
                }
                $projectBudgetSource->Details_Expense = $source_detail;
                
                $projectBudgetSource->save();
                Log::info('Project budget source created with ID: ' . $budget_source);
                
                // Handle amount value in BudgetSourceTotal model instead
                $amountKey = 'amount_' . $budget_source;
                $amount = $request->has($amountKey) ? $request->$amountKey : null;
                if (is_array($amount)) {
                    $amount = array_sum($amount);
                }
                
                if ($amount) {
                    $budgetSourceTotal = new BudgetSourceTotalModel;
                    $budgetSourceTotal->Amount_Total = $amount;
                    $budgetSourceTotal->Project_has_Budget_Source_Id = $projectBudgetSource->Id_Project_has_Budget_Source;
                    $budgetSourceTotal->save();
                }
            }
        
            if ($request->has('activity') && is_array($request->activity)) {
                foreach ($request->activity as $index => $activity) {
                    if (!is_string($activity)) continue;
                    
                    $budgetForm = new BudgetFormModel;
                    $budgetForm->Budget_Source_Id = $request->budget_source;
                    $budgetForm->Project_Id = $project->Id_Project;
                    $budgetForm->Big_Topic = $activity ?? null;
                    
                    // Handle total_amount value
                    $total_amount = isset($request->total_amount[$index]) ? $request->total_amount[$index] : null;
                    if (is_array($total_amount)) {
                        $total_amount = array_sum($total_amount);
                    }
                    $budgetForm->Amount_Big = $total_amount;
                    
                    $budgetForm->save();
                    Log::info('Budget form created with big topic: ' . $activity);
        
                    if ($request->has('subActivity') && is_array($request->subActivity) && isset($request->subActivity[$index])) {
                        foreach ($request->subActivity[$index] as $subIndex => $subActivity) {
                            $subtopicBudgetForm = new SubtopicBudgetHasBudgetFormModel;
                            $subtopicBudgetForm->Subtopic_Budget_Id = $subActivity ?? null;
                            $subtopicBudgetForm->Budget_Form_Id = $budgetForm->Id_Budget_Form;
                            
                            // Handle description value
                            $description = isset($request->description[$index][$subIndex]) ? 
                                $request->description[$index][$subIndex] : null;
                            if (is_array($description)) {
                                $description = implode(', ', $description);
                            }
                            $subtopicBudgetForm->Details_Subtopic_Form = $description;
                            
                            // Handle amount value
                            $amount = isset($request->amount[$index][$subIndex]) ? 
                                $request->amount[$index][$subIndex] : null;
                            if (is_array($amount)) {
                                $amount = array_sum($amount);
                            }
                            $subtopicBudgetForm->Amount_Sub = $amount;
                            
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
        $project = ListProjectModel::with([
            'subProjects', 
            'successIndicators.valueTargets', 
            'projectBudgetSources.budgetSource', 
            'projectBudgetSources.budgetSourceTotal',
            'expenseTypes.expenses.expenHasSubtopicBudgets',
            'pdca',          
            'monthlyPlans',  
            'shortProjects'  
        ])->findOrFail($Id_Project);
    
        // Get budget information - more direct access
        $budgetSources = BudgetSourceModel::all();
        $projectBudgetSources = ProjectHasBudgetSourceModel::with(['budgetSource', 'budgetSourceTotal'])
            ->where('Project_Id', $Id_Project)
            ->get();
        
        // Calculate total project budget
        $totalProjectBudget = 0;
        foreach ($projectBudgetSources as $pbs) {
            if (isset($pbs->budgetSourceTotal)) {
                $totalProjectBudget += (float)$pbs->budgetSourceTotal->Amount_Total;
            }
        }
        
        // Get expense type with detailed expense information
        $expenseTypes = ExpenseTypesModel::with(['expenses.expenHasSubtopicBudgets'])
            ->where('Project_Id', $Id_Project)
            ->get();
        
        $expenseStatus = $project->expenseTypes->first()->Expense_Status ?? 'O';
        $expenses = [];
        $expenseSubtopics = [];
        $expenseTotalsByDay = [];
        
        // Process expenses and subtopics
        foreach ($expenseTypes as $expenseType) {
            foreach ($expenseType->expenses as $expense) {
                $expenses[] = $expense;
                
                // Group expense subtopics by expense ID
                $expenseSubtopics[$expense->Id_Expense] = $expense->expenHasSubtopicBudgets;
                
                // Calculate total amount for each expense
                $dayTotal = 0;
                foreach ($expense->expenHasSubtopicBudgets as $subtopic) {
                    $dayTotal += (float)$subtopic->Amount_Expense_Budget;
                }
                $expenseTotalsByDay[$expense->Id_Expense] = $dayTotal;
            }
        }
        
        // ดึงข้อมูล SDGs ที่เลือกสำหรับโครงการนี้
        $selectedSdgs = ProjectHasSDGModel::where('Project_Id', $Id_Project)
            ->pluck('SDGs_Id')
            ->toArray();
    
        // ดึงข้อมูล Integration Categories ที่เลือกสำหรับโครงการนี้
        $selectedIntegrations = ProjectHasIntegrationCategoryModel::where('Project_Id', $Id_Project)
            ->pluck('Integration_Category_Id')
            ->toArray();
    
        // สร้าง array เพื่อเก็บรายละเอียดของแต่ละ integration category
        $integrationDetails = [];
        $projectIntegrations = ProjectHasIntegrationCategoryModel::where('Project_Id', $Id_Project)->get();
        foreach ($projectIntegrations as $integration) {
            $integrationDetails[$integration->Integration_Category_Id] = $integration->Integration_Details;
        }
    
        $strategics = StrategicModel::with(['strategies.kpis', 'strategies.strategicObjectives', 'projects'])
            ->findOrFail($project->Strategic_Id);
        $strategies = $strategics->strategies;
        $projects = $strategics->projects; 
        $employees = EmployeeModel::all();
        $sdgs = SDGsModel::all(); 
        $nameStrategicPlan = $strategics->Name_Strategic_Plan;
        $integrationCategories = IntegrationModel::orderBy('Id_Integration_Category', 'asc')->get();
        $months = MonthsModel::orderBy('Id_Months', 'asc')->pluck('Name_Month', 'Id_Months');
        $pdcaStages = PdcaModel::all();
        
        // Fetch PDCA details for this project by stages
        $pdcaDetails = PdcaDetailsModel::where('Project_Id', $Id_Project)->get();
        $pdcaDetailsByStage = [];
        
        foreach ($pdcaDetails as $detail) {
            $pdcaDetailsByStage[$detail->PDCA_Stages_Id] = $detail;
        }
        
        // Get monthly plan information for PDCA stages
        $monthlyPlans = MonthlyPlansModel::where('Project_id', $Id_Project)->get();
        $monthlyPlansByStage = [];
        
        foreach ($monthlyPlans as $plan) {
            if (!isset($monthlyPlansByStage[$plan->PDCA_Stages_Id])) {
                $monthlyPlansByStage[$plan->PDCA_Stages_Id] = [];
            }
            $monthlyPlansByStage[$plan->PDCA_Stages_Id][] = $plan;
        }
        
        // Get short project details
        $shortProjects = ShortProjectModel::where('Project_Id', $Id_Project)->get();
        
        $subtopBudgets = SubtopBudgetModel::all();
        $kpis = KpiModel::all();
        $strategicObjectives = StrategicObjectivesModel::all();
    
        // Fetch platforms with programs and KPIs
        $platforms = PlatformModel::where('Project_Id', $Id_Project)->with('programs.kpis')->get();
            
        // Define $mainCategories
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
    
        // Group subtopics by category for easier display
        $subtopicsByCategory = [];
        foreach ($subtopBudgets as $subtop) {
            $parts = explode(' / ', $subtop->Name_Subtopic_Budget);
            if (count($parts) > 1) {
                $mainCategory = trim($parts[0]);
                if (!isset($subtopicsByCategory[$mainCategory])) {
                    $subtopicsByCategory[$mainCategory] = [];
                }
                $subtopicsByCategory[$mainCategory][] = $subtop;
            }
        }
    
        // Log important information for debugging
        Log::info('Project budget sources', [
            'project_id' => $Id_Project,
            'budget_sources_count' => count($projectBudgetSources),
            'total_budget' => $totalProjectBudget
        ]);
        
        Log::info('Expense information', [
            'project_id' => $Id_Project,
            'expense_types_count' => count($expenseTypes),
            'expenses_count' => count($expenses),
        ]);
                
        return view('Project.editBigFormProject', compact(
            'project', 'strategics', 'strategies', 'projects', 'employees', 'sdgs', 
            'selectedSdgs', 'nameStrategicPlan', 'integrationCategories', 'selectedIntegrations',
            'integrationDetails', 'months', 'pdcaStages', 'budgetSources', 'subtopBudgets', 
            'kpis', 'strategicObjectives', 'mainCategories', 'platforms',
            'pdcaDetails', 'pdcaDetailsByStage', 'monthlyPlans', 'monthlyPlansByStage', 'shortProjects',
            'projectBudgetSources', 'totalProjectBudget', 'expenseTypes', 'expenseStatus', 
            'expenses', 'expenseSubtopics', 'expenseTotalsByDay', 'subtopicsByCategory'
        ));
    }

    public function resetStatus($id)
    {
        $project = ListProjectModel::findOrFail($id);
        $project->approvals->first()->Status = 'I';
        $project->approvals->first()->save();

        $project->Count_Steps = 2;
        $project->save();

        $existingPlatforms = $project->platforms()->with(['programs.kpis'])->get();

        foreach ($existingPlatforms as $platform) {
            foreach ($platform->programs as $program) {
                $program->kpis()->delete();
            }
            $platform->programs()->delete();
        }
        $project->platforms()->delete();
        
        PdcaDetailsModel::where('Project_Id', $id)->delete();
        MonthlyPlansModel::where('Project_id', $id)->delete();
        
        ShortProjectModel::where('Project_Id', $id)->delete();
        
        ValueTargetModel::where('Project_Id', $id)->delete();
        SuccessIndicatorsModel::where('Project_Id', $id)->delete();
        
        $projectBudgetSources = ProjectHasBudgetSourceModel::where('Project_Id', $id)->get();
        
        foreach ($projectBudgetSources as $pbs) {
            BudgetSourceTotalModel::where('Project_has_Budget_Source_Id', $pbs->Id_Project_has_Budget_Source)->delete();
        }

        $projectBudgetSources = ProjectHasBudgetSourceModel::where('Project_Id', $id)->get();
        
        foreach ($projectBudgetSources as $pbs) {
            BudgetSourceTotalModel::where('Project_has_Budget_Source_Id', $pbs->Id_Project_has_Budget_Source)->delete();
        }
        
        ProjectHasBudgetSourceModel::where('Project_Id', $id)->delete();
        
        $expenseTypes = ExpenseTypesModel::where('Project_Id', $id)->get();
        
        foreach ($expenseTypes as $expenseType) {
            $expenses = ExpenseModel::where('Expense_Types_Id', $expenseType->Id_Expense_Types)->get();
            
            foreach ($expenses as $expense) {
                ExpenHasSubtopicBudgetModel::where('Expense_Id', $expense->Id_Expense)->delete();
            }
            
            ExpenseModel::where('Expense_Types_Id', $expenseType->Id_Expense_Types)->delete();
        }
        
        ExpenseTypesModel::where('Project_Id', $id)->delete();

        $request = request();
        $logEntries = []; 

        if ($request->has('platforms')) {
            foreach ($request->platforms as $platformData) {
                if (!empty($platformData['name'])) {
                    $platform = new PlatformModel;
                    $platform->Name_Platform = $platformData['name'];
                    $platform->Project_Id = $project->Id_Project;
                    $platform->save();
                    $logEntries[] = 'Platform created with name: ' . $platformData['name'];

                    if (isset($platformData['programs']) && is_array($platformData['programs'])) {
                        foreach ($platformData['programs'] as $programData) {
                            if (!empty($programData['name'])) {
                                $program = new ProgramModel;
                                $program->Name_Program = $programData['name'];
                                $program->Platform_Id = $platform->Id_Platform;
                                $program->save();
                                $logEntries[] = 'Program created with name: ' . $programData['name'];

                                if (isset($programData['kpis']) && is_array($programData['kpis'])) {
                                    foreach ($programData['kpis'] as $kpiData) {
                                        if (!empty($kpiData['name'])) {
                                            $kpi = new Kpi_ProgramModel;
                                            $kpi->Name_KPI = $kpiData['name'];
                                            $kpi->Program_Id = $program->Id_Program;
                                            $kpi->save();
                                            $logEntries[] = 'KPI created with name: ' . $kpiData['name'];
                                        }
                                    }
                                }
                            }
                        }
                    }
                    // Support for the old format if needed (fallback)
                    else if (!empty($platformData['program'])) {
                        $program = new ProgramModel;
                        $program->Name_Program = $platformData['program'];
                        $program->Platform_Id = $platform->Id_Platform;
                        $program->save();
                        $logEntries[] = 'Program created with name (old format): ' . $platformData['program'];

                        if (isset($platformData['kpis']) && is_array($platformData['kpis'])) {
                            foreach ($platformData['kpis'] as $kpiName) {
                                if (!empty($kpiName)) {
                                    $kpi = new Kpi_ProgramModel;
                                    $kpi->Name_KPI = $kpiName;
                                    $kpi->Program_Id = $program->Id_Program;
                                    $kpi->save();
                                    $logEntries[] = 'KPI created with name (old format): ' . $kpiName;
                                }
                            }
                        }
                    }
                }
            }
        }
        
        // Process success indicators and value targets
        if ($request->has('indicators') && is_array($request->indicators) && 
            $request->has('targets') && is_array($request->targets) && 
            $request->has('target_types') && is_array($request->target_types)) {
            
            foreach ($request->indicators as $index => $indicatorText) {
                if (!empty($indicatorText) && isset($request->targets[$index]) && !empty($request->targets[$index]) && isset($request->target_types[$index])) {
                    // Create the success indicator
                    $indicator = new SuccessIndicatorsModel();
                    $indicator->Project_Id = $project->Id_Project;
                    $indicator->Description_Indicators = $indicatorText;
                    $indicator->Type_Indicators = $request->target_types[$index];
                    $indicator->save();
                    $logEntries[] = 'Success indicator created: ' . $indicatorText;
                    
                    // Create the value target
                    $target = new ValueTargetModel();
                    $target->Project_Id = $project->Id_Project;
                    $target->Value_Target = $request->targets[$index];
                    $target->Type_Value_Target = $request->target_types[$index];
                    $target->Success_Indicators_Id = $indicator->Id_Success_Indicators; 
                    $target->save();
                    $logEntries[] = 'Value target created: ' . $request->targets[$index];
                }
            }
        }
        
        // Process budget information
        if ($request->has('Status_Budget') && $request->Status_Budget !== 'N') {
            if ($request->has('budget_source')) {
                foreach ($request->budget_source as $sourceId) {
                    $projectBudgetSource = new ProjectHasBudgetSourceModel;
                    $projectBudgetSource->Project_Id = $project->Id_Project;
                    $projectBudgetSource->Budget_Source_Id = $sourceId;
                    $projectBudgetSource->Details_Expense = $request->source_detail ?? null;
                    $projectBudgetSource->save();
                    $logEntries[] = 'Budget source created with ID: ' . $sourceId;
                    
                    $amountKey = 'amount_' . $sourceId;
                    $amount = $request->input($amountKey);
                    
                    if ($amount) {
                        $budgetSourceTotal = new BudgetSourceTotalModel;
                        $budgetSourceTotal->Amount_Total = $amount;
                        $budgetSourceTotal->Project_has_Budget_Source_Id = $projectBudgetSource->Id_Project_has_Budget_Source;
                        $budgetSourceTotal->save();
                        $logEntries[] = 'Budget source total created with amount: ' . $amount;
                    }
                }
                
                $expenseType = new ExpenseTypesModel;
                $expenseType->Project_Id = $project->Id_Project;
                $expenseType->Expense_Status = $request->date_type ?? 'O'; 
                $expenseType->save();
                $logEntries[] = 'Expense type created with status: ' . $expenseType->Expense_Status;
                
                if ($request->has('date')) {
                    foreach ($request->date as $index => $date) {
                        if (!empty($date)) {
                            $expense = new ExpenseModel;
                            $expense->Expense_Types_Id = $expenseType->Id_Expense_Types;
                            $expense->Date_Expense = $date;
                            $expense->Details_Expense = $request->budget_details[$index] ?? null;
                            $expense->save();
                            $logEntries[] = 'Expense created for date: ' . $date;
                            
                            // Process standard day budget categories
                            if (isset($request->budget_category[$index]) && is_array($request->budget_category[$index])) {
                                foreach ($request->budget_category[$index] as $subIndex => $categoryId) {
                                    // Initialize variables
                                    $descriptions = null;
                                    $amounts = null;
                                    
                                    // Try to find descriptions in various possible locations
                                    if (isset($request->item_desc[$categoryId][$subIndex])) {
                                        $descriptions = $request->item_desc[$categoryId][$subIndex];
                                    } else if (isset($request->item_desc[$subIndex][$categoryId])) {
                                        $descriptions = $request->item_desc[$subIndex][$categoryId];
                                    } else if (isset($request->item_desc[$index][$categoryId])) {
                                        $descriptions = $request->item_desc[$index][$categoryId];
                                    } else {
                                        $itemDescKey = "item_desc_{$index}";
                                        if ($request->has($itemDescKey) && isset($request->$itemDescKey[$categoryId])) {
                                            $descriptions = $request->$itemDescKey[$categoryId];
                                        } else {
                                            continue; // Skip if no descriptions found
                                        }
                                    }
                                    
                                    // Try to find amounts in various possible locations
                                    if (isset($request->item_amount[$categoryId][$subIndex])) {
                                        $amounts = $request->item_amount[$categoryId][$subIndex];
                                    } else if (isset($request->item_amount[$subIndex][$categoryId])) {
                                        $amounts = $request->item_amount[$subIndex][$categoryId];
                                    } else if (isset($request->item_amount[$index][$categoryId])) {
                                        $amounts = $request->item_amount[$index][$categoryId];
                                    } else {
                                        $itemAmountKey = "item_amount_{$index}";
                                        if ($request->has($itemAmountKey) && isset($request->$itemAmountKey[$categoryId])) {
                                            $amounts = $request->$itemAmountKey[$categoryId];
                                        } else {
                                            continue; // Skip if no amounts found
                                        }
                                    }
                                    
                                    if ($categoryId !== null && $categoryId !== '' && is_numeric($categoryId)) {
                                        // Flatten nested arrays of descriptions and amounts
                                        $processedDescriptions = $this->flattenArray($descriptions);
                                        $processedAmounts = $this->flattenArray($amounts);
                                        
                                        // Process each description with its corresponding amount
                                        foreach ($processedDescriptions as $descIndex => $description) {
                                            $amount = isset($processedAmounts[$descIndex]) ? $processedAmounts[$descIndex] : 0;
                                            
                                            // Convert to string safely
                                            $descriptionStr = is_array($description) ? json_encode($description) : (string)$description;
                                            $amountVal = is_array($amount) ? array_sum($amount) : (float)$amount;
                                            
                                            // Skip empty descriptions
                                            if (empty($descriptionStr)) continue;
                                            
                                            $expenseSubtopic = new ExpenHasSubtopicBudgetModel;
                                            $expenseSubtopic->Subtopic_Budget_Id = (int)$categoryId;
                                            $expenseSubtopic->Expense_Id = $expense->Id_Expense;
                                            $expenseSubtopic->Details_Expense_Budget = $descriptionStr;
                                            $expenseSubtopic->Amount_Expense_Budget = $amountVal;
                                            $expenseSubtopic->save();
                                            
                                            $logEntries[] = "Budget item created: {$descriptionStr} - {$amountVal}";
                                        }
                                    }
                                }
                            }
                            
                            // Process additional day budget categories
                            if ($index > 0) {
                                $categoryKey = "category_{$index}";
                                
                                if ($request->has($categoryKey) && is_array($request->$categoryKey)) {
                                    foreach ($request->$categoryKey as $catIndex => $categoryId) {
                                        if ($categoryId !== null && $categoryId !== '' && is_numeric($categoryId)) {
                                            $descKey = "item_desc_{$index}";
                                            $amountKey = "item_amount_{$index}";
                                            
                                            if ($request->has($descKey) && isset($request->$descKey[$categoryId])) {
                                                $additionalDesc = $request->$descKey[$categoryId];
                                                $additionalAmount = $request->has($amountKey) && isset($request->$amountKey[$categoryId]) 
                                                    ? $request->$amountKey[$categoryId] 
                                                    : [];
                                                    
                                                // Flatten nested arrays of descriptions and amounts
                                                $processedDescriptions = $this->flattenArray($additionalDesc);
                                                $processedAmounts = $this->flattenArray($additionalAmount);
                                                
                                                // Process each description with its corresponding amount
                                                foreach ($processedDescriptions as $descIndex => $description) {
                                                    $amount = isset($processedAmounts[$descIndex]) ? $processedAmounts[$descIndex] : 0;
                                                    
                                                    // Convert to string safely
                                                    $descriptionStr = is_array($description) ? json_encode($description) : (string)$description;
                                                    $amountVal = is_array($amount) ? array_sum($amount) : (float)$amount;
                                                    
                                                    // Skip empty descriptions
                                                    if (empty($descriptionStr)) continue;
                                                    
                                                    $expenseSubtopic = new ExpenHasSubtopicBudgetModel;
                                                    $expenseSubtopic->Subtopic_Budget_Id = (int)$categoryId;
                                                    $expenseSubtopic->Expense_Id = $expense->Id_Expense;
                                                    $expenseSubtopic->Details_Expense_Budget = $descriptionStr;
                                                    $expenseSubtopic->Amount_Expense_Budget = $amountVal;
                                                    $expenseSubtopic->save();
                                                    
                                                    $logEntries[] = "Additional day budget item created: {$descriptionStr} - {$amountVal}";
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
        // Process PDCA data
        if ($request->has('pdca')) {
            foreach ($request->pdca as $stageId => $details) {
                if (!empty($details['detail'])) {
                    $pdcaDetail = new PdcaDetailsModel;
                    $pdcaDetail->PDCA_Stages_Id = $stageId;
                    $pdcaDetail->Details_PDCA = $details['detail'];
                    $pdcaDetail->Project_Id = $project->Id_Project;
                    $pdcaDetail->save();
                    $logEntries[] = 'PDCA stage created with ID: ' . $stageId;

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
                            $logEntries[] = 'PDCA stage month saved: ' . $month . ' for fiscal year: ' . $fiscalYear;
                        }
                    }
                }
            }
        }
        
        // Process short project data
        if ($request->has('Project_Type') && $request->Project_Type == 'S' && $request->has('Details_Short_Project')) {
            $project->Project_Type = 'S';
            $project->save();
            
            foreach ($request->Details_Short_Project as $detail) {
                if (!empty($detail)) {
                    $shortProject = new ShortProjectModel;
                    $shortProject->Project_Id = $project->Id_Project;
                    $shortProject->Details_Short_Project = $detail;
                    $shortProject->save();
                    $logEntries[] = 'Short project created with detail: ' . $detail;
                }
            }
        } else if ($request->has('Project_Type') && $request->Project_Type == 'L') {
            $project->Project_Type = 'L';
            $project->save();
            $logEntries[] = 'Long-term project type set';
        }

        // Log all entries in the correct order
        foreach ($logEntries as $entry) {
            Log::info($entry);
        }

        // Generate and save PDF after resetting the status and updating platforms
        $this->generateAndSavePDF($id);

        return redirect()->route('proposeProject')->with('success', 'Project status reset to I and Count_Steps reset to 2 successfully.');
    }

    /**
     * Helper function to flatten multi-level arrays
     */
    private function flattenArray($array) {
        $result = [];
        
        if (!is_array($array)) {
            return [$array];
        }
        
        foreach ($array as $item) {
            if (is_array($item)) {
                $result = array_merge($result, $this->flattenArray($item));
            } else {
                $result[] = $item;
            }
        }
        
        return $result;
    }

    public function generateAndSavePDF($Id_Project)
    {
        try {
            // Define digits function locally if it doesn't exist
            if (!function_exists('digits')) {
                function digits($number) {
                    if (!$number) return toThaiNumber('0.00');
                    $formatted = number_format((float)$number, 2, '.', ',');
                    return toThaiNumber($formatted);
                }
            }

            $project = ListProjectModel::with(['strategic.strategies', 'sdgs','monthlyPlans.pdca','monthlyPlans.pdca.pdcaDetail'])
                        ->where('Id_Project', $Id_Project)
                        ->firstOrFail();

            $projectBudgetSources = ProjectHasBudgetSourceModel::with('budgetSource')
                        ->where('Project_Id', $Id_Project)->get();

            $outcome = OutcomeModel::with('project')->where('Project_Id', $Id_Project)->get();
            $output = OutputModel::with('project')->where('Project_Id', $Id_Project)->get();
            $expectedResult = ExpectedResultsModel::with('project')->where('Project_Id', $Id_Project)->get();

            $project->formatted_first_time = formatDateThai($project->First_Time);
            $project->formatted_end_time = formatDateThai($project->End_Time);

            $projectBudgetSources = $projectBudgetSources->map(function ($budget) {
                $budget->Amount_Total = toThaiNumber($budget->Amount_Total);
                return $budget;
            });

            $quarterProjects = MonthlyPlansModel::where('Project_Id', $Id_Project)
            ->pluck('Fiscal_Year')
            ->unique();

            // Load PDCA details separately - keep these for backward compatibility
            $pdcaDetails = PdcaDetailsModel::where('Project_Id', $Id_Project)->get();
            $monthlyPlans = MonthlyPlansModel::where('Project_id', $Id_Project)->get();
            
            // Group monthly plans by PDCA stage ID for easy access in the view
            $monthlyPlansByStage = [];
            foreach ($monthlyPlans as $plan) {
                if (!isset($monthlyPlansByStage[$plan->PDCA_Stages_Id])) {
                    $monthlyPlansByStage[$plan->PDCA_Stages_Id] = [];
                }
                $monthlyPlansByStage[$plan->PDCA_Stages_Id][] = $plan;
            }
        
            // Group PDCA details by stage ID for easy access in the view
            $pdcaDetailsByStage = [];
            foreach ($pdcaDetails as $detail) {
                $pdcaDetailsByStage[$detail->PDCA_Stages_Id] = $detail;
            }

            $data = [
                'title' => $project->Name_Project,
                'date' => toThaiNumber(date('d/m/Y')),
                'project' => $project,
                'projectBudgetSources' => $projectBudgetSources,
                'outcome' => $outcome,
                'output' => $output,
                'expectedResult' => $expectedResult,
                'quarterProjects' => $quarterProjects,
                'pdcaDetails' => $pdcaDetails,
                'pdcaDetailsByStage' => $pdcaDetailsByStage,
                'monthlyPlans' => $monthlyPlans,
                'monthlyPlansByStage' => $monthlyPlansByStage,
                'digits' => function($number) { // Add digits helper function
                    if (!$number) return toThaiNumber('0.00');
                    $formatted = number_format((float)$number, 2, '.', ',');
                    return toThaiNumber($formatted);
                }
            ];

            $pdf = PDF::loadView('PDF.PDF', $data);
            $pdf->setPaper('A4');
            $pdf->getDomPDF()->set_option("fontDir", public_path('fonts/'));
            $pdf->getDomPDF()->set_option("font_cache", public_path('fonts/'));
            $pdf->getDomPDF()->set_option("defaultFont", "THSarabunNew");
            $pdf->getDomPDF()->set_option("isRemoteEnabled", true);

            // สร้างชื่อไฟล์ที่ไม่ซ้ำกันโดยใช้เวลาปัจจุบันแบบละเอียด (timestamp)
            $timestamp = date('Y-m-d_H-i-s');
            $filename = 'project_' . $project->Id_Project . '_summary_' . $timestamp . '.pdf';
            $folderPath = 'uploads/project_' . $project->Id_Project;
            $filePath = $folderPath . '/' . $filename;

            $pdfContent = $pdf->output();
            $pdfSize = strlen($pdfContent);
            $pdfSizeInMB = round($pdfSize / (1024 * 1024), 2);
            $maxFileSizeInMB = config('filesystems.max_upload_size', 20);

            if ($pdfSizeInMB > $maxFileSizeInMB) {
                throw new \Exception("PDF size ({$pdfSizeInMB} MB) exceeds the limit of {$maxFileSizeInMB} MB");
            }

            if (!Storage::disk('public')->exists($folderPath)) {
                Storage::disk('public')->makeDirectory($folderPath);
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
                
                return $filePath; // คืนค่า path ของไฟล์ที่สร้างใหม่
            } else {
                throw new \Exception('Failed to save PDF file');
            }
        } catch (\Exception $e) {
            // Improved error logging
            Log::error('Error generating PDF', [
                'project_id' => $Id_Project,
                'error' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            throw $e; // Re-throw the exception so the caller can handle it
        }
    }

    public function updateField(Request $request)
    {   
        log::info($request);
        $project = ListProjectModel::findOrFail($request->id);
        $field = $request->field;
        $value = $request->value;

        $project->$field = $value;
        $project->save();

        return response()->json(['success' => true]);
    }

    public function updateSdgs(Request $request, $id)
    {
        try {
            $project = ListProjectModel::findOrFail($id);
            $sdgId = $request->sdg_id;
            $selected = $request->selected;
            
            // บันทึกข้อมูลความสัมพันธ์ระหว่างโครงการกับ SDGs
            if ($selected) {
                // เพิ่ม SDG หากมีการเลือก - ใช้ชื่อตารางและคอลัมน์ตามโมเดล
                $existingRecord = ProjectHasSDGModel::where('Project_Id', $id)
                    ->where('SDGs_Id', $sdgId)
                    ->first();
                    
                if (!$existingRecord) {
                    $projectSdg = new ProjectHasSDGModel();
                    $projectSdg->Project_Id = $id;
                    $projectSdg->SDGs_Id = $sdgId;
                    $projectSdg->save();
                    
                    Log::info('SDG เพิ่มเรียบร้อย', [
                        'project_id' => $id,
                        'sdg_id' => $sdgId
                    ]);
                }
            } else {
                // ลบ SDG หากมีการยกเลิกการเลือก - ใช้ชื่อตารางและคอลัมน์ตามโมเดล
                ProjectHasSDGModel::where('Project_Id', $id)
                    ->where('SDGs_Id', $sdgId)
                    ->delete();
                    
                Log::info('SDG ลบเรียบร้อย', [
                    'project_id' => $id,
                    'sdg_id' => $sdgId
                ]);
            }
            
            return response()->json([
                'success' => true,
                'message' => $selected ? 'เพิ่ม SDG เรียบร้อยแล้ว' : 'ลบ SDG เรียบร้อยแล้ว'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating SDGs: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateIntegration(Request $request, $id)
    {
        try {
            $project = ListProjectModel::findOrFail($id);
            $categoryId = $request->category_id;
            $selected = $request->selected;
            $details = $request->details;
            
            // ตรวจสอบว่ามีรายการนี้อยู่แล้วหรือไม่
            $existingRecord = ProjectHasIntegrationCategoryModel::where('Project_Id', $id)
                ->where('Integration_Category_Id', $categoryId)
                ->first();
            
            if ($selected) {
                // เพิ่มหรืออัปเดตการบูรณาการ
                if (!$existingRecord) {
                    $projectIntegration = new ProjectHasIntegrationCategoryModel();
                    $projectIntegration->Project_Id = $id;
                    $projectIntegration->Integration_Category_Id = $categoryId;
                    $projectIntegration->Integration_Details = $details;
                    $projectIntegration->save();
                    
                    Log::info('เพิ่มการบูรณาการเรียบร้อย', [
                        'project_id' => $id,
                        'category_id' => $categoryId
                    ]);
                } else {
                    $existingRecord->Integration_Details = $details;
                    $existingRecord->save();
                    
                    Log::info('อัปเดตการบูรณาการเรียบร้อย', [
                        'project_id' => $id,
                        'category_id' => $categoryId
                    ]);
                }
            } else {
                // ลบการบูรณาการ
                if ($existingRecord) {
                    $existingRecord->delete();
                    
                    Log::info('ลบการบูรณาการเรียบร้อย', [
                        'project_id' => $id,
                        'category_id' => $categoryId
                    ]);
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => $selected ? 'เพิ่มการบูรณาการเรียบร้อยแล้ว' : 'ลบการบูรณาการเรียบร้อยแล้ว'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating integration: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateIntegrationDetails(Request $request, $id)
    {
        try {
            $categoryId = $request->category_id;
            $details = $request->details;
            
            // อัปเดตรายละเอียดการบูรณาการ
            $integration = ProjectHasIntegrationCategoryModel::where('Project_Id', $id)
                ->where('Integration_Category_Id', $categoryId)
                ->first();
            
            if ($integration) {
                $integration->Integration_Details = $details;
                $integration->save();
                
                Log::info('อัปเดตรายละเอียดการบูรณาการเรียบร้อย', [
                    'project_id' => $id,
                    'category_id' => $categoryId
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'อัปเดตรายละเอียดเรียบร้อยแล้ว'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'ไม่พบข้อมูลการบูรณาการ'
                ], 404);
            }
        } catch (\Exception $e) {
            Log::error('Error updating integration details: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getOutputs($id)
    {
        try {
            $outputs = OutputModel::where('Project_Id', $id)->get();
            
            return response()->json([
                'success' => true,
                'outputs' => $outputs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการดึงข้อมูล: ' . $e->getMessage()
            ], 500);
        }
    }

    public function createOutputWithValue($projectId, Request $request)
    {
        try {
            // สร้าง output ใหม่พร้อมค่าที่ส่งมา
            $output = OutputModel::create([
                'Project_Id' => $projectId,
                'Name_Output' => $request->value // ให้ใช้ชื่อฟิลด์ตามโมเดลจริง
            ]);
            
            return response()->json([
                'success' => true,
                'output_id' => $output->Id_Output,
                'message' => 'Output created successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create output: ' . $e->getMessage()
            ], 500);
        }
    }

    public function createEmptyOutput(Request $request, $id)
    {
        try {
            // Check if project exists
            $project = ListProjectModel::findOrFail($id);
            
            // Create empty output record
            $output = new OutputModel();
            $output->Project_Id = $id;
            $output->Name_Output = null;
            $output->save();
            
            return response()->json([
                'success' => true,
                'output_id' => $output->Id_Output,
                'message' => 'Empty output created successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create empty output: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateOutputById($id, Request $request)
    {
        log::info($request);

        try {
            $output = OutputModel::findOrFail($id);
            $output->Name_Output = $request->input('value');
            $output->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Output updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update output: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function deleteOutputById($id)
    {
        try {
            // Find the output record
            $output = OutputModel::findOrFail($id);
            
            // Delete the record
            $output->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'ลบรายการเรียบร้อยแล้ว'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }

    public function createOutcomeWithValue($projectId, Request $request)
    {
        try {
            // สร้าง outcome ใหม่พร้อมค่าที่ส่งมา
            $outcome = OutcomeModel::create([
                'Project_Id' => $projectId,
                'Name_Outcome' => $request->value
            ]);
            
            return response()->json([
                'success' => true,
                'outcome_id' => $outcome->Id_Outcome,
                'message' => 'Outcome created successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create outcome: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create outcome: ' . $e->getMessage()
            ], 500);
        }
    }

    public function createEmptyOutcome($projectId)
    {
        try {
            $outcome = OutcomeModel::create([
                'Project_Id' => $projectId,
                'Name_Outcome' => null
            ]);
            
            return response()->json([
                'success' => true,
                'outcome_id' => $outcome->Id_Outcome
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create outcome: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateOutcomeById($id, Request $request)
    {
        try {
            $outcome = OutcomeModel::findOrFail($id);
            $outcome->Name_Outcome = $request->value;
            $outcome->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Outcome updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update outcome: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteOutcomeById($id)
    {
        try {
            $outcome = OutcomeModel::findOrFail($id);
            $outcome->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Outcome deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete outcome: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getOutcomes($projectId)
    {
        try {
            $outcomes = OutcomeModel::where('Project_Id', $projectId)->get();
            return response()->json([
                'success' => true,
                'outcomes' => $outcomes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch outcomes: ' . $e->getMessage()
            ], 500);
        }
    }

    // For Expected Results
    public function createExpectedResultWithValue($projectId, Request $request)
    {
        try {
            // สร้าง expected result ใหม่พร้อมค่าที่ส่งมา
            $result = ExpectedResultsModel::create([
                'Project_Id' => $projectId,
                'Name_Expected_Results' => $request->value
            ]);
            
            return response()->json([
                'success' => true,
                'result_id' => $result->Id_Expected_Results,
                'message' => 'Expected result created successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create expected result: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create expected result: ' . $e->getMessage()
            ], 500);
        }
    }

    public function createEmptyExpectedResult($projectId)
    {
        try {
            $result = ExpectedResultsModel::create([
                'Project_Id' => $projectId,
                'Name_Expected_Results' => null
            ]);
            
            return response()->json([
                'success' => true,
                'result_id' => $result->Id_Expected_Results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create expected result: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateExpectedResultById($id, Request $request)
    {
        try {
            $result = ExpectedResultsModel::findOrFail($id);
            $result->Name_Expected_Results = $request->value;
            $result->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Expected result updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update expected result: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteExpectedResultById($id)
    {
        try {
            $result = ExpectedResultsModel::findOrFail($id);
            $result->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Expected result deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete expected result: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getExpectedResults($projectId)
    {
        try {
            $results = ExpectedResultsModel::where('Project_Id', $projectId)->get();
            return response()->json([
                'success' => true,
                'results' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch expected results: ' . $e->getMessage()
            ], 500);
        }
    }

    public function createLocationWithValue($projectId, Request $request)
    {
        try {
            // สร้าง location ใหม่พร้อมค่าที่ส่งมา
            $location = LocationModel::create([
                'Project_Id' => $projectId,
                'Name_Location' => $request->value
            ]);
            
            return response()->json([
                'success' => true,
                'location_id' => $location->Id_Location,
                'message' => 'Location created successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create location: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create location: ' . $e->getMessage()
            ], 500);
        }
    }

    public function createEmptyLocation($projectId)
    {
        try {
            $location = LocationModel::create([
                'Project_Id' => $projectId,
                'Name_Location' => null 
            ]);
            
            return response()->json([
                'success' => true,
                'location_id' => $location->Id_Location
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create location: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateLocationById($id, Request $request)
    {
        try {
            $location = LocationModel::findOrFail($id);
            $location->Name_Location = $request->value; // ใช้ Name_Location ตาม model
            $location->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Location updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update location: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteLocationById($id)
    {
        try {
            $location = LocationModel::findOrFail($id);
            $location->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Location deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete location: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getLocations($projectId)
    {
        try {
            $locations = LocationModel::where('Project_Id', $projectId)->get();
            return response()->json([
                'success' => true,
                'locations' => $locations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch locations: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * สร้างข้อมูล Target Group เปล่า
     */
    public function createEmptyTargetGroup($projectId)
    {
        try {
            // สร้างกลุ่มเป้าหมายเปล่า
            $target = TargetModel::create([
                'Project_Id' => $projectId,
                'Name_Target' => '',
                'Quantity_Target' => 0,
                'Unit_Target' => ''
            ]);
            
            return response()->json([
                'success' => true,
                'target_id' => $target->Id_Target_Project,
                'message' => 'Empty target created successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create empty target: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create empty target: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * สร้างข้อมูล Target Group พร้อมค่า
     */
    public function createTargetGroupWithValue($projectId, Request $request)
    {
        try {
            // สร้างกลุ่มเป้าหมายพร้อมข้อมูล
            $target = TargetModel::create([
                'Project_Id' => $projectId,
                'Name_Target' => $request->name ?? '',
                'Quantity_Target' => $request->count ?? 0,
                'Unit_Target' => $request->unit ?? ''
            ]);
            
            // ถ้ามีรายละเอียดเพิ่มเติม สร้าง Target Details
            if ($request->has('details') && !empty($request->details)) {
                $targetDetails = TargetDetailsModel::create([
                    'Details_Target' => $request->details,
                    'Target_Project_Id' => $target->Id_Target_Project
                ]);
            }
            
            return response()->json([
                'success' => true,
                'target_id' => $target->Id_Target_Project,
                'message' => 'Target created successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create target with value: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create target with value: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * อัปเดตข้อมูล Target Group
     */
    public function updateTargetGroupById($id, Request $request)
    {
        try {
            $target = TargetModel::findOrFail($id);
            
            // อัปเดตข้อมูลตามที่ส่งมา
            if ($request->has('name')) {
                $target->Name_Target = $request->name;
            }
            
            if ($request->has('count')) {
                $target->Quantity_Target = $request->count;
            }
            
            if ($request->has('unit')) {
                $target->Unit_Target = $request->unit;
            }
            
            $target->save();
            
            // อัปเดต Target Details ถ้ามี
            if ($request->has('details')) {
                $targetDetails = TargetDetailsModel::where('Target_Project_Id', $id)->first();
                
                if ($targetDetails) {
                    $targetDetails->Details_Target = $request->details;
                    $targetDetails->save();
                } else if (!empty($request->details)) {
                    // สร้างใหม่ถ้ายังไม่มีและมีข้อมูล
                    TargetDetailsModel::create([
                        'Details_Target' => $request->details,
                        'Target_Project_Id' => $id
                    ]);
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Target updated successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update target: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update target: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ลบข้อมูล Target Group
     */
    public function deleteTargetGroupById($id)
    {
        try {
            // ลบ Target Details ก่อน (เพื่อรักษา foreign key integrity)
            TargetDetailsModel::where('Target_Project_Id', $id)->delete();
            
            // ลบ Target
            $target = TargetModel::findOrFail($id);
            $target->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Target deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to delete target: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete target: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ดึงข้อมูล Target Group ทั้งหมดของโครงการ
     */
    public function getTargetGroups($projectId)
    {
        try {
            // ดึงข้อมูล target พร้อมกับ target details ที่เกี่ยวข้อง
            $targets = TargetModel::with('targetDetails')
                                ->where('Project_Id', $projectId)
                                ->get();
            
            return response()->json([
                'success' => true,
                'targets' => $targets
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get targets: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to get targets: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateTargetDetails($projectId, Request $request)
    {
        try {
            // ตรวจสอบโครงการ
            $project = ListProjectModel::findOrFail($projectId);
            
            // ค้นหา target details ที่มีอยู่แล้ว (ถ้ามี)
            // เราต้องหา target_id ของโครงการนี้ก่อน
            $target = TargetModel::where('Project_Id', $projectId)->first();
            
            if (!$target) {
                // ถ้ายังไม่มี target สำหรับโครงการนี้ ให้สร้างก่อน
                $target = TargetModel::create([
                    'Project_Id' => $projectId,
                    'Name_Target' => '',
                    'Quantity_Target' => 0,
                    'Unit_Target' => ''
                ]);
            }
            
            // ค้นหา details ที่มีอยู่แล้ว
            $targetDetails = TargetDetailsModel::where('Target_Project_Id', $target->Id_Target_Project)->first();
            
            // กรณีที่มี details อยู่แล้วให้อัพเดท
            if ($targetDetails) {
                $targetDetails->Details_Target = $request->value;
                $targetDetails->save();
            } else {
                // กรณีที่ยังไม่มี details ให้สร้างใหม่
                $targetDetails = TargetDetailsModel::create([
                    'Details_Target' => $request->value,
                    'Target_Project_Id' => $target->Id_Target_Project
                ]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Target details updated successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update target details: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update target details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ดึงข้อมูลรายละเอียดกลุ่มเป้าหมาย
     */
    public function getTargetDetails($projectId)
    {
        try {
            // หา target สำหรับโครงการนี้
            $target = TargetModel::where('Project_Id', $projectId)->first();
            
            if (!$target) {
                return response()->json([
                    'success' => true,
                    'target_details' => null
                ]);
            }
            
            // ดึงข้อมูลรายละเอียด
            $targetDetails = TargetDetailsModel::where('Target_Project_Id', $target->Id_Target_Project)->first();
            
            return response()->json([
                'success' => true,
                'target_details' => $targetDetails ? $targetDetails->Details_Target : null
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get target details: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to get target details: ' . $e->getMessage()
            ], 500);
        }
    }

    // IndicatorController.php
    public function getIndicators($projectId)
    {
        try {
            $indicators = ProjectHasIndicatorsModel::where('Project_Id', $projectId)->get();
            
            // เพิ่มเติมข้อมูลเกี่ยวกับประเภทตัวชี้วัด
            foreach ($indicators as $indicator) {
                $indicatorType = IndicatorsModel::find($indicator->Indicators_Id);
                $indicator->Type_Indicators = $indicatorType->Type_Indicators;
            }
            
            return response()->json([
                'success' => true,
                'indicators' => $indicators
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function createIndicatorWithValue($projectId, $type, Request $request)
    {
        try {
            // ตรวจสอบว่าประเภทตัวชี้วัดถูกต้อง
            $type = (int)$type;
            if ($type !== 1 && $type !== 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'ประเภทตัวชี้วัดไม่ถูกต้อง'
                ]);
            }
    
            // สร้างหรือค้นหาระเบียนตัวชี้วัด
            $indicator = IndicatorsModel::firstOrCreate(
                ['Id_Indicators' => $type],
                ['Type_Indicators' => $type == 1 ? 'Quantitative' : 'Qualitative']
            );
    
            // สร้างความสัมพันธ์ระหว่างโครงการและตัวชี้วัด
            $projectIndicator = new ProjectHasIndicatorsModel();
            $projectIndicator->Project_Id = $projectId;
            $projectIndicator->Indicators_Id = $type;
            
            // ใช้ชื่อฟิลด์ที่ถูกต้อง
            $projectIndicator->Details_Indicators = $request->input('Details_Indicators', '');
            $projectIndicator->save();
    
            return response()->json([
                'success' => true,
                'indicator_id' => $projectIndicator->Id_Project_has_Indicators,
                'message' => 'สร้างตัวชี้วัดเรียบร้อยแล้ว'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ]);
        }
    }

    public function createEmptyIndicator($projectId, $type)
    {
        try {
            // สร้าง record ตัวชี้วัด (ถ้ายังไม่มี)
            $indicatorType = IndicatorsModel::firstOrCreate(
                ['Id_Indicators' => $type],
                ['Type_Indicators' => $type == 1 ? 'Quantitative' : 'Qualitative']
            );
            
            // สร้างความสัมพันธ์ระหว่างโปรเจคและตัวชี้วัด
            $projectIndicator = new ProjectHasIndicatorsModel();
            $projectIndicator->Project_Id = $projectId;
            $projectIndicator->Indicators_Id = $type;
            $projectIndicator->Details_Indicators = NULL; // เริ่มต้นด้วยค่าว่าง
            $projectIndicator->save();
            
            return response()->json([
                'success' => true,
                'indicator_id' => $projectIndicator->Id_Project_has_Indicators
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateIndicator($indicatorId, Request $request)
    {
        try {
            $indicator = ProjectHasIndicatorsModel::find($indicatorId);
            
            if (!$indicator) {
                return response()->json([
                    'success' => false,
                    'message' => 'ไม่พบข้อมูลตัวชี้วัด'
                ]);
            }
    
            // ใช้ชื่อฟิลด์ที่ถูกต้อง
            $indicator->Details_Indicators = $request->input('Details_Indicators', '');
            $indicator->save();
    
            return response()->json([
                'success' => true,
                'message' => 'อัปเดตตัวชี้วัดเรียบร้อยแล้ว'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ]);
        }
    }

    public function deleteIndicator($indicatorId)
    {
        try {
            $indicator = ProjectHasIndicatorsModel::find($indicatorId);
            if (!$indicator) {
                return response()->json([
                    'success' => false,
                    'message' => 'ไม่พบข้อมูลตัวชี้วัด'
                ]);
            }
            
            $indicator->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'ลบข้อมูลเรียบร้อย'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function deleteAllIndicators($projectId, $type)
    {
        try {
            ProjectHasIndicatorsModel::where('Project_Id', $projectId)
                ->where('Indicators_Id', $type)
                ->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'ลบข้อมูลเรียบร้อย'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function deleteIndicatorsByType($projectId, $type)
    {
        try {
            // ลบข้อมูลตัวชี้วัดทั้งหมดตามประเภท
            ProjectHasIndicatorsModel::where('Project_Id', $projectId)
                ->where('Indicators_Id', $type)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'ลบข้อมูลเรียบร้อยแล้ว'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ]);
        }
    }

    public function addObjective(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'project_id' => 'required|integer',
                'description' => 'required|string',
                'type' => 'required|string|in:manual,selected'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'ข้อมูลไม่ถูกต้อง: ' . $validator->errors()->first()
                ]);
            }

            $objective = new ObjectiveProjectModel();
            $objective->Project_Id = $request->project_id;
            $objective->Description_Objective = $request->description;
            $objective->Type_Objective = $request->type;
            $objective->save();

            return response()->json([
                'success' => true,
                'objective_id' => $objective->Id_Objective_Project,
                'message' => 'เพิ่มวัตถุประสงค์เรียบร้อยแล้ว'
            ]);
        } catch (\Exception $e) {
            Log::error('Error adding objective: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * อัปเดตวัตถุประสงค์โครงการ
     */
    public function updateObjective($objectiveId, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'description' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'ข้อมูลไม่ถูกต้อง: ' . $validator->errors()->first()
                ]);
            }

            $objective = ObjectiveProjectModel::findOrFail($objectiveId);
            $objective->Description_Objective = $request->description;
            
            // เมื่อมีการแก้ไขด้วยตนเอง ให้เปลี่ยนประเภทเป็น manual
            $objective->Type_Objective = 'manual';
            $objective->save();

            return response()->json([
                'success' => true,
                'message' => 'อัปเดตวัตถุประสงค์เรียบร้อยแล้ว'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating objective: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * ลบวัตถุประสงค์โครงการ
     */
    public function deleteObjective($objectiveId)
    {
        try {
            $objective = ObjectiveProjectModel::findOrFail($objectiveId);
            $objective->delete();

            return response()->json([
                'success' => true,
                'message' => 'ลบวัตถุประสงค์เรียบร้อยแล้ว'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting objective: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ]);
        }
    }

}