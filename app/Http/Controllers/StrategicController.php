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

    public function index()
    {
        $strategic = StrategicModel::all();
        return view('strategic.viewStrategic', compact('strategic'));
        // return view('welcome', compact('strategic'));
    }

    public function addStrategic(Request $request)
    {
        StrategicModel::create([
            'Name_Strategic_Plan' => $request->Name_Strategic_Plan,
            'Goals_Strategic' => $request->Goals_Strategic,
        ]);
        return redirect('/strategic')->with('success', 'บันทึกสำเร็จ');
    }

    public function deleteStrategic($Id_Strategic)
    {
        $strategic = StrategicModel::findOrFail($Id_Strategic);
        $strategic->delete();
        return redirect()->route('strategic.index')->with('success', '');
    }
}