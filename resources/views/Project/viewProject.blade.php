@extends('navbar.app')

<hade>
    <link rel="stylesheet" href="{{ asset('css/viewProject.css') }}">
</hade>

@section('content')
<div class="container py-4">
    <div class="card">
        <h3 class="card-header">ข้อมูลโครงการ</h3>
        <div class="card-body">

            <!-- ชื่อโครงการ -->
            <div class="content-box">
                <div class="section-header">
                    <h4>
                        1. ชื่อโครงการ
                    </h4>
                </div>
                <div id="projectDetails" class="toggle-content">
                    <div class="form-group">
                        <input type="text" class="form-control @error('Name_Project') is-invalid @enderror"
                            id="Name_Project" name="Name_Project" placeholder="กรอกชื่อโครงการ"
                            value="{{ $project->Name_Project }}" required>
                        @error('Name_Project')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div id="projectContainer">
                        @csrf
                        @foreach($project->subProjects as $index => $subProject)
                        <div class="form-group mt-2">
                            <label for="Name_Sup_Project_{{ $index + 1 }}" class="form-label">โครงการย่อยที่
                                {{ $index + 1 }}</label>
                            <input type="text" class="form-control" id="Name_Sup_Project_{{ $index + 1 }}"
                                name="Name_Sup_Project[]" value="{{ $subProject->Name_Sup_Project }}"
                                placeholder="กรอกชื่อโครงการย่อย">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- ลักษณะโครงการ -->
            <div class="content-box">
                <div class="section-header">
                    <h4>
                        2. ลักษณะโครงการ
                    </h4>
                </div>
                <div class="form-group-radio">
                    <div class="radio-item">
                        <input type="radio" name="projectType" value="1" id="newProject"
                            onchange="toggleTextbox(this, 'textbox-projectType-')" checked>
                        <label for="newProject">โครงการใหม่</label>
                    </div>
                    <div class="radio-item">
                        <input type="radio" name="projectType" value="2" id="continuousProject"
                            onchange="toggleTextbox(this, 'textbox-projectType-')">
                        <label for="continuousProject">โครงการต่อเนื่อง</label>
                    </div>
                </div>
                <div class="form-group" style="display: none;">
                    <input type="text" id="textbox-projectType-2" class="hidden form-control" data-group="projectType"
                        placeholder="กรอกชื่อโครงการเดิม">
                </div>
            </div>

            <!-- ผู้รับผิดชอบโครงการ -->
            <div class="content-box">
                <div class="section-header">
                    <h4>
                        3. ผู้รับผิดชอบโครงการ
                    </h4>
                </div>
                <div id="responsibleDetails">
                    <div class="form-group">
                        <label for="employee_id" class="form-label">เลือกผู้รับผิดชอบ</label>
                        <select class="form-select @error('employee_id') is-invalid @enderror" id="employee_id"
                            name="employee_id" disabled>
                            <option value="" selected disabled>เลือกผู้รับผิดชอบ</option>
                            @foreach($employees as $employee)
                            <option value="{{ $employee->Id_Employee }}"
                                {{ $employee->Id_Employee == $project->Employee_Id ? 'selected' : '' }}>
                                {{ $employee->Firstname_Employee }} {{ $employee->Lastname_Employee }}
                            </option>
                            @endforeach
                        </select>
                        @error('employee_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- ความสอดคล้องกับยุทธศาสตร์มหาวิทยาลัย -->
            <div class="content-box">
                <div class="section-header">
                    <h4>
                        4. ความสอดคล้องกับยุทธศาสตร์มหาวิทยาลัย
                    </h4>
                </div>
                <div id="strategicDetails">
                    <div id="platform-container">
                        @foreach($project->platforms as $platform)
                        <div class="platform-card">
                            <div class="card-header">
                                <h3 class="card-title">{{ $platform->Name_Platform }}</h3>
                            </div>
                            <div class="form-group">
                                <label class="form-label">ชื่อแพลตฟอร์ม</label>
                                <input type="text" name="platforms[{{ $loop->index }}][name]" class="form-control"
                                    value="{{ $platform->Name_Platform }}" disabled>
                            </div>

                            @foreach($platform->programs as $program)
                            <div class="form-group">
                                <label class="form-label">โปรแกรม</label>
                                <input type="text"
                                    name="platforms[{{ $loop->parent->index }}][programs][{{ $loop->index }}][name]"
                                    class="form-control" value="{{ $program->Name_Program }}" disabled>
                            </div>

                            <div class="form-group kpi-container">
                                <div class="kpi-header">
                                    <label class="form-label">KPI</label>
                                </div>
                                <div class="kpi-group">
                                    @foreach($program->kpis as $kpi)
                                    <div class="input-group mt-2">
                                        <input type="text"
                                            name="platforms[{{ $loop->parent->parent->index }}][programs][{{ $loop->index }}][kpis][{{ $loop->index }}][name]"
                                            class="form-control" value="{{ $kpi->Name_KPI }}" disabled>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- ความสอดคล้องกับยุทธศาสตร์ส่วนงาน -->
            <div class="content-box">
                <div class="section-header">
                    <h4>
                        5. ความสอดคล้องกับยุทธศาสตร์ส่วนงาน
                    </h4>
                </div>
                <div id="departmentStrategicDetails">
                    <div class="mb-3 col-md-6">
                        <div class="mb-3">
                            <label for="Name_Strategic_Plan" class="form-label">ชื่อแผนยุทธศาสตร์</label>
                            <input type="text" class="form-control" id="Name_Strategic_Plan" name="Name_Strategic_Plan"
                                value="{{ $strategics->Name_Strategic_Plan }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="Name_Strategy" class="form-label">กลยุทธ์</label>
                            <input type="text" class="form-control @error('Name_Strategy') is-invalid @enderror"
                                name="Name_Strategy" id="Name_Strategy" value="{{ $project->Name_Strategy ?? '-' }}"
                                readonly>
                            @error('Name_Strategy')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- SDGs -->
            <div class="content-box">
                <div class="section-header">
                    <h4>
                        6. SDGs ที่เกี่ยวข้อง
                    </h4>
                </div>
                <div id="sdgDetails">
                    <div class="form-group">
                        @if($project->sdgs->isEmpty())
                        <div class="input-group mt-2">
                            <input type="text" class="form-control" value="ไม่มี SDGs ที่เกี่ยวข้อง" disabled>
                        </div>
                        @else
                        @foreach($project->sdgs as $sdg)
                        <div class="input-group mt-2">
                            <input type="text" class="form-control" value="{{ $sdg->Name_SDGs }}" disabled>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- การบูรณาการงานโครงการ -->
            <div class="content-box">
                <div class="section-header">
                    <h4>
                        7. การบูรณาการงานโครงการ/กิจกรรม
                    </h4>
                </div>
                <div id="integrationDetails">
                    <div class="form-group">
                        @if($project->projectHasIntegrationCategories->isEmpty())
                        <div class="input-group mt-2">
                            <input type="text" class="form-control" value="ไม่มีการบูรณาการที่เกี่ยวข้อง" disabled>
                        </div>
                        @else
                        @foreach($project->projectHasIntegrationCategories as $integration)
                        <div class="input-group mt-2">
                            <input type="text" class="form-control"
                                value="{{ $integration->integrationCategory->Name_Integration_Category }}" disabled>
                            <input type="text" class="form-control" value="{{ $integration->Integration_Details }}"
                                disabled>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- หลักการและเหตุผล -->
            <div class="content-box">
                <div class="section-header">
                    <h4>
                        8. หลักการและเหตุผล
                    </h4>
                </div>
                <div id="rationaleDetails">
                    <div class="form-group">
                        <textarea class="form-control" name="Principles_Reasons" placeholder="กรอกข้อมูล"
                            disabled>{{ $project->Principles_Reasons }}</textarea>
                    </div>
                </div>
            </div>

            <!-- วัตถุประสงค์โครงการ -->
            <div class="content-box">
                <div class="section-header">
                    <h4>
                        9. วัตถุประสงค์โครงการ
                    </h4>
                </div>
                <div id="objectiveDetails">
                    <div class="form-group">
                        <textarea class="form-control" id="Objective_Project" name="Objective_Project"
                            placeholder="กรอกข้อมูล" disabled>{{ $project->Objective_Project }}</textarea>
                    </div>
                </div>
            </div>

            <!-- กลุ่มเป้าหมาย -->
            <div class="content-box">
                <div class="section-header">
                    <h4>
                        10. กลุ่มเป้าหมาย
                    </h4>
                </div>
                <div id="targetGroupDetails" style="display: block;">
                    <div id="targetGroupContainer">
                        @foreach($project->targets as $target)
                        <div class="target-group-item">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" name="target_group[]" class="form-control"
                                        placeholder="กรอกกลุ่มเป้าหมาย" value="{{ $target->Name_Target }}" disabled>
                                    <input type="number" name="target_count[]" class="form-control" placeholder="จำนวน"
                                        value="{{ $target->Quantity_Target }}" disabled>
                                    <input type="text" name="target_unit[]" class="form-control" placeholder="หน่วย"
                                        value="{{ $target->Unit_Target }}" disabled>
                                </div>
                            </div>
                            @foreach($target->targetDetails as $detail)
                            <div class="form-group mt-3">
                                <label>รายละเอียดกลุ่มเป้าหมาย</label>
                                <textarea class="form-control" name="target_details[]" placeholder="กรอกรายละเอียด"
                                    disabled>{{ $detail->Details_Target }}</textarea>
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- สถานที่ดำเนินงาน -->
            <div class="content-box">
                <div class="section-header">
                    <h4>
                        11. สถานที่ดำเนินงาน
                    </h4>
                </div>
                <div id="locationDetails" style="display: block;">
                    <div id="locationContainer">
                        @if($project->locations->isNotEmpty())
                        @foreach($project->locations as $location)
                        <div class="form-group location-item">
                            <input type="text" class="form-control small-input" name="location[]"
                                value="{{ $location->Name_Location }}" disabled>
                        </div>
                        @endforeach
                        @else
                        <div class="form-group location-item">
                            <input type="text" class="form-control small-input" value="-" disabled>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- ตัวชี้วัด -->
            <div class="content-box">
                <div class="section-header">
                    <h4>
                        12. ตัวชี้วัด
                    </h4>
                </div>
                <div id="indicatorsDetails" style="display: block;">
                    @php
                    $quantitativeIndicators = $project->projectHasIndicators->filter(function($indicator) {
                    return $indicator->indicators->Type_Indicators === 'Quantitative';
                    });
                    $qualitativeIndicators = $project->projectHasIndicators->filter(function($indicator) {
                    return $indicator->indicators->Type_Indicators === 'Qualitative';
                    });
                    @endphp

                    @if($quantitativeIndicators->isNotEmpty())
                    <div class="form-group">
                        <h6>เชิงปริมาณ</h6>
                        @foreach($quantitativeIndicators as $indicator)
                        <div class="form-group">
                            <textarea class="form-control" name="indicators_details[]" placeholder="กรอกรายละเอียด"
                                disabled>{{ $indicator->Details_Indicators }}</textarea>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="form-group">
                        <h6>เชิงปริมาณ</h6>
                        <div class="form-group">
                            <textarea class="form-control" placeholder="-" disabled>-</textarea>
                        </div>
                    </div>
                    @endif

                    @if($qualitativeIndicators->isNotEmpty())
                    <div class="form-group">
                        <h6>เชิงคุณภาพ</h6>
                        @foreach($qualitativeIndicators as $indicator)
                        <div class="form-group">
                            <textarea class="form-control" name="indicators_details[]" placeholder="กรอกรายละเอียด"
                                disabled>{{ $indicator->Details_Indicators }}</textarea>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="form-group">
                        <h6>เชิงคุณภาพ</h6>
                        <div class="form-group">
                            <textarea class="form-control" placeholder="-" disabled>-</textarea>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- filepath: /home/aries/Project/Project_Plan/resources/views/Project/viewProject.blade.php -->

            <div class="content-box">
                <div class="section-header">
                    <h4>
                        13. ระยะเวลาดำเนินโครงการ
                    </h4>
                </div>
                <div id="projectDurationDetails" style="display: block;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="First_Time">วันที่เริ่มต้น:</label>
                                <input type="text" class="form-control" id="First_Time" name="First_Time"
                                    value="{{ $firstTime ?? '-' }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="End_Time">วันที่สิ้นสุด:</label>
                                <input type="text" class="form-control" id="End_Time" name="End_Time"
                                    value="{{ $endTime ?? '-' }}" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!--  -->
            <div class="content-box">
                <div class="section-header">
                    <h4>
                        14. ขั้นตอนและแผนการดำเนินงาน (PDCA)
                        <i class='bx bx-chevron-up' id="toggleIconPlan" style="cursor: pointer; font-size: 1.5em;"
                            onclick="togglePlanDetails()"></i>
                    </h4>
                </div>
                <div id="planDetails">
                    <div class="form-group-radio mb-4">
                        <input type="radio" name="Project_Type" value="S" id="shortTermProject"
                            {{ $project->Project_Type == 'S' ? 'checked' : '' }} disabled>
                        <label for="shortTermProject">โครงการระยะสั้น</label>
                        &nbsp;&nbsp;
                        <input type="radio" name="Project_Type" value="L" id="longTermProject"
                            {{ $project->Project_Type == 'L' ? 'checked' : '' }} disabled>
                        <label for="longTermProject">โครงการระยะยาว</label>
                    </div>

                    @if($project->Project_Type == 'S')
                    <!-- วิธีการดำเนินงาน -->
                    <div id="textbox-planType-1" data-group="planType">
                        <div class="method-form">
                            <div class="form-label">วิธีการดำเนินงาน</div>
                            <div id="methodContainer" class="method-items">
                                @foreach($project->methods as $method)
                                <div class="form-group mt-2">
                                    <input type="text" class="form-control" value="{{ $method }}" readonly>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($project->Project_Type == 'L')
                    <div id="textbox-planType-2" data-group="planType">
                        <table class="table-PDCA">
                            <thead>
                                <tr>
                                    <th rowspan="2">กิจกรรมและแผนการเบิกจ่ายงบประมาณ</th>
                                    <th colspan="12">ปีงบประมาณ พ.ศ. 2567</th>
                                </tr>
                                <tr>
                                    @foreach($months as $month)
                                    <th>{{ $month }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pdcaStages as $stage)
                                <tr>
                                    <td class="PDCA">
                                        <div class="plan-text">{{ $stage->Name_PDCA }}</div>
                                        @php
                                        $details = $pdcaDetails->where('PDCA_Stages_Id',
                                        $stage->Id_PDCA_Stages)->first();
                                        @endphp
                                        <textarea class="plan-textarea auto-expand"
                                            name="pdca[{{ $stage->Id_PDCA_Stages }}][detail]"
                                            placeholder="เพิ่มรายละเอียด">{{ $details ? $details->Details_PDCA : '' }}</textarea>
                                    </td>
                                    @for($i = 1; $i <= 12; $i++) <td class="checkbox-container">
                                        <input type="checkbox" name="pdca[{{ $stage->Id_PDCA_Stages }}][months][]"
                                            value="{{ $i }}"
                                            {{ $monthlyPlans->where('PDCA_Stages_Id', $stage->Id_PDCA_Stages)->where('Months_Id', $i)->isNotEmpty() ? 'checked' : '' }}>
                                        </td>
                                        @endfor
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>


        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // หลักการและเหตุผล&&วัตถุประสงค์โครงการ
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        const lineHeight = parseInt(window.getComputedStyle(textarea).lineHeight);
        const rows = Math.ceil(textarea.scrollHeight / lineHeight);
        textarea.setAttribute('rows', rows);
    });

    // ตัวชี้วัด
    const textareas = document.querySelectorAll('#indicatorsDetails textarea');
    textareas.forEach(textarea => {
        const lineHeight = parseInt(window.getComputedStyle(textarea).lineHeight);
        const rows = Math.ceil(textarea.scrollHeight / lineHeight);
        textarea.setAttribute('rows', rows);
    });

});
</script>
@endsection