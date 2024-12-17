<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Strategic;
use App\Models\Sdg;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function index()
    {
        $strategics = Strategic::with('projects')->get();

        return view('Project.index', compact('strategics')); 
    }

    public function showCreateProject()
    {
        $strategics = Strategic::with('projects')->get();
        $sdgs = Sdg::all();
        return view('Project.FormInsertProject', compact('strategics', 'sdgs'));
    }

    public function createProject(Request $request)
    {
        $Project = new Project;
        $Project->Strategic_Id = $request->Strategic_Id;
        $Project->Name_Project = $request->Name_Project;
        $Project->save();

       // ตรวจสอบและกรองค่า 'on' ที่อาจถูกส่งมา
        if ($request->has('sdgs')) {
            // กรองค่า 'on' ออกไปก่อน
            $SDGs_Id = array_filter($request->sdgs, function($value) {
                return is_numeric($value);  // ตรวจสอบว่าเป็นตัวเลขหรือไม่
            });

            // เชื่อม SDGs ที่เลือก
            if (!empty($SDGs_Id)) {
                $Project->sdgs()->attach($SDGs_Id);
            }
        }

        return redirect()->route('index')->with('success', 'โครงการถูกสร้างเรียบร้อยแล้ว');

    }

    public function editProject(Request $request, $Id_Project)
    {
        $projects = Project::find($Id_Project);
        $strategics = Strategic::all();
        $projects = Project::with('sdgs')->findOrFail($Id_Project);
        $sdgs = Sdg::all();

        if($request->isMethod('post')){
            $Project->Strategic_Id = $request->Strategic_Id;
            $Project->Name_Project = $request->Name_Project;
            if ($request->has('sdgs')) {
                $Project->sdgs()->detach();// ลบการเชื่อมโยงเดิม (ถ้ามี) ก่อน
                $Project->sdgs()->attach($request->sdgs); // เชื่อมโยง SDGs ใหม่
            }
            $Project->save();
            return redirect()->route('index')->with('success', 'แก้ไขโครงการเรียบร้อยแล้ว');
        }
        
        return view('Project.FormEditProject', compact('projects', 'strategics', 'sdgs'));
    }

    public function updateProject(Request $request, $Id_Project)
    {
        $Project = Project::find($Id_Project); // ค้นหา Project ตาม ID
        if (!$Project) {
            return redirect()->route('index')->with('error', 'ไม่พบโครงการที่ต้องการอัปเดต');
        }

        $Project->Strategic_Id = $request->Strategic_Id;
        $Project->Name_Project = $request->Name_Project;
        if ($request->has('sdgs')) {
            $Project->sdgs()->detach();
            $Project->sdgs()->attach($request->sdgs); // เชื่อมโยง SDGs_Id ที่เลือก
        } else {
            $Project->sdgs()->detach();  // ถ้าไม่ได้เลือก SDGs (uncheck) ให้ลบออกทั้งหมด
        }
        $Project->save();

        return redirect()->route('index')->with('success', 'โครงการถูกอัปเดตเรียบร้อยแล้ว');
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
        ]);
    }


    
 
}
