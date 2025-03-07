@extends('navbar.app')

<hade>
    <link rel="stylesheet" href="{{ asset('css/createProject.css') }}">

    <style>
    .editable {
        border: 1px solid transparent;
        padding: 5px;
        cursor: pointer;
        transition: border-color 0.3s, background-color 0.3s;
    }

    .editable:hover {
        border-color: #ccc;
        background-color: #f9f9f9;
    }

    .editable:focus {
        outline: none;
        border-color: #007bff;
        background-color: #e9f7ff;
    }

    .editable.editing {
        border-color: #007bff;
        background-color: #e9f7ff;
    }
    </style>
</hade>

@section('content')
<div class="container py-4">
    <div class="card">
        <h3 class="card-header">กรอกข้อมูลโครงการ</h3>

        <div class="card-body">
            <form action="{{ route('projects.resetStatus', ['id' => $project->Id_Project]) }}" method="POST"
                class="needs-validation" novalidate>
                @csrf

                <!-- ชื่อโครงการ -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            1. ชื่อโครงการ
                        </h4>
                    </div>
                    <div id="projectDetails">
                        <div class="form-group">
                            <label for="Name_Project" class="form-label">สร้างชื่อโครงการ</label>
                            <div class="editable" style="border: 1px solid #007bff; padding: px; border-radius: 5px;"
                                contenteditable="true"
                                onblur="saveData(this, '{{ $project->Id_Project }}', 'Name_Project')"
                                onkeypress="checkEnter(event, this)">
                                {{ $project->Name_Project }}
                            </div>
                            @error('Name_Project')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div id="projectContainer">
                            @csrf
                            @foreach ($subProjects as $index => $subProject)
                                <div class="form-group mb-2 dynamic-field">
                                    <div class="input-group">
                                        <span class="input-group-text">1.{{ $index + 1 }}</span>
                                        <div class="editable"
                                            style="border: 1px solid #28a745; padding: 5px; border-radius: 5px; margin-bottom: 5px;"
                                            contenteditable="true"
                                            onblur="saveSubProjects(this, '{{ $project->Id_Project }}', 'subProjects')" 
                                            onkeypress="checkEnterSubProject(event, this, {{ $project->Id_Project }})">
                                            {{ $subProject->Name_Sub_Project }}
                                        </div>
                                        <button type="button" class="btn btn-danger" onclick="removeField(this)">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                        <div>
                            <button type="button" class="btn-addlist"
                                onclick="addField1('projectContainer', 'Name_Sub_Project[]')">
                                <i class='bx bx-plus-circle'></i>เพิ่มชื่อโครงการย่อย
                            </button>
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

                    <div class="form-group">
                        <div class="editable hidden" style="border: 1px solid #007bff; padding: px; border-radius: 5px;" 
                            id="textbox-projectType-2"
                            data-group="projectType" contenteditable="true"
                            onblur="saveData(this, '{{ $project->Id_Project }}', 'projectType')"
                            onkeyup="checkEnter(event, this)">
                        </div>
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
                            <select class="form-select @error('employee_id') is-invalid @enderror" id="employee_id" name="employee_id"
                                onchange="saveData(this, '{{ $project->Id_Project }}', 'Employee_Id')">
                                <option value="" selected disabled>เลือกผู้รับผิดชอบ</option>
                                @foreach($employees as $employee)
                                <option value="{{ $employee->Id_Employee }}"
                                    {{ $employee->Id_Employee == $project->Employee_Id ? 'selected' : '' }}>
                                    {{ $employee->Firstname }} {{ $employee->Lastname }}
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
                            <div class="platform-card">
                                <div class="card-header">
                                    <h3 class="card-title">แพลตฟอร์มที่ 1</h3>
                                    <button type="button" class="btn btn-danger" onclick="removePlatform(this)">
                                        <i class='bx bx-trash'></i> ลบแพลตฟอร์ม
                                    </button>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">ชื่อแพลตฟอร์ม</label>
                                    <div class="editable form-control" name="platforms[0][name]"
                                        style="border: 1px solid #007bff; padding: 5px; border-radius: 5px;"
                                        contenteditable="true"
                                        onblur="saveData(this)"
                                        onkeypress="checkEnter(event, this)">
                                    </div>
                                </div>
                                

                                <div class="form-group">
                                    <label class="form-label">โปรแกรม</label>
                                    <input type="text" name="platforms[0][program]" class="form-control"
                                        placeholder="กรุณากรอกชื่อโปรแกรม" required>
                                </div>

                                <div class="form-group kpi-container">
                                    <div class="kpi-header">
                                        <label class="form-label">KPI</label>
                                        
                                    </div>
                                    <div class="kpi-group">
                                        <div class="input-group">
                                            <input type="text" name="platforms[0][kpis][]" class="form-control"
                                                placeholder="กรุณากรอก KPI" required>
                                            <button type="button" class="btn btn-danger" onclick="removeKpi(this)">
                                                <i class='bx bx-trash'></i>
                                            </button>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-addlist" onclick="addKpi(this)">
                                        <i class='bx bx-plus-circle'></i>เพิ่ม KPI
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn-addlist" onclick="addPlatform()"><i class='bx bx-plus-circle'></i>เพิ่มแพลตฟอร์ม</button>
                </div>

                <!-- ความสอดคล้องกับยุทธศาสตร์ส่วนงาน -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            5. ความสอดคล้องกับยุทธศาสตร์ส่วนงาน
                        </h4>
                    </div>
                    <div id="departmentStrategicDetails">
                        <div class=" mb-3 col-md-6">
                            <div class="mb-3">
                                <label for="Name_Strategic_Plan" class="form-label">ชื่อแผนยุทธศาสตร์</label>
                                <input type="text" class="form-control" id="Name_Strategic_Plan"
                                    name="Name_Strategic_Plan" value="{{ $nameStrategicPlan }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="Name_Strategy" class="form-label">กลยุทธ์</label>
                                <select class="form-select @error('Name_Strategy') is-invalid @enderror" name="Name_Strategy" id="Name_Strategy" 
                                        onchange="saveData(this, '{{ $project->Id_Project }}', 'Name_Strategy')" required>
                                    
                                    <option value="" selected disabled>เลือกกลยุทธ์</option>
                                    @if($strategies->isNotEmpty())
                                        @foreach($strategies as $strategy)
                                        <option value="{{ $strategy->Name_Strategy }}"
                                            {{ $strategy->Name_Strategy == $project->Name_Strategy ? 'selected' : '' }}>
                                            {{ $strategy->Name_Strategy }}
                                        </option>
                                        @endforeach
                                    @else
                                    <option value="" disabled>ไม่มีกลยุทธ์ที่เกี่ยวข้อง
                                    </option>
                                    @endif
                                </select>
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
                            6. ความสอดคล้องกับ (SDGs)
                        </h4>
                    </div>
                    <div id="sdgsDetails">
                        <div class=" sdgs-grid">
                        @foreach ($sdgs as $sdg)
                            <div class="form-group-sdgs">
                                <div class="form-check">
                                    <label class="form-check-label" for="sdg_{{ $sdg->id_SDGs }}">
                                    <input 
                                        class="form-check-input editable" 
                                        type="checkbox" 
                                        name="sdgs[]" 
                                        value="{{ $sdg->id_SDGs }}" 
                                        id="sdg_{{ $sdg->id_SDGs }}" 
                                        onchange="saveData(this, '{{ $project->Id_Project }}', 'sdgs')"
                                        @if($project->sdgs->contains('id_SDGs', $sdg->id_SDGs)) checked @endif>
                                    {{ $sdg->Name_SDGs }}  
                                    </label>
                                </div>
                            </div>
                        @endforeach


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
                        <div class=" dropdown-container">
                            <div class="dropdown-options">
                                @foreach ($integrationCategories as $category)
                                    @php
                                        $selectedCategory = $project->projectHasIntegrationCategories->where('Id_Integration_Category', $category->Id_Integration_Category)->first();
                                    @endphp
                                    <div class="option-item">
                                        <label>
                                            <input type="checkbox"
                                                name="projectHasIntegrationCategories[{{ $category->Id_Integration_Category }}][checked]"
                                                onchange="saveIntegrationData(this, {{ $project->id }})"
                                                @if($selectedCategory) checked @endif>
                                            {{ $category->Name_Integration_Category }}
                                        </label>

                                        @if ($category->Name_Integration_Category)
                                            <input type="text" class="additional-info"
                                                name="projectHasIntegrationCategories[{{ $category->Id_Integration_Category }}][details]"
                                                value="{{ $category->Integration_Details }}" 
                                                data-name="{{ $category->Name_Integration_Category }}" 
                                                onchange="saveIntegrationData(this, {{ $project->id }})"
                                                placeholder="ระบุข้อมูลเพิ่มเติม"
                                                style="width: 100%;" 
                                                disabled>
                                        @endif
                                    </div>
                                @endforeach

                            </div>
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
                            <textarea class="form-control @error('Principles_Reasons') is-invalid @enderror" rows="15"
                                name="Principles_Reasons" placeholder="กรอกข้อมูล"
                                onblur="saveData(this, '{{ $project->Id_Project }}', 'Principles_Reasons')"
                                onkeypress="checkEnter(event, this)">
                                {{ $project->Principles_Reasons }}
                            </textarea>
                            @error('Principles_Reasons')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                            <select class="form-select @error('Objective_Project') is-invalid @enderror" 
                            id="Objective_Project" name="Objective_Project" required
                            onchange="saveData(this, '{{ $project->Id_Project }}', 'Objective_Project')">
                                <option value="" disabled>กรอกข้อมูลวัตถุประสงค์</option>
                                @foreach($strategicObjectives as $objective)
                                <option value="{{ $objective->Details_Strategic_Objectives }}"
                                    data-strategy-id="{{ $objective->Strategy_Id_Strategy }}"
                                    {{ $project->Objective_Project == $objective->Details_Strategic_Objectives ? 'selected' : '' }}>
                                    {{ $objective->Details_Strategic_Objectives }}
                                </option>
                                @endforeach
                            </select>
                            @error('Objective_Project')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @include('Project.App.ProjectObjective')
                    </div>
                </div>

                <!-- กลุ่มเป้าหมาย -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            10. กลุ่มเป้าหมาย
                        </h4>
                    </div>
                    <div id="targetGroupDetails">
                        <div id="targetGroupContainer">
                            <div class="target-group-item">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" name="target_group[]" class="form-control"
                                            placeholder="กรอกกลุ่มเป้าหมาย" required>
                                        <input type="number" name="target_count[]" class="form-control"
                                            placeholder="จำนวน" required>
                                        <input type="text" name="target_unit[]" class="form-control" placeholder="หน่วย"
                                            required>
                                        <button type="button" class="btn btn-danger btn-sm remove-target-group">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="addTargetGroupBtn" class="btn-addlist">
                            <i class='bx bx-plus-circle'></i>เพิ่มกลุ่มเป้าหมาย
                        </button>

                        <div class="form-group mt-3">
                            <label>พื้นที่/ชุมชนเป้าหมาย (ถ้ามี ระบุ)</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="targetAreaCheckbox"
                                    onchange="toggleTargetAreaDetails()">
                                <label class="form-check-label"
                                    for="targetAreaCheckbox">เลือกพื้นที่/ชุมชนเป้าหมาย</label>
                            </div>
                            <div id="targetAreaDetails">
                                <div class="form-group mt-3">
                                    <label>รายละเอียดกลุ่มเป้าหมาย</label>
                                    <textarea class="form-control" name="target_details"
                                        placeholder="กรอกรายละเอียด"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- สถานที่ -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            11. สถานที่ดำเนินงาน
                        </h4>
                    </div>
                    <div id="locations-container">
                        <div class="location-item">
                                <input type="text" class="form-control small-input editable" name="location[]" placeholder="กรอกสถานที่"
                                    style="border: 1px solid #007bff; padding: px; border-radius: 5px;"
                                    name="location[]" placeholder="กรอกสถานที่"    
                                    contenteditable="true"
                                    onblur="saveData(this, '{{ $project->Id_Project }}', 'Name_Location')"
                                    onkeypress="checkEnter(event, this)">
                                <button type="button" class="btn btn-danger btn-sm remove-location">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" id="addLocationBtn" class="btn-addlist">
                            <i class='bx bx-plus-circle'></i>เพิ่มสถานที่
                        </button> 
                    </div>
                </div>

                <!-- ตัวชี้วัด -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            12. ตัวชี้วัด
                        </h4>
                    </div>
                    <div id="indicatorsDetails">
                        <div class="form-group-radio mt-3">
                            <div class="radio-group">
                                <input type="checkbox" name="goal[]" value="1" id="quantitative"
                                    onchange="toggleGoalInputs(this)">
                                <label for="quantitative">เชิงปริมาณ</label>

                                <input type="checkbox" name="goal[]" value="2" id="qualitative"
                                    onchange="toggleGoalInputs(this)">
                                <label for="qualitative">เชิงคุณภาพ</label>
                            </div>
                        </div>
                        <div id="quantitative-inputs" class="goal-inputs hidden">
                            <h6>เชิงปริมาณ</h6>
                            <div id="quantitative-items" class="mt-3">
                                <div class="form-group mt-2">
                                    <label>ข้อที่ 1</label>
                                    <div class="d-flex">
                                        <input type="text" class="form-control" name="quantitative[]"
                                            placeholder="เพิ่มรายการ" required>
                                        <button type="button" class="btn btn-danger btn-sm remove-quantitative-item">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn-addlist" onclick="addQuantitativeItem()">
                                <i class='bx bx-plus-circle'></i>เพิ่มรายการ
                            </button>
                        </div>

                        <div id="qualitative-inputs" class="goal-inputs hidden">
                            <h6>เชิงคุณภาพ</h6>
                            <div id="qualitative-items" class="mt-3">
                                <div class="form-group mt-2">
                                    <label>ข้อที่ 1</label>
                                    <div class="d-flex">
                                        <input type="text" class="form-control" name="qualitative[]"
                                            placeholder="เพิ่มข้อความ">
                                        <button type="button" class="btn btn-danger btn-sm remove-qualitative-item">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn-addlist" onclick="addQualitativeItem()">
                                <i class='bx bx-plus-circle'></i>เพิ่มรายการ
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ระยะเวลาดำเนินโครงการ -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            13. ระยะเวลาดำเนินโครงการ
                        </h4>
                    </div>
                    <div id="projectDurationDetails">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group editable">
                                    <label for="First_Time">วันที่เริ่มต้น:</label>
                                    <input type="date" class="form-control" id="First_Time" name="First_Time" 
                                    style="border: 1px solid #007bff; padding: px; border-radius: 5px;"
                                    value="{{ $project->First_Time }}" required
                                    onchange="saveData(this, '{{ $project->Id_Project }}', 'First_Time')">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group editable">
                                    <label for="End_Time">วันที่สิ้นสุด:</label>
                                    <input type="date" class="form-control" id="End_Time" name="End_Time"
                                    style="border: 1px solid #007bff; padding: px; border-radius: 5px;" 
                                    value="{{ $project->End_Time }}"required
                                    onchange="saveData(this, '{{ $project->Id_Project }}', 'End_Time')">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ขั้นตอนและแผนการดำเนินงาน -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            14. ขั้นตอนและแผนการดำเนินงาน (PDCA)
                        </h4>
                    </div>
                    <div id="planDetails">
                        <div class="form-group-radio mb-4">
                            <input type="radio" name="Project_Type" value="S" id="shortTermProject" checked>
                            <label for="shortTermProject">โครงการระยะสั้น</label>
                            &nbsp;&nbsp;
                            <input type="radio" name="Project_Type" value="L" id="longTermProject">
                            <label for="longTermProject">โครงการระยะยาว</label>
                        </div>

                        <!-- วิธีการดำเนินงาน -->
                        <div id="textbox-planType-1" data-group="planType">
                            <div class="method-form">
                                <div class="form-label">วิธีการดำเนินงาน</div>
                                <div id="methodContainer" class="method-items">
                                    <div class="form-group mt-2 d-flex">
                                        <input type="text" class="form-control" name="Details_Short_Project[]"
                                            placeholder="เพิ่มรายการ">
                                        <button type="button" class="btn btn-danger btn-sm remove-method">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </div>
                                </div>
                                <button type="button" class="btn-addlist" onclick="addMethodItem()">
                                    <i class='bx bx-plus-circle'></i>เพิ่มรายการ
                                </button>
                            </div>
                        </div>

                        <div id="textbox-planType-2" class="hidden" data-group="planType">
                            <table class="table-PDCA">
                                <thead>
                                    <tr>
                                        <th rowspan="2">กิจกรรมและแผนการเบิกจ่ายงบประมาณ
                                        </th>
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
                                            <div class="plan-text">{{ $stage->Name_PDCA }}
                                            </div>
                                            <textarea class="plan-textarea auto-expand"
                                                name="pdca[{{ $stage->Id_PDCA_Stages }}][detail]"
                                                placeholder="เพิ่มรายละเอียด"></textarea>
                                        </td>
                                        @for($i = 1; $i <= 12; $i++) <td class="checkbox-container">
                                            <input type="checkbox" name="pdca[{{ $stage->Id_PDCA_Stages }}][months][]"
                                                value="{{ $i }}">
                                            </td>
                                            @endfor
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- แหล่งงบประมาณ -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            6. แหล่งงบประมาณ
                        </h4>
                    </div>
                    <div id="budgetDetails">
                        <div class="form-group-radio">
                            <div class="radio-group">
                                <input type="radio" name="Status_Budget" value="N" id="non_income"
                                    onchange="toggleIncomeForm(this)" checked>
                                <label for="non_income">ไม่ใช้งบประมาณ</label>

                                <input type="radio" name="Status_Budget" value="Y" id="income_seeking"
                                    onchange="toggleIncomeForm(this)">
                                <label for="income_seeking">ใช้งบประมาณ</label>
                            </div>
                        </div>

                        <div id="incomeForm" class="income-form" style="display: none;">
                            <div class="form-group">
                                <label>แหล่งงบประมาณ</label>
                                <div class="mb-4">
                                    @foreach($budgetSources as $source)
                                    <div class="form-check mb-2 d-flex align-items-center">
                                        <input type="checkbox" id="{{ $source->Id_Budget_Source }}" name="budget_source"
                                            value="{{ $source->Id_Budget_Source }}" class="form-check-input"
                                            data-id="{{ $source->Id_Budget_Source }}"
                                            onchange="handleSourceSelect(this)">
                                        <label class="form-check-label d-flex align-items-center w-100"
                                            for="{{ $source->Id_Budget_Source }}">
                                            <span class="label-text">{{ $source->Name_Budget_Source }}</span>
                                            <input type="number" name="amount_{{ $source->Id_Budget_Source }}"
                                                class="form-control form-control-sm w-25 ml-2" placeholder="จำนวนเงิน"
                                                disabled>
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
                                            placeholder="ระบุรายละเอียดค่าใช้จ่าย"></textarea>
                                    </div>
                                </div>

                                <div class="form-group-radio">
                                    <label>เลือกประเภท</label>
                                    <div class="radio-group">
                                        <input type="radio" name="date_type" value="single" id="single_day"
                                            onchange="toggleDateForm(this)" checked>
                                        <label for="single_day">วันเดียว</label>

                                        <input type="radio" name="date_type" value="multiple" id="multiple_days"
                                            onchange="toggleDateForm(this)">
                                        <label for="multiple_days">หลายวัน</label>
                                    </div>
                                </div>

                                <!-- แบบฟอร์มงบประมาณ -->
                                <div id="budgetFormsContainer">
                                    <div id="budgetFormTemplate" class="budget-form card mb-3">
                                        <div class="card-body">
                                            <button type="button" class="btn btn-danger btn-sm remove-form-btn"
                                                onclick="removeBudgetForm(this)">ลบแบบฟอร์ม</button>

                                            <!-- Date and Details -->
                                            <div class="mb-3 d-flex justify-content-between align-items-start">
                                                <div style="flex: 1;">
                                                    <label class="form-label">วันที่</label>
                                                    <input type="date" name="date[]" class="form-control"
                                                        style="width: 150px;">
                                                </div>
                                            </div>
                                            <div class="mb-3 d-flex align-items-center">
                                                <div style="flex: 3; margin-right: 1rem;">
                                                    <label class="form-label">รายละเอียด</label>
                                                    <textarea name="details[]" class="form-control"
                                                        placeholder="ระบุรายละเอียด"></textarea>
                                                </div>
                                                <div style="flex: 1;">
                                                    <label class="form-label">จำนวนเงินทั้งหมด</label>
                                                    <div class="input-group">
                                                        <input type="number" name="total_amount[]" class="form-control"
                                                            placeholder="จำนวนเงิน">
                                                        <span class="input-group-text">บาท</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Category and Sub-Items -->
                                            <div class="card mb-3">
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">หมวดหมู่</label>
                                                        <select name="category[]" class="form-control">
                                                            <option value="" disabled selected>เลือกหมวดหมู่</option>
                                                            @foreach($subtopBudgets as $subtop)
                                                            <option value="{{ $subtop->Id_Subtopic_Budget }}">
                                                                {{ $subtop->Name_Subtopic_Budget }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div id="subActivityContainer">
                                                        <div class="sub-activity mb-3">
                                                            <div class="card mb-3">
                                                                <div class="card-body">
                                                                    <div class="mb-3 d-flex align-items-center">
                                                                        <div style="flex: 3;">
                                                                            <label
                                                                                class="form-label">รายละเอียดของหมวดหมู่</label>
                                                                            <textarea name="description[0][]"
                                                                                class="form-control"
                                                                                placeholder="เช่น ค่าอาหารว่างสำหรับการจัดประชุมคณะกรรมการจัดการความรู้"></textarea>
                                                                        </div>
                                                                        <div style="flex: 1; margin-left: 1rem;">
                                                                            <label class="form-label">จำนวนเงิน</label>
                                                                            <div class="input-group">
                                                                                <input type="number" name="amount[0][]"
                                                                                    class="form-control"
                                                                                    placeholder="จำนวนเงิน">
                                                                                <span
                                                                                    class="input-group-text">บาท</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <button type="button" class="btn btn-success btn-sm"
                                                                        onclick="addDetail(this)">เพิ่มรายละเอียด</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn btn-secondary btn-sm"
                                                        onclick="addSubActivity(this)">เพิ่มหัวข้อย่อยพร้อมรายละเอียด</button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary btn-sm" style="display: none;"
                                    onclick="addBudgetForm()">เพิ่มแบบฟอร์ม</button>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Output -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            16. เป้าหมายเชิงผลผลิต (Output)
                        </h4>
                    </div>
                    <div id="outputDetails">
                        <div id="outputContainer" class="dynamic-container">
                            <div class="form-group mt-2 d-flex">
                                <input type="text" class="form-control" name="outputs[]" placeholder="เพิ่มรายการ"
                                onkeypress="checkEnter(event, this)" 
                                onblur="saveData(this, '{{ $project->Id_Project }}', 'Name_Output')" >
                                <button type="button" class="btn btn-danger btn-sm remove-field">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" class="btn-addlist" onclick="addField('outputContainer', 'outputs[]')">
                            <i class='bx bx-plus-circle'></i>เพิ่มรายการ
                        </button>
                    </div>
                </div>

                <!-- Outcome -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            17. เป้าหมายเชิงผลลัพธ์ (Outcome)
                        </h4>
                    </div>
                    <div id="outcomeDetails">
                        <div id="outcomeContainer" class="dynamic-container">
                            <div class="form-group mt-2 d-flex">
                                <input type="text" class="form-control" name="outcomes[]" placeholder="เพิ่มรายการ">
                                <button type="button" class="btn btn-danger btn-sm remove-field">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" class="btn-addlist" onclick="addField('outcomeContainer', 'outcomes[]')">
                            <i class='bx bx-plus-circle'></i>เพิ่มรายการ
                        </button>
                    </div>
                </div>

                <!-- ผลที่คาดว่าจะได้รับ -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            18. ผลที่คาดว่าจะได้รับ
                        </h4>
                    </div>
                    <div id="resultDetails">
                        <div id="resultContainer" class="dynamic-container">
                            <div class="form-group mt-2 d-flex">
                                <input type="text" class="form-control" name="expected_results[]"
                                    placeholder="เพิ่มรายการ">
                                <button type="button" class="btn btn-danger btn-sm remove-field">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" class="btn-addlist"
                            onclick="addField('resultContainer', 'expected_results[]')">
                            <i class='bx bx-plus-circle'></i>เพิ่มรายการ
                        </button>
                    </div>
                </div>

                <!-- ตัวชี้วัดความสำเร็จของโครงการ -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            19. ตัวชี้วัดความสำเร็จของโครงการ
                        </h4>
                    </div>
                    <div id="successIndicatorsDetails">
                        <div class="form-group">
                            <div class="d-flex align-items-center">
                                <select class="form-select @error('Success_Indicators') is-invalid @enderror"
                                    onchange="saveData(this, '{{ $project->Id_Project }}', 'Success_Indicators')" 
                                    id="Success_Indicators" name="Success_Indicators">
                                    <option value="" disabled selected>กรอกตัวชี้วัดความสำเร็จของโครงการ</option>
                                    @foreach($kpis as $kpi)
                                    <option value="{{ $kpi->Name_Kpi }}" data-strategy-id="{{ $kpi->Strategy_Id }}"
                                        data-target-value="{{ $kpi->Target_Value }}">
                                        {{ $kpi->Name_Kpi }}
                                    </option>
                                    @endforeach
                                </select>
                                <div class="form-check ms-2 d-flex align-items-center">
                                    <input class="form-check-input" type="checkbox"
                                        id="Success_Indicators_Other_Checkbox">
                                    <label class="form-check-label ms-1" for="Success_Indicators_Other_Checkbox">
                                        อื่น ๆ
                                    </label>
                                </div>
                            </div>
                            <textarea class="form-control mt-2" id="Success_Indicators_Other"
                                name="Success_Indicators_Other" placeholder="กรอกตัวชี้วัดความสำเร็จอื่น ๆ"
                                style="display: none;"></textarea>
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
                            20. ค่าเป้าหมาย
                        </h4>
                    </div>
                    <div id="valueTargetDetails">
                        <div class="form-group">
                            <div class="d-flex align-items-center">
                                <select class="form-select @error('Value_Target') is-invalid @enderror"
                                    onchange="saveData(this, '{{ $project->Id_Project }}', 'Value_Target')" 
                                    id="Value_Target" name="Value_Target">
                                    <option value="" disabled selected>กรอกค่าเป้าหมาย</option>
                                    <!-- Add options here if needed -->
                                </select>
                                <div class="form-check ms-2 d-flex align-items-center">
                                    <input class="form-check-input" type="checkbox" id="Value_Target_Other_Checkbox">
                                    <label class="form-check-label ms-1" for="Value_Target_Other_Checkbox">
                                        อื่น ๆ
                                    </label>
                                </div>
                            </div>
                            <textarea class="form-control mt-2" id="Value_Target_Other" name="Value_Target_Other"
                                placeholder="กรอกค่าเป้าหมายอื่น ๆ" style="display: none;"></textarea>
                            @error('Value_Target')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>



                <!-- เอกสารเพิ่มเติม -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>21. เอกสารเพิ่มเติม</h4>
                    </div>
                    <div class="form-group">
                        <input type="file" class="form-control" id="myFile" name="filename">
                        <small class="form-text text-muted">อัพโหลดไฟล์เอกสารที่เกี่ยวข้องกับโครงการ</small>
                    </div>
                </div>

                <!-- ปุ่มบันทึก -->
                <!-- <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class='bx bx-save'></i> บันทึกข้อมูล
                    </button>
                </div> -->
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ====== ชื่อโครงการ =======
    window.addField1 = function(containerId, fieldName) {
        const container = document.getElementById(containerId);
        if (!container) return;

        const index = container.children.length; // Start from 1
        const div = document.createElement('div');
        div.classList.add('form-group', 'mb-2', 'dynamic-field');
        div.innerHTML = `
        <div class="input-group">
            <span class="input-group-text">1.${index}</span>
            <div class="editable"
                style="border: 1px solid #28a745; padding: 5px; border-radius: 5px; margin-bottom: 5px;" placeholder="กรอกชื่อโครงการย่อย"
                contenteditable="true"
                onblur="saveSubProjects(this, '{{ $project->Id_Project }}', 'subProjects')" 
                onkeypress="checkEnterSubProject(event, this, {{ $project->Id_Project }})">
            </div>
            
            <button type="button" class="btn btn-danger" onclick="removeField(this)"
                <i class='bx bx-trash'></i>
            </button>
        </div>
    `;
        container.appendChild(div);
        updateRemoveButtons(container);
    };

    window.removeField = function(button) {
        const field = button.closest('.dynamic-field');
        const container = field.parentElement;
        field.remove();
        updateRemoveButtons(container);
        updateFieldNumbers(container);
    };

    function updateRemoveButtons(container) {
        const buttons = container.querySelectorAll('.btn-danger');
        buttons.forEach(btn => {
            btn.style.display = buttons.length > 0 ? 'block' : 'none';
        });
    }

    function updateFieldNumbers(container) {
        const fields = container.querySelectorAll('.dynamic-field .input-group-text');
        fields.forEach((field, index) => {
            field.textContent = `1.${index + 1}`;
        });
    }

    // ======= ลักษณะโครงการ ==========
    window.toggleTextbox = function(radio, prefix) {
        const textboxContainer = document.querySelector(`#${prefix}2`).closest('.form-group');
        const textbox = document.getElementById(`${prefix}2`);

        if (radio.value === '2') {
            textboxContainer.style.display = 'block';
            textbox.classList.remove('hidden');
            textbox.required = true;
        } else {
            textboxContainer.style.display = 'none';
            textbox.classList.add('hidden');
            textbox.required = false;
            textbox.value = '';
        }

        
    };

    document.addEventListener('DOMContentLoaded', function() {
        const newProjectRadio = document.getElementById('newProject');
        const textbox = document.getElementById('textbox-projectType-2');
        if (newProjectRadio.checked) {
            textbox.classList.add('hidden');
            textbox.closest('.form-group').style.display = 'none';
        }

        const projectTypeDetails = document.querySelector('.form-group-radio');
        const toggleIcon = document.getElementById('toggleIconProjectType');
        projectTypeDetails.style.display = 'none';
        toggleIcon.classList.remove('bx-chevron-down');
        toggleIcon.classList.add('bx-chevron-up');
    });

    // ============ ผู้รับผิดชอบโครงการ============

    // ============ จัดการความสอดคล้องกับยุทธศาสตร์มหาวิทยาลัย============
    let platformCount = 1;

    window.addPlatform = function() {
        const container = document.getElementById('platform-container');
        const platformTemplate = document.querySelector('.platform-card').cloneNode(true);

        platformTemplate.querySelector('.card-title').textContent = `แพลตฟอร์มที่ ${platformCount + 1}`;

        const inputs = platformTemplate.querySelectorAll('input');
        inputs.forEach(input => {
            const name = input.name.replace('[0]', `[${platformCount}]`);
            input.name = name;
            input.value = '';
        });

        const kpiContainer = platformTemplate.querySelector('.kpi-container');
        const kpiGroups = kpiContainer.querySelectorAll('.kpi-group');
        for (let i = 1; i < kpiGroups.length; i++) {
            kpiGroups[i].remove();
        }

        const removeBtn = platformTemplate.querySelector('.btn-danger');
        removeBtn.style.display = 'block';

        container.appendChild(platformTemplate);
        platformCount++;
        updatePlatformNumbers();
    }

    window.removePlatform = function(button) {
        const platformCard = button.closest('.platform-card');
        platformCard.remove();
        updatePlatformNumbers();
    }

    window.addKpi = function(btn) {
        const kpiContainer = btn.closest('.kpi-container');
        const kpiGroup = kpiContainer.querySelector('.kpi-group').cloneNode(true);

        kpiGroup.querySelector('input').value = '';

        const removeBtn = kpiGroup.querySelector('.btn-danger');
        if (!removeBtn) {
            const newRemoveBtn = document.createElement('button');
            newRemoveBtn.type = 'button';
            newRemoveBtn.className = 'btn btn-danger';
            newRemoveBtn.innerHTML = "<i class='bx bx-trash'></i>";
            newRemoveBtn.onclick = function() {
                removeKpi(this);
            };
            kpiGroup.querySelector('.input-group').appendChild(newRemoveBtn);
        } else {
            removeBtn.style.display = 'block';
        }

        // kpiContainer.appendChild(kpiGroup);
        kpiContainer.insertBefore(kpiGroup, btn);
        
    }

    window.removeKpi = function(btn) {
        const kpiGroup = btn.closest('.kpi-group');
        const kpiContainer = kpiGroup.closest('.kpi-container');

        if (kpiContainer.querySelectorAll('.kpi-group').length > 1) {
            kpiGroup.remove();
        }
    }

    function updatePlatformNumbers() {
        const platformCards = document.querySelectorAll('.platform-card');
        platformCards.forEach((card, index) => {
            card.querySelector('.card-title').textContent = `แพลตฟอร์มที่ ${index + 1}`;
            const inputs = card.querySelectorAll('input');
            inputs.forEach(input => {
                const name = input.name.replace(/\[\d+\]/, `[${index}]`);
                input.name = name;
            });
        });
        platformCount = platformCards.length;
    }

    // ============ ความสอดคล้องกับยุทธศาสตร์ส่วนงาน ============

    // ============ ความสอดคล้องกับ (SDGs) ============

    // ============ การบูรณาการงานโครงการ ============

    window.toggleSelectTextbox = function(checkbox) {
        const textbox = checkbox.closest('.option-item').querySelector('.additional-info');
        if (textbox) {
            textbox.disabled = !checkbox.checked;
            if (!textbox.disabled) {
                textbox.focus();
            } else {
                textbox.value = '';
            }
        }
        saveIntegrationData(checkbox);
    }

    // ============ หลักการและเหตุผล ============

    // ============ วัตถุประสงค์โครงการ ============

    // ============ จัดการกลุ่มเป้าหมาย ============
    const targetGroupContainer = document.getElementById('targetGroupContainer');
    const addTargetGroupBtn = document.getElementById('addTargetGroupBtn');

    if (addTargetGroupBtn && targetGroupContainer) {
        addTargetGroupBtn.addEventListener('click', function() {
            const newGroup = document.createElement('div');
            newGroup.className = 'target-group-item';
            newGroup.innerHTML = `
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" class="form-control" name="target_group[]" placeholder="กรอกกลุ่มเป้าหมาย" required>
                        <input type="number" class="form-control" name="target_count[]" placeholder="จำนวน" required>
                        <input type="text" class="form-control" name="target_unit[]" placeholder="หน่วย" required>
                        <button type="button" class="btn btn-danger btn-sm remove-target-group">
                            <i class='bx bx-trash'></i>
                        </button>
                    </div>
                </div>
            `;
            targetGroupContainer.appendChild(newGroup);
            updateTargetGroupButtons();
        });

        targetGroupContainer.addEventListener('click', function(e) {
            if (e.target.closest('.remove-target-group')) {
                e.target.closest('.target-group-item').remove();
                updateTargetGroupButtons();
            }
        });
    }

    function updateTargetGroupButtons() {
        const buttons = targetGroupContainer.querySelectorAll('.remove-target-group');
        buttons.forEach(btn => {
            btn.style.display = buttons.length > 1 ? 'block' : 'none';
        });
    }

    window.toggleTargetAreaDetails = function() {
        const targetAreaDetails = document.getElementById('targetAreaDetails');
        const checkbox = document.getElementById('targetAreaCheckbox');

        if (checkbox.checked) {
            targetAreaDetails.style.display = 'block';
        } else {
            targetAreaDetails.style.display = 'none';
        }
    }

    // ============ สถานที่ ============
    window.toggleLocationDetails = function() {
        const locationDetails = document.getElementById('locationDetails');
        const toggleIcon = document.getElementById('toggleIconLocation');

        if (locationDetails.style.display === 'none' || locationDetails.style.display === '') {
            locationDetails.style.display = 'block';
            toggleIcon.classList.remove('bx-chevron-up');
            toggleIcon.classList.add('bx-chevron-down');
        } else {
            locationDetails.style.display = 'none';
            toggleIcon.classList.remove('bx-chevron-down');
            toggleIcon.classList.add('bx-chevron-up');
        }
    }

    const locationContainer = document.getElementById('locationContainer');
    const addLocationBtn = document.getElementById('addLocationBtn');

    if (addLocationBtn && locationContainer) {
        addLocationBtn.addEventListener('click', function() {
            const newLocation = document.createElement('div');
            newLocation.className = 'form-group location-item';
            newLocation.innerHTML = `
            <input type="text" class="form-control small-input" name="location[]" placeholder="กรอกสถานที่">
            <button type="button" class="btn btn-danger btn-sm remove-location">
                <i class='bx bx-trash'></i>
            </button>
        `;
            locationContainer.appendChild(newLocation);
            updateLocationButtons();
        });

        locationContainer.addEventListener('click', function(e) {
            if (e.target.closest('.remove-location')) {
                e.target.closest('.location-item').remove();
                updateLocationButtons();
            }
        });
    }

    function updateLocationButtons() {
        const buttons = locationContainer.querySelectorAll('.remove-location');
        buttons.forEach(btn => {
            btn.style.display = buttons.length > 1 ? 'block' : 'none';
        });
    }

    // ============ ตัวชี้วัด ============
    window.toggleIndicatorsDetails = function() {
        const indicatorsDetails = document.getElementById('indicatorsDetails');
        const toggleIcon = document.getElementById('toggleIconIndicators');

        if (indicatorsDetails.style.display === 'none' || indicatorsDetails.style.display === '') {
            indicatorsDetails.style.display = 'block';
            toggleIcon.classList.remove('bx-chevron-up');
            toggleIcon.classList.add('bx-chevron-down');
        } else {
            indicatorsDetails.style.display = 'none';
            toggleIcon.classList.remove('bx-chevron-down');
            toggleIcon.classList.add('bx-chevron-up');
        }
    }

    window.toggleGoalInputs = function(checkbox) {
        const quantitativeInputs = document.getElementById('quantitative-inputs');
        const qualitativeInputs = document.getElementById('qualitative-inputs');

        if (checkbox.id === 'quantitative') {
            quantitativeInputs.style.display = checkbox.checked ? 'block' : 'none';
        } else if (checkbox.id === 'qualitative') {
            qualitativeInputs.style.display = checkbox.checked ? 'block' : 'none';
        }
    }

    window.addQuantitativeItem = function() {
        const quantitativeItems = document.getElementById('quantitative-items');
        const newItem = document.createElement('div');
        newItem.className = 'form-group mt-2';
        const itemNumber = quantitativeItems.children.length + 1;
        newItem.innerHTML = `
            <label>ข้อที่ ${itemNumber}</label>
            <div class="d-flex">
                <input type="text" class="form-control" name="quantitative[]" placeholder="เพิ่มรายการ">
                <button type="button" class="btn btn-danger btn-sm remove-quantitative-item">
                    <i class='bx bx-trash'></i>
                </button>
            </div>
        `;
        quantitativeItems.appendChild(newItem);
        updateQuantitativeButtons();
    }

    window.addQualitativeItem = function() {
        const qualitativeItems = document.getElementById('qualitative-items');
        const newItem = document.createElement('div');
        newItem.className = 'form-group mt-2';
        const itemNumber = qualitativeItems.children.length + 1;
        newItem.innerHTML = `
            <label>ข้อที่ ${itemNumber}</label>
            <div class="d-flex">
                <input type="text" class="form-control" name="qualitative[]" placeholder="เพิ่มข้อความ">
                <button type="button" class="btn btn-danger btn-sm remove-qualitative-item">
                    <i class='bx bx-trash'></i>
                </button>
            </div>
        `;
        qualitativeItems.appendChild(newItem);
        updateQualitativeButtons();
    }

    document.getElementById('quantitative-items').addEventListener('click', function(e) {
        if (e.target.closest('.remove-quantitative-item')) {
            e.target.closest('.form-group').remove();
            updateQuantitativeButtons();
        }
    });

    document.getElementById('qualitative-items').addEventListener('click', function(e) {
        if (e.target.closest('.remove-qualitative-item')) {
            e.target.closest('.form-group').remove();
            updateQualitativeButtons();
        }
    });

    function updateQuantitativeButtons() {
        const items = document.querySelectorAll('#quantitative-items .form-group');
        items.forEach((item, index) => {
            item.querySelector('label').textContent = `ข้อที่ ${index + 1}`;
            const btn = item.querySelector('.remove-quantitative-item');
            btn.style.display = items.length > 1 ? 'block' : 'none';
        });
    }

    function updateQualitativeButtons() {
        const items = document.querySelectorAll('#qualitative-items .form-group');
        items.forEach((item, index) => {
            item.querySelector('label').textContent = `ข้อที่ ${index + 1}`;
            const btn = item.querySelector('.remove-qualitative-item');
            btn.style.display = items.length > 1 ? 'block' : 'none';
        });
    }

    // ============ ระยะเวลาดำเนินโครงการ ============
    window.toggleProjectDurationDetails = function() {
        const projectDurationDetails = document.getElementById('projectDurationDetails');
        const toggleIcon = document.getElementById('toggleIconProjectDuration');

        if (projectDurationDetails.style.display === 'none' || projectDurationDetails.style.display ===
            '') {
            projectDurationDetails.style.display = 'block';
            toggleIcon.classList.remove('bx-chevron-up');
            toggleIcon.classList.add('bx-chevron-down');
        } else {
            projectDurationDetails.style.display = 'none';
            toggleIcon.classList.remove('bx-chevron-down');
            toggleIcon.classList.add('bx-chevron-up');
        }
    };

    const today = new Date().toISOString().split('T')[0];
    const startDateInput = document.getElementById('First_Time');
    const endDateInput = document.getElementById('End_Time');

    startDateInput.setAttribute('min', today);

    startDateInput.addEventListener('change', function() {
        endDateInput.setAttribute('min', this.value);
    });

    if (startDateInput.value) {
        endDateInput.setAttribute('min', startDateInput.value);
    }

    // ============ ขั้นตอนและแผนการดำเนินงาน ============
    const shortTermProject = document.getElementById('shortTermProject');
    const longTermProject = document.getElementById('longTermProject');
    const textboxPlanType1 = document.getElementById('textbox-planType-1');
    const textboxPlanType2 = document.getElementById('textbox-planType-2');

    shortTermProject.addEventListener('change', function() {
        if (shortTermProject.checked) {
            textboxPlanType1.style.display = 'block';
            textboxPlanType2.style.display = 'none';
        }
    });

    longTermProject.addEventListener('change', function() {
        if (longTermProject.checked) {
            textboxPlanType2.style.display = 'block';
            textboxPlanType1.style.display = 'none';
        }
    });

    const methodContainer = document.getElementById('methodContainer');
    const addMethodBtn = document.querySelector('.btn-addlist');

    window.addMethodItem = function() {
        const newMethod = document.createElement('div');
        newMethod.className = 'form-group mt-2 d-flex';
        newMethod.innerHTML = `
        <input type="text" class="form-control" name="Details_Short_Project[]" placeholder="เพิ่มรายการ">
        <button type="button" class="btn btn-danger btn-sm remove-method">
            <i class='bx bx-trash'></i>
        </button>
    `;
        methodContainer.appendChild(newMethod);
        updateMethodButtons();
    }

    methodContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-method')) {
            e.target.closest('.form-group').remove();
            updateMethodButtons();
        }
    });

    function updateMethodButtons() {
        const buttons = methodContainer.querySelectorAll('.remove-method');
        buttons.forEach(btn => {
            btn.style.display = buttons.length > 1 ? 'block' : 'none';
        });
    }

    // ============ แหล่งงบประมาณ ============
    // แสดง/ซ่อนฟอร์มรายได้ ตามค่า checkbox
    window.toggleIncomeForm = function(checkbox) {
        const incomeForm = document.getElementById('incomeForm');
        incomeForm.style.display = checkbox.checked ? 'block' : 'none';
    };

    // แสดง/ซ่อนรายละเอียดงบประมาณ
    window.toggleBudgetDetails = function() {
        const budgetDetails = document.getElementById('budgetDetails');
        const toggleIcon = document.getElementById('toggleIconBudget');

        const isHidden = budgetDetails.style.display === 'none' || budgetDetails.style.display === '';
        budgetDetails.style.display = isHidden ? 'block' : 'none';
        toggleIcon.classList.toggle('bx-chevron-up', !isHidden);
        toggleIcon.classList.toggle('bx-chevron-down', isHidden);
    };

    // เช็คการเลือก budget source
    window.handleSourceSelect = function(checkbox) {
        const selectedId = checkbox.getAttribute('data-id');
        const amountInput = document.querySelector(`input[name="amount_${selectedId}"]`);

        if (amountInput) {
            amountInput.disabled = !checkbox.checked;
            if (!checkbox.checked) amountInput.value = '';
        }
    };

    // แสดง/ซ่อนฟอร์มงบประมาณ ตามค่า checkbox
    window.toggleBudgetForm = function(checkbox) {
        const budgetFormsContainer = document.getElementById('budgetFormsContainer');
        budgetFormsContainer.style.display = checkbox.checked ? 'block' : 'none';
    };


    window.addDateForm = function() {
        const container = document.getElementById('multipleDaysContainer');
        const newDateForm = document.createElement('div');
        newDateForm.className = 'mb-3 d-flex align-items-start';
        newDateForm.innerHTML = `
        <div style="flex: 1;">
            <label class="form-label">วันที่</label>
            <input type="date" name="date[]" class="form-control">
        </div>
    `;
        container.appendChild(newDateForm);
    }

    window.removeDateForm = function(button) {
        const dateForm = button.closest('.mb-3');
        dateForm.remove();
    }

    window.addBudgetForm = function() {
        const budgetFormsContainer = document.getElementById('budgetFormsContainer');
        const formCount = budgetFormsContainer.getElementsByClassName('budget-form').length;

        const newBudgetForm = document.createElement('div');
        newBudgetForm.className = 'budget-form card mb-3';
        newBudgetForm.innerHTML = `
        <div class="card-body">
            <button type="button" class="btn btn-danger btn-sm remove-form-btn" onclick="removeBudgetForm(this)">ลบแบบฟอร์ม</button>

            <!-- Date and Details -->
            <div class="mb-3 d-flex justify-content-between align-items-start">
                <div style="flex: 1;">
                    <label class="form-label">วันที่</label>
                    <input type="date" name="date[]" class="form-control" style="width: 150px;">
                </div>
            </div>
            <div class="mb-3 d-flex align-items-center">
                <div style="flex: 3; margin-right: 1rem;">
                    <label class="form-label">รายละเอียด</label>
                    <textarea name="details[]" class="form-control" placeholder="ระบุรายละเอียด"></textarea>
                </div>
                <div style="flex: 1;">
                    <label class="form-label">จำนวนเงินทั้งหมด</label>
                    <div class="input-group">
                        <input type="number" name="total_amount[]" class="form-control" placeholder="จำนวนเงิน">
                        <span class="input-group-text">บาท</span>
                    </div>
                </div>
            </div>

            <!-- Category and Sub-Items -->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">หมวดหมู่</label>
                        <select name="category[${formCount}][]" class="form-control">
                            <option value="" disabled selected>เลือกหมวดหมู่</option>
                            ${getSubtopBudgetsOptions()}
                        </select>
                    </div>
                    <div id="subActivityContainer">
                        <div class="sub-activity mb-3">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="mb-3 d-flex align-items-center">
                                        <div style="flex: 3;">
                                            <label class="form-label">รายละเอียดของหมวดหมู่</label>
                                            <textarea name="description[${formCount}][]" class="form-control" placeholder="เช่น ค่าอาหารว่างสำหรับการจัดประชุมคณะกรรมการจัดการความรู้"></textarea>
                                        </div>
                                        <div style="flex: 1; margin-left: 1rem;">
                                            <label class="form-label">จำนวนเงิน</label>
                                            <div class="input-group">
                                                <input type="number" name="amount[${formCount}][]" class="form-control" placeholder="880">
                                                <span class="input-group-text">บาท</span>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-success btn-sm" onclick="addDetail(this)">เพิ่มรายละเอียด</button>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeDetail(this)">ลบรายละเอียด</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="addSubActivity(this)">เพิ่มหัวข้อย่อยพร้อมรายละเอียด</button>
                </div>
            </div>
        </div>
    `;

        budgetFormsContainer.appendChild(newBudgetForm);
    }

    window.removeBudgetForm = function(button) {
        const budgetForm = button.closest('.budget-form');
        budgetForm.remove();
    }

    window.addDetail = function(button) {
        const subActivity = button.closest('.sub-activity');
        const formCount = button.closest('.budget-form').getAttribute('data-form-index') || '0';

        const newDetail = document.createElement('div');
        newDetail.className = 'card mb-3';
        newDetail.innerHTML = `
        <div class="card-body">
            <div class="mb-3 d-flex align-items-center">
                <div style="flex: 3;">
                    <label class="form-label">รายละเอียดของหมวดหมู่</label>
                    <textarea name="description[${formCount}][]" class="form-control" placeholder="เช่น ค่าอาหารว่างสำหรับการจัดประชุมคณะกรรมการจัดการความรู้"></textarea>
                </div>
                <div style="flex: 1; margin-left: 1rem;">
                    <label class="form-label">จำนวนเงิน</label>
                    <div class="input-group">
                        <input type="number" name="amount[${formCount}][]" class="form-control" placeholder="880">
                        <span class="input-group-text">บาท</span>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeDetail(this)">ลบรายละเอียด</button>
        </div>
    `;
        subActivity.appendChild(newDetail);
    }

    window.addSubActivity = function(button) {
        const subActivityContainer = button.closest('.budget-form').querySelector('#subActivityContainer');
        const formCount = button.closest('.budget-form').getAttribute('data-form-index') || '0';

        const newSubActivity = document.createElement('div');
        newSubActivity.className = 'sub-activity mb-3';
        newSubActivity.innerHTML = `
        <div class="card mb-3">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">หมวดหมู่</label>
                    <select name="category[${formCount}][]" class="form-control">
                        <option value="" disabled selected>เลือกหมวดหมู่</option>
                        ${getSubtopBudgetsOptions()}
                    </select>
                </div>
                <div class="mb-3 d-flex align-items-center">
                    <div style="flex: 3;">
                        <label class="form-label">รายละเอียดของหมวดหมู่</label>
                        <textarea name="description[${formCount}][]" class="form-control" placeholder="เช่น ค่าอาหารว่างสำหรับการจัดประชุมคณะกรรมการจัดการความรู้"></textarea>
                    </div>
                    <div style="flex: 1; margin-left: 1rem;">
                        <label class="form-label">จำนวนเงิน</label>
                        <div class="input-group">
                            <input type="number" name="amount[${formCount}][]" class="form-control" placeholder="880">
                            <span class="input-group-text">บาท</span>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-success btn-sm" onclick="addDetail(this)">เพิ่มรายละเอียด</button>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeSubActivity(this)">ลบหัวข้อย่อย</button>
            </div>
        </div>
    `;
        subActivityContainer.appendChild(newSubActivity);
    }

    // Helper function to get subtopBudgets options
    function getSubtopBudgetsOptions() {
        const subtopBudgetsSelect = document.querySelector('select[name="category[]"]');
        return subtopBudgetsSelect ? subtopBudgetsSelect.innerHTML : '';
    }

    window.removeDetail = function(button) {
        const detailItem = button.closest('.card');
        detailItem.remove();
    }

    window.removeSubActivity = function(button) {
        const subActivityItem = button.closest('.sub-activity');
        subActivityItem.remove();
    }

    window.updateRemoveButtons = function(detailsContainer) {
        if (!detailsContainer) return;

        const detailItems = detailsContainer.querySelectorAll('.detail-item');
        const removeButtons = detailsContainer.querySelectorAll('.remove-btn');
        removeButtons.forEach(button => {
            button.style.display = detailItems.length > 1 ? 'inline-block' : 'none';
        });
    }

    window.updateFormTitles = function() {
        const budgetForms = document.querySelectorAll('.budget-form');
        budgetForms.forEach((form, index) => {
            form.setAttribute('data-form-index', index);
            const titleElement = form.querySelector('h5');
            if (titleElement) {
                titleElement.innerText = `แบบฟอร์มที่ ${index + 1}`;
            }
        });
    }

    // Initialize when the document is loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Initial setup for date type selection
        const singleDayRadio = document.getElementById('single_day');
        if (singleDayRadio) {
            toggleDateForm(singleDayRadio);
        }

        document.querySelectorAll('.detailsContainer').forEach(container => updateRemoveButtons(
            container));
        updateFormTitles();
    });

    // ============ เป้าหมายเชิงผลผลิต (Output) ============
    window.addField = function(containerId, fieldName) {
        const container = document.getElementById(containerId);
        const newField = document.createElement('div');
        newField.className = 'form-group mt-2 d-flex';
        newField.innerHTML = `
        <input type="text" class="form-control" name="${fieldName}" placeholder="เพิ่มรายการ"
               onblur="saveData(this, '{{ $project->Id_Project }}', '${fieldName}')"
               onkeypress="checkEnter(event, this)">
        <button type="button" class="btn btn-danger btn-sm remove-field">
            <i class='bx bx-trash'></i>
        </button>
    `;
        container.appendChild(newField);
        updateFieldButtons(containerId);
    }

    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-field')) {
            e.target.closest('.form-group').remove();
            updateFieldButtons(e.target.closest('.dynamic-container').id);
        }
    });

    function updateFieldButtons(containerId) {
        const container = document.getElementById(containerId);
        const buttons = container.querySelectorAll('.remove-field');
        buttons.forEach(btn => {
            btn.style.display = buttons.length > 1 ? 'block' : 'none';
        });
    }


    // ============ ตัวชี้วัดความสำเร็จของโครงการ ============


    // ============ ค่าเป้าหมาย ============
});
</script>


<script>
        function saveData(element, projectId, fieldName) {
            let newValue;

            if (element.tagName === 'INPUT' && element.type === 'checkbox') {
                newValue = element.checked ? 1 : 0;

                if (fieldName === 'sdgs') {
                    saveSDGsData(element, projectId);
                    return;
                }

            } else if (element.tagName === 'INPUT' || element.tagName === 'SELECT' || element.tagName === 'TEXTAREA') {
                newValue = element.value;
            } else if (element.tagName === 'DIV' && element.contentEditable === 'true') {
                newValue = element.innerText;
            } else if (element.tagName === 'INPUT' && element.type === 'date') {
                newValue = new Date(element.value).toISOString().split('T')[0]; 
            } else {
                newValue = element.innerText;
            }

            console.log("📌 Project ID:", projectId); // ✅ 
            console.log("📌 Projects Name:", fieldName);  // ✅ 

            fetch('{{ route('projects.updateField') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ id: projectId, field: fieldName, value: newValue })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Data saved successfully', projectId);
                } else {
                    console.error('Error saving data');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    

        function saveSubProjects(element, projectId) {
            if (!element) {
                console.error("❌ Error: Element is undefined!");
                return;
            }
            
            const subProjectName = element.innerText.trim();
            if (!subProjectName) {
                console.warn("⚠️ Warning: No sub-project name found.");
                return;
            }

            console.log("Project ID:", projectId);
            console.log("SubProject Name:", subProjectName);

            fetch('{{ route('projects.updateSubProject') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    project_id: projectId,
                    sub_projects: subProjectName 
                })
            })
            .then(response => {  
                console.log("📌 Response Status:", response.status);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();  // แปลงเป็น JSON
            })
            .then(data => {
                console.log(data);  // แสดงข้อมูลที่ได้รับจากเซิร์ฟเวอร์
                if (data.success) {
                    console.log('Sub Projects updated successfully', subProjectName);
                } else {
                    console.error('Error updating Sub Projects');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });

        }

        function checkEnterSubProject(event, element, projectId) {
        if (event.key === 'Enter') {
            event.preventDefault(); 
            element.blur();

            if (element && element.innerText) {
                let subProjects = element.innerText.trim();
                // let projectId = element.dataset.projectId;

                if (subProjects) {
                    saveSubProjects(element, projectId);  // ส่งข้อมูลไปบันทึก
                } else {
                    console.warn('No sub-project name to save.');
                }
            } else {
                console.warn('Element or innerText is missing.');
            }
        }
    }


        function removeField(button) {
            const field = button.closest(".dynamic-field");
            if (field) {
                field.remove();
            }
        }



    function saveSDGsData(element, projectId) {
        let sdgId = element.value;
        let isChecked = element.checked ? 1 : 0;

        fetch('{{ route('projects.updateSDGs') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                project_id: projectId,
                sdg_id: sdgId,
                is_checked: isChecked
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('SDG data updated successfully');
            } else {
                console.error('Error updating SDG data');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }


    function saveOutputs(outputs, projectId) {
    return fetch('{{ route('projects.updateOutputs') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'  
        },
        body: JSON.stringify({
            outputs: outputs, 
            id: projectId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Data saved successfully');
        } else {
            alert('Error saving data');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

    function checkEnter(event, element) {
        if (event.key === 'Enter') {
            event.preventDefault(); 
            element.blur();
        }
    }

    



    document.querySelectorAll('.editable').forEach(element => {
        element.addEventListener('focus', () => {
            element.classList.add('editing');
        });

        element.addEventListener('blur', () => {
            element.classList.remove('editing');
        });
        
    });

    // document.addEventListener('DOMContentLoaded', function () {
    //     document.querySelectorAll('input[name="Name_Sub_Project[]"]').forEach(input => {
    //         input.addEventListener('keydown', function (event) {
    //             if (event.key === 'Enter') {
    //                 event.preventDefault(); 
    //                 let projectId = 1; 
    //                 saveSubProjects(projectId);
    //             }
    //         });
    //     });
    // });


</script>


@endsection