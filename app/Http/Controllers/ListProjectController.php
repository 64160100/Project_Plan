<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListProjectModel;
use App\Models\StrategicModel;

class ListProjectController extends Controller
{
    public function project()
    {
        $strategics = StrategicModel::with('projects')->get();

        return view('listProject', compact('strategics'));
    }

    Public function createProject(Request $request)
    {
        $project = new ListProjectModel;
        $project->strategic_Id = $request->strategic_id;
        $project->Name_Project = $request->project_name;
        $project->save();

        return redirect()->route('project')->with('success', 'Project created successfully.');
    }
}