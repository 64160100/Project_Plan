@extends('navbar.app')

<head>
    <link rel="stylesheet" href="{{ asset('css/proposeProject.css') }}">
</head>

@section('content')
<div class="container">
    <h1>เสนอโครงการเพื่อพิจารณา</h1>
    @foreach($projects as $project)
    <div class="outer-container"  id="{{ $project->Id_Project }}">
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
                            @if($project->budgetStatus === 'Y')
                            500,000 บาท
                            @else
                            -
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
                        style="max-height: 200px; overflow-y: auto; width: 300px;">
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
                                        ถึง: ผู้บริหารพิจารณาเบื้องต้น
                                    </div>
                                    @elseif($project->Count_Steps === 1)
                                    <div class="status-text">
                                        ขั้นตอนที่ 2: อยู่ระหว่างการพิจารณาเบื้องต้น
                                    </div>
                                    <div class="status-text">
                                        สถานะ: รอการพิจารณาจากผู้บริหาร
                                    </div>
                                    @elseif($project->Count_Steps === 2)
                                    <div class="status-text">
                                        ขั้นตอนที่ 3: การพิจารณาด้านงบประมาณ
                                    </div>
                                    <div class="status-text">
                                        ถึง: ฝ่ายการเงินตรวจสอบงบประมาณ
                                    </div>
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
                                        สถานะ: อยู่ระหว่างการพิจารณาโดยผู้บริหาร
                                    </div>
                                    @elseif($project->Count_Steps === 6)
                                    <div class="status-text">
                                        ขั้นตอนที่ 7: การดำเนินโครงการ
                                    </div>
                                    <div class="status-text">
                                        สถานะ: อยู่ระหว่างการดำเนินงาน
                                    </div>
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
                                        สถานะ: รอการรับรองจากผู้บริหาร
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
                                        สถานะ: รอการพิจารณาจากผู้บริหาร
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
                        @if($project->Count_Steps === 6 &&
                        \Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($project->End_Time)))
                        <form action="{{ route('projects.submitForApproval', ['id' => $project->Id_Project]) }}"
                            method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-log-in-circle'></i> ส่งให้ผู้บริหารตรวจสอบ
                            </button>
                        </form>
                        @else
                        <form action="{{ route('projects.submitForApproval', ['id' => $project->Id_Project]) }}"
                            method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-log-in-circle'></i> เสนอเพื่อพิจารณา
                            </button>
                        </form>
                        @endif
                        @elseif($project->Count_Steps === 9)
                        <form action="{{ route('projects.submitForApproval', ['id' => $project->Id_Project]) }}"
                            method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-secondary">สิ้นสุดโครงการ</button>
                        </form>
                        @elseif($project->approvals->first()->Status === 'N')
                        <a href="{{ route('projects.edit', ['id' => $project->Id_Project ]) }}" class="btn btn-warning">
                            <i class='bx bx-edit'></i> กลับไปแก้ไขฟอร์ม
                        </a>
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