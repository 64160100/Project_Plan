<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Strategic;

class StrategicController extends Controller
{
    // public function viewStrategic($id)
    // {
    //     $strategic = Strategic::find($id);

    //     // ตรวจสอบว่าไม่มีข้อมูล
    //     if (!$strategic) {
    //         return abort(404, 'Strategic Plan not found');
    //     }

    //     return view('Project.ViewProjectInStrategic', compact('strategic'));
    // }

    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
