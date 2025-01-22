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
                        <i class='bx bx-chevron-up' id="toggleIconTargetGroup"
                            style="cursor: pointer; font-size: 1.5em;" onclick="toggleTargetGroupDetails()"></i>
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


        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        const lineHeight = parseInt(window.getComputedStyle(textarea).lineHeight);
        const rows = Math.ceil(textarea.scrollHeight / lineHeight);
        textarea.setAttribute('rows', rows);
    });

});
</script>
@endsection