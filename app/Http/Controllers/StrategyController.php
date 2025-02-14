<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StrategyModel;
use App\Models\StrategicModel;
use App\Models\KpiModel;
use App\Models\StrategicObjectivesModel;
use App\Models\StrategicOpportunityModel;
use App\Models\StrategicOpportunityDetailsModel;
use App\Models\StrategicChallengesModel;
use App\Models\StrategicAdvantagesModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class StrategyController extends Controller
{
    public function index($Id_Strategic)
    {
        try {
            $strategy = StrategyModel::where('Strategic_Id', $Id_Strategic)->get();
            $strategic = StrategicModel::with('strategies.kpis')->findOrFail($Id_Strategic);
    
            $strategicOpportunities = StrategicOpportunityModel::where('Strategic_Id', $Id_Strategic)
                ->with(['details', 'challenges', 'advantages'])
                ->get();
            
            return view('strategy.viewStrategy', compact('strategic', 'strategy', 'strategicOpportunities'));
        } catch (\Exception $e) {
            Log::error('Error in index: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Failed to load strategy data: ' . $e->getMessage()]);
        }
    }

    public function addStrategy(Request $request)
    {
        $strategy = StrategyModel::create([
            'Name_Strategy' => $request->Name_Strategy,
            'Strategic_Id' => $request->Strategic_Id,
        ]);
    
        if (!$strategy) {
            return redirect()->back()->with('error', 'ไม่สามารถสร้างกลยุทธ์ได้.');
        }
        return redirect()->route('StrategicObjectives.index', ['Id_Strategy' => $strategy->Id_Strategy]);
    }

    public function Kpi($Id_Strategy)
    {
        return view('strategy.addKpi', compact('Id_Strategy'));
    }

    public function addKpi(Request $request, $Id_Strategy) 
    {
        $strategy = StrategyModel::findOrFail($Id_Strategy);
        $Name_Kpi = $request->Name_Kpi;
        $Target_Value = $request->Target_Value;
    
        if ($Name_Kpi && $Target_Value) {
            foreach ($Name_Kpi as $key => $name) {
                KpiModel::create([
                    'Name_Kpi' => $name,
                    'Target_Value' => $Target_Value[$key],
                    'Strategy_Id' => $strategy->Id_Strategy 
                ]);
            }
        }
        return redirect()->route('strategy.index', $strategy->Strategic_Id);
    }

    public function StrategicObjectives($Id_Strategy)
    {
        return view('strategy.addStrategicObjectives', compact('Id_Strategy'));
    }
    
    public function addStrategicObjectives(Request $request, $Id_Strategy) 
    {
        $strategies = StrategyModel::with('strategicObjectives')->where('Strategic_Id', $Id_Strategy)->get();
        $Details_Strategic_Objectives = $request->Details_Strategic_Objectives;
    
        if ($Details_Strategic_Objectives) {
            foreach ($Details_Strategic_Objectives as $detail) {
                StrategicObjectivesModel::create([
                    'Details_Strategic_Objectives' => $detail,
                    'Strategy_Id' => $Id_Strategy
                ]);
            }
        }
        return redirect()->route('kpi.index', ['Id_Strategy' => $Id_Strategy]);
    }

    public function editStrategy($Id_Strategy)
    {
        $strategy = StrategyModel::with('kpis')->findOrFail($Id_Strategy);
        $Id_Strategic = $strategy->Strategic_Id;
        return view('strategy.editstrategy', compact('strategy', 'Id_Strategic'));
    }

    public function updateStrategy(Request $request, $Id_Strategy)
    {
        $strategy = StrategyModel::findOrFail($Id_Strategy);
        $strategy->update([
            'Name_Strategy' => $request->Name_Strategy,
        ]);
    
        if ($request->has('kpis') && is_array($request->kpis)) {
            foreach ($request->kpis as $Id_Kpi => $kpiData) {
                $kpi = KpiModel::findOrFail($Id_Kpi);
                $kpi->update($kpiData);
            }
        }
    
        if ($request->has('strategicObjectives') && is_array($request->strategicObjectives)) {
            foreach ($request->strategicObjectives as $Id_Strategic_Objectives => $SOData) {
                $so = StrategicObjectivesModel::findOrFail($Id_Strategic_Objectives);
                $so->update($SOData);
            }
        }
    
        if ($request->has('newStrategicObjectives') && is_array($request->newStrategicObjectives)) {
            foreach ($request->newStrategicObjectives as $newObjective) {
                StrategicObjectivesModel::create([
                    'Details_Strategic_Objectives' => $newObjective,
                    'Strategy_Id' => $Id_Strategy
                ]);
            }
        }
    
        if ($request->has('newKpis') && is_array($request->newKpis)) {
            foreach ($request->newKpis as $newKpi) {
                KpiModel::create([
                    'Name_Kpi' => $newKpi['Name_Kpi'],
                    'Target_Value' => $newKpi['Target_Value'],
                    'Strategy_Id' => $Id_Strategy
                ]);
            }
        }
    
        return redirect()->route('strategy.index', ['Id_Strategic' => $strategy->Strategic_Id])
                        ->with('success', 'กลยุทธ์และตัวชี้วัดถูกอัปเดตเรียบร้อยแล้ว');
    }
    
    public function deleteStrategy($Id_Strategy)
    {
        $strategy = StrategyModel::findOrFail($Id_Strategy);
        $strategy->kpis()->delete();
        $strategy->strategicObjectives()->delete();
        $strategy->delete();
        return redirect()->route('strategy.index', $strategy->Strategic_Id)->with('success', 'Strategy and related KPIs deleted successfully.');
    }

    public function destroy($id)
    {
        $kpi = KpiModel::findOrFail($id);
        $kpi->delete();
    
        return redirect()->back()->with('success', 'KPI deleted successfully.');
    }

}