@extends('navbar.app')

@php
use Carbon\Carbon;
Carbon::setLocale('th');
@endphp
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Index</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('css/requestApproval.css') }}">
    <style>
    @font-face {
        font-family: 'THSarabunNew';
        font-style: normal;
        font-weight: normal;
        src: url('{{ storage_path('fonts/THSarabunNew.ttf') }}') format('truetype');
    }

    @font-face {
        font-family: 'THSarabunNew';
        font-style: normal;
        font-weight: bold;
        src: url('{{ storage_path('fonts/THSarabunNew Bold.ttf') }}') format('truetype');
    }

    .page-container {
        padding: 40px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        margin-bottom: 1px;
        border: 1px solid #000;
    }

    th {
        background-color: #c8e6c9 !important;
        font-weight: bold;
        text-align: center;
        border: 2px solid #000 !important;
        padding: 10px;
        font-size: 14px;
    }

    td {
        border: 2px solid #000 !important;
        padding: 10px;
        font-size: 14px;
        vertical-align: top;
        word-wrap: break-word;
        background-color: white;
    }

    .summary-row {
        background-color: #c8e6c9 !important;
    }

    .summary-row td {
        background-color: #c8e6c9 !important;
        padding: 20px 10px !important;
        border: 2px solid #000 !important;
        line-height: 1.2 !important;
        vertical-align: middle !important;
        font-weight: bold;
    }

    td[rowspan] {
        border: 2px solid #000 !important;
    }

    .button-container {
        margin: 20px 0;
        text-align: center;
    }

    .print-button {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .text-gray {
        color: #a9a9a9;
    }

    .row-gray {
        background-color: #f0f0f0;
    }

    @media print {
        .button-container {
            display: none;
        }

        @page {
            size: A4 landscape;
        }
    }
    </style>
</head>

@section('content')
<div class="container">
    <h1>การอนุมัติทั้งหมด</h1>

    @foreach($logData as $fiscalQuarter => $group)
    @if($group)
    @php
    list($fiscalYear, $quarterName) = explode('-', $fiscalQuarter);
    @endphp
    <div class="card mb-4 border-2">
        <div class="card-body">
            <h5 class="card-title">ปีงบประมาณ: {{ $fiscalYear }} ไตรมาส: {{ $quarterName }}</h5>
            @php
            $displayedStrategicPlans = [];
            @endphp
            @foreach($strategics as $strategic)
            @foreach($strategic->quarterProjects as $quarterProject)
            @if($quarterProject->quarterProject->Fiscal_Year == $fiscalYear && $quarterProject->quarterProject->Quarter
            == $quarterName)
            @php
            $hasStrategies = !$strategic->strategies->isEmpty();
            $hasProjects = $strategic->strategies->pluck('projects')->flatten()->isNotEmpty();
            $filteredProjects = $strategic->strategies->pluck('projects')->flatten()->filter(function($project) {
            return $project->Count_Steps == 1;
            });
            $filteredProjectCount = $filteredProjects->count();
            $totalStrategicBudget = $filteredProjects->sum(function($project) {
            return $project->projectBudgetSources ? $project->projectBudgetSources->sum('Amount_Total') : 0;
            });

            // Check if all projects in a strategy are disapproved
            $allStrategiesGray = true;
            foreach ($strategic->strategies as $strategy) {
            $allProjectsGray = true;
            foreach ($strategy->projects as $project) {
            if ($project->approvals->first()->Status !== 'N') {
            $allProjectsGray = false;
            break;
            }
            }
            if (!$allProjectsGray) {
            $allStrategiesGray = false;
            break;
            }
            }
            @endphp

            @if($filteredProjectCount > 0 && !in_array($strategic->Name_Strategic_Plan, $displayedStrategicPlans))
            @php
            $displayedStrategicPlans[] = $strategic->Name_Strategic_Plan;
            @endphp
            <details class="accordion">
                <summary class="accordion-btn">
                    <b>
                        <a>{{ $strategic->Name_Strategic_Plan }}</a>
                        @if(!$hasStrategies)
                        <br><span class="badge bg-danger">ยังไม่มีกลยุทธ์</span>
                        @elseif(!$hasProjects)
                        <br><span class="badge bg-warning">มีกลยุทธ์แต่ยังไม่มีโครงการ</span>
                        <br>กลยุทธ์ที่มี:
                        <ul class="strategy-list">
                            @foreach($strategic->strategies as $strategy)
                            <li>{{ $strategy->Name_Strategy }}</li>
                            @endforeach
                        </ul>
                        @endif
                        <br>จำนวนโครงการ : {{ $filteredProjectCount }} โครงการ
                        <br>งบประมาณรวม: {{ number_format($totalStrategicBudget, 2) }} บาท
                    </b>
                </summary>
                <div class="accordion-content">
                    <div class="table-responsive">
                        <table class="summary-table">
                            <thead>
                                <tr>
                                    <th style="width:10%; text-align: center;">ยุทธศาสตร์ สำนักหอสมุด</th>
                                    <th style="width:10%; text-align: center;">กลยุทธ์ สำนักหอสมุด</th>
                                    <th style="width:14%; text-align: center;">โครงการ</th>
                                    <th style="width:14%; text-align: center;">ตัวชี้วัดความสำเร็จ<br>ของโครงการ</th>
                                    <th style="width:12%; text-align: center;">ค่าเป้าหมาย</th>
                                    <th style="width:10%; text-align: center;">งบประมาณ (บาท)</th>
                                    <th style="width:12%; text-align: center;">ผู้รับผิดชอบ</th>
                                    <th style="width:10%; text-align: center;">การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                            @php
                            $displayedStrategicName = false;
                            $totalRowCount = $strategic->strategies->sum(function($strategy) {
                            return $strategy->projects->filter(function($project) {
                            return $project->Count_Steps == 1;
                            })->count();
                            });
                            @endphp
                            @foreach($strategic->strategies as $strategy)
                            @php
                            $strategyDisplayed = false;
                            $allProjectsGray = true;
                            $filteredProjects = $strategy->projects->filter(function($project) {
                            return $project->Count_Steps == 1;
                            });
                            @endphp
                            @if($filteredProjects->count() > 0)
                            @foreach($filteredProjects as $index => $project)
                            @php
                            if ($project->approvals->first()->Status !== 'N') {
                            $allProjectsGray = false;
                            }
                            @endphp
                            <tr>
                                @if (!$displayedStrategicName)
                                <td rowspan="{{ $totalRowCount }}">{{ $strategic->Name_Strategic_Plan }}</td>
                                @php
                                $displayedStrategicName = true;
                                @endphp
                                @endif

                                @if (!$strategyDisplayed)
                                <td rowspan="{{ $filteredProjects->count() }}"
                                    class="{{ $allProjectsGray ? 'text-gray' : '' }}">
                                    {{ $strategy->Name_Strategy }}
                                </td>
                                @php
                                $strategyDisplayed = true;
                                @endphp
                                @endif
                                <td class="{{ $project->approvals->first()->Status === 'N' ? 'text-gray' : '' }}">
                                    <b>{{ $project->Name_Project }}</b>
                                </td>
                                <td class="{{ $project->approvals->first()->Status === 'N' ? 'text-gray' : '' }}">
                                    {{ $project->Success_Indicators ?? '-' }}
                                </td>
                                <td class="{{ $project->approvals->first()->Status === 'N' ? 'text-gray' : '' }}">
                                    {{ $project->Value_Target ?? '-' }}
                                </td>
                                <td class="{{ $project->Status_Budget === 'N' ? 'text-gray' : '' }}"
                                    style="text-align: center;">
                                    @if($project->Status_Budget === 'N')
                                    ไม่ใช้งบประมาณ
                                    @else
                                    @php
                                    $totalBudget = $project->projectBudgetSources ?
                                    $project->projectBudgetSources->sum('Amount_Total') : 0;
                                    @endphp
                                    {{ number_format($totalBudget, 2) }}
                                    @endif
                                </td>
                                <td class="{{ $project->approvals->first()->Status === 'N' ? 'text-gray' : '' }}">
                                    {{ $project->employee->Firstname ?? '-' }}
                                    {{ $project->employee->Lastname ?? '-' }}
                                </td>
                                <td>
                                    @if($project->approvals->first()->Status !== 'N')
                                    <button type="button" class="btn btn-danger btn-sm custom-disapprove-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#commentModal-{{ $project->Id_Project }}">ไม่อนุมัติ</button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </details>
            @endif
            @endif
            @endforeach
            @endforeach
            <div class="status-section mt-3">
                <div class="status-header">การดำเนินการ</div>
                <div class="status-card">
                    <div class="status-right">
                        <div class="action-buttons">
                            @if(isset($logData) && is_array($logData) && count($logData) > 0)
                            @if(is_array($group) && count($group) > 0)
                            <form action="{{ route('updateAllStatus') }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="fiscalQuarter" value="{{ $fiscalQuarter }}">
                                @foreach($group as $logEntry)
                                @php
                                $logEntryParts = explode(', ', $logEntry);
                                $projectId = explode(': ', $logEntryParts[1])[1];
                                @endphp
                                <input type="hidden" name="approvals[{{ $projectId }}][id]" value="{{ $projectId }}">
                                <input type="hidden" name="approvals[{{ $projectId }}][status]" value="Y">
                                @endforeach
                                <button type="submit" class="status-button approved">
                                    <i class='bx bx-like'></i> อนุมัติทั้งหมด
                                </button>
                            </form>
                            <button type="button" class="status-button not-approved" data-bs-toggle="modal"
                                data-bs-target="#commentModal-{{ $fiscalQuarter }}">
                                <i class='bx bx-dislike'></i> ไม่อนุมัติทั้งหมด
                            </button>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal for not approving all projects in a fiscal quarter -->
            <div class="modal fade" id="commentModal-{{ $fiscalQuarter }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">เพิ่มความคิดเห็นสำหรับการไม่อนุมัติทั้งหมด ({{ $fiscalQuarter }})
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('updateAllStatus') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="fiscalQuarter" value="{{ $fiscalQuarter }}">
                                @foreach($group as $logEntry)
                                @php
                                $logEntryParts = explode(', ', $logEntry);
                                $projectId = explode(': ', $logEntryParts[1])[1];
                                @endphp
                                <input type="hidden" name="approvals[{{ $projectId }}][id]" value="{{ $projectId }}">
                                <input type="hidden" name="approvals[{{ $projectId }}][status]" value="N">
                                @endforeach
                                <div class="mb-3">
                                    <label for="comment" class="form-label">ความคิดเห็น:</label>
                                    <textarea class="form-control" id="comment" name="comment" rows="3"
                                        required></textarea>
                                </div>
                                <button type="submit" class="btn btn-danger">ยืนยันการไม่อนุมัติทั้งหมด</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal for not approving individual projects -->
            @foreach ($strategics as $strategic)
            @foreach ($strategic->projects as $project)
            @if($project->Count_Steps == 1)
            <div class="modal fade" id="commentModal-{{ $project->Id_Project }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">เพิ่มความคิดเห็น</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('disapproveProject', ['id' => $project->Id_Project]) }}"
                                method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="comment" class="form-label">ความคิดเห็น:</label>
                                    <textarea class="form-control" id="comment" name="comment" rows="3"
                                        required></textarea>
                                </div>
                                <button type="submit" class="btn btn-danger">ยืนยันการไม่อนุมัติ</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
            @endforeach

        </div>
    </div>
    @endif
    @endforeach


    @foreach($approvals as $approval)
    @if($approval->Status !== 'Y' && $approval->Status !== 'N')
    @if($approval->project->Count_Steps != 1)
    <div class="outer-container" id="{{ $approval->project->Id_Project }}">
        <div class="container">
            <div class="header">
                <div class="project-title">{{ $approval->project->Name_Project }}</div>
                <p>{{ $approval->project->employee->department->Name_Department ?? 'ยังไม่มีผู้รับผิดชอบโครงการ' }}</p>
                <div class="project-info">
                    <div class="info-item">
                        <span class="info-icon">
                            <i class='bx bx-calendar' style="width: 20px; height: 0px;"></i>
                        </span>
                        <div>
                            <div class="info-label">วันที่เริ่ม</div>
                            <div class="info-value">{{ $approval->project->formattedFirstTime ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="info-item">
                        <span class="info-icon">
                            <i class='bx bx-user' style="width: 20px; height: 0px;"></i>
                        </span>
                        <div>
                            <div class="info-label">ผู้รับผิดชอบ</div>
                            <div class="info-value">
                                @if($approval->project->employee && ($approval->project->employee->Firstname_Employee ||
                                    $approval->project->employee->Lastname_Employee))
                                    {{ $approval->project->employee->Firstname_Employee ?? '' }}
                                    {{ $approval->project->employee->Lastname_Employee ?? '' }}
                                @else
                                -
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="info-item">
                        <span class="info-icon">
                            <i class='bx bx-wallet-alt' style="width: 20px; height: 0px;"></i>
                        </span>
                        <div>
                            <div class="info-label">งบประมาณ</div>
                            <div class="info-value">-</div>
                        </div>
                    </div>
                </div>

                <div class="project-actions">
                    <div>
                        <a href="{{ route('StorageFiles.index') }}" class="action-link">
                            <i class='bx bx-info-circle'></i>
                            ดูรายละเอียดโครงการ
                        </a>
                    </div>
                    <div class="dropdown">
                        <a href="#" class="action-link dropdown-toggle"
                            id="commentsDropdown-{{ $approval->Id_Approve }}" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class='bx bx-message'></i>
                            ข้อเสนอแนะ({{ $approval->recordHistory->where('Status_Record', 'N')->count() }})
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="commentsDropdown-{{ $approval->Id_Approve }}"
                            style="max-height: 200px; overflow-y: auto; width: 300px;">
                            @php
                            $filteredRecords = $approval->recordHistory->where('Status_Record', 'N');
                            @endphp
                            @if($filteredRecords->count() > 0)
                            @foreach($filteredRecords as $record)
                            <li class="p-2 border-bottom">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="font-weight-bold">{{ $record->Name_Record ?? 'Unknown' }}</span>
                                    <span class="text-muted small">{{ $record->formattedDateTime ?? 'N/A' }}</span>
                                </div>
                                <p class="mb-0">{{ $record->comment ?? 'No Comment' }}</p>
                            </li>
                            @endforeach
                            @else
                            <li class="p-2 text-center text-muted">ไม่มีข้อเสนอแนะ</li>
                            @endif
                        </ul>
                    </div>
                    <div>
                        <a href="#" class="action-link">
                            <i class='bx bx-error warning-icon'></i>
                            แจ้งเตือน
                        </a>
                    </div>
                </div>

                <div class="status-section">
                    <div class="status-header">การดำเนินการ</div>
                    <div class="status-card">
                        <div class="status-left">
                            <div class="status-text">
                                รออนุมัติ: {{ floor($approval->recordHistory->last()->daysSinceTimeRecord ?? 0) }} วัน
                            </div>
                        </div>
                        <div class="status-right">
                            <div class="action-buttons">
                                <form
                                    action="{{ route('approvals.updateStatus', ['id' => $approval->Id_Approve, 'status' => 'Y']) }}"
                                    method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="status-button approved">
                                        <i class='bx bx-like'></i> อนุมัติ
                                    </button>
                                </form>
                                <button type="button" class="status-button not-approved" data-bs-toggle="modal"
                                    data-bs-target="#commentModal-{{ $approval->Id_Approve }}">
                                    <i class='bx bx-dislike'></i> ไม่อนุมัติ
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="commentModal-{{ $approval->Id_Approve }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">เพิ่มความคิดเห็น</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form
                            action="{{ route('approvals.updateStatus', ['id' => $approval->Id_Approve, 'status' => 'N']) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="comment" class="form-label">ความคิดเห็น:</label>
                                <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="status-button not-approved">ยืนยันการไม่อนุมัติ</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endif
    @endforeach

</div>
@endsection