@extends('navbar.app')

@php
use Carbon\Carbon;
Carbon::setLocale('th');
@endphp

<head>
    <meta charset="UTF-8">
    <title>แบบรายงานผลการดำเนินงานโครงการ</title>
    <style>
    * {
        font-family: 'Sarabun', sans-serif;
        box-sizing: border-box;
    }

    .report-container {
        background: white;
        padding: 40px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .header {
        text-align: center;
        margin-bottom: 40px;
        border-bottom: 2px solid #1e88e5;
        padding-bottom: 20px;
    }

    .header img {
        max-width: 250px; /* Increase the max-width to make the logo larger */
        margin-bottom: 20px;
    }

    .section {
        margin-bottom: 30px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 5px;
    }

    .section-title {
        color: #1e88e5;
        font-size: 1.2em;
        margin-bottom: 15px;
        border-bottom: 1px solid #dee2e6;
        padding-bottom: 5px;
    }

    .subsection {
        margin-left: 20px;
        margin-bottom: 15px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-control {
        width: 100%;
        padding: 8px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        min-height: 40px;
    }

    textarea.form-control {
        min-height: 100px;
    }

    .table-container {
        margin: 20px 0;
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        border: 1px solid #dee2e6;
        padding: 12px;
        text-align: left;
    }

    th {
        background-color: #f8f9fa;
    }

    .step-buttons {
        display: flex;
        gap: 20px;
        margin-top: 30px;
        justify-content: center;
    }

    .step-button {
        position: relative;
        padding: 12px 24px;
        border-radius: 8px;
        border: none;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        min-width: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .step-button::before {
        content: attr(data-step);
        position: absolute;
        top: -25px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 14px;
        color: #666;
    }

    .step-button.primary {
        background-color: #1e88e5;
        color: white;
    }

    .step-button.primary:hover:not(:disabled) {
        background-color: #1565c0;
    }

    .step-button.secondary {
        background-color: #757575;
        color: white;
    }

    .step-button.secondary:hover:not(:disabled) {
        background-color: #616161;
    }

    .step-button.secondary.active {
        background-color: #2e7d32;
        /* เปลี่ยนเป็นสีเขียว เมื่อปุ่มแรกถูกกด */
    }

    .step-button.secondary.active:hover:not(:disabled) {
        background-color: #1b5e20;
    }

    .step-button:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .step-button i {
        font-size: 20px;
    }
    </style>
</head>

@section('content')
<div class="container">
    <div class="report-container">
        <div class="header">
            <img src="{{asset('images/logo_BUU_LIB.png')}}" alt="มหาวิทยาลัยบูรพา">
            <h1>แบบรายงานผลการดำเนินงานโครงการ</h1>
            <h2>สำนักหอสมุด มหาวิทยาลัยบูรพา</h2>
        </div>

        <div class="section">
            <div class="section-title">1. ข้อมูลโครงการ</div>
            <div class="form-group">
                <label>ชื่อโครงการ:</label>
                <input type="text" class="form-control" value="{{ $project->Name_Project }}">
            </div>
            <div class="form-group">
                <label>ผู้รับผิดชอบโครงการ:</label>
                <input type="text" class="form-control" value="{{ $project->employee->Name_Employee ?? '' }}">
            </div>
        </div>

        <div class="section">
            <div class="section-title">2. วัตถุประสงค์</div>
            <div class="form-group">
                <textarea class="form-control"
                    placeholder="ระบุวัตถุประสงค์ของโครงการ">{{ $project->Objective_Project }}</textarea>
            </div>
        </div>

        <div class="section">
            <div class="section-title">3. รายละเอียดการดำเนินงาน</div>
            <div class="form-group">
                <label>กลุ่มเป้าหมาย:</label>
                <input type="text" class="form-control" value="{{ $project->Target_Group }}">
            </div>
            <div class="form-group">
                <label>ระยะเวลาดำเนินงาน:</label>
                <input type="text" class="form-control" value="{{ $project->Duration }}">
            </div>
            <div class="form-group">
                <label>สถานที่ดำเนินงาน:</label>
                <input type="text" class="form-control" value="{{ $project->Location }}">
            </div>
            <div class="form-group">
                <label>วิทยากร:</label>
                <input type="text" class="form-control" value="{{ $project->Speaker }}">
            </div>
        </div>

        <div class="section">
            <div class="section-title">4. ตัวชี้วัดความสำเร็จ</div>
            <div class="subsection">
                <h4>ตัวชี้วัดเชิงปริมาณ</h4>
                <textarea class="form-control">{{ $project->Quantitative_Indicators }}</textarea>
            </div>
            <div class="subsection">
                <h4>ตัวชี้วัดเชิงคุณภาพ</h4>
                <textarea class="form-control">{{ $project->Qualitative_Indicators }}</textarea>
            </div>
        </div>

        <div class="section">
            <div class="section-title">5. สรุปผลการดำเนินงาน</div>
            <div class="form-group">
                <textarea class="form-control" rows="6">{{ $project->Summary }}</textarea>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>กิจกรรม</th>
                            <th>วันที่จัด</th>
                            <th>สถานที่</th>
                            <th>จำนวนผู้เข้าร่วม</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($project->supProjects)
                        @foreach($project->supProjects as $activity)
                        <tr>
                            <td>{{ $activity->Name }}</td>
                            <td>{{ $activity->Date }}</td>
                            <td>{{ $activity->Location }}</td>
                            <td>{{ $activity->Participants }}</td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="4">No activities found.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div class="section">
            <div class="section-title">6. การมีส่วนร่วมของหน่วยงานภายนอก/ชุมชน</div>
            <div class="form-group">
                <textarea class="form-control">{{ $project->External_Participation }}</textarea>
            </div>
        </div>

        <div class="section">
            <div class="section-title">7. งบประมาณ</div>
            <div class="form-group">
                <label>งบประมาณที่ใช้ทั้งสิ้น:</label>
                <input type="text" class="form-control" value="{{ $project->Budget }}">
            </div>
        </div>

        <div class="section">
            <div class="section-title">8. ข้อเสนอแนะ</div>
            <div class="form-group">
                <textarea class="form-control">{{ $project->Suggestions }}</textarea>
            </div>
        </div>

        <div class="step-buttons">
            <form id="complete-form" action="{{ route('projects.complete', ['id' => $project->Id_Project]) }}" method="POST">
                @csrf
                <button type="submit" class="step-button primary" id="complete-button" data-step="ขั้นตอนที่ 1" {{ $project->approvals->first()->Status == 'Y' ? 'disabled' : '' }}>
                    <i class='bx bx-check-circle'></i> เสร็จสิ้น
                </button>
            </form>
            <form id="submit-form" action="{{ route('projects.submitForApproval', ['id' => $project->Id_Project]) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="Y">
                <button type="submit" class="step-button secondary" id="submit-button" data-step="ขั้นตอนที่ 2" {{ $project->approvals->first()->Status == 'Y' ? '' : 'disabled' }}>
                    <i class='bx bx-log-in-circle'></i> เสนอเพื่อพิจารณา
                </button>
            </form>
        </div>
        
    </div>
</div>

<script>
document.getElementById('complete-form').addEventListener('submit', function() {
    const submitButton = document.getElementById('submit-button');
    submitButton.disabled = false;
    submitButton.classList.add('active'); // เพิ่ม class active เมื่อปุ่มแรกถูกกด
});
</script>
@endsection