<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Strategic;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function index()
    {
        $strategics  = Strategic::all();

        $strategics  = Strategic::with('projects')->get();

        return view('Project.index', compact('strategics')); 
    }


    public function viewProjectInStrategic($Id_Strategic)
    {
        $strategics = Strategic::findOrFail($Id_Strategic);
        $strategics = Strategic::where('Id_Strategic', $Id_Strategic)->firstOrFail();

        $strategics = Strategic::with('projects')
                        ->where('Id_Strategic', $Id_Strategic)
                        ->firstOrFail();
        
        if ($Id_Strategic == 1) {
            return view('Project.ViewProjectInStrategic',
            [
                'strategics' => $strategics,
            ]);
        } elseif ($Id_Strategic == 2) {
            return view('Project.ViewProjectInStrategic', 
            [
                'strategics' => $strategics,
            ]);
        } elseif ($Id_Strategic == 3) {
            return view('Project.ViewProjectInStrategic', 
            [
                'strategics' => $strategics,
            ]);
        } elseif ($Id_Strategic == 4) {
            return view('Project.ViewProjectInStrategic', 
            [
                'strategics' => $strategics,
            ]);
        } else {
            abort(404, 'not found.'); // กรณีที่ Id_Strategic ไม่ตรง
        }
    }


    public function viewProject($Id_Project)  // รับพารามิเตอร์ id จาก URL
    {
        // $projects = Project::findOrFail($Id_Project);
        $projects = Project::with('strategic')->findOrFail($Id_Project); // ดึงข้อมูลโครงการและยุทธศาสตร์ที่เกี่ยวข้อง

        // $strategics = Strategic::with('projects')->get();

        return view('Project.ViewProject', [
            'projects' => $projects,
            // 'strategics' => $strategics,
        ]);
    }



    public function addProject(Request $request)
    {

        if($request->isMethod('post')) {
            dd($request->all());
            $validatedData = $request->validate([
                'Name_Project' => 'required|string|max:255',
                'Strategic_Id' => 'required|exists:strategics,Id_Strategic', // ตรวจสอบว่า Id_Strategic มีอยู่ในฐานข้อมูล
            ]);

            Project::create([
                // 'Id_Project' => $request->Id_Project,
                'Strategic_Id' => $request->Strategic_Id,
                'Name_Project' => $request->Name_Project,
            ]);
        
            return redirect('/viewProjectInStrategic');   
    
        }

        $strategics = Strategic::all();
        $strategics['Strategic'] = Strategic::SelectFormStrategic();

        return view('Project.FormInsertProject', ['strategics' => $strategics]);
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

    
}
