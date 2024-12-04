<?php

namespace App\Http\Controllers;

use App\Models\StrategicModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class strategicController extends Controller
{
    public function strategic()
    {
        $strategic = StrategicModel::all();
        Log::info('Strategic Data:', ['Strategic' => $strategic->toArray()]);
        return view('dashboard', compact('strategic'));
    }

    public function editStrategic($Id_Strategic)
    {
        $strategic = StrategicModel::findOrFail($Id_Strategic);
        return view('strategic.editStrategic', compact('strategic'));
    }

    public function updateStrategic(Request $request, $Id_Strategic)
    {
        $strategic = StrategicModel::findOrFail($Id_Strategic);
        $strategic->Name_Strategic_Plan = $request->Name_Strategic_Plan;
        $strategic->Goals_Strategic = $request->Goals_Strategic;
        $strategic->save();

        return redirect()->route('account.strategic')->with('success', 'Strategic updated successfully.');
    }
}