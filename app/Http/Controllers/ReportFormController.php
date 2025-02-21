<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListProjectModel;
use App\Models\RecordHistory;
use Carbon\Carbon;

class ReportFormController extends Controller
{
    public function showReportForm($id)
    {
        $project = ListProjectModel::with(['strategic', 'strategy', 'employee', 'approvals', 'supProjects', 'storageFiles', 'sdgs', 'platforms', 'projectHasSDGs', 'projectHasIntegrationCategories', 'targets', 'locations', 'projectHasIndicators', 'pdca'])->findOrFail($id);
        return view('ReportForm', compact('project'));
    }

    public function completeProject(Request $request, $id)
    {
        $project = ListProjectModel::with('approvals')->findOrFail($id);

        $approval = $project->approvals->first();
        $approval->Status = 'Y';
        $approval->save();

        return redirect()->back()->with('success', 'Project marked as complete.');
    }

    public function submitForApproval(Request $request, $id)
    {
        $project = ListProjectModel::with('approvals')->findOrFail($id);
        if ($project->Count_Steps == 6) {
            $project->Count_Steps = 7;
            $project->save();

            $approval = $project->approvals->first();

            $employee = $request->session()->get('employee');
            $permissions = $employee ? $employee->permissions : collect();
            $nameResponsible = $employee ? $employee->Firstname_Employee . ' ' . $employee->Lastname_Employee : 'Unknown';
            $permissionName = $permissions->first()->Name_Permission ?? 'Unknown';

            $comment = "รายงานความคืบหน้าการดำเนินโครงการ";

            RecordHistory::create([
                'Approve_Id' => $approval->Id_Approve,
                'Approve_Project_Id' => $approval->Project_Id,
                'Comment' => $comment,
                'Time_Record' => Carbon::now('Asia/Bangkok'),
                'Status_Record' => $approval->Status,
                'Name_Record' => $nameResponsible,
                'Permission_Record' => $permissionName,
            ]);

            $approval->Status = 'I';
            $approval->save();
        }

        return redirect()->route('proposeProject')->with('success', 'Project submitted for approval.');
    }
}