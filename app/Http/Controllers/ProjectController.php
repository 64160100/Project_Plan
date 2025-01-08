<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Strategic;
use App\Models\Strategy;
use App\Models\Sdg;
use App\Models\Platform;
use App\Models\Sup_Project;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function index()
    {
        $strategics = Strategic::with('projects')->get();
        $strategics->each(function ($strategics) {
            $strategics->project_count = $strategics->projects->count();
        });

        return view('Project.index', compact('strategics')); 
    }

    public function showCreateProject($Strategic_Id)
    {
        $strategics = Strategic::with('strategies')->find($Strategic_Id); // ดึงข้อมูล Strategic พร้อมกับ strategies
        $strategics = Strategic::with('projects')->find($Strategic_Id); // ดึงข้อมูล Strategic พร้อมกับ projects
        $strategies = $strategics->strategies; //ดึงข้อมูล strategics เพื่อเข้าถึงฟังก์ชัน strategies ในตาราง Strategic
        $sdgs = Sdg::all();

        return view('Project.FormInsertProject', compact('strategics', 'strategies','sdgs'));
    }

    
    public function createProject(Request $request, $Strategic_Id)
    {
        // ค้นหาข้อมูลกลยุทธ์
        $strategics = Strategic::with(['strategies', 'projects'])->findOrFail($Strategic_Id);
        $strategies = $strategics->strategies;

        // สร้างโครงการใหม่
        $projects = new Project;
        $projects->Strategic_Id = $request->Strategic_Id;
        $projects->Name_Project = $request->Name_Project;
        $projects->Name_Strategy = $request->Name_Strategy;
        $projects->First_Time = $request->First_Time;
        $projects->End_Time = $request->End_Time;
        $projects->save();  // บันทึกโครงการใหม่

        $objectiveProjects = $request->Objective_Project;
        $indicatorsProjects = $request->Indicators_Project;
        $targetProjects = $request->Target_Project;

        if ($request->has('sdgs')) {
            $selectedSDGs = $request->sdgs; // รับค่าที่ส่งมาจากฟอร์ม (Array)
            $projects->sdgs()->sync($selectedSDGs); // เชื่อมโยง SDGs กับ Project
        }

        if ($request->has('Name_Sup_Project')) {
            $this->createSupProjects($projects->Id_Project, $request->Name_Sup_Project);
        }

        
        // ตรวจสอบว่ามีข้อมูลทั้งสามฟิลด์หรือไม่
        if ($objectiveProjects && $indicatorsProjects && $targetProjects) {
            
            foreach ($objectiveProjects as $index => $objective) {
                // ตรวจสอบว่า index ของ $indicatorsProjects และ $targetProjects มีข้อมูลตรงกันหรือไม่
                $indicator = isset($indicatorsProjects[$index]) ? $indicatorsProjects[$index] : null;
                $target = isset($targetProjects[$index]) ? $targetProjects[$index] : null;
        
                $projects->update([
                    'Strategic_Id' => $projects->Strategic_Id,
                    'Name_Project' => $projects->Name_Project,
                    'Name_Strategy' => $projects->Name_Strategy,
                    'First_Time' => $projects->First_Time,
                    'End_Time' => $projects->End_Time,
                    'Objective_Project' => $objective,
                    'Indicators_Project' => $indicator,
                    'Target_Project' => $target
                ]);
            }
        }

        return redirect()->route('index', ['Strategic_Id' => $Strategic_Id])
                        ->with('success', 'โครงการถูกสร้างเรียบร้อยแล้ว');
    }

    // public function createProject(Request $request, $Strategic_Id)
    // {
    //     // ค้นหาข้อมูลกลยุทธ์
    //     $strategics = Strategic::with(['strategies', 'projects'])->findOrFail($Strategic_Id);
    
    //     // สร้างโครงการใหม่
    //     $projects = new Project;
    //     $projects->Strategic_Id = $Strategic_Id;
    //     $projects->Name_Project = $request->Name_Project;
    //     $projects->Name_Strategy = $request->Name_Strategy;
    //     $projects->First_Time = $request->First_Time;
    //     $projects->End_Time = $request->End_Time;
    
    //     // บันทึกโครงการครั้งแรกโดยไม่รวม Objective, Indicators, Target
    //     $projects->save();
    
    //     // อัปเดต Objective, Indicators, Target ทีละแถว
    //     if ($request->has('Objective_Project') || $request->has('Indicators_Project') || $request->has('Target_Project')) {
    //         $projects->Objective_Project = $request->Objective_Project; // ส่งเป็นข้อความธรรมดา
    //         $projects->Indicators_Project = $request->Indicators_Project; // ส่งเป็นข้อความธรรมดา
    //         $projects->Target_Project = $request->Target_Project; // ส่งเป็นข้อความธรรมดา
    
    //         // บันทึกการอัปเดต
    //         $projects->save();
    //     }
    
    //     // Redirect กลับไปหน้า index
    //     return redirect()->route('index', ['Strategic_Id' => $Strategic_Id])
    //                     ->with('success', 'โครงการถูกสร้างเรียบร้อยแล้ว');
    // }


    private function createSupProjects($Id_Project, $supProjectNames)
    {
        if (!is_array($supProjectNames)) {
            $supProjectNames = [$supProjectNames];
        }
    
        foreach ($supProjectNames as $supProjectName) {
            if (!empty($supProjectName)) {
                if (is_array($supProjectName)) {
                    $supProjectName = implode(', ', $supProjectName);
                }
                Sup_Project::create([
                    'Project_Id_Project' => $Id_Project,
                    'Name_Sup_Project' => $supProjectName
                ]);
            }
        }
    }



    public function editProject(Request $request, $Id_Project)
    {
        $projects = Project::with('sdgs')->findOrFail($Id_Project);
        $strategics = Strategic::with('strategies')->findOrFail($projects->Strategic_Id); //ดึงข้อมูล Strategic ที่มี Strategic_Id พร้อมฟังก์ชัน strategies
        $strategies = Strategy::all();
        $sdgs = Sdg::all();

        if($request->isMethod('post')){
            $projects->Strategic_Id = $request->Strategic_Id;
            $projects->Name_Project = $request->Name_Project;
            $projects->Name_Strategy = $request->Name_Strategy;
            $projects->Objective_Project = $request->Objective_Project;
            $projects->Indicators_Project = $request->Indicators_Project;
            $projects->Target_Project = $request->Target_Project;
            $projects->First_Time = $request->First_Time;
            $projects->End_Time = $request->End_Time;

            $projects->save();

            // sdgs
            if ($request->has('sdgs')) {
                $selectedSDGs = $request->sdgs; // รับค่าที่ส่งมาจากฟอร์ม (Array)
                $projects->sdgs()->sync($selectedSDGs);
            } else {
                $projects->sdgs()->sync([]);  // ถ้าไม่มีการเลือก SDGs ให้ยกเลิกการเชื่อมโยงทั้งหมด
            }

            //Name_Sup_Project
            if ($request->has('Name_Sup_Project')) {
                $this->createSupProjects($projects->Id_Project, $request->Name_Sup_Project);
            }
            return redirect()->route('index')->with('success', 'แก้ไขโครงการเรียบร้อยแล้ว');
        }
        
        return view('Project.FormEditProject', compact('projects', 'strategies', 'sdgs', 'strategies'));
    }

    public function updateProject(Request $request, $Id_Project)
    {
        $projects = Project::with('sdgs')->findOrFail($Id_Project);
        $strategics = Strategic::with('strategies')->findOrFail($projects->Strategic_Id);
        $strategies = $strategics->strategies;
        
        $projects->Strategic_Id = $request->Strategic_Id;
        $projects->Name_Project = $request->Name_Project;
        $projects->Objective_Project = $request->Objective_Project;
        $projects->Indicators_Project = $request->Indicators_Project;
        $projects->Target_Project = $request->Target_Project;

        $projects->First_Time = $request->First_Time;
        $projects->End_Time = $request->End_Time;
        $projects->save();

        // update Name_Strategy
        if ($request->has('Name_Strategy')) {
            $projects->Name_Strategy = $request->Name_Strategy;
        }

        // update sdgs
        if ($request->has('sdgs')) {
            $projects->sdgs()->detach();
            $projects->sdgs()->attach($request->sdgs); // เชื่อมโยง SDGs_Id ที่เลือก
        } else {
            $projects->sdgs()->detach();  // ถ้าไม่ได้เลือก SDGs (uncheck) ให้ลบออกทั้งหมด
        }

        // อัปเดตโครงการย่อย
        if ($request->has('Name_Sup_Project')) {
            $this->updateSupProjects($projects->Id_Project, $request->Name_Sup_Project);
        }
        return redirect()->route('index')->with('success', 'โครงการถูกอัปเดตเรียบร้อยแล้ว');
    }

    private function updateSupProjects($Id_Project, $supProjectNames)
    {
        if (!is_array($supProjectNames)) {
            $supProjectNames = [$supProjectNames];
        }
    
        // ดึงโครงการย่อยทั้งหมดที่มีอยู่
        $existingSupProjects = Sup_Project::where('Project_Id_Project', $Id_Project)->get();
    
        foreach ($supProjectNames as $index => $supProjectName) {
            if (!empty($supProjectName)) {
                if (is_array($supProjectName)) {
                    $supProjectName = implode(', ', $supProjectName);
                }
    
                // ถ้ามีโครงการย่อยอยู่แล้ว ให้อัปเดต Name_Sup_Project
                if ($existingSupProjects->count() > $index) {
                    $existingSupProjects[$index]->update(['Name_Sup_Project' => $supProjectName]);
                } else {
                    // ถ้าไม่มี ให้สร้างใหม่
                    Sup_Project::create([
                        'Project_Id_Project' => $Id_Project,
                        'Name_Sup_Project' => $supProjectName
                    ]);
                }
            }
        }
        if ($existingSupProjects->count() > count($supProjectNames)) {
            Sup_Project::where('Project_Id_Project', $Id_Project)
                ->whereNotIn('Name_Sup_Project', $supProjectNames)
                ->delete();
        }
    }

    public function viewProjectInStrategic($Id_Strategic)
    {
        $strategics = Strategic::findOrFail($Id_Strategic); //ค้นหาโดย Primary Key 
        $strategics = Strategic::where('Id_Strategic', $Id_Strategic)->firstOrFail(); //ค้นหา Id_Strategic ใน Strategic

        $strategics = Strategic::with('projects')
                        ->where('Id_Strategic', $Id_Strategic)
                        ->firstOrFail();

        $strategics = Strategic::with('projects')->findOrFail($Id_Strategic);


        // แสดงโครงการในยุทศาสตร์
        if ($strategics) {
            return view('Project.ViewProjectInStrategic', [
                'strategics' => $strategics,
            ]);
        } else {
            abort(404, 'not found.');
        }

    }

    public function viewProject($Id_Project)  // รับพารามิเตอร์ id จาก URL
    {
        $projects = Project::with('strategic')->findOrFail($Id_Project); // ดึงข้อมูลโครงการและยุทธศาสตร์ที่เกี่ยวข้อง
        
        return view('Project.ViewProject', [
            'projects' => $projects,
        ]);

    }
}
