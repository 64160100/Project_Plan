<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StrategicModel;
use App\Models\FiscalYearQuarterModel;

class StrategicController extends Controller
{
    public function index(Request $request)
    {
        $fiscalYears = FiscalYearQuarterModel::select('Fiscal_Year')->distinct()->pluck('Fiscal_Year');
        $query = StrategicModel::query();

        if ($request->has('Fiscal_Year') && $request->Fiscal_Year != '') {
            $query->whereHas('quarterProjects', function ($q) use ($request) {
                $q->where('Fiscal_Year', $request->Fiscal_Year);
            });
        }

        if ($request->has('Quarter') && $request->Quarter != '') {
            $query->whereHas('quarterProjects', function ($q) use ($request) {
                $q->where('Quarter', $request->Quarter);
            });
        }

        $strategic = $query->get();

        if ($strategic->isEmpty()) {
            $strategic = null;
        }

        return view('strategic.viewStrategic', compact('strategic', 'fiscalYears'));
    }

    public function addStrategic(Request $request)
    {
        StrategicModel::create([
            'Name_Strategic_Plan' => $request->Name_Strategic_Plan,
            'Goals_Strategic' => $request->Goals_Strategic,
        ]);
        return redirect('/strategic')->with('success', 'บันทึกสำเร็จ');
    }

    public function updateStrategic(Request $request, $Id_Strategic)
    {
        $strategic = StrategicModel::findOrFail($Id_Strategic);
        $strategic->Name_Strategic_Plan = $request->Name_Strategic_Plan;
        $strategic->Goals_Strategic = $request->Goals_Strategic;
        $strategic->save();
        return redirect()->route('strategic.index')->with('success', '');
    }

    public function deleteStrategic($Id_Strategic)
    {
        $strategic = StrategicModel::findOrFail($Id_Strategic);
        $strategic->delete();
        return redirect()->route('strategic.index')->with('success', '');
    }
}