@extends('navbar.app')

@php
use Carbon\Carbon;
Carbon::setLocale('th');
@endphp

<head>
    <meta charset="UTF-8">
    <title>แบบรายงานผลการดำเนินงานโครงการ</title>
    <link rel="stylesheet" href="{{ asset('css/reportResult.css') }}">

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
                <input type="text" class="form-control" value="{{ $project->Name_Project }}" readonly>
            </div>
            
        </div>

        <div class="section">
            <div class="section-title">2. ผู้รับผิดชอบโครงการ</div>
            <div class="form-group">
                <label>ผู้รับผิดชอบโครงการ:</label>
            </div>
        </div>


        <div class="section">
            <div class="section-title">3. วัตถุประสงค์</div>
            <div class="form-group">
                <textarea class="form-control"
                rows="5"
                 style="resize: vertical !important; min-height: 100px !important; max-height: 500px !important;"
                placeholder="ระบุวัตถุประสงค์ของโครงการ" readonly>{{ $project->Objective_Project }}</textarea>
            </div>
        </div>

        <div class="section">
            <div class="section-title">4. กลุ่มเป้าหมาย</div>
            <div class="form-group">
                <label>กลุ่มเป้าหมาย:</label>
                @foreach($project->targets as $target)
                <div style="display: flex; gap: 10px; align-items: center;">
                    <input type="text" class="form-control mt-2" value="{{ $target->Name_Target }}" style="flex: 2;" readonly>
                    <input type="text" class="form-control mt-2" value="{{ $target->Quantity_Target }}" style="flex: 1; text-align: center;" readonly>
                    <input type="text" class="form-control mt-2" value="{{ $target->Unit_Target }}" style="flex: 1;" readonly>
                </div>
                @endforeach
            </div>

            <div class="form-group">
                <label>พื้นที่/ชุมชนเป้าหมาย (ถ้ามี ระบุ)</label>
                <div>
                    @foreach($project->targets as $target)
                        @foreach($target->targetDetails as $detail)
                            <input type="text" class="form-control" value="{{ $detail->Details_Target }}" style="flex: 1;" readonly>  
                        @endforeach
                    @endforeach
                </div>
                
            </div>
            
        </div>

        <div class="section">
            <div class="section-title">5. ระยะเวลาดำเนินงาน</div>
            <div class="form-group">
                <label>ระยะเวลาดำเนินงาน</label><br>
                <div>วันที่เริ่มต้น:</div>
                <input type="text" class="form-control mb-2" value="{{ ($project->First_Time) }}" readonly>
                <div>วันที่สิ้นสุด:</div>
                <input type="text" class="form-control mb-2" value="{{ $project->End_Time }}" readonly>
            </div>
        </div>

        <div class="section">
            <div class="section-title">6. สถานที่ดำเนินงาน</div>
            <div class="form-group">
                <label>สถานที่ดำเนินงาน:</label>
                @foreach($project->locations as $location)
                    <input type="text" class="form-control" value="{{ $location->Name_Location }}" readonly><br>
                @endforeach
            </div>
        </div>

        <div class="section">
            <div class="section-title">7. วิทยากร</div>
            <div class="form-group">
                <label>วิทยากร:</label>
                <input type="text" class="form-control" value="{{ $project->Name_Speaker }}" readonly>
            </div>
        </div>

        <div class="section">
        <div class="section-title">8. รูปแบบกิจกรรมการดำเนินงาน</div>
        <b>วิธีการดำเนินงาน</b><br>
        <p> 
            @foreach($project->shortProjects as $shortProject)
                
                    {{ $loop->iteration }}. {{ $shortProject->Details_Short_Project }}<br>
            @endforeach
        </p>

        <p><b>ขั้นตอนและแผนการดำเนินงาน(PDCA)</b><br></p>
        <!-- โครงการระยะยาว -->
        <table>
            <thead>
                <tr>
                    <th rowspan="2" style="width: 35%; line-height: 0.6; text-align: center; vertical-align: middle;" >กิจกรรมและแผนการเบิกจ่ายงบประมาณ</th>
                    <th colspan="12" style="text-align: center; vertical-align: middle;">
                        <span>ปีงบประมาณ พ.ศ.</span>
                        @php
                            $uniqueYears = $quarterProjects->pluck('quarterProject.Fiscal_Year')->unique();
                        @endphp

                        @foreach($uniqueYears as $year)
                            <span>{{ $year }}</span>
                        @endforeach
                    </th>
                </tr>
                <tr>
                    @foreach($months as $month)
                        <th style="text-align: center; vertical-align: middle; min-width: 80px;">{{ $month }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                

                
            </tbody>
        </table>

        </div>


        <div class="section">
            <div class="section-title">9. ตัวชี้วัดความสำเร็จ</div>

            @if($project->projectHasIndicators->where('indicators.Type_Indicators', 'Quantitative')->isNotEmpty())
                <label><b>เชิงปริมาณ</b></label>
                @foreach($project->projectHasIndicators as $projectIndicator)
                    @if($projectIndicator->indicators && $projectIndicator->indicators->Type_Indicators === 'Quantitative')
                        <input type="text" class="form-control mb-2" value="{{ $projectIndicator->Details_Indicators }}" readonly>
                    @endif
                @endforeach
            @endif

            @if($project->projectHasIndicators->where('indicators.Type_Indicators', 'Qualitative')->isNotEmpty())
                <label class="mt-3"><b>เชิงคุณภาพ</b></label>
                @foreach($project->projectHasIndicators as $projectIndicator)
                    @if($projectIndicator->indicators && $projectIndicator->indicators->Type_Indicators === 'Qualitative')
                        <input type="text" class="form-control mb-2" value="{{ $projectIndicator->Details_Indicators }}" readonly>
                    @endif
                @endforeach
            @endif
        </div>

        <div class="section">
            <form action="{{ route('projects.complete', ['id' => $project->Id_Project]) }}" method="POST">
                @csrf
                @method('POST')

                <div class="section-title">10. สรุปผลการดำเนินงาน</div>
                    <div class="form-group">
                        <label><b>สรุปผล</b></label>
                        @if ($project->approvals->first()->Status == 'Y')
                            <textarea class="form-control" rows="15" readonly>{{ $project->Summary }}</textarea>
                        @else
                            <textarea class="form-control" name="Summary" rows="15">{{ old('Summary', $project->Summary) }}</textarea>
                        @endif
                    </div>

                <div class="section-title">ผลสำเร็จตามตัวชี้วัดของโครงการ</div>
                    @if($project->projectHasIndicators->where('indicators.Type_Indicators', 'Quantitative')->isNotEmpty())
                        <label><b>ตัวชี้วัดเชิงปริมาณ</b></label>
                        @foreach($project->projectHasIndicators as $projectIndicator)
                            @if($projectIndicator->indicators && $projectIndicator->indicators->Type_Indicators === 'Quantitative')
                                <input type="text" class="form-control mb-2" value="{{ $projectIndicator->Details_Indicators }}" readonly>
                            @endif
                        @endforeach
                    @endif

                    @if($project->projectHasIndicators->where('indicators.Type_Indicators', 'Qualitative')->isNotEmpty())
                        <label class="mt-3"><b>ตัวชี้วัดเชิงคุณภาพ</b></label>
                        @foreach($project->projectHasIndicators as $projectIndicator)
                            @if($projectIndicator->indicators && $projectIndicator->indicators->Type_Indicators === 'Qualitative')
                                <input type="text" class="form-control mb-2" value="{{ $projectIndicator->Details_Indicators }}" readonly>
                            @endif
                        @endforeach
                    @endif

                    <div class="section-title mt-3">การมีส่วนร่วมของหน่วยงานภายนอก/ชุมชน</div>
                        <div class="form-group">
                            <label><b>การมีส่วนร่วมของบุคคลภายนอก</b></label>
                            @if ($project->approvals->first()->Status == 'Y' )
                                <input type="text" class="form-control" value="{{ $project->External_Participation }}" readonly>
                            @else
                                <textarea class="form-control" name="External_Participation">{{ old('External_Participation', $project->External_Participation) }}</textarea>
                            @endif
                        </div>

                    <div class="section-title mt-3">งบประมาณ</div>
                    @if (!empty($project) && $project->Status_Budget == 'Y')
                        <label>งบประมาณที่ใช้ทั้งสิ้น:</label>
                    @else
                        <div class="text-danger"><b>ไม่มีงบประมาณ</b></div>
                    @endif 

                    <div class="section-title mt-3">ข้อเสนอแนะ</div>
                    <div class="form-group">
                        <label><b>ข้อเสนอแนะ</b></label>
                        @if ( $project->approvals->first()->Status == 'Y' )
                            <input type="text" class="form-control" value="{{ $project->Suggestions }}" readonly>
                        @else
                            <textarea class="form-control" name="Suggestions">{{ old('Suggestions', $project->Suggestions) }}</textarea>
                        @endif
                    </div>

                <div class="step-buttons">
                    <button type="submit" class="step-button primary" name="action" value="complete" style="height: 48px;" 
                        data-step="ขั้นตอนที่ 1" {{ $project->approvals->first()->Status == 'Y' ? 'disabled' : '' }}>
                        <i class='bx bx-check-circle'></i> เสร็จสิ้น
                    </button>
            </form>
            <form id="submit-form" action="{{ route('projects.submitForApproval', ['id' => $project->Id_Project]) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="Y">
                
                <button type="submit" class="step-button secondary" id="submit-button" data-step="ขั้นตอนที่ 2" 
                    {{ $project->approvals->first()->Status == 'Y' ? '' : 'disabled' }}>
                    <i class='bx bx-log-in-circle'></i> เสนอเพื่อพิจารณา
                </button>
            </form>
                </div>
            
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