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
use App\Models\FiscalYearQuarterModel;
use App\Models\StorageFileModel;
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

            try {
                // Define file size limits and allowed types
                $maxFileSizeInMB = config('filesystems.max_upload_size', 20);
                $maxFileSizeInBytes = $maxFileSizeInMB * 1024 * 1024;
            
                // Create base directory if it doesn't exist
                $baseDir = storage_path('app/public/uploads');
                if (!file_exists($baseDir)) {
                    mkdir($baseDir, 0755, true);
                }
            
                // สร้างชื่อโฟลเดอร์ตาม project_id
                $folderPath = 'uploads/project_' . $project->Id_Project;
            
                // Ensure directory exists
                if (!Storage::disk('public')->exists($folderPath)) {
                    Storage::disk('public')->makeDirectory($folderPath);
                    Log::info('Project directory created', ['path' => $folderPath]);
                }
            
                // Load project with relationships
                $pdfData = [
                    'project' => $project->load([
                        'supProjects',
                        'projectBudgetSources.budget_source',
                        'strategic',
                        'strategy',
                        'employee',
                        'targets',
                        'locations',
                        'pdca',
                        'platforms',
                        'projectHasSDGs.sdgs',
                        'projectHasIntegrationCategories.integrationCategory',
                        'projectHasIndicators.indicators'
                    ]),
                    'strategy' => $strategy,
                    'nameCreator' => $nameCreator,
                    'roleCreator' => $roleCreator,
                    'projectBudgetSources' => $project->projectBudgetSources
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
            
                    Log::info('PDF generated and saved successfully', [
                        'project_id' => $project->Id_Project,
                        'file_name' => $filename,
                        'path' => $filePath,
                        'size' => $storageFile->getSizeInMB() . 'MB'
                    ]);
            
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
        
        log::info($project);
    
        return view('Project.editBigFormProject', compact('project', 'strategics', 'strategies', 'projects', 'employees', 'sdgs', 'nameStrategicPlan', 'integrationCategories', 'months', 'pdcaStages', 'budgetSources', 'subtopBudgets', 'kpis', 'strategicObjectives', 'sourcePage'));
    }

    public function resetStatus($id)
    {
        $project = ListProjectModel::findOrFail($id);
        $project->approvals->first()->Status = 'I';
        $project->approvals->first()->save();

        return redirect()->route('proposeProject')->with('success', 'Project status reset to I successfully.');
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