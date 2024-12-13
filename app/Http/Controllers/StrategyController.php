<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StrategyModel;
use App\Models\StrategicModel;
use App\Models\KpiModel;
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
    
            $strategicOpportunities = StrategicOpportunityModel::where('Strategic_Id_Strategic', $Id_Strategic)
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
        // Validation
        $request->validate([
            'Name_Strategy' => 'required|string|max:255',
            'Strategy_Objectives' => 'required|string|max:1000',
            'Strategic_Id' => 'required|integer',
            'Name_Kpi' => 'required|string|max:255',
            'Target_Value' => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $strategy = new StrategyModel();
            $strategy->Name_Strategy = $request->Name_Strategy;
            $strategy->Strategy_Objectives = $request->Strategy_Objectives;
            $strategy->Strategic_Id = $request->Strategic_Id;
            $strategy->save(); 

            
            if (!$strategy->Id_Strategy) {
                throw new \Exception('Failed to save strategy');
            }
            KpiModel::create([
                'Name_Kpi' => $request->Name_Kpi,
                'Target_Value' => $request->Target_Value,
                'Strategy_Id' => $strategy->Id_Strategy,
            ]);

            DB::commit();

            return redirect()->route('strategy.index', $request->Strategic_Id)
                ->with('success', 'Strategy and KPI created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to save strategy and KPI: ' . $e->getMessage()]);
        }
    }

}
