<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListProjectModel;
use App\Models\RecordHistory;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ReportFormController extends Controller
{
    public function showReportForm($id)
    {
        $project = ListProjectModel::with(['strategic', 'strategy', 'employee', 'approvals', 'subProjects', 'storageFiles', 'sdgs', 'platforms', 'projectHasSDGs', 'projectHasIntegrationCategories', 'targets', 'locations', 'projectHasIndicators', 'pdca'])->findOrFail($id);
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

}