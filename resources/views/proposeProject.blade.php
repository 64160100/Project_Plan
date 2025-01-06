@extends('navbar.app')

<head>
    <link rel="stylesheet" href="{{ asset('css/proposeProject.css') }}">
</head>

@section('content')
<div class="container">
    <h1>เสนอโครงการเพื่อพิจารณา</h1>
    @foreach($projects as $project)
    <div class="outer-container">
        <div class="container">
            <div class="header">
                <div class="project-title">{{ $project->Name_Project }}</div>
                <div class="project-subtitle">{{ $project->employee->department->Name_Department ?? 'No Department' }}
                </div>
                <div class="project-info">
                    <div class="info-item">
                        <div class="info-top">
                            <i class='bx bxs-calendar-event' style="width: 20px; height: 0px;"></i>
                            <span class="info-label">วันที่เริ่ม</span>
                        </div>
                        <span class="info-value">
                            {{ $project->formattedFirstTime }}
                        </span>
                    </div>
                    <div class="info-item">
                        <div class="info-top">
                            <i class='bx bx-group' style="width: 20px; height: 0px;"></i>
                            <span class="info-label">ผู้รับผิดชอบ</span>
                        </div>
                        <span class="info-value">
                            {{ $project->employee->Firstname_Employee }} {{ $project->employee->Lastname_Employee }}
                        </span>
                    </div>
                    <div class="info-item">
                        <div class="info-top">
                            <i class='bx bx-wallet-alt' style="width: 20px; height: 0px;"></i>
                            <span class="info-label">งบประมาณ</span>
                        </div>
                        <span class="info-value">500,000 บาท</span>
                    </div>
                </div>
                <ul>
                    @foreach($project->approvals->first()->recordHistory as $history)
                    <li>{{ $history->comment }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="status-section">
                <div class="status-header">สถานะการพิจารณา</div>
                <div class="project-status">
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
                                    {{ $history->Name_Record ?? 'Unknown' }}
                                </div>
                                <div class="status-text">({{ $history->Permission_Record ?? 'Unknown' }})
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
                    <div class="status-card">
                        <div class="status-left">
                            <i class='bx bx-envelope' style="width: 40px;"></i>
                            <div>
                                <div class="status-text">
                                    @if($project->Count_Steps === 0)
                                    เสนอโครงการเพื่อขออนุมัติ หัวหน้าฝ่าย
                                    @elseif($project->Count_Steps === 1)
                                    รอ หัวหน้าฝ่าย
                                    @elseif($project->Count_Steps === 2)
                                    ผู้บริหาร
                                    @elseif($project->Count_Steps === 3)
                                    เสร็จสิ้น
                                    @else
                                    {{ $project->approvals->first()->Status ?? 'รอการอนุมัติ' }}
                                    @endif
                                </div>
                                <div class="status-text">
                                    @if($project->Count_Steps === 0)
                                    เสนอเพื่อพิจารณา
                                    @elseif($project->Count_Steps === 1)
                                    รอหัวหน้าฝ่าย
                                    @elseif($project->Count_Steps === 2)
                                    รอผู้บริหาร พิจารณา
                                    @elseif($project->Count_Steps === 3)
                                    เสร็จสิ้น
                                    @else
                                    {{ $project->approvals->first()->Status ?? 'รอการอนุมัติ' }}
                                    @endif
                                </div>
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
                                เสร็จสิ้น
                                @else
                                {{ $project->approvals->first()->Status ?? 'รอการอนุมัติ' }}
                                @endif
                            </button>
                        </div>
                    </div>
                    <div class="button-container">
                        @if($project->Count_Steps === 0)
                        <form action="{{ route('projects.submitForApproval', ['id' => $project->Id_Project]) }}"
                            method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-log-in-circle'></i> เสนอเพื่อพิจารณา
                            </button>
                        </form>
                        @elseif($project->Count_Steps === 3)
                        <button type="button" class="btn btn-secondary" disabled>ดำเนินการเสร็จสิ้น</button>
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
    </div>
    @endforeach
    @endsection