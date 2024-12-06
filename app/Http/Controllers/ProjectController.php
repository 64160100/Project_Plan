<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Strategic;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $strategic = Strategic::all();

        $strategic = Strategic::with('projects')->get();

        return view('Project.index', compact('strategic')); 

    }

    
   
    public function addProject(Request $request)
    {
        if($request->isMethod('post')) {
            // ตรวจสอบความถูกต้องของข้อมูล
            $validatedData = $request->validate([
                'Name_Project' => 'required|string|max:255',
                'Strategic_Id' => 'required|integer|exists:strategics,id',
            ]);
    
            // บันทึกข้อมูลลงในฐานข้อมูล
            $project = new Project();
            $project->Name_Project = $request->Name_Project;
            $project->Strategic_Id = $request->Id_Strategic;
            $project->save();
    
            return redirect('/index');
        }
    
        // ดึงข้อมูลยุทธศาสตร์
        $strategic['Strategic'] = Strategic::SelectFormStrategic();
        return view('Project.FormInsertProject', ['strategic' => $strategic]);
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
