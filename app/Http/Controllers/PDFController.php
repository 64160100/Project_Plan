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




class PDFController extends Controller
{
    /**
     * บันทึกคำร้อง
     *
     * @return \Illuminate\Http\Response
     */
    public function generatePDF()
    {
        // $strategic = StrategicModel::find(5);
        $strategy = StrategyModel::find(11);
        $data = [
            'title' => 'Welcome to ItSolutionStuff.com',
            'date' => date('m/d/Y'),
            // 'strategic' => $strategic,
            'strategy' => $strategy,
        ]; 
           
        $pdf = PDF::loadView('PDF.PDF', $data);
    
        return $pdf->stream('itsolutionstuff.pdf');
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
            
        log::info($project);

        return view('PDF.ctrlP.pdfproject', compact('project', 'strategies', 'projectBudgetSources'));
    }

}
