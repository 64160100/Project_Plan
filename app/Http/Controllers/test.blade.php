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
    
        return $pendingApprovals;
    }