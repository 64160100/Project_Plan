@php
use Carbon\Carbon;
Carbon::setLocale('th');
@endphp

@extends('navbar.app')

<head>
    <link rel="stylesheet" href="{{ asset('css/proposeProject.css') }}">
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
    </style>
</head>

@section('content')
<div class="container">
    <h1>เสนอโครงการเพื่อพิจารณา</h1>

    @foreach($projects as $project)
    @if($project->Count_Steps !== 0)
    <div class="outer-container">
        <div class="container">
            <div class="header">
                <div class="project-title">{{ $project->Name_Project }}</div>
                <div class="project-subtitle">
                    {{ $project->employee->department->Name_Department ?? 'ยังไม่มีผู้รับผิดชอบโครงการ' }}
                </div>
                <div class="project-info">
                    <div class="info-item">
                        <div class="info-top">
                            <i class='bx bxs-calendar-event' style="width: 20px; height: 0px;"></i>
                            <span class="info-label">วันที่เริ่ม</span>
                        </div>
                        <span class="info-value">{{ $project->formattedFirstTime }}</span>
                    </div>
                    <div class="info-item">
                        <div class="info-top">
                            <i class='bx bx-group' style="width: 20px; height: 0px;"></i>
                            <span class="info-label">ผู้รับผิดชอบ</span>
                        </div>
                        <span class="info-value">
                            @if($project->employee && ($project->employee->Firstname_Employee ||
                            $project->employee->Lastname_Employee))
                            {{ $project->employee->Firstname_Employee ?? '' }}
                            {{ $project->employee->Lastname_Employee ?? '' }}
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
                        <span class="info-value">
                            @if($project->Status_Budget === 'Y')
                            @php
                            $totalBudget = $project->projectBudgetSources->sum('Amount_Total');
                            @endphp
                            {{ number_format($totalBudget, 2) }} บาท
                            @else
                            ไม่ใช้งบประมาณ
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <div class="project-actions">
                <a href="{{ route('StorageFiles.index', ['project_id' => $project->Id_Project]) }}" class="action-link">
                    <i class='bx bx-info-circle'></i>
                    ดูรายละเอียดโครงการ
                </a>

                <div class="dropdown">
                    <a href="#" class="action-link dropdown-toggle" id="commentsDropdown-{{ $project->Id_Project }}"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class='bx bx-message'></i>
                        ข้อเสนอแนะ({{ $project->approvals->first()->recordHistory->where('Status_Record', 'N')->count() }})
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="commentsDropdown-{{ $project->Id_Project }}"
                        style="max-height: 400px; overflow-y: auto; width: 400px;">
                        @php
                        $filteredRecords = $project->approvals->first()->recordHistory->where('Status_Record', 'N');
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
                <div class="status-header">
                    สถานะการพิจารณา
                    <i class='bx bxs-chevron-right toggle-icon' id="toggle-icon-{{ $project->Id_Project }}"></i>
                </div>
                <div class="project-status" id="project-status-{{ $project->Id_Project }}">
                    @if($project->approvals->isNotEmpty() && $project->approvals->first()->recordHistory->isNotEmpty())
                    @foreach($project->approvals->first()->recordHistory as $history)
                    <div class="status-card">
                        <div class="status-left">
                            <i class='bx bx-envelope' style="width: 40px;"></i>
                            <div>
                                <div class="status-text">
                                    {{ $history->comment ?? 'No Comment' }}
                                </div>
                                <div class="status-text">
                                    อนุมัติโดย: {{ $history->Name_Record ?? 'Unknown' }}
                                </div>
                                <div class="status-text">
                                    ตำแหน่ง: {{ $history->Permission_Record ?? 'Unknown' }}
                                </div>
                            </div>
                        </div>
                        <div class="status-right">
                            <span class="status-date">
                                {{ $history->formattedDateTime ?? 'N/A' }}
                            </span>
                            @if($history->Status_Record === 'Y')
                            <button class="status-button approval-status approved">
                                เสร็จสิ้น
                            </button>
                            @elseif($history->Status_Record === 'N')
                            <a href="{{ route('approveProject', ['id' => $history->Approve_Id]) }}"
                                class="status-button approval-status not-approved">
                                ไม่อนุมัติ
                            </a>
                            @else
                            <button class="status-button approval-status pending">
                                รอการอนุมัติ
                            </button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                    @endif

                    @if($project->approvals->first()->Status !== 'N')
                    <div class="status-card">
                        <div class="status-left">
                            <i class='bx bx-envelope' style="width: 40px;"></i>
                            <div>
                                <div class="status-text">
                                    @if($project->Count_Steps === 0)
                                    <div class="status-text">
                                        ขั้นตอนที่ 1: เริ่มต้นการเสนอโครงการ
                                    </div>
                                    <div class="status-text">
                                        ถึง: ผู้อำนวยการพิจารณาเบื้องต้น
                                    </div>
                                    @elseif($project->Count_Steps === 1)
                                    <div class="status-text">
                                        ขั้นตอนที่ 2: อยู่ระหว่างการพิจารณาเบื้องต้น
                                    </div>
                                    <div class="status-text">
                                        สถานะ: รอการพิจารณาจากผู้อำนวยการ
                                    </div>
                                    @elseif($project->Count_Steps === 2)
                                    @if($project->approvals->first()->Status === 'Y')
                                    <div class="status-text">
                                        กรอกข้อมูลของโครงการทั้งหมด
                                    </div>
                                    @else
                                    @if($project->Status_Budget === 'N')
                                    <div class="status-text">
                                        ขั้นตอนที่ 3: การพิจารณาโดยหัวหน้าฝ่าย
                                    </div>
                                    <div class="status-text">
                                        สถานะ: อยู่ระหว่างการพิจารณาโดยหัวหน้าฝ่าย
                                    </div>
                                    @else
                                    <div class="status-text">
                                        ขั้นตอนที่ 3: การพิจารณาด้านงบประมาณ
                                    </div>
                                    <div class="status-text">
                                        ถึง: ฝ่ายการเงินตรวจสอบงบประมาณ
                                    </div>
                                    @endif
                                    @endif
                                    @elseif($project->Count_Steps === 3)
                                    <div class="status-text">
                                        ขั้นตอนที่ 4: การตรวจสอบความเหมาะสมด้านงบประมาณ
                                    </div>
                                    <div class="status-text">
                                        สถานะ: อยู่ระหว่างการตรวจสอบโดยฝ่ายการเงิน
                                    </div>
                                    @elseif($project->Count_Steps === 4)
                                    <div class="status-text">
                                        ขั้นตอนที่ 5: การพิจารณาโดยหัวหน้าฝ่าย
                                    </div>
                                    <div class="status-text">
                                        สถานะ: อยู่ระหว่างการตรวจสอบโดยหัวหน้าฝ่าย
                                    </div>
                                    @elseif($project->Count_Steps === 5)
                                    <div class="status-text">
                                        ขั้นตอนที่ 6: การพิจารณาขั้นสุดท้าย
                                    </div>
                                    <div class="status-text">
                                        สถานะ: อยู่ระหว่างการพิจารณาโดยผู้อำนวยการ
                                    </div>
                                    @elseif($project->Count_Steps === 6)
                                    <div class="status-text">
                                        ขั้นตอนที่ 7: การดำเนินโครงการ
                                    </div>
                                    @if(\Carbon\Carbon::now()->lte(\Carbon\Carbon::parse($project->End_Time)))
                                    <div class="status-text text-success">
                                        สถานะ: เสร็จทันเวลา
                                    </div>
                                    @else
                                    <div class="status-text text-danger">
                                        สถานะ: เสร็จไม่ทันเวลา
                                    </div>
                                    @endif
                                    @elseif($project->Count_Steps === 7)
                                    <div class="status-text">
                                        ขั้นตอนที่ 8: การตรวจสอบผลการดำเนินงาน
                                    </div>
                                    <div class="status-text">
                                        สถานะ: รอการตรวจสอบจากหัวหน้าฝ่าย
                                    </div>
                                    @elseif($project->Count_Steps === 8)
                                    <div class="status-text">
                                        ขั้นตอนที่ 9: การรับรองผลการดำเนินงาน
                                    </div>
                                    <div class="status-text">
                                        สถานะ: รอการรับรองจากผู้อำนวยการ
                                    </div>
                                    @elseif($project->Count_Steps === 9)
                                    <div class="status-text">
                                        ขั้นตอนที่ 10: การปิดโครงการ
                                    </div>
                                    <div class="status-text">
                                        สถานะ: ดำเนินการเสร็จสิ้นสมบูรณ์
                                    </div>
                                    @elseif($project->Count_Steps === 11)
                                    <div class="status-text">
                                        สถานะพิเศษ: การดำเนินการล่าช้า
                                    </div>
                                    <div class="status-text">
                                        สถานะ: รอการพิจารณาจากผู้อำนวยการ
                                    </div>
                                    @else
                                    <div class="status-text">
                                        {{ $project->approvals->first()->Status ?? 'รอการพิจารณา' }}
                                    </div>
                                    @endif
                                </div>
                                @if($project->Count_Steps === 6 || $project->Count_Steps === 11)
                                <div class="status-text">
                                    วันที่สิ้นสุด: {{ $project->End_Time }}
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="status-right">
                            <span class="status-date">
                                @if($project->approvals->first()->recordHistory->isNotEmpty())
                                {{ $project->approvals->first()->recordHistory->first()->formattedTimeRecord }}
                                @else
                                N/A
                                @endif
                            </span>
                            <button class="status-button approval-status pending">
                                @if($project->Count_Steps === 0)
                                ส่ง Email
                                @elseif($project->Count_Steps === 1)
                                กำลังพิจารณา
                                @elseif($project->Count_Steps === 2)
                                กำลังพิจารณา
                                @elseif($project->Count_Steps === 3)
                                กำลังพิจารณา
                                @elseif($project->Count_Steps === 4)
                                กำลังพิจารณา
                                @elseif($project->Count_Steps === 5)
                                กำลังพิจารณา
                                @elseif($project->Count_Steps === 6)
                                กำลังพิจารณา
                                @elseif($project->Count_Steps === 7)
                                กำลังพิจารณา
                                @elseif($project->Count_Steps === 8)
                                เสร็จสิ้น
                                @elseif($project->Count_Steps === 9)
                                สิ้นสุดโครงการ
                                @elseif($project->Count_Steps === 11)
                                โครงการเสร็จไม่ทันเวลา
                                @else
                                {{ $project->approvals->first()->Status ?? 'รอการอนุมัติ' }}
                                @endif
                            </button>
                        </div>
                    </div>
                    @endif

                    <div class="button-container">
                        @if(in_array($project->Count_Steps, [0, 2, 6]))
                        @if($project->Count_Steps === 6)
                        @if(\Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($project->End_Time)))
                        <form action="{{ route('projects.submitForApproval', ['id' => $project->Id_Project]) }}"
                            method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-log-in-circle'></i> ส่งให้ผู้อำนวยการตรวจสอบ
                            </button>
                        </form>
                        @else
                        <a href="{{ route('reportForm', ['id' => $project->Id_Project]) }}" class="btn btn-success">
                            <i class='bx bx-file'></i> ปุ่มรายงานโครงการ
                        </a>
                        @endif
                        @else
                        @if($project->Count_Steps === 2 && $project->approvals->first()->Status === 'Y')
                        <a href="{{ route('projects.edit', ['id' => $project->Id_Project ]) }}" class="btn btn-warning">
                            <i class='bx bx-edit'></i> แก้ไขฟอร์ม
                        </a>
                        @else
                        <form action="{{ route('projects.submitForApproval', ['id' => $project->Id_Project]) }}"
                            method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-log-in-circle'></i> เสนอเพื่อพิจารณา
                            </button>
                        </form>
                        @endif
                        @endif
                        @elseif($project->Count_Steps === 9)
                        <form action="{{ route('projects.submitForApproval', ['id' => $project->Id_Project]) }}"
                            method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-secondary">สิ้นสุดโครงการ</button>
                        </form>
                        @else
                        <button type="button" class="btn btn-secondary" disabled>
                            <i class='bx bx-log-in-circle'></i> เสนอเพื่อพิจารณา
                        </button>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </div>
    @endif
    @endforeach

    <!-- <div class="mb-3">
        <a href="{{ route('createSetProject') }}" class="btn btn-primary">
            <i class='bx bx-plus'></i> สร้างชุดโครงการ ({{ $countStepsZero }} โครงการ)
        </a>
    </div> -->

    @foreach ($quartersByFiscalYear as $fiscalYear => $yearQuarters)
    @foreach ($yearQuarters->sortBy('Quarter') as $quarter)
    @php
    $quarterProjects = $filteredStrategics->filter(function($strategic) use ($quarter) {
    return $strategic->quarterProjects->contains('Quarter_Project_Id', $quarter->Id_Quarter_Project);
    });
    $quarterStyle = $quarterStyles[$quarter->Quarter] ?? 'border-gray-200';

    $hasIncompleteStrategies = isset($incompleteStrategiesByYear[$fiscalYear]) &&
    $incompleteStrategiesByYear[$fiscalYear]->isNotEmpty();
    $missingStrategies = [];
    $logDataIncompleteStrategies = [];

    // Process logData to extract incomplete strategies and missing strategies for the specific fiscal year and quarter
    foreach ($logData as $logEntry) {
    if (strpos($logEntry, "Fiscal Year: $fiscalYear, Quarter: $quarter->Quarter") !== false) {
    if (strpos($logEntry, 'Status: No strategies created') !== false || strpos($logEntry, 'Status: No projects created')
    !== false) {
    $logDataIncompleteStrategies[] = preg_replace('/Fiscal Year: \d{4}, Quarter: \d, Status: (No strategies created|No
    projects created)/', '', $logEntry);
    }
    if (strpos($logEntry, 'Missing Strategy:') !== false) {
    preg_match('/Missing Strategy: (.*?),/', $logEntry, $matches);
    if (isset($matches[1])) {
    $missingStrategies[] = $matches[1];
    }
    }
    }
    }

    // Format logDataIncompleteStrategies to display only the required parts
    $logDataIncompleteStrategies = array_map(function($logEntry) {
    preg_match('/Strategy: (.*?), Strategic: (.*?),/', $logEntry, $matches);
    if (isset($matches[1], $matches[2])) {
    return "{$matches[2]}, {$matches[1]}";
    } elseif (preg_match('/Strategic: (.*?),/', $logEntry, $matches)) {
    return "{$matches[1]}, ไม่มีกลยุทธ์";
    } else {
    return $logEntry;
    }
    }, $logDataIncompleteStrategies);

    foreach ($quarterProjects as $Strategic) {
    foreach ($Strategic->projects as $project) {
    $strategyName = $project->Name_Strategy;
    $strategyProjects = $Strategic->projects->filter(function($p) use ($strategyName) {
    return $p->Name_Strategy === $strategyName;
    });

    $allProjectsStatusN = $strategyProjects->every(function($p) {
    return in_array('N', $p->approvalStatuses) || !isset($p->approvalStatuses);
    });

    $hasProjectStatusI = $strategyProjects->contains(function($p) {
    return in_array('I', $p->approvalStatuses);
    });

    if ($allProjectsStatusN && !$hasProjectStatusI) {
    $missingStrategies[] = $strategyName;
    }
    }
    }

    $missingStrategies = array_unique($missingStrategies);
    $allStrategiesComplete = empty($missingStrategies) && empty($logDataIncompleteStrategies);
    @endphp

    @if($quarterProjects->isNotEmpty())
    <div class="card mb-4 border-2 {{ $quarterStyle }}">
        <div class="card-body">
            <h5 class="card-title">เสนอหาผู้อำนวยการ ปีงบประมาณ {{ $fiscalYear }} ไตรมาส {{ $quarter->Quarter }}</h5>

            @if ($hasIncompleteStrategies || !empty($missingStrategies) || !empty($logDataIncompleteStrategies) ||
            $quarterProjects->contains(function($strategic) {
            return $strategic->projects->contains(function($project) {
            return $project->approvals->first()->Status !== 'I' && $project->Count_Steps !== 2;
            });
            }) ||
            $quarterProjects->contains(function($strategic) {
            $projectsWithCountStepsZero = $strategic->projects->filter(function($project) {
            return $project->Count_Steps === 0;
            });
            $projectsWithStatusI = $projectsWithCountStepsZero->contains(function($project) {
            return $project->approvals->first()->Status === 'I';
            });
            return !$projectsWithStatusI && $projectsWithCountStepsZero->isNotEmpty();
            }) ||
            $quarterProjects->contains(function($strategic) {
            $projectsWithCountStepsOne = $strategic->projects->filter(function($project) {
            return $project->Count_Steps === 1;
            });
            $projectsWithCountStepsZeroAndStatusN = $strategic->projects->filter(function($project) {
            return $project->Count_Steps === 0 && $project->approvals->first()->Status === 'N';
            });
            return $projectsWithCountStepsOne->isNotEmpty() && $projectsWithCountStepsZeroAndStatusN->isNotEmpty();
            }))
            <div class="alert alert-warning">
                <strong>กลยุทธ์ยังไม่ครบสำหรับปีงบประมาณ {{ $fiscalYear }} ไตรมาส {{ $quarter->Quarter }}</strong>
                <ul>
                    @if ($hasIncompleteStrategies)
                    @foreach ($incompleteStrategiesByYear[$fiscalYear] as $strategy)
                    <li>{{ $strategy }}</li>
                    @endforeach
                    @endif
                    @foreach ($missingStrategies as $strategy)
                    <li>{{ $strategy }}: ไม่มีกลยุทธ์</li>
                    @endforeach
                    @foreach ($logDataIncompleteStrategies as $logEntry)
                    <li>{{ $logEntry }}</li>
                    @endforeach
                    @foreach ($quarterProjects as $strategic)
                    @if (!in_array($strategic->Name_Strategy, $missingStrategies))
                    @foreach ($strategic->projects as $project)
                    @if ($project->approvals->first()->Status !== 'I' && $project->Count_Steps !== 2)
                    <li>โครงการ {{ $project->Name_Project }} ยังไม่ได้ส่งไปหา ผู้อำนวยการ</li>
                    @endif
                    @endforeach
                    @endif
                    @endforeach
                </ul>
            </div>
            @else
            <div class="alert alert-success">
                <strong>กลยุทธ์ครบแล้วสำหรับปีงบประมาณ {{ $fiscalYear }} ไตรมาส {{ $quarter->Quarter }}</strong>
            </div>
            @endif

            <div class="mb-3">
                @foreach ($quarterProjects as $Strategic)
                @php
                $filteredProjects = $Strategic->projects->filter(function($project) {
                return $project->Count_Steps == 0;
                });
                $filteredProjectCount = $filteredProjects->count();
                $firstStrategicPlanName = $Strategic->Name_Strategic_Plan;

                // Check for missing strategies
                $hasStrategies = !$Strategic->strategies->isEmpty();
                $hasProjects = $filteredProjectCount > 0;

                $totalStrategicBudget = $filteredProjects->sum(function($project) {
                return $project->projectBudgetSources ? $project->projectBudgetSources->sum('Amount_Total') : 0;
                });
                $projectsByStrategy = $filteredProjects->groupBy('Name_Strategy');
                @endphp

                <details class="accordion" id="{{ $Strategic->Id_Strategic }}">
                    <summary class="accordion-btn">
                        <b>
                            <a>{{ $firstStrategicPlanName }}</a>
                            @if(!$hasStrategies)
                            <br><span class="badge bg-danger">ยังไม่มีกลยุทธ์</span>
                            @elseif(!$hasProjects)
                            <br><span class="badge bg-warning">มีกลยุทธ์แต่ยังไม่มีโครงการ</span>
                            <br>กลยุทธ์ที่มี:
                            <ul class="strategy-list">
                                @foreach($Strategic->strategies as $strategy)
                                <li>{{ $strategy->Name_Strategy }}</li>
                                @endforeach
                            </ul>
                            @endif
                            <br>จำนวนโครงการ : {{ $filteredProjectCount }} โครงการ
                            <br>งบประมาณรวม: {{ number_format($totalStrategicBudget, 2) }} บาท
                        </b>
                    </summary>
                    @if ($filteredProjectCount > 0)
                    <div class="accordion-content">
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
                                    <th style="width:18%; text-align: center;">การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($projectsByStrategy as $strategyName => $projects)
                                @php
                                $strategyCount = $projects->count();
                                @endphp
                                @foreach ($projects as $index => $Project)
                                @php
                                $isStatusN = in_array('N', $Project->approvalStatuses);
                                $isStatusI = in_array('I', $Project->approvalStatuses);
                                $allProjectsDeleted = $projects->every(function($project) {
                                return in_array('N', $project->approvalStatuses);
                                });
                                @endphp
                                <tr>
                                    @if ($index === 0 && $loop->parent->first)
                                    <td rowspan="{{ $filteredProjectCount }}">{{ $firstStrategicPlanName }}</td>
                                    @endif
                                    @if ($index === 0)
                                    <td class="{{ $allProjectsDeleted ? 'text-gray' : '' }}"
                                        rowspan="{{ $strategyCount }}">
                                        {{ $strategyName ?? '-' }}
                                    </td>
                                    @endif
                                    <td class="{{ $isStatusN && !$isStatusI ? 'text-gray' : '' }}">
                                        <b>{{ $Project->Name_Project }}</b><br>
                                        @foreach($Project->supProjects as $supProject)
                                        - {{ $subProject->Name_Sup_Project }}<br>
                                        @endforeach
                                    </td>
                                    <td class="{{ $isStatusN && !$isStatusI ? 'text-gray' : '' }}">
                                        {!! $Project->Success_Indicators ? nl2br(e($Project->Success_Indicators)) : '-'
                                        !!}
                                    </td>
                                    <td class="{{ $isStatusN && !$isStatusI ? 'text-gray' : '' }}">
                                        {!! $Project->Value_Target ? nl2br(e($Project->Value_Target)) : '-' !!}
                                    </td>
                                    <td class="{{ $isStatusN && !$isStatusI ? 'text-gray' : '' }}"
                                        style="text-align: center;">
                                        @if($Project->Status_Budget === 'N')
                                        ไม่ใช้งบประมาณ
                                        @else
                                        @php
                                        $totalBudget = $Project->projectBudgetSources ?
                                        $Project->projectBudgetSources->sum('Amount_Total') : 0;
                                        @endphp
                                        {{ number_format($totalBudget, 2) }}
                                        @endif
                                    </td>
                                    <td class="{{ $isStatusN && !$isStatusI ? 'text-gray' : '' }}">
                                        {{ $Project->employee->Firstname_Employee ?? '-' }}
                                        {{ $Project->employee->Lastname_Employee ?? '' }}
                                    </td>
                                    <td class="{{ $isStatusN && !$isStatusI ? 'text-gray' : '' }}"
                                        style="text-align: center;">
                                        <a href="{{ route('editProject', ['Id_Project' => $Project->Id_Project, 'sourcePage' => 'proposeProject']) }}"
                                            class="btn btn-warning btn-sm">แก้ไข</a>
                                        @if (!$isStatusN || $isStatusI)
                                        <form
                                            action="{{ route('projects.updateStatus', ['id' => $Project->Id_Project]) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to update the status of this project?');">ลบ</button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                @endforeach
                                <tr class="summary-row">
                                    <td colspan="2" style="text-align: left; font-weight: bold;">รวมรายได้ทั้งหมด:</td>
                                    <td colspan="6" style="text-align: center; font-weight: bold;">
                                        {{ number_format($totalStrategicBudget, 2) }} บาท
                                    </td>
                                </tr>
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
            </div>

            <div class="button-container mt-3">
                <form action="{{ route('projects.submitForAllApproval') }}" method="POST">
                    @csrf
                    <input type="hidden" name="quarter" value="{{ $quarter->Quarter }}">
                    <input type="hidden" name="fiscal_year" value="{{ $fiscalYear }}">
                    @foreach ($quarterProjects as $Strategic)
                    @foreach ($Strategic->projects as $project)
                    <input type="hidden" name="project_ids[]" value="{{ $project->Id_Project }}">
                    @endforeach
                    @endforeach
                    <button type="submit" class="btn btn-primary w-full"
                        {{ $allStrategiesComplete && !$hasStatusN ? '' : 'disabled' }}>
                        เสนอโครงการทั้งหมด ไตรมาส {{ $quarter->Quarter }}
                    </button>
                </form>
            </div>

        </div>
    </div>
    @endif
    @endforeach
    @endforeach

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusHeaders = document.querySelectorAll('.status-header');

    statusHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const projectStatus = this.nextElementSibling;
            const toggleIcon = this.querySelector('.toggle-icon');

            if (projectStatus.classList.contains('show')) {
                projectStatus.style.maxHeight = 0;
                projectStatus.classList.remove('show');
                toggleIcon.classList.remove('rotate');
            } else {
                projectStatus.style.maxHeight = projectStatus.scrollHeight + 'px';
                projectStatus.classList.add('show');
                toggleIcon.classList.add('rotate');
            }
        });
    });
});
</script>
@endsection