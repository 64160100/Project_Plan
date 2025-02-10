@php
use Carbon\Carbon;
Carbon::setLocale('th');
@endphp

@extends('navbar.app')

<hade>
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
        margin-bottom: 20px;
    }

    th,
    td {
        border: 1px solid #000;
        padding: 10px;
        font-size: 14px;
        vertical-align: top;
        word-wrap: break-word;
        background-color: white;
    }

    th {
        background-color: #c8e6c9 !important;
        font-weight: bold;
        text-align: center;
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

    @media print {
        .button-container {
            display: none;
        }

        @page {
            size: A4 landscape;
        }
    }
    </style>
</hade>

@section('content')
<div class="container">
    <h1>การอนุมัติทั้งหมด</h1>
    @foreach($approvals as $approval)
    @if($approval->Status !== 'Y' && $approval->Status !== 'N')
    @if($approval->project->Count_Steps != 1)
    <div class="outer-container">
        <div class="container">
            <div class="header">
                <div class="project-title">{{ $approval->project->Name_Project }}</div>
                <p>{{ $approval->project->employee->department->Name_Department ?? 'ยังไม่มีผู้รับผิดชอบโครงการ' }}</p>
                <div class="project-info">
                    <div class="info-item">
                        <div class="info-top">
                            <i class='bx bx-calendar' style="width: 20px; height: 0px;"></i>
                            <span class="info-label">วันที่เริ่ม</span>
                        </div>
                        <span class="info-value">
                            {{ $approval->project->formattedFirstTime ?? '-' }}
                        </span>
                    </div>
                    <div class="info-item">
                        <div class="info-top">
                            <i class='bx bx-user' style="width: 20px; height: 0px;"></i>
                            <span class="info-label">ผู้รับผิดชอบ</span>
                        </div>
                        <span class="info-value">
                            @if($approval->project->employee && ($approval->project->employee->Firstname_Employee ||
                            $approval->project->employee->Lastname_Employee))
                            {{ $approval->project->employee->Firstname_Employee ?? '' }}
                            {{ $approval->project->employee->Lastname_Employee ?? '' }}
                            @else
                            -
                            @endif
                        </span>

                    </div>
                    <div class="info-item">
                        <div class="info-top">
                            <i class='bx bx-wallet-alt' style="width: 20px; height: 0px;"></i>
                            <span class="info-label">งบประมาณ</span>
                        </div>
                        <span class="info-value">-</span>
                    </div>
                </div>

                <div class="project-actions">
                    <a href="{{ route('StorageFiles.index') }}" class="action-link">
                        <i class='bx bx-info-circle'></i>
                        ดูรายละเอียดโครงการ
                    </a>

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

                    <a href="#" class="action-link">
                        <i class='bx bx-error warning-icon'></i>
                        แจ้งเตือน
                    </a>
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

    <!-- การอนุมัติจากผู้อำนวยการช่วงแรก -->
    @if($employee->IsDirector === 'Y')
    @php
    $hasProjectsToApprove = false;
    foreach ($strategics as $Strategic) {
    if ($Strategic->projects->contains(function($project) {
    return $project->Count_Steps == 1;
    })) {
    $hasProjectsToApprove = true;
    break;
    }
    }
    @endphp

    @if($hasProjectsToApprove)
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">การอนุมัติจากผู้อำนวยการ</h5>
            @foreach ($strategics as $Strategic)
            @php
            $filteredProjects = $Strategic->projects->filter(function($project) {
            return $project->Count_Steps == 1;
            });
            $filteredProjectCount = $filteredProjects->count();
            @endphp
            <details class="accordion" id="{{ $Strategic->Id_Strategic }}">
                <summary class="accordion-btn">
                    <b>{{ $Strategic->Name_Strategic_Plan }}</b><br>จำนวนโครงการ :
                    {{ $filteredProjectCount }} โครงการ
                </summary>
                @if ($filteredProjectCount > 0)
                <div class="accordion-content">
                    <table>
                        <thead>
                            <tr>
                                <th style="width:10%">ยุทธศาสตร์ สำนักหอสมุด</th>
                                <th style="width:10%">กลยุทธ์ สำนักหอสมุด</th>
                                <th style="width:14%">โครงการ</th>
                                <th style="width:14%">ตัวชี้วัดความสำเร็จ<br>ของโครงการ</th>
                                <th style="width:12%">ค่าเป้าหมาย</th>
                                <th style="width:10%">งบประมาณ (บาท)</th>
                                <th style="width:12%">ผู้รับผิดชอบ</th>
                                <th style="width:10%">การจัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($filteredProjects as $index => $Project)
                            @php
                            $isStatusN = $Project->approvals->contains('Status', 'N');
                            @endphp
                            <tr>
                                @if ($index === 0)
                                <td rowspan="{{ $filteredProjectCount }}">{{ $Strategic->Name_Strategic_Plan }}</td>
                                @endif
                                <td class="{{ $isStatusN ? 'text-gray' : '' }}">{{ $Project->Name_Strategy ?? '-' }}
                                </td>
                                <td class="{{ $isStatusN ? 'text-gray' : '' }}">
                                    <b>{{ $Project->Name_Project }}</b><br>
                                    @foreach($Project->subProjects as $subProject)
                                    - {{ $subProject->Name_Sup_Project }}<br>
                                    @endforeach
                                </td>
                                <td class="{{ $isStatusN ? 'text-gray' : '' }}">{!! $Project->Success_Indicators ?
                                    nl2br(e($Project->Success_Indicators)) : '-' !!}</td>
                                <td class="{{ $isStatusN ? 'text-gray' : '' }}">{!! $Project->Value_Target ?
                                    nl2br(e($Project->Value_Target)) : '-' !!}</td>
                                <td class="{{ $isStatusN ? 'text-gray' : '' }}" style="text-align: center;">
                                    @php
                                    $totalBudget = $Project->projectBatchHasProjects ?
                                    $Project->projectBatchHasProjects->sum('Amount_Total') : 0;
                                    @endphp
                                    @if($totalBudget === 0)
                                    ไม่ใช้งบประมาณ
                                    @else
                                    {{ number_format($totalBudget, 2) }}
                                    @endif
                                </td>
                                <td class="{{ $isStatusN ? 'text-gray' : '' }}">
                                    {{ $Project->employee->Firstname_Employee ?? '-' }}
                                    {{ $Project->employee->Lastname_Employee ?? '' }}
                                </td>
                                <td class="{{ $isStatusN ? 'text-gray' : '' }}">
                                    @if(!$isStatusN)
                                    <button type="button" class="btn btn-danger btn-sm custom-disapprove-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#commentModal-{{ $Project->Id_Project }}">ไม่อนุมัติ</button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="accordion-content">
                    <p>ไม่มีโครงการที่เกี่ยวข้อง</p>
                </div>
                @endif
            </details>
            @endforeach
            <div class="status-section mt-3">
                <div class="status-header">การดำเนินการ</div>
                <div class="status-card">
                    <div class="status-right">
                        <div class="action-buttons">
                            @if(isset($approval))
                            <form action="{{ route('approvals.updateAllStatus') }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('PUT')
                                @foreach($approvals as $approval)
                                <input type="hidden" name="approvals[{{ $approval->Id_Approve }}][id]"
                                    value="{{ $approval->Id_Approve }}">
                                <input type="hidden" name="approvals[{{ $approval->Id_Approve }}][status]" value="Y">
                                @endforeach
                                <button type="submit" class="status-button approved">
                                    <i class='bx bx-like'></i> อนุมัติทั้งหมด
                                </button>
                            </form>
                            <button type="button" class="status-button not-approved" data-bs-toggle="modal"
                                data-bs-target="#commentModal-{{ $approval->Id_Approve }}">
                                <i class='bx bx-dislike'></i> ไม่อนุมัติทั้งหมด
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for not approving all projects -->
    @foreach ($strategics as $Strategic)
    <div class="modal fade" id="commentModal-{{ $Strategic->Id_Strategic }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">เพิ่มความคิดเห็น</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('disapproveAll', ['id' => $Strategic->Id_Strategic]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="Count_Steps" value="0">
                        @foreach ($Strategic->projects as $Project)
                        <input type="hidden" name="project_ids[]" value="{{ $Project->Id_Project }}">
                        @endforeach
                        <div class="mb-3">
                            <label for="comment" class="form-label">ความคิดเห็น:</label>
                            <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger">ยืนยันการไม่อนุมัติ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Modal for not approving individual projects -->
    @foreach ($strategics as $Strategic)
    @foreach ($Strategic->projects as $Project)
    <div class="modal fade" id="commentModal-{{ $Project->Id_Project }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">เพิ่มความคิดเห็น</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('disapproveProject', ['id' => $Project->Id_Project]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="comment" class="form-label">ความคิดเห็น:</label>
                            <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger">ยืนยันการไม่อนุมัติ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    @endforeach
    @endif
    @endif

</div>
@endsection