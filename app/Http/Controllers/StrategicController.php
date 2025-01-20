<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Strategic;


class StrategicController extends Controller
{
    public function index()
    {
        $strategic = Strategic::all();
        return view('strategic.viewStrategic', compact('strategic'));
    }

    public function addStrategic(Request $request)
    {
        Strategic::create([
            'Name_Strategic_Plan' => $request->Name_Strategic_Plan,
            'Goals_Strategic' => $request->Goals_Strategic,
        ]);
        return redirect('/strategic')->with('success', 'บันทึกสำเร็จ');
    }

    public function updateStrategic(Request $request, $Id_Strategic)
    {
        $strategic = Strategic::findOrFail($Id_Strategic);
        $strategic->Name_Strategic_Plan = $request->Name_Strategic_Plan;
        $strategic->Goals_Strategic = $request->Goals_Strategic;
        $strategic->save();
        return redirect()->route('strategic.index')->with('success', '');
    }

    public function deleteStrategic($Id_Strategic)
    {
        $strategic = Strategic::findOrFail($Id_Strategic);
        $strategic->delete();
        return redirect()->route('strategic.index')->with('success', '');
    }
    
}
