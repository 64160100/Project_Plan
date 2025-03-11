<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PDF;
use App\Models\StrategicModel;
use App\Models\StrategyModel;
use App\Models\ListProjectModel;
use App\Models\SupProjectModel;
use App\Models\ProjectHasBudgetSourceModel;
use App\Models\TargetModel;
use App\Models\TargetDetailsModel;
use App\Models\ProjectHasIntegrationCategoryModel;
use App\Models\KpiModel;
use App\Models\OutcomeModel;
use App\Models\OutputModel;
use App\Models\ExpectedResultsModel;
use App\Models\SubtopicBudgetHasBudgetFormModel;
use App\Models\ProjectHasSDGModel;
use App\Models\SubtopBudgetModel;
use App\Models\MonthsModel;
use App\Models\PdcaModel;
use App\Models\StrategicHasQuarterProjectModel;

use Carbon\Carbon;

class PDFController extends Controller
{
    /**
     * บันทึกคำร้อง
     *
     * @return \Illuminate\Http\Response
     */
    public function generatePDF($Id_Project)
    {
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

        $data = [
            'title' => $project->Name_Project,
            'date' => toThaiNumber(date('d/m/Y')),
            'project' => $project,
            'projectBudgetSources' => $projectBudgetSources,
            'outcome' => $outcome,
            'output' => $output,
            'expectedResult' => $expectedResult,
        ];

        $pdf = PDF::loadView('PDF.PDF', $data);
        $pdf->getDomPDF()->set_option("fontDir", public_path('fonts/'));
        $pdf->getDomPDF()->set_option("font_cache", public_path('fonts/'));
        $pdf->getDomPDF()->set_option("defaultFont", "THSarabunNew");
        $pdf->getDomPDF()->set_option("isRemoteEnabled", true);

        return $pdf->stream('project_report.pdf');
    }

    public function ActionPlanPDF()
    {
        $projects = ListProjectModel::with(['strategic.strategies'])
                    ->orderBy('Strategic_Id')
                    ->orderBy('Name_Strategy')
                    ->get();    

        $strategyCounts = $projects->groupBy('Name_Strategy')->map->count();
        $data = [
            // 'title' => '',
            'projects' => $projects,
            'strategyCounts' => $strategyCounts,
        ]; 
           
        $pdf = PDF::loadView('PDF.PDFActionPlan', $data);
    
        return $pdf->stream('action_plan.pdf');
    }

    public function PDFStrategic($Id_Strategic)
    {
        $projects = ListProjectModel::with(['strategic.strategies'])
                    ->where('Strategic_Id', $Id_Strategic)
                    ->orderBy('Name_Strategy')
                    ->get();   
                    
        $strategicName = $projects->first()->strategic->Name_Strategic_Plan ?? 'Default Title';
        $data = [
            'title' => $strategicName, 
            'projects' => $projects,
        ]; 
        $pdf = PDF::loadView('PDF.PDFStrategic', $data);
        return $pdf->stream($strategicName . '.pdf');  
    }

    public function PDFProject($Id_Project)
    {
        $projects = ListProjectModel::with(['strategic.strategies'])
                    ->where('Id_Project', $Id_Project)
                    ->firstOrFail();    
    
        $data = [      
            'title' => $projects->Name_Project,
            'projects' => $projects,
        ]; 
        $pdf = PDF::loadView('PDF.PDFProject', $data);
        return $pdf->stream($projects->Name_Project .'.pdf');  
    }

    // PDF ที่เรียกจากหน้าHTML
    public function ctrlpPDFStrategic($Id_Strategic)
    {
        $projects = ListProjectModel::with(['strategic.strategies'])
                    ->where('Strategic_Id', $Id_Strategic)
                    ->orderBy('Name_Strategy')
                    ->get();   
        
        $strategicName = $projects->first()->strategic->Name_Strategic_Plan ?? 'Default Title';
        $data = [
            'title' => $strategicName, 
            'projects' => $projects,
        ]; 
        return view('PDF.ctrlP.pdfstrategic', $data);
    }
    
    public function ctrlpPDFProject($Id_Project)
    {
        $project = ListProjectModel::with([
            'subProjects',
            'projectHasIntegrationCategories',
            'targets.targetDetails',
        ])->where('Id_Project', $Id_Project)->firstOrFail();
    
        $strategics = StrategicModel::with(['strategies'])->findOrFail($project->Strategic_Id);
        $strategies = $strategics->strategies;
    
        $projectBudgetSources = ProjectHasBudgetSourceModel::where('Project_Id', $Id_Project)->get();
            
        return view('PDF.ctrlP.pdfproject', compact('project', 'strategies', 'projectBudgetSources'));
    }

    public function generatePDFReportForm($id)
    {

        $project = ListProjectModel::with(['employee', 'targets.targetDetails', 'locations', 
                            'projectHasIndicators.indicators','shortProjects',
                            'monthlyPlans','monthlyPlans.month', 'monthlyPlans.pdca',])->findOrFail($id);

                            log::info($project);
        $projectBudgetSources = ProjectHasBudgetSourceModel::with('budgetSource')
                    ->where('Project_Id', $id)->get();

        $months = MonthsModel::orderBy('Id_Months', 'asc')->pluck('Name_Month', 'Id_Months');
        $pdcaStages = PdcaModel::all();

        $quarterProjectId = request()->input('quarter_project_id', StrategicHasQuarterProjectModel::pluck('Quarter_Project_Id')->first() ?? 1);
        $quarterProjects = StrategicHasQuarterProjectModel::with(['strategic', 'quarterProject'])
                    ->where('Quarter_Project_Id', $quarterProjectId)
                    ->get();

        // app/Helpers.php
        $project->formatted_first_time = formatDateThai($project->First_Time);
        $project->formatted_end_time = formatDateThai($project->End_Time);
    
        $data = [
            'title' => $project->Name_Project,
            'date' => toThaiNumber(date('d/m/Y')),
            'project' => $project,
            'months' => $months,
            'pdcaStages' => $pdcaStages,
            'quarterProjects' => $quarterProjects,
        ];
    
        $pdf = PDF::loadView('PDF.PDFReportForm', $data);

        $pdf->getDomPDF()->set_option("fontDir", public_path('fonts/'));
        $pdf->getDomPDF()->set_option("font_cache", public_path('fonts/'));
        $pdf->getDomPDF()->set_option("defaultFont", "THSarabunNew");
        $pdf->getDomPDF()->set_option("isRemoteEnabled", true);

        return $pdf->stream('project_report.pdf');
    }
}