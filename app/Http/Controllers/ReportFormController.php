<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListProjectModel;
use App\Models\RecordHistory;

use App\Models\ProjectHasIndicatorsModel;
use App\Models\StrategicObjectivesModel;
use App\Models\KpiModel;
use App\Models\StrategyModel;
use App\Models\BudgetFormModel;
use App\Models\SubtopBudgetModel;
use App\Models\SubtopicBudgetHasBudgetFormModel;
use App\Models\ProjectHasBudgetSourceModel;
use App\Models\MonthsModel;
use App\Models\PdcaModel;
use App\Models\StrategicHasQuarterProjectModel;
use Carbon\Carbon;

class ReportFormController extends Controller
{
    public function showReportForm($id)
    {
        $project = ListProjectModel::with([ 'employee', 'targets.targetDetails', 'locations', 
                            'projectHasIndicators.indicators','shortProjects', 'budgetForm',
                            'monthlyPlans','monthlyPlans.month', 'monthlyPlans.pdca',])->findOrFail($id);

        $months = MonthsModel::orderBy('Id_Months', 'asc')->pluck('Name_Month', 'Id_Months');
        $pdcaStages = PdcaModel::all();

        $quarterProjectId = request()->input('quarter_project_id', StrategicHasQuarterProjectModel::pluck('Quarter_Project_Id')->first() ?? 1);
        $quarterProjects = StrategicHasQuarterProjectModel::with(['strategic', 'quarterProject'])
                    ->where('Quarter_Project_Id', $quarterProjectId)
                    ->get();

        $data = [
            'title' => $project->Name_Project,
            'date' => toThaiNumber(date('d/m/Y')),
            'project' => $project,
            'months' => $months,
            'pdcaStages' => $pdcaStages,
            'quarterProjects' => $quarterProjects,    
        ];

        return view('ReportForm', $data);
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
            $nameResponsible = $employee ? $employee->Firstname . ' ' . $employee->Lastname : 'Unknown';
            $roleCreator = $employee ? $employee->Position_Name : 'Unknown';

            $comment = "รายงานความคืบหน้าการดำเนินโครงการ";

            RecordHistory::create([
                'Approve_Id' => $approval->Id_Approve,
                'Approve_Project_Id' => $approval->Project_Id,
                'Comment' => $comment,
                'Time_Record' => Carbon::now('Asia/Bangkok'),
                'Status_Record' => $approval->Status,
                'Name_Record' => $nameResponsible,
                'Permission_Record' => $roleCreator,
            ]);

            $approval->Status = 'I';
            $approval->save();
        }

        return redirect()->route('proposeProject')->with('success', 'Project submitted for approval.');
    }

    public function reportResult() {
        $strategicId = 1;
        $report = ListProjectModel::find(1);

        $strategicId = $report->Strategic_Id;
        $strategies = StrategyModel::with(['kpis', 'strategic'])
                    ->where('Strategic_Id', $strategicId)
                    ->get();

        $projectIndicator = ProjectHasIndicatorsModel::with('indicators')
                        ->where('Project_Id', 1)
                        ->get();

        $budgetProject = BudgetFormModel::where('Project_Id', 1)->first();

        $subBudgetProject = SubtopicBudgetHasBudgetFormModel::with('subtopicBudget')
                        ->where('Subtopic_Budget_Id',1)
                        ->where('Budget_Form_Id', 1)
                        ->first();

        $projectBudgetSource = ProjectHasBudgetSourceModel::with('budgetSource')
                        ->where('Id_Project_has_Budget_Source',1)
                        ->where('Budget_Source_Id', 1)
                        ->first();

        $data = [
            'report' => $report,
            'strategies' => $strategies,
            'projectIndicator' => $projectIndicator,
            'budgetProject' => $budgetProject,
            'subBudgetProject' => $subBudgetProject,
            'projectBudgetSource' => $projectBudgetSource,
        ];
        return view('reportResult', $data);
    }  

}