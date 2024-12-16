<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\ListProjectModel;
use App\Models\StrategicModel;

class ListProjectController extends Controller
{
    public function project()
    {
        $strategics = StrategicModel::with('projects')->get();        
        return view('Project.listProject', compact('strategics'));
    }

    public function showCreateForm()
    {
        $strategics = StrategicModel::with('projects')->get();        
        return view('Project.createProject', compact('strategics'));
    }

    public function createProject(Request $request)
    {
        $project = new ListProjectModel;
        $project->strategic_Id = $request->strategic_id;
        $project->Name_Project = $request->project_name;
        $project->save();

        return redirect()->route('project')->with('success', 'Project created successfully.');
    }
}