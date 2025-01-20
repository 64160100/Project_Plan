<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\StrategicModel;
use App\Models\StrategyModel;
use App\Models\ProjectModel;


class PDFController extends Controller
{
    /**
     * บันทึกคำร้อง
     *
     * @return \Illuminate\Http\Response
     */
    public function generatePDF()
    {
        $strategy = StrategyModel::find(11);
        $data = [
            'title' => 'Welcome to ItSolutionStuff.com',
            'date' => date('m/d/Y'),
            'strategy' => $strategy,
        ]; 
           
        $pdf = PDF::loadView('PDF.PDF', $data);
    
        return $pdf->stream('itsolutionstuff.pdf');
    }
    
    public function ActionPlanPDF()
    {
        $projects = ProjectModel::with(['strategic.strategies'])
                    ->orderBy('Strategic_Id')
                    ->orderBy('Name_Strategy')
                    ->get();    

        $strategyCounts = $projects->groupBy('Name_Strategy')->map->count();
        $data = [
            'projects' => $projects,
            'strategyCounts' => $strategyCounts,
        ]; 
           
        $pdf = PDF::loadView('PDF.PDFActionPlan', $data);
    
        return $pdf->stream('action_plan.pdf');
    }


    public function PDFStrategic($Id_Strategic)
    {
        $projects = ProjectModel::with(['strategic.strategies'])
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
        $projects = ProjectModel::with(['strategic.strategies'])
                    ->where('Id_Project', $Id_Project)
                    ->firstOrFail();    
    
        $data = [      
            'title' => $projects->Name_Project,
            'projects' => $projects,
        ]; 
        $pdf = PDF::loadView('PDF.PDFProject', $data);
        return $pdf->stream($projects->Name_Project .'.pdf');  
    }

        public function ctrlpPDFStrategic($Id_Strategic)
    {
        $projects = ProjectModel::with(['strategic.strategies'])
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
        $projects = ProjectModel::with(['strategic.strategies'])
                    ->where('Id_Project', $Id_Project)
                    ->firstOrFail();    
    
        $data = [      
            'title' => $projects->Name_Project,
            'projects' => $projects,
        ]; 
        return view('PDF.ctrlP.pdfproject', $data);

    }

}
