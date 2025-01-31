<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectBatchHasProjectModel;
use App\Models\BatchProjectModel;
use App\Models\ListProjectModel;
use Illuminate\Support\Facades\Log;

class ProjectBatchController extends Controller
{
    public function createSetProject(Request $request)
    {
        $projects = ListProjectModel::with([
            'employee.department'
        ])->get();
    
        $batches = BatchProjectModel::all();
    
        $projectBatchRelations = ProjectBatchHasProjectModel::with([
            'batch:Id_Project_Batch,Name_Batch',
            'project:Id_Project,Name_Project',
            'batch:Count_Steps_Batch'
        ])
        ->select('Project_Batch_Id', 'Project_Id', 'Count_Steps_Batch')
        ->orderBy('Project_Batch_Id')
        ->get();

        log::info($projectBatchRelations);  
            
        return view('Project.createSetProject', compact('projects', 'batches', 'projectBatchRelations'));
    }
    
    public function storeProjectBatch(Request $request)
    {
        $request->validate([
            'batch_name' => 'required|string|max:255',
            'project_ids' => 'required|string',
        ]);

        $projectIds = explode(',', $request->input('project_ids'));

        $batch = new BatchProjectModel();
        $batch->Name_Batch = $request->input('batch_name');
        $batch->save();

        foreach ($projectIds as $projectId) {
            ProjectBatchHasProjectModel::create([
                'Project_Batch_Id' => $batch->Id_Project_Batch,
                'Project_Id' => $projectId,
                'Count_Steps_Batch' => 0,
            ]);
        }

        return redirect()->route('createSetProject')->with('success', 'ชุดโครงการถูกสร้างเรียบร้อยแล้ว');
    }

    public function addProjectsToBatch(Request $request)
    {
        $request->validate([
            'Id_Project_Batch' => 'required|exists:mydb.Project_Batch,Id_Project_Batch',
            'project_ids' => 'required|string',
        ]);

        $projectIds = explode(',', $request->input('project_ids'));

        Log::info('Received request to add projects to batch:', [
            'Id_Project_Batch' => $request->input('Id_Project_Batch'),
            'project_ids' => $projectIds,
        ]);

        foreach ($projectIds as $projectId) {
            ProjectBatchHasProjectModel::create([
                'Project_Batch_Id' => $request->input('Id_Project_Batch'),
                'Project_Id' => $projectId,
            ]);
        }

        $updatedBatch = ProjectBatchHasProjectModel::with([
            'batch:Id_Project_Batch,Name_Batch',
            'project:Id_Project,Name_Project',
        ])
        ->where('Project_Batch_id', $request->input('Id_Project_Batch'))
        ->get();

        Log::info('Batch update successful:', [
            'Id_Project_Batch' => $request->input('Id_Project_Batch'),
            'updated_batch' => $updatedBatch->toArray(),
        ]);

        return redirect()->route('createSetProject')->with('success', 'เพิ่มโครงการในชุดเรียบร้อยแล้ว');
    }
        
    public function submitProjectBatch($id)
    {
        $batch = ProjectBatchHasProjectModel::with('project')->where('Project_Batch_id', $id)->get();
    
        foreach ($batch as $relation) {
            $project = $relation->project;
            if ($project->Count_Steps === 0) {
                $project->Count_Steps = 1;
                $project->save();
            }
        }
    
        return redirect()->route('createSetProject')->with('success', 'ชุดโครงการถูกส่งเพื่อพิจารณา');
    }
    
    public function removeBatch($batch_id)
    {
        ProjectBatchHasProjectModel::where('Project_Batch_id', $batch_id)->delete();
    
        BatchProjectModel::where('Id_Project_Batch', $batch_id)->delete();
    
        return redirect()->route('createSetProject')->with('success', 'ลบชุดโครงการเรียบร้อยแล้ว');
    }

    public function removeProjectFromBatch($batch_id, $project_id)
    {
        ProjectBatchHasProjectModel::where('Project_Batch_id', $batch_id)
            ->where('Project_Id', $project_id)
            ->delete();

        return redirect()->route('createSetProject')->with('success', 'ลบโครงการออกจากชุดเรียบร้อยแล้ว');
    }

    public function showBatchesProject($id)
    {
        $project = ListProjectModel::findOrFail($id);
        return view('Project.showBatchesProject', compact('project'));
    }
}
