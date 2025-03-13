<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\ApproveModel;
use App\Models\ListProjectModel;
use App\Models\RecordHistory;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $employee = $request->session()->get('employee');
        //แจ้งเตือนการอนุญาติ
        $request->session()->forget('pendingApprovalsCount');
        $pendingApprovals = $this->getPendingApprovals($employee);
        $pendingApprovalsCount = $this->calculateApprovalCount($pendingApprovals);
        $request->session()->put('pendingApprovalsCount', $pendingApprovalsCount);
        $this->storeProjectIdsAndNamesInSession($request, $pendingApprovals);

        //แจ้งเตือนจดหมาย
        $results = $this->getRecordHistoriesAndStatusNCount($employee);
        $recordHistories = $results['recordHistories'];
        $statusNCount = $results['statusNCount'];
    
        return view('dashboard', ['employee' => $employee]);
    }

    private function getPendingApprovals($employee)
    {
        $pendingApprovals = collect();
        if ($employee) {
            if ($employee->IsAdmin === 'Y') {
                $pendingApprovals = ApproveModel::whereHas('project', function ($query) {
                    $query->whereNotIn('Count_Steps', [0, 2, 6, 9]);
                })->where('Status', 'I')->get();
            } elseif ($employee->IsManager === 'Y') {
                $pendingApprovals = ApproveModel::whereHas('project', function ($query) {
                    $query->whereIn('Count_Steps', [4, 7]);
                })->where('Status', 'I')->get();
            } elseif ($employee->IsDirector === 'Y') {
                $pendingApprovals = ApproveModel::whereHas('project', function ($query) {
                    $query->whereIn('Count_Steps', [1, 5, 8]);
                })->where('Status', 'I')->get();
            } elseif ($employee->IsFinance === 'Y') {
                $pendingApprovals = ApproveModel::whereHas('project', function ($query) {
                    $query->whereIn('Count_Steps', [3]);
                })->where('Status', 'I')->get();
            } else {
                $pendingApprovals = ApproveModel::whereHas('project', function ($query) use ($employee) {
                    $query->whereIn('Count_Steps', [0, 2, 6, 9])
                        ->where('Employee_Id', $employee->Id_Employee);
                })->where('Status', 'I')->get();
            }
        } 
        return $pendingApprovals ?? collect();
    }

    private function calculateApprovalCount($pendingApprovals)
    {
        $filteredApprovals = $pendingApprovals->filter(function ($approval) {
            return optional($approval->project)->Count_Steps != 1;
        });
        $hasCountStepsOne = $pendingApprovals->contains(function ($approval) {
            return optional($approval->project)->Count_Steps == 1;
        });
        return $filteredApprovals->count() + ($hasCountStepsOne ? 1 : 0);
    }

    private function storeProjectIdsAndNamesInSession($request, $pendingApprovals)
    {
        // id
        $projectIds = $pendingApprovals->pluck('Project_Id');
        $request->session()->put('projectIds', $projectIds);
        // Project names
        $projectNames = ListProjectModel::whereIn('Id_Project', $projectIds)->pluck('Name_Project');
        $request->session()->put('projectNames', $projectNames);
        // Count_Steps
        $countSteps = ListProjectModel::whereIn('Id_Project', $projectIds)->pluck('Count_Steps')
        ->toArray();
        $request->session()->put('countSteps', $countSteps);
    }

    private function getRecordHistoriesAndStatusNCount($employee)
    {
        if ($employee->IsAdmin === 'Y') {
            $recordHistories = RecordHistory::with('approvals.project')
                ->whereHas('approvals', function ($query) {
                    $query->where('Status', '!=', 'Y');
                })
                ->orderBy('Id_Record_History', 'desc')->get();
    
            $statusNCount = RecordHistory::whereHas('approvals', function ($query) {
                $query->where('Status', 'N');
            })
                ->whereIn('Id_Record_History', function ($subQuery) {
                    $subQuery->selectRaw('MAX(Id_Record_History)')
                        ->from('Record_History')
                        ->groupBy('Approve_Project_Id');
                })
                ->count();
        } else {
            $recordHistories = RecordHistory::whereHas('approvals', function ($query) {
                    $query->where('Status', '!=', 'Y');
                })
                ->whereHas('approvals.project', function ($query) use ($employee) {
                    $query->where('Employee_Id', $employee->Id_Employee);
                })
                ->with('approvals.project')
                ->orderBy('Id_Record_History', 'desc')->get();
    
            $statusNCount = RecordHistory::where('Status_Record', 'N')
                ->whereHas('approvals', function ($query) {
                    $query->where('Status', 'N');
                })
                ->whereIn('Id_Record_History', function ($subQuery) {
                    $subQuery->selectRaw('MAX(Id_Record_History)')
                        ->from('Record_History')
                        ->groupBy('Approve_Project_Id');
                })
                ->count();
        }
        return compact('recordHistories', 'statusNCount');
    }
}