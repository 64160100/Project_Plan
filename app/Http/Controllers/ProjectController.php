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

        $strategics  = Strategic::with('projects')->get();

        return view('Project.index', compact('strategics')); 
    }


    public function viewProjectInStrategic($Id_Strategic)
    {
        $strategics = Strategic::findOrFail($Id_Strategic); //ค้นหาโดย Primary Key 
        $strategics = Strategic::where('Id_Strategic', $Id_Strategic)->firstOrFail(); //ค้นหา Id_Strategic ใน Strategic

        $strategics = Strategic::with('projects')
                        ->where('Id_Strategic', $Id_Strategic)
                        ->firstOrFail();

        $strategics = Strategic::with('projects')->findOrFail($Id_Strategic);

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
            abort(404, 'not found.');
        }

        return view('Project.viewProjectInStrategic', [
            'strategics' => $strategics,
            // 'projects' => $strategic->projects, // ดึง Projects ที่สัมพันธ์กับ Strategic
        ]);
    }


    public function viewProject($Id_Project)  // รับพารามิเตอร์ id จาก URL
    {
        $projects = Project::with('strategic')->findOrFail($Id_Project); // ดึงข้อมูลโครงการและยุทธศาสตร์ที่เกี่ยวข้อง

        return view('Project.ViewProject', [
            'projects' => $projects,
            // 'strategics' => $strategics,
        ]);
    }

    public function showCreateForm()
    {
        $strategics = Strategic::with('projects')->get();
        return view('Project.FormInsertProject', compact('strategics'));
    }

    public function createProject(Request $request)
    {

        $Project = new Project;
        $Project->Strategic_Id = $request->Strategic_Id;
        $Project->Name_Project = $request->Name_Project;
        $Project->save();

        return redirect()->route('index')->with('success', 'โครงการถูกสร้างเรียบร้อยแล้ว');

    }
 
}
