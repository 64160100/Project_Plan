<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StrategicModel;
use App\Models\StrategicHasQuarterProjectModel;
use App\Models\FiscalYearQuarterModel;
use Illuminate\Support\Facades\Log;

class StrategicController extends Controller
{
    public function index(Request $request)
    {
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
    
        $strategic = $query->get();

        Log::info($quarters);
    
        return view('strategic.viewStrategic', compact('strategic', 'quarters'));
    }
    
    public function addStrategic(Request $request)
    {
        $strategic = StrategicModel::create([
            'Name_Strategic_Plan' => $request->Name_Strategic_Plan,
            'Goals_Strategic' => $request->Goals_Strategic,
        ]);

        StrategicHasQuarterProjectModel::create([
            'Strategic_Id' => $strategic->Id_Strategic,
            'Quarter_Project_Id' => $request->Fiscal_Year_Quarter_Add,
        ]);

        return redirect('/strategic')->with('success', 'บันทึกสำเร็จ');
    }

    public function updateStrategic(Request $request, $Id_Strategic)
    {
        $strategic = StrategicModel::findOrFail($Id_Strategic);
        $strategic->Name_Strategic_Plan = $request->Name_Strategic_Plan;
        $strategic->Goals_Strategic = $request->Goals_Strategic;
        $strategic->save();

        StrategicHasQuarterProjectModel::where('Strategic_Id', $Id_Strategic)->delete();

        StrategicHasQuarterProjectModel::create([
            'Strategic_Id' => $Id_Strategic,
            'Quarter_Project_Id' => $request->Fiscal_Year_Quarter_Add,
        ]);

        return redirect()->route('strategic.index')->with('success', 'อัปเดตสำเร็จ');
    }

    public function deleteStrategic($Id_Strategic)
    {
        StrategicHasQuarterProjectModel::where('Strategic_Id', $Id_Strategic)->delete();

        $strategic = StrategicModel::findOrFail($Id_Strategic);
        $strategic->delete();

        return redirect()->route('strategic.index')->with('success', 'ลบสำเร็จ');
    }
}