@extends('navbar.app')

<hade>
    <link rel="stylesheet" href="{{ asset('css/createFirstForm.css') }}">
</hade>

@section('content')
<div class="container py-4">
    <div class="card">
        <h3 class="card-header">แก้ไขข้อมูลโครงการ</h3>

        <div class="card-body">
            <form action="{{ route('updateProject', ['Id_Project' => $project->Id_Project]) }}" method="POST"
                class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                <!-- ความสอดคล้องกับยุทธศาสตร์ส่วนงาน -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            1. ความสอดคล้องกับยุทธศาสตร์ส่วนงาน
                        </h4>
                    </div>
                    <div id="departmentStrategicDetails">
                        <div class="mb-3 col-md-6">
                            <div class="mb-3">
                                <label for="Name_Strategic" class="form-label">ชื่อแผนยุทธศาสตร์</label>
                                <select class="form-control" id="Name_Strategic" name="Name_Strategic"
                                    onchange="filterStrategies()">
                                    @foreach($strategics as $strategic)
                                    <option value="{{ $strategic->Id_Strategic }}"
                                        {{ $project->Strategic_Id == $strategic->Id_Strategic ? 'selected' : '' }}>
                                        {{ $strategic->Name_Strategic_Plan }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="Name_Strategy" class="form-label">กลยุทธ์</label>
                                <select class="form-select @error('Name_Strategy') is-invalid @enderror"
                                    name="Name_Strategy" id="Name_Strategy" required>
                                    <option value="" disabled>เลือกกลยุทธ์</option>
                                    @foreach($strategies as $strategy)
                                    <option value="{{ $strategy->Id_Strategy }}"
                                        {{ $project->Name_Strategy == $strategy->Id_Strategy ? 'selected' : '' }}>
                                        {{ $strategy->Name_Strategy }}</option>
                                    @endforeach
                                </select>
                                @error('Name_Strategy')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
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
                                onchange="toggleTextbox(this, 'textbox-projectType-')"
                                {{ $project->projectType == 1 ? 'checked' : '' }}>
                            <label for="newProject">โครงการใหม่</label>
                        </div>
                        <div class="radio-item">
                            <input type="radio" name="projectType" value="2" id="continuousProject"
                                onchange="toggleTextbox(this, 'textbox-projectType-')"
                                {{ $project->projectType == 2 ? 'checked' : '' }}>
                            <label for="continuousProject">โครงการต่อเนื่อง</label>
                        </div>
                    </div>
                    <div class="form-group"
                        style="{{ $project->projectType == 2 ? 'display: block;' : 'display: none;' }}">
                        <input type="text" id="textbox-projectType-2" class="form-control" data-group="projectType"
                            placeholder="กรอกชื่อโครงการเดิม" value="{{ $project->oldProjectName }}">
                    </div>
                </div>

                <!-- ชื่อโครงการ -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            3. ชื่อโครงการ
                        </h4>
                    </div>
                    <div id="projectDetails">
                        <div class="form-group">
                            <label for="Name_Project" class="form-label">สร้างชื่อโครงการ</label>
                            <input type="text" class="form-control @error('Name_Project') is-invalid @enderror"
                                id="Name_Project" name="Name_Project" placeholder="กรอกชื่อโครงการ"
                                value="{{ $project->Name_Project }}" required>
                            @error('Name_Project')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div id="projectContainer">
                            @csrf
                        </div>
                        <div>
                            <button type="button" class="btn-addlist"
                                onclick="addField1('projectContainer', 'Name_Sup_Project[]')">
                                <i class='bx bx-plus-circle'></i>เพิ่มชื่อโครงการย่อย
                            </button>
                        </div>
                    </div>
                </div>

                <!-- วัตถุประสงค์โครงการ -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            4. วัตถุประสงค์โครงการ
                        </h4>
                    </div>
                    <div id="objectiveDetails">
                        <div class="form-group">
                            <select class="form-control @error('Objective_Project') is-invalid @enderror"
                                id="Objective_Project" name="Objective_Project" required>
                                <option value="" disabled>กรอกข้อมูลวัตถุประสงค์</option>
                                @foreach($strategicObjectives as $objective)
                                <option value="{{ $objective->Details_Strategic_Objectives }}"
                                    data-strategy-id="{{ $objective->Strategy_Id_Strategy }}"
                                    {{ $project->Objective_Project == $objective->Details_Strategic_Objectives ? 'selected' : '' }}>
                                    {{ $objective->Details_Strategic_Objectives }}</option>
                                @endforeach
                            </select>
                            @error('Objective_Project')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @include('Project.App.ProjectObjective')
                    </div>
                </div>

                <!-- ตัวชี้วัดความสำเร็จของโครงการ -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            5. ตัวชี้วัดความสำเร็จของโครงการ
                        </h4>
                    </div>
                    <div id="successIndicatorsDetails">
                        <div class="form-group">
                            <label for="Success_Indicators"></label>
                            <select class="form-control @error('Success_Indicators') is-invalid @enderror"
                                id="Success_Indicators" name="Success_Indicators">
                                <option value="" disabled>กรอกตัวชี้วัดความสำเร็จของโครงการ</option>
                                @foreach($kpis as $kpi)
                                <option value="{{ $kpi->Name_Kpi }}" data-strategy-id="{{ $kpi->Strategy_Id }}"
                                    data-target-value="{{ $kpi->Target_Value }}"
                                    {{ $project->Success_Indicators == $kpi->Name_Kpi ? 'selected' : '' }}>
                                    {{ $kpi->Name_Kpi }}</option>
                                @endforeach
                            </select>
                            @error('Success_Indicators')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- ค่าเป้าหมาย -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            6. ค่าเป้าหมาย
                        </h4>
                    </div>
                    <div id="valueTargetDetails">
                        <div class="form-group">
                            <label for="Value_Target"></label>
                            <select class="form-control @error('Value_Target') is-invalid @enderror" id="Value_Target"
                                name="Value_Target">
                                <option value="" disabled>กรอกค่าเป้าหมาย</option>
                                <option value="{{ $project->Value_Target }}" selected>{{ $project->Value_Target }}
                                </option>
                            </select>
                            @error('Value_Target')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- ระยะเวลาดำเนินโครงการ -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            7. ระยะเวลาดำเนินโครงการ
                        </h4>
                    </div>
                    <div id="projectDurationDetails">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="First_Time">วันที่เริ่มต้น:</label>
                                    <input type="date" class="form-control" id="First_Time" name="First_Time"
                                        value="{{ $project->First_Time }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="End_Time">วันที่สิ้นสุด:</label>
                                    <input type="date" class="form-control" id="End_Time" name="End_Time"
                                        value="{{ $project->End_Time }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- แหล่งงบประมาณ -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            8. แหล่งงบประมาณ
                        </h4>
                    </div>
                    <div id="budgetDetails">
                        <div class="form-group-radio">
                            <div class="radio-group">
                                <input type="radio" name="Status_Budget" value="N" id="non_income"
                                    onchange="toggleIncomeForm(this)"
                                    {{ $project->Status_Budget == 'N' ? 'checked' : '' }}>
                                <label for="non_income">ไม่ใช้งบประมาณ</label>

                                <input type="radio" name="Status_Budget" value="Y" id="income_seeking"
                                    onchange="toggleIncomeForm(this)"
                                    {{ $project->Status_Budget == 'Y' ? 'checked' : '' }}>
                                <label for="income_seeking">ใช้งบประมาณ</label>
                            </div>
                        </div>

                        <div id="incomeForm" class="income-form"
                            style="{{ $project->Status_Budget == 'Y' ? 'display: block;' : 'display: none;' }}">
                            <div class="form-group">
                                <label>แหล่งงบประมาณ</label>
                                <div class="mb-4">
                                    @foreach($budgetSources ?? collect() as $source)
                                    <div class="form-check mb-2 d-flex align-items-center">
                                        <input type="radio" id="{{ $source->Id_Budget_Source }}" name="budget_source"
                                            value="{{ $source->Id_Budget_Source }}" class="form-check-input"
                                            data-id="{{ $source->Id_Budget_Source }}"
                                            {{ $project->budget_source == $source->Id_Budget_Source ? 'checked' : '' }}
                                            onchange="handleSourceSelect(this)">
                                        <label class="form-check-label d-flex align-items-center w-100"
                                            for="{{ $source->Id_Budget_Source }}">
                                            <span class="label-text">{{ $source->Name_Budget_Source }}</span>
                                            <input type="number" name="amount_{{ $source->Id_Budget_Source }}"
                                                class="form-control form-control-sm w-25 ml-2" placeholder="จำนวนเงิน"
                                                value="{{ $project->{'amount_'.$source->Id_Budget_Source} }}"
                                                {{ $project->budget_source == $source->Id_Budget_Source ? '' : 'disabled' }}>
                                            <span class="ml-2">บาท</span>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>

                                <!-- รายละเอียดการเบิกจ่าย -->
                                <div id="sourceDetailForm">
                                    <div class="mb-3">
                                        <label class="form-label">รายละเอียดค่าใช้จ่าย</label>
                                        <textarea name="source_detail" class="form-control"
                                            placeholder="ระบุรายละเอียดค่าใช้จ่าย">{{ $project->source_detail }}</textarea>
                                    </div>
                                </div>

                                <div class="form-group-radio">
                                    <label>เลือกประเภท</label>
                                    <div class="radio-group">
                                        <input type="radio" name="date_type" value="single" id="single_day"
                                            onchange="toggleDateForm(this)"
                                            {{ $project->date_type == 'single' ? 'checked' : '' }}>
                                        <label for="single_day">วันเดียว</label>

                                        <input type="radio" name="date_type" value="multiple" id="multiple_days"
                                            onchange="toggleDateForm(this)"
                                            {{ $project->date_type == 'multiple' ? 'checked' : '' }}>
                                        <label for="multiple_days">หลายวัน</label>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm" style="display: none;"
                                onclick="addBudgetForm()">เพิ่มแบบฟอร์ม</button>
                        </div>
                    </div>
                </div>

                <!-- ผู้รับผิดชอบโครงการ -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            9. ผู้รับผิดชอบโครงการ
                        </h4>
                    </div>
                    <div id="responsibleDetails">
                        <div class="form-group">
                            <label for="employee_id" class="form-label">เลือกผู้รับผิดชอบ</label>
                            <select class="form-select @error('employee_id') is-invalid @enderror" id="employee_id"
                                name="employee_id">
                                <option value="" disabled>เลือกผู้รับผิดชอบ</option>
                                @foreach($employees as $employee)
                                <option value="{{ $employee->Id_Employee }}"
                                    {{ $project->employee_id == $employee->Id_Employee ? 'selected' : '' }}>
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

                <!-- ปุ่มบันทึก -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class='bx bx-save'></i> บันทึกข้อมูล
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection