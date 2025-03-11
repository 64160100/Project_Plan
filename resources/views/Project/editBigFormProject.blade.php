@extends('navbar.app')

<hade>
    <link rel="stylesheet" href="{{ asset('css/createProject.css') }}">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/i18n/datepicker-th.min.js"></script>


    <style>
    /* CSS สำหรับ inline editing */
    .editable-objective {
        cursor: pointer;
        padding: 5px;
        border-radius: 4px;
        transition: background-color 0.2s;
    }

    .editable-objective:hover {
        background-color: rgba(0, 123, 255, 0.05);
        position: relative;
    }

    .editable-objective:hover::after {
        content: "\f4c7";
        /* Boxicons bx-edit icon */
        font-family: 'boxicons' !important;
        display: inline-block;
        margin-left: 8px;
        font-size: 16px;
        color: #007bff;
        opacity: 0.7;
    }

    .editable-objective.editing {
        outline: none;
    }

    .editable-objective.saving {
        opacity: 0.7;
        background-color: #f8f9fa;
        position: relative;
    }

    .editable-objective.saving::after {
        content: "\eb2c";
        /* Boxicons bx-loader-alt icon */
        font-family: 'boxicons' !important;
        animation: spin 1s infinite linear;
        display: inline-block;
        margin-left: 8px;
        font-size: 16px;
        color: #007bff;
    }

    /* Animation for loading spinner */
    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    /* Toast notification styling */
    .toast-notification {
        padding: 12px 20px;
        border-radius: 4px;
        color: white;
        margin-bottom: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        min-width: 300px;
        max-width: 500px;
        opacity: 0;
        transform: translateY(20px);
        animation: toast-in 0.3s ease forwards;
    }

    .toast-notification.hide {
        animation: toast-out 0.3s ease forwards;
    }

    .output-field:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        background-color: #f8f9fa;
    }

    .output-field.saving {
        background-color: #f8f9fa !important;
        opacity: 0.7;
    }

    .save-success {
        border-color: #28a745 !important;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25) !important;
    }

    .save-error {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
    }

    @keyframes toast-in {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes toast-out {
        0% {
            opacity: 1;
            transform: translateY(0);
        }

        100% {
            opacity: 0;
            transform: translateY(-20px);
        }
    }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                        </div>
                    </div>
                </div>

                <!-- ผู้รับผิดชอบโครงการ -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            2. ผู้รับผิดชอบโครงการ
                        </h4>
                    </div>
                    <div id="responsibleDetails">
                        <div class="form-group">
                            <label for="employee_id" class="form-label">เลือกผู้รับผิดชอบ</label>
                            <div class="editable-container">
                                <select class="form-select editable-select" id="employee_id" name="employee_id"
                                    onchange="saveEmployeeData(this, '{{ $project->Id_Project }}', 'Employee_Id')">
                                    <option value="" disabled>เลือกผู้รับผิดชอบ</option>
                                    @foreach($employees as $employee)
                                    <option value="{{ $employee->Id_Employee }}"
                                        {{ $employee->Id_Employee == $project->Employee_Id ? 'selected' : '' }}>
                                        {{ $employee->Firstname }} {{ $employee->Lastname }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('employee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- ความสอดคล้องกับยุทธศาสตร์ส่วนงาน -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            3. ความสอดคล้องกับยุทธศาสตร์ส่วนงาน
                        </h4>
                    </div>
                    <div id="departmentStrategicDetails">
                        <div class="mb-3 col-md-6">
                            <div class="mb-3">
                                <label for="Name_Strategic_Plan" class="form-label">ชื่อแผนยุทธศาสตร์</label>
                                <input type="text" class="form-control" id="Name_Strategic_Plan"
                                    name="Name_Strategic_Plan" value="{{ $nameStrategicPlan }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="Name_Strategy" class="form-label">กลยุทธ์</label>
                                <input type="text" class="form-control" id="Name_Strategy" name="Name_Strategy"
                                    value="{{ $project->Name_Strategy }}" readonly>
                            </div>
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
                            <div class="platform-card card mb-3">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title m-0">แพลตฟอร์มที่ 1</h5>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="removePlatform(this)">
                                        <i class='bx bx-trash'></i> <span class="d-none d-md-inline">ลบแพลตฟอร์ม</span>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label class="form-label">ชื่อแพลตฟอร์ม</label>
                                        <input type="text" name="platforms[0][name]" class="form-control"
                                            placeholder="กรุณากรอกชื่อแพลตฟอร์ม" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="form-label">โปรแกรม</label>
                                        <input type="text" name="platforms[0][program]" class="form-control"
                                            placeholder="กรุณากรอกชื่อโปรแกรม" required>
                                    </div>

                                    <div class="form-group kpi-container">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label class="form-label m-0">KPI</label>
                                            <button type="button" class="btn btn-success btn-sm" onclick="addKpi(this)">
                                                <i class='bx bx-plus'></i> เพิ่ม KPI
                                            </button>
                                        </div>
                                        <div class="kpi-group">
                                            <div class="d-flex align-items-center">
                                                <div class="input-group">
                                                    <span class="input-group-text location-number">1</span>
                                                    <input type="text" name="platforms[0][kpis][]" class="form-control"
                                                        placeholder="กรุณากรอก KPI" style="min-width: 800px;" required>
                                                    <button type="button" class="btn btn-danger btn-sm remove-field"
                                                        onclick="removeKpi(this)" style="display: none;">
                                                        <i class='bx bx-trash'></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary mt-3" onclick="addPlatform()">
                            <i class='bx bx-plus-circle'></i> เพิ่มแพลตฟอร์ม
                        </button>
                    </div>
                </div>

                <!-- SDGs -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            5. ความสอดคล้องกับ (SDGs)
                        </h4>
                    </div>
                    <div id="sdgsDetails" class="editable-container">
                        <div class="sdgs-grid">
                            @foreach ($sdgs as $sdg)
                            <div class="form-group-sdgs">
                                <div class="form-check d-flex align-items-center">
                                    <input class="form-check-input sdg-checkbox" type="checkbox"
                                        value="{{ $sdg->id_SDGs }}" id="sdg_{{ $sdg->id_SDGs }}"
                                        {{ in_array($sdg->id_SDGs, $selectedSdgs ?? []) ? 'checked' : '' }}
                                        onchange="saveSdgData(this, '{{ $project->Id_Project }}', {{ $sdg->id_SDGs }})">
                                    <label class="form-check-label ml-2" for="sdg_{{ $sdg->id_SDGs }}">
                                        {{ $sdg->Name_SDGs }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="sdgs-status mt-2" id="sdgsStatus" style="display: none;">
                            <div class="d-flex align-items-center text-primary">
                                <i class='bx bx-loader-alt bx-spin me-2'></i> กำลังบันทึกการเปลี่ยนแปลง...
                            </div>
                        </div>
                    </div>
                </div>

                <!-- การบูรณาการงานโครงการ -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            6. การบูรณาการงานโครงการ/กิจกรรม
                        </h4>
                    </div>
                    <div id="integrationDetails">
                        <div class="dropdown-container">
                            <div class="dropdown-options">
                                @foreach ($integrationCategories as $category)
                                <div class="option-item">
                                    <label>
                                        <input type="checkbox" class="integration-checkbox"
                                            data-category-id="{{ $category->Id_Integration_Category }}"
                                            {{ in_array($category->Id_Integration_Category, $selectedIntegrations ?? []) ? 'checked' : '' }}
                                            onchange="saveIntegrationData(this, '{{ $project->Id_Project }}', {{ $category->Id_Integration_Category }})">
                                        {{ $category->Name_Integration_Category }}
                                    </label>
                                    <textarea class="additional-info" rows="2"
                                        id="integration_details_{{ $category->Id_Integration_Category }}"
                                        name="integrationCategories[{{ $category->Id_Integration_Category }}][details]"
                                        placeholder="ระบุข้อมูลเพิ่มเติม"
                                        {{ !in_array($category->Id_Integration_Category, $selectedIntegrations ?? []) ? 'disabled' : '' }}
                                        onblur="saveIntegrationDetails(this, '{{ $project->Id_Project }}', {{ $category->Id_Integration_Category }})"
                                        style="width: 100%; resize: none;">{{ $integrationDetails[$category->Id_Integration_Category] ?? '' }}</textarea>
                                    <div class="integration-status text-primary"
                                        style="font-size: 0.8rem; margin-top: 3px; display: none;">
                                        <i class='bx bx-loader-alt bx-spin'></i> กำลังบันทึกข้อมูล...
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div id="integrationStatus"
                            style="display: none; background-color: #e9f7ff; padding: 8px; border-radius: 4px; margin-top: 10px;">
                            <div class="d-flex align-items-center text-primary">
                                <i class='bx bx-loader-alt bx-spin me-2'></i> กำลังบันทึกการเปลี่ยนแปลง...
                            </div>
                        </div>
                    </div>
                </div>
                <!-- หลักการและเหตุผล -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            7. หลักการและเหตุผล
                        </h4>
                    </div>
                    <div id="rationaleDetails" class="editable-container">
                        <div class="form-group">
                            <div class="editable"
                                style="border: 1px solid #ced4da; padding: 10px; border-radius: 5px; min-height: 300px;"
                                contenteditable="true"
                                onblur="saveData(this, '{{ $project->Id_Project }}', 'Principles_Reasons')"
                                onkeypress="checkEnter(event, this)">
                                {{ $project->Principles_Reasons }}
                            </div>
                            @error('Principles_Reasons')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- วัตถุประสงค์โครงการ -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>8. วัตถุประสงค์โครงการ</h4>
                    </div>
                    <div id="objectiveDetails">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label fw-bold">วัตถุประสงค์โครงการ:</label>
                                    <div class="d-flex flex-column">
                                        <select class="form-control @error('objective_select') is-invalid @enderror"
                                            id="objective_select" name="objective_select">
                                            <option value="" disabled selected>เลือกวัตถุประสงค์โครงการ</option>
                                            @foreach($strategicObjectives as $objective)
                                            <option value="{{ $objective->id }}">
                                                {{ $objective->Details_Strategic_Objectives }}</option>
                                            @endforeach
                                        </select>
                                        <div class="d-flex align-items-center mt-2">
                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                id="toggleObjectiveInput">
                                                <i class='bx bx-edit'></i> กรอกวัตถุประสงค์ด้วยตนเอง
                                            </button>
                                        </div>
                                        <textarea class="form-control mt-2" id="objective_manual"
                                            name="objective_manual" placeholder="กรอกวัตถุประสงค์โครงการด้วยตนเอง"
                                            style="display: none; resize: vertical;" rows="15"></textarea>
                                        @error('objective_select')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" id="addObjectiveBtn" class="btn btn-primary">
                            <i class='bx bx-plus-circle me-1'></i> เพิ่มวัตถุประสงค์
                        </button>

                        <div id="objectivesContainer" class="mt-3" data-project-id="{{ $project->Id_Project }}">
                            @foreach($project->objectives as $objective)
                            <div class="objective-item mb-2 p-3 border rounded bg-light position-relative"
                                data-id="{{ $objective->Id_Objective_Project }}">
                                <button type="button"
                                    class="btn-close position-absolute top-0 end-0 m-2 delete-objective"
                                    data-id="{{ $objective->Id_Objective_Project }}"></button>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="fw-bold mb-1">วัตถุประสงค์:</div>
                                        <div class="objective-text editable-objective"
                                            data-project-id="{{ $project->Id_Project }}"
                                            data-objective-id="{{ $objective->Id_Objective_Project }}"
                                            onclick="makeEditable(this)">
                                            {{ $objective->Description_Objective }}
                                        </div>
                                        <input type="hidden" name="Objective_Project[]"
                                            value="{{ $objective->Description_Objective }}">
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Template สำหรับสร้างรายการวัตถุประสงค์ใหม่ (ซ่อนไว้) -->
                <template id="objectiveItemTemplate">
                    <div class="objective-item mb-2 p-3 border rounded bg-light position-relative" data-id="__ID__">
                        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 delete-objective"
                            data-id="__ID__"></button>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="fw-bold mb-1">วัตถุประสงค์:</div>
                                <div class="objective-text editable-objective"
                                    data-project-id="{{ $project->Id_Project }}" data-objective-id="__ID__"
                                    onclick="makeEditable(this)">
                                    __OBJECTIVE__
                                </div>
                                <input type="hidden" name="Objective_Project[]" value="__OBJECTIVE_VALUE__">
                            </div>
                        </div>
                    </div>
                </template>

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
                                        <span class="input-group-text target-group-number">10.1</span>
                                        <input type="text" name="target_group[]" class="form-control"
                                            placeholder="กรอกกลุ่มเป้าหมาย" required>
                                        <input type="number" name="target_count[]" class="form-control"
                                            placeholder="จำนวน" required>
                                        <input type="text" name="target_unit[]" class="form-control" placeholder="หน่วย"
                                            required>
                                        <button type="button" class="btn btn-danger btn-sm remove-target-group"
                                            style="display: none;">
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
                            <div class="form-check" style="display: flex; align-items: center;">
                                <input class="form-check-input" type="checkbox" id="targetAreaCheckbox"
                                    onchange="toggleTargetAreaDetails()">
                                <label class="form-check-label" for="targetAreaCheckbox"
                                    style="margin-left: 5px; margin-bottom: 0;">
                                    เลือกพื้นที่/ชุมชนเป้าหมาย
                                </label>
                            </div>
                            <div id="targetAreaDetails" style="display: none;">
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
                    <div id="locationDetails">
                        <div id="locationContainer">
                            <div class="form-group location-item">
                                <div class="d-flex align-items-center">
                                    <div class="input-group">
                                        <span class="input-group-text location-number">11.1</span>
                                        <input type="text" class="form-control" name="location[]"
                                            placeholder="กรอกสถานที่" style="min-width: 800px;">
                                        <button type="button" class="btn btn-danger btn-sm remove-location"
                                            style="display: none;">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </div>
                                </div>
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

                        <div id="quantitative-inputs" class="goal-inputs" style="display: none;">
                            <h6>เชิงปริมาณ</h6>
                            <div id="quantitative-items" class="mt-3">
                                <div class="form-group location-item">
                                    <div class="d-flex align-items-center">
                                        <div class="input-group">
                                            <span class="input-group-text location-number">1</span>
                                            <input type="text" class="form-control" name="quantitative[]"
                                                placeholder="เพิ่มรายการ">
                                            <button type="button" class="btn btn-danger btn-sm remove-quantitative-item"
                                                style="display: none;">
                                                <i class='bx bx-trash'></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn-addlist" id="addQuantitativeItemBtn"
                                onclick="addQuantitativeItem()">
                                <i class='bx bx-plus-circle'></i>เพิ่มรายการ
                            </button>
                        </div>

                        <div id="qualitative-inputs" class="goal-inputs" style="display: none;">
                            <h6>เชิงคุณภาพ</h6>
                            <div id="qualitative-items" class="mt-3">
                                <div class="form-group location-item">
                                    <div class="d-flex align-items-center">
                                        <div class="input-group">
                                            <span class="input-group-text location-number">1</span>
                                            <input type="text" class="form-control" name="qualitative[]"
                                                placeholder="เพิ่มข้อความ">
                                            <button type="button" class="btn btn-danger btn-sm remove-qualitative-item"
                                                style="display: none;">
                                                <i class='bx bx-trash'></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn-addlist" id="addQualitativeItemBtn"
                                onclick="addQualitativeItem()">
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
                                <div class="form-group">
                                    <label for="First_Time">วันที่เริ่มต้น:</label>
                                    <input type="date" class="form-control" id="First_Time" name="First_Time" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="End_Time">วันที่สิ้นสุด:</label>
                                    <input type="date" class="form-control" id="End_Time" name="End_Time" required>
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
                                    <div class="form-group location-item mt-2">
                                        <div class="d-flex align-items-center">
                                            <div class="input-group">
                                                <span class="input-group-text location-number">1</span>
                                                <input type="text" class="form-control" name="Details_Short_Project[]"
                                                    placeholder="เพิ่มรายการ">
                                                <button type="button" class="btn btn-danger btn-sm remove-method"
                                                    style="display: none;">
                                                    <i class='bx bx-trash'></i>
                                                </button>
                                            </div>
                                        </div>
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
                            15. แหล่งงบประมาณ
                        </h4>
                    </div>
                    <div id="budgetDetails">
                        <div class="form-group-radio mb-3">
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
                            <!-- แหล่งที่มาของงบประมาณ -->
                            <div class="form-group mb-4">
                                <label class="form-label fw-bold">แหล่งที่มาของงบประมาณ:</label>
                                <div class="card mb-3">
                                    <div class="card-body">
                                        @foreach($budgetSources as $source)
                                        <div class="form-check mb-2 d-flex align-items-center">
                                            <input type="checkbox" id="{{ $source->Id_Budget_Source }}"
                                                name="budget_source[]" value="{{ $source->Id_Budget_Source }}"
                                                class="form-check-input" data-id="{{ $source->Id_Budget_Source }}"
                                                onchange="handleSourceCheckbox(this)">
                                            <label class="form-check-label d-flex align-items-center w-100"
                                                for="{{ $source->Id_Budget_Source }}">
                                                <span class="label-text me-2">{{ $source->Name_Budget_Source }}</span>
                                                <div class="input-group" style="max-width: 200px;">
                                                    <input type="number" name="amount_{{ $source->Id_Budget_Source }}"
                                                        class="form-control form-control-sm" placeholder="จำนวนเงิน"
                                                        disabled>
                                                    <span class="input-group-text">บาท</span>
                                                </div>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- ตารางงบประมาณแบบกรอกข้อมูล -->
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="form-group mb-4">
                                        <label class="form-label fw-bold">รายละเอียดงบประมาณ:</label>
                                        <div class="form-group-radio mb-3">
                                            <label class="form-label">ระยะเวลาการใช้งบประมาณ:</label>
                                            <div class="radio-group">
                                                <input type="radio" name="date_type" value="O" id="single_day"
                                                    onchange="toggleDateForm(this)" checked>
                                                <label for="single_day">วันเดียว</label>

                                                <input type="radio" name="date_type" value="M" id="multiple_days"
                                                    onchange="toggleDateForm(this)">
                                                <label for="multiple_days">หลายวัน</label>
                                            </div>
                                        </div>

                                        <!-- แบบฟอร์มงบประมาณตามวันที่ (อยู่ในกรอบเดียวกัน) -->
                                        <div class="mt-4">
                                            <div id="budgetFormsContainer">
                                                <div id="budgetFormTemplate" class="budget-form mb-4 pb-3 border-bottom"
                                                    data-form-index="0">
                                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                                        <div style="flex: 1;">
                                                            <div class="d-flex align-items-center">
                                                                <div class="me-3">
                                                                    <label class="form-label">วันที่ดำเนินการ</label>
                                                                    <input type="date" name="date[]"
                                                                        class="form-control" style="width: 200px;">
                                                                </div>
                                                                <div class="flex-grow-1">
                                                                    <label
                                                                        class="form-label">รายละเอียดค่าใช้จ่ายสำหรับวันนี้</label>
                                                                    <textarea name="budget_details[]"
                                                                        class="form-control"
                                                                        placeholder="ระบุรายละเอียดค่าใช้จ่ายสำหรับวันที่นี้"
                                                                        rows="2"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="ms-2">
                                                            <button type="button"
                                                                class="btn btn-warning btn-sm reset-form-btn"
                                                                onclick="resetBudgetForm(this)">
                                                                <i class="bx bx-reset"></i> รีเซ็ตข้อมูล
                                                            </button>
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm remove-form-btn ms-2"
                                                                onclick="removeBudgetForm(this)" style="display: none;">
                                                                <i class="bx bx-trash"></i> ลบวันที่นี้
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <!-- ตารางงบประมาณรวม -->
                                                    <div class="table-responsive mt-4">
                                                        <table class="table table-bordered">
                                                            <thead class="bg-light">
                                                                <tr>
                                                                    <th style="width: 5%;">ลำดับ</th>
                                                                    <th style="width: 25%;">หมวดหมู่</th>
                                                                    <th>รายการ</th>
                                                                    <th style="width: 20%;">จำนวน (บาท)</th>
                                                                    <th style="width: 5%;"></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="budget_categories_container">
                                                                <!-- แถวแรกสำหรับเลือกหมวดหมู่ -->
                                                                <tr class="category-row" id="category-row-0"
                                                                    data-row-id="0">
                                                                    <td class="align-middle text-center">1</td>
                                                                    <td class="align-middle">
                                                                        <select class="form-select category-select"
                                                                            name="budget_category[0][]" data-row-id="0"
                                                                            onchange="handleCategorySelect(this)">
                                                                            <option value="" selected disabled>
                                                                                เลือกหมวดหมู่</option>
                                                                            @foreach($mainCategories as $index =>
                                                                            $categoryName)
                                                                            <option value="{{ $index }}">
                                                                                {{ $categoryName }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>

                                                                    <td colspan="2" class="align-middle text-center">
                                                                        <span
                                                                            class="text-muted">โปรดเลือกหมวดหมู่ก่อน</span>
                                                                    </td>

                                                                    <td class="align-middle text-center">
                                                                        <!-- ปุ่มลบในแถวแรกจะถูกซ่อนไว้ -->
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-danger"
                                                                            onclick="removeCategoryRow(this)"
                                                                            style="display: none;">
                                                                            <i class="bx bx-trash"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <td colspan="5">
                                                                        <button type="button"
                                                                            class="btn btn-outline-primary"
                                                                            onclick="addCategoryRow()">
                                                                            <i class="bx bx-plus-circle"></i>
                                                                            เพิ่มหมวดหมู่
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                                <tr class="bg-light font-weight-bold">
                                                                    <td colspan="3" class="text-end">รวมทั้งสิ้น</td>
                                                                    <td class="text-center">
                                                                        <span id="grand_total">0</span> บาท
                                                                    </td>
                                                                    <td></td>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <!-- ปุ่มเพิ่มแบบฟอร์ม (สำหรับหลายวัน) -->
                                        <div class="mt-3 mb-3" id="addBudgetFormBtnContainer" style="display: none;">
                                            <button type="button" class="btn btn-primary btn-sm"
                                                onclick="addBudgetForm()">
                                                <i class="bx bx-plus"></i> เพิ่มข้อมูลวันอื่น
                                            </button>
                                        </div>
                                    </div>
                                </div>
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
                            <div class="form-group location-item mt-2">
                                <div class="d-flex align-items-center">
                                    <div class="input-group">
                                        <span class="input-group-text location-number">1</span>
                                        <input type="text" class="form-control output-field" name="outputs[]"
                                            placeholder="เพิ่มรายการ" data-field-type="output">
                                        <button type="button" class="btn btn-danger btn-sm remove-field"
                                            style="display: none;">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </div>
                                </div>
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
                            <div class="form-group location-item mt-2">
                                <div class="d-flex align-items-center">
                                    <div class="input-group">
                                        <span class="input-group-text location-number">1</span>
                                        <input type="text" class="form-control" name="outcomes[]"
                                            placeholder="เพิ่มรายการ">
                                        <button type="button" class="btn btn-danger btn-sm remove-field"
                                            style="display: none;">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </div>
                                </div>
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
                            <div class="form-group location-item mt-2">
                                <div class="d-flex align-items-center">
                                    <div class="input-group">
                                        <span class="input-group-text location-number">1</span>
                                        <input type="text" class="form-control" name="expected_results[]"
                                            placeholder="เพิ่มรายการ">
                                        <button type="button" class="btn btn-danger btn-sm remove-field"
                                            style="display: none;">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn-addlist"
                            onclick="addField('resultContainer', 'expected_results[]')">
                            <i class='bx bx-plus-circle'></i>เพิ่มรายการ
                        </button>
                    </div>
                </div>

                <!-- ตัวชี้วัดความสำเร็จของโครงการและค่าเป้าหมาย (รวมเป็นหัวข้อเดียว) -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            19. ตัวชี้วัดความสำเร็จของโครงการและค่าเป้าหมาย
                        </h4>
                    </div>

                    <!-- ฟอร์มเพิ่มตัวชี้วัดและค่าเป้าหมาย -->
                    <div id="indicatorForm">
                        <div class="row mb-3">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label class="form-label fw-bold">ตัวชี้วัดความสำเร็จของโครงการ:</label>
                                    <div class="d-flex flex-column">
                                        <select class="form-control @error('Success_Indicators') is-invalid @enderror"
                                            id="Success_Indicators" name="Success_Indicators">
                                            <option value="" disabled selected>กรอกตัวชี้วัดความสำเร็จของโครงการ
                                            </option>
                                            @foreach($kpis as $kpi)
                                            <option value="{{ $kpi->Name_Kpi }}"
                                                data-strategy-id="{{ $kpi->Strategy_Id }}"
                                                data-target-value="{{ $kpi->Target_Value }}">
                                                {{ $kpi->Name_Kpi }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <div class="d-flex align-items-center mt-2">
                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                id="toggleIndicatorInput">
                                                <i class='bx bx-edit'></i> กรอกตัวชี้วัดด้วยตนเอง
                                            </button>
                                        </div>
                                        <textarea class="form-control mt-2" id="Success_Indicators_Other"
                                            name="Success_Indicators_Other"
                                            placeholder="กรอกตัวชี้วัดความสำเร็จด้วยตนเอง"
                                            style="display: none;"></textarea>
                                        @error('Success_Indicators')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- ส่วนของค่าเป้าหมาย -->
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label fw-bold">ค่าเป้าหมาย:</label>
                                    <div class="d-flex flex-column">
                                        <select class="form-control @error('Value_Target') is-invalid @enderror"
                                            id="Value_Target" name="Value_Target">
                                            <option value="" disabled selected>กรอกค่าเป้าหมาย</option>
                                        </select>
                                        <div class="d-flex align-items-center mt-2">
                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                id="toggleTargetInput">
                                                <i class='bx bx-edit'></i> กรอกค่าเป้าหมายด้วยตนเอง
                                            </button>
                                        </div>
                                        <textarea class="form-control mt-2" id="Value_Target_Other"
                                            name="Value_Target_Other" placeholder="กรอกค่าเป้าหมายด้วยตนเอง"
                                            style="display: none;"></textarea>
                                        @error('Value_Target')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" id="addIndicatorBtn" class="btn btn-primary">
                            <i class='bx bx-plus-circle me-1'></i> เพิ่มตัวชี้วัดและค่าเป้าหมาย
                        </button>

                        <div id="indicatorsContainer" class="mt-3">
                            @foreach($project->successIndicators as $indicator)
                            @foreach($indicator->valueTargets as $target)
                            <div class="indicator-item mb-2 p-3 border rounded bg-light position-relative">
                                <button type="button"
                                    class="btn-close position-absolute top-0 end-0 m-2 delete-indicator"
                                    data-id="{{ $indicator->Id_Success_Indicators }}"></button>
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="fw-bold mb-1">ตัวชี้วัดความสำเร็จ:</div>
                                        <div class="indicator-text">{{ $indicator->Description_Indicators }}</div>
                                        <input type="hidden" name="indicators[]"
                                            value="{{ $indicator->Description_Indicators }}">
                                    </div>
                                    <div class="col-md-5">
                                        <div class="fw-bold mb-1">ค่าเป้าหมาย:</div>
                                        <div class="target-value">{{ $target->Value_Target }}</div>
                                        <input type="hidden" name="targets[]" value="{{ $target->Value_Target }}">
                                        <input type="hidden" name="target_types[]"
                                            value="{{ $target->Type_Value_Target }}">
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>



                <!-- เอกสารเพิ่มเติม -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>20. เอกสารเพิ่มเติม</h4>
                    </div>
                    <div class="form-group">
                        <input type="file" class="form-control" id="myFile" name="filename">
                        <small class="form-text text-muted">อัพโหลดไฟล์เอกสารที่เกี่ยวข้องกับโครงการ</small>
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

<script src="{{ asset('js/editBigFormProject.js') }}"></script>

<script>
// Add this to your editBigFormProject.js file or within a script tag
document.addEventListener('DOMContentLoaded', function() {
    // Initialize location container with existing locations if any
    initializeLocations();

    // Add event listener to the add location button
    document.getElementById('addLocationBtn').addEventListener('click', addNewLocation);

    // Add event listeners to any existing remove buttons
    document.querySelectorAll('.remove-location').forEach(button => {
        if (document.querySelectorAll('.location-item').length > 1) {
            button.style.display = 'block';
        }
        button.addEventListener('click', removeLocation);
    });
});

// Initialize locations from database if they exist
function initializeLocations() {
    const projectId = document.querySelector('form').getAttribute('action').split('/').slice(-2)[0];

    // Fetch existing locations from server
    fetch(`/projects/${projectId}/locations`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.locations.length > 0) {
                // Clear the default empty input
                document.getElementById('locationContainer').innerHTML = '';

                // Add each location from the database
                data.locations.forEach((location, index) => {
                    addLocationFromData(location, index + 1);
                });

                // Show remove buttons if multiple locations exist
                if (data.locations.length > 1) {
                    document.querySelectorAll('.remove-location').forEach(button => {
                        button.style.display = 'block';
                    });
                }
            }
        })
        .catch(error => {
            console.error('Error fetching locations:', error);
        });
}

// Add a new location input field
function addNewLocation() {
    const container = document.getElementById('locationContainer');
    const items = container.querySelectorAll('.location-item');
    const index = items.length + 1;

    const locationDiv = document.createElement('div');
    locationDiv.className = 'form-group location-item';
    locationDiv.innerHTML = `
        <div class="d-flex align-items-center">
            <div class="input-group">
                <span class="input-group-text location-number">11.${index}</span>
                <input type="text" class="form-control location-input" name="location[]"
                    placeholder="กรอกสถานที่" style="min-width: 800px;" data-location-id="">
                <button type="button" class="btn btn-danger btn-sm remove-location">
                    <i class='bx bx-trash'></i>
                </button>
            </div>
        </div>
    `;

    container.appendChild(locationDiv);

    // Add event listener to the new input
    const newInput = locationDiv.querySelector('.location-input');
    setupLocationInput(newInput);

    // Add event listener to the new remove button
    const removeButton = locationDiv.querySelector('.remove-location');
    removeButton.addEventListener('click', removeLocation);

    // Show all remove buttons if there's more than one location
    if (items.length > 0) {
        document.querySelectorAll('.remove-location').forEach(button => {
            button.style.display = 'block';
        });
    }

    // Focus on the new input
    newInput.focus();
}

// Add a location from database data
function addLocationFromData(locationData, index) {
    const container = document.getElementById('locationContainer');

    const locationDiv = document.createElement('div');
    locationDiv.className = 'form-group location-item';
    locationDiv.innerHTML = `
        <div class="d-flex align-items-center">
            <div class="input-group">
                <span class="input-group-text location-number">11.${index}</span>
                <input type="text" class="form-control location-input" name="location[]"
                    placeholder="กรอกสถานที่" style="min-width: 800px;" value="${locationData.location_name}" 
                    data-location-id="${locationData.id}">
                <button type="button" class="btn btn-danger btn-sm remove-location" style="display: none;">
                    <i class='bx bx-trash'></i>
                </button>
            </div>
        </div>
    `;

    container.appendChild(locationDiv);

    // Add event listener to the input
    const newInput = locationDiv.querySelector('.location-input');
    setupLocationInput(newInput);

    // Add event listener to the remove button
    const removeButton = locationDiv.querySelector('.remove-location');
    removeButton.addEventListener('click', removeLocation);
}

// Setup event listeners for a location input
function setupLocationInput(input) {
    // Handle Enter key press
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            saveLocation(this);
        }
    });

    // Handle blur event (when input loses focus)
    input.addEventListener('blur', function() {
        saveLocation(this);
    });

    // Add visual indicator for editable field
    input.addEventListener('focus', function() {
        this.classList.add('editing');
    });
}

// Save location data to the server
function saveLocation(inputElement) {
    const locationValue = inputElement.value.trim();
    if (!locationValue) return; // Don't save empty values

    const projectId = document.querySelector('form').getAttribute('action').split('/').slice(-2)[0];
    const locationId = inputElement.getAttribute('data-location-id') || '';

    // Add saving indicator
    inputElement.classList.add('saving');
    inputElement.disabled = true;

    // Send data to server
    fetch(`/projects/${projectId}/save-location`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                location_id: locationId,
                location_name: locationValue
            })
        })
        .then(response => response.json())
        .then(data => {
            inputElement.classList.remove('saving', 'editing');
            inputElement.disabled = false;

            if (data.success) {
                // Update the location ID if it was a new location
                if (data.location_id) {
                    inputElement.setAttribute('data-location-id', data.location_id);
                }

                // Visual feedback for successful save
                inputElement.classList.add('save-success');
                setTimeout(() => {
                    inputElement.classList.remove('save-success');
                }, 2000);

                // Show toast notification
                showToast('บันทึกสถานที่เรียบร้อยแล้ว', 'success');
            } else {
                // Visual feedback for error
                inputElement.classList.add('save-error');
                setTimeout(() => {
                    inputElement.classList.remove('save-error');
                }, 2000);

                showToast(data.message || 'เกิดข้อผิดพลาดในการบันทึกข้อมูล', 'error');
            }
        })
        .catch(error => {
            inputElement.classList.remove('saving', 'editing');
            inputElement.disabled = false;
            inputElement.classList.add('save-error');
            setTimeout(() => {
                inputElement.classList.remove('save-error');
            }, 2000);

            console.error('Error saving location:', error);
            showToast('เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์', 'error');
        });
}

// Remove a location
function removeLocation() {
    const locationItem = this.closest('.location-item');
    const locationInput = locationItem.querySelector('.location-input');
    const locationId = locationInput.getAttribute('data-location-id');
    const projectId = document.querySelector('form').getAttribute('action').split('/').slice(-2)[0];

    // If the location has an ID, delete it from the database
    if (locationId) {
        // Show confirmation dialog
        if (!confirm('คุณต้องการลบสถานที่นี้ใช่หรือไม่?')) {
            return;
        }

        // Send delete request to server
        fetch(`/projects/${projectId}/delete-location/${locationId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    removeLocationFromDOM(locationItem);
                    showToast('ลบสถานที่เรียบร้อยแล้ว', 'warning');
                } else {
                    showToast(data.message || 'เกิดข้อผิดพลาดในการลบข้อมูล', 'error');
                }
            })
            .catch(error => {
                console.error('Error deleting location:', error);
                showToast('เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์', 'error');
            });
    } else {
        // If it doesn't have an ID, it's a new item that hasn't been saved, so just remove it from DOM
        removeLocationFromDOM(locationItem);
    }
}

// Remove location item from DOM and renumber remaining items
function removeLocationFromDOM(locationItem) {
    const container = document.getElementById('locationContainer');
    locationItem.remove();

    // Renumber the remaining items
    const items = container.querySelectorAll('.location-item');
    items.forEach((item, index) => {
        item.querySelector('.location-number').textContent = `11.${index + 1}`;
    });

    // Hide remove buttons if only one location remains
    if (items.length === 1) {
        items[0].querySelector('.remove-location').style.display = 'none';
    }
}

// Show toast notification function
function showToast(message, type = 'success') {
    // Create toast container if it doesn't exist
    if (!document.getElementById('toast-container')) {
        const toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.style.position = 'fixed';
        toastContainer.style.bottom = '20px';
        toastContainer.style.right = '20px';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }

    // Create toast element
    const toast = document.createElement('div');
    toast.className = 'toast-notification';

    // Set background color and icon based on type
    if (type === 'success') {
        toast.style.backgroundColor = '#28a745';
        toast.innerHTML = `<i class='bx bx-check' style='margin-right:8px; font-size:20px;'></i> ${message}`;
    } else if (type === 'warning') {
        toast.style.backgroundColor = '#ffc107';
        toast.style.color = '#212529';
        toast.innerHTML = `<i class='bx bx-info-circle' style='margin-right:8px; font-size:20px;'></i> ${message}`;
    } else if (type === 'error') {
        toast.style.backgroundColor = '#dc3545';
        toast.innerHTML = `<i class='bx bx-error-circle' style='margin-right:8px; font-size:20px;'></i> ${message}`;
    } else if (type === 'info') {
        toast.style.backgroundColor = '#17a2b8';
        toast.innerHTML =
            `<i class='bx bx-loader-alt bx-spin' style='margin-right:8px; font-size:20px;'></i> ${message}`;
    }

    // Add to container and animate
    document.getElementById('toast-container').appendChild(toast);
    setTimeout(() => toast.classList.add('show'), 10);

    // Remove after 3 seconds
    setTimeout(() => {
        toast.classList.add('hide');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script>


<script>
function saveData(element, projectId, fieldName) {
    const newValue = element.innerText;

    // สร้าง toast notification container ถ้ายังไม่มี
    if (!document.getElementById('toast-container')) {
        const toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.style.position = 'fixed';
        toastContainer.style.bottom = '20px';
        toastContainer.style.right = '20px';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }

    // แสดงสถานะกำลังโหลด
    element.classList.add('saving');

    // ส่งข้อมูลไปยัง server ด้วย AJAX - ระบุ projectId ใน URL
    fetch(`/projects/${projectId}/update-field`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                field: fieldName,
                value: newValue
            })
        })
        .then(response => response.json())
        .then(data => {
            // ลบคลาส saving
            element.classList.remove('saving');

            if (data.success) {
                // สร้าง toast notification
                const toast = document.createElement('div');
                toast.className = 'toast-notification show';
                toast.style.backgroundColor = '#28a745';
                toast.innerHTML =
                    `<i class='bx bx-check' style='margin-right:8px; font-size:20px;'></i> บันทึกข้อมูลเรียบร้อยแล้ว`;

                document.getElementById('toast-container').appendChild(toast);

                // ซ่อนตัวเองหลังจาก 3 วินาที
                setTimeout(() => {
                    toast.classList.add('hide');
                    setTimeout(() => {
                        toast.remove();
                    }, 300);
                }, 3000);

                element.classList.remove('editing');
                console.log('Data saved successfully');
            } else {
                // กรณีเกิดข้อผิดพลาด
                const toast = document.createElement('div');
                toast.className = 'toast-notification show error';
                toast.style.backgroundColor = '#dc3545';
                toast.innerHTML =
                    `<i class='bx bx-error-circle' style='margin-right:8px; font-size:20px;'></i> ${data.message || 'เกิดข้อผิดพลาดในการบันทึกข้อมูล'}`;

                document.getElementById('toast-container').appendChild(toast);

                setTimeout(() => {
                    toast.classList.add('hide');
                    setTimeout(() => {
                        toast.remove();
                    }, 300);
                }, 3000);

                console.error('Error saving data:', data.message);
            }
        })
        .catch(error => {
            element.classList.remove('saving');

            // แสดงข้อความแจ้งเตือนข้อผิดพลาด
            const toast = document.createElement('div');
            toast.className = 'toast-notification show error';
            toast.style.backgroundColor = '#dc3545';
            toast.innerHTML =
                `<i class='bx bx-error-circle' style='margin-right:8px; font-size:20px;'></i> เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์`;

            document.getElementById('toast-container').appendChild(toast);

            setTimeout(() => {
                toast.classList.add('hide');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000);

            console.error('Error:', error);
        });
}

function checkEnter(event, element) {
    if (event.key === 'Enter') {
        event.preventDefault();
        element.blur();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.editable').forEach(element => {
        element.addEventListener('focus', () => {
            element.classList.add('editing');
        });
        element.addEventListener('blur', () => {
            element.classList.remove('editing');
        });
    });
});
</script>

<script>
// เพิ่มฟังก์ชันนี้ในส่วน <script> ที่มีอยู่แล้ว
function saveEmployeeData(element, projectId, fieldName) {
    const newValue = element.value;
    const selectedText = element.options[element.selectedIndex].text;

    // สร้าง toast notification container ถ้ายังไม่มี
    if (!document.getElementById('toast-container')) {
        const toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.style.position = 'fixed';
        toastContainer.style.bottom = '20px';
        toastContainer.style.right = '20px';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }

    // แสดงสถานะกำลังโหลด
    const container = element.closest('.editable-container');
    container.classList.add('saving');

    // ส่งข้อมูลไปยัง server ด้วย AJAX
    fetch(`/projects/${projectId}/update-field`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                field: fieldName,
                value: newValue
            })
        })
        .then(response => response.json())
        .then(data => {
            // ลบคลาส saving
            container.classList.remove('saving');

            if (data.success) {
                // สร้าง toast notification
                const toast = document.createElement('div');
                toast.className = 'toast-notification show';
                toast.style.backgroundColor = '#28a745';
                toast.innerHTML =
                    `<i class='bx bx-check' style='margin-right:8px; font-size:20px;'></i> เปลี่ยนผู้รับผิดชอบเป็น ${selectedText} เรียบร้อยแล้ว`;

                document.getElementById('toast-container').appendChild(toast);

                // ซ่อนตัวเองหลังจาก 3 วินาที
                setTimeout(() => {
                    toast.classList.add('hide');
                    setTimeout(() => {
                        toast.remove();
                    }, 300);
                }, 3000);
            } else {
                // กรณีเกิดข้อผิดพลาด
                const toast = document.createElement('div');
                toast.className = 'toast-notification show error';
                toast.style.backgroundColor = '#dc3545';
                toast.innerHTML =
                    `<i class='bx bx-error-circle' style='margin-right:8px; font-size:20px;'></i> ${data.message || 'เกิดข้อผิดพลาดในการบันทึกข้อมูล'}`;

                document.getElementById('toast-container').appendChild(toast);

                setTimeout(() => {
                    toast.classList.add('hide');
                    setTimeout(() => {
                        toast.remove();
                    }, 300);
                }, 3000);

                // หากมีข้อผิดพลาดให้เลือกค่าเดิม
                if (data.original_value) {
                    element.value = data.original_value;
                }
            }
        })
        .catch(error => {
            container.classList.remove('saving');

            // แสดงข้อความแจ้งเตือนข้อผิดพลาด
            const toast = document.createElement('div');
            toast.className = 'toast-notification show error';
            toast.style.backgroundColor = '#dc3545';
            toast.innerHTML =
                `<i class='bx bx-error-circle' style='margin-right:8px; font-size:20px;'></i> เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์`;

            document.getElementById('toast-container').appendChild(toast);

            setTimeout(() => {
                toast.classList.add('hide');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000);

            console.error('Error:', error);
        });
}

// ฟังก์ชันสำหรับบันทึกข้อมูล SDGs
function saveSdgData(checkbox, projectId, sdgId) {
    // แสดงสถานะกำลังบันทึก
    const statusElement = document.getElementById('sdgsStatus');
    statusElement.style.display = 'block';

    const isChecked = checkbox.checked;

    // สร้าง toast notification container ถ้ายังไม่มี
    if (!document.getElementById('toast-container')) {
        const toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.style.position = 'fixed';
        toastContainer.style.bottom = '20px';
        toastContainer.style.right = '20px';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }

    // ส่งข้อมูลไปยัง server
    fetch(`/projects/${projectId}/update-sdgs`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                sdg_id: sdgId,
                selected: isChecked
            })
        })
        .then(response => response.json())
        .then(data => {
            // ซ่อนสถานะการบันทึก
            statusElement.style.display = 'none';

            if (data.success) {
                // สร้าง toast notification
                const toast = document.createElement('div');
                toast.className = 'toast-notification show';
                toast.style.backgroundColor = '#28a745';

                // ข้อความแสดงการเปลี่ยนแปลง
                const sdgLabel = document.querySelector(`label[for="sdg_${sdgId}"]`).textContent.trim();
                const actionText = isChecked ? 'เพิ่ม' : 'ลบ';

                toast.innerHTML = `
                <i class='bx bx-check' style='margin-right:8px; font-size:20px;'></i> 
                ${actionText} SDG: ${sdgLabel} เรียบร้อยแล้ว
            `;

                document.getElementById('toast-container').appendChild(toast);

                // ซ่อนตัวเองหลังจาก 3 วินาที
                setTimeout(() => {
                    toast.classList.add('hide');
                    setTimeout(() => {
                        toast.remove();
                    }, 300);
                }, 3000);
            } else {
                // กรณีเกิดข้อผิดพลาด
                const toast = document.createElement('div');
                toast.className = 'toast-notification show error';
                toast.style.backgroundColor = '#dc3545';
                toast.innerHTML = `
                <i class='bx bx-error-circle' style='margin-right:8px; font-size:20px;'></i> 
                ${data.message || 'เกิดข้อผิดพลาดในการบันทึกข้อมูล'}
            `;

                document.getElementById('toast-container').appendChild(toast);

                setTimeout(() => {
                    toast.classList.add('hide');
                    setTimeout(() => {
                        toast.remove();
                    }, 300);
                }, 3000);

                // คืนสถานะเดิมของ checkbox
                checkbox.checked = !isChecked;
            }
        })
        .catch(error => {
            // ซ่อนสถานะการบันทึก
            statusElement.style.display = 'none';

            // แสดงข้อความแจ้งเตือนข้อผิดพลาด
            const toast = document.createElement('div');
            toast.className = 'toast-notification show error';
            toast.style.backgroundColor = '#dc3545';
            toast.innerHTML = `
            <i class='bx bx-error-circle' style='margin-right:8px; font-size:20px;'></i> 
            เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์
        `;

            document.getElementById('toast-container').appendChild(toast);

            setTimeout(() => {
                toast.classList.add('hide');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000);

            // คืนสถานะเดิมของ checkbox
            checkbox.checked = !isChecked;
            console.error('Error:', error);
        });
}
</script>

<script>
// ฟังก์ชันสำหรับบันทึกข้อมูลการบูรณาการ
function saveIntegrationData(checkbox, projectId, categoryId) {
    // แสดงสถานะกำลังบันทึก
    const statusElement = document.getElementById('integrationStatus');
    statusElement.style.display = 'block';

    const isChecked = checkbox.checked;
    const detailsInput = document.getElementById(`integration_details_${categoryId}`);

    // เปิด/ปิดการใช้งาน input
    detailsInput.disabled = !isChecked;

    // เพิ่ม event listener สำหรับการกด Enter
    if (isChecked) {
        detailsInput.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault(); // ป้องกันการขึ้นบรรทัดใหม่
                this.blur(); // ทำให้ input เสียโฟกัส เพื่อให้ onblur ทำงาน
            }
        });
    } else {
        detailsInput.removeEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                this.blur();
            }
        });
    }

    // สร้าง toast notification container ถ้ายังไม่มี
    if (!document.getElementById('toast-container')) {
        const toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.style.position = 'fixed';
        toastContainer.style.bottom = '20px';
        toastContainer.style.right = '20px';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }

    // ส่งข้อมูลไปยัง server
    fetch(`/projects/${projectId}/update-integration`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                category_id: categoryId,
                selected: isChecked,
                details: detailsInput.value
            })
        })
        .then(response => response.json())
        .then(data => {
            // ซ่อนสถานะการบันทึก
            statusElement.style.display = 'none';

            if (data.success) {
                // สร้าง toast notification
                const toast = document.createElement('div');
                toast.className = 'toast-notification show';
                toast.style.backgroundColor = '#28a745';

                // ข้อความแสดงการเปลี่ยนแปลง
                const categoryName = checkbox.parentElement.textContent.trim();
                const actionText = isChecked ? 'เพิ่ม' : 'ลบ';

                toast.innerHTML = `
                <i class='bx bx-check' style='margin-right:8px; font-size:20px;'></i> 
                ${actionText} การบูรณาการ: ${categoryName} เรียบร้อยแล้ว
            `;

                document.getElementById('toast-container').appendChild(toast);

                // ซ่อนตัวเองหลังจาก 3 วินาที
                setTimeout(() => {
                    toast.classList.add('hide');
                    setTimeout(() => {
                        toast.remove();
                    }, 300);
                }, 3000);
            } else {
                // กรณีเกิดข้อผิดพลาด
                const toast = document.createElement('div');
                toast.className = 'toast-notification show error';
                toast.style.backgroundColor = '#dc3545';
                toast.innerHTML = `
                <i class='bx bx-error-circle' style='margin-right:8px; font-size:20px;'></i> 
                ${data.message || 'เกิดข้อผิดพลาดในการบันทึกข้อมูล'}
            `;

                document.getElementById('toast-container').appendChild(toast);

                setTimeout(() => {
                    toast.classList.add('hide');
                    setTimeout(() => {
                        toast.remove();
                    }, 300);
                }, 3000);

                // คืนสถานะเดิมของ checkbox
                checkbox.checked = !isChecked;
                detailsInput.disabled = !checkbox.checked;
            }
        })
        .catch(error => {
            // ซ่อนสถานะการบันทึก
            statusElement.style.display = 'none';

            // แสดงข้อความแจ้งเตือนข้อผิดพลาด
            const toast = document.createElement('div');
            toast.className = 'toast-notification show error';
            toast.style.backgroundColor = '#dc3545';
            toast.innerHTML = `
            <i class='bx bx-error-circle' style='margin-right:8px; font-size:20px;'></i> 
            เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์
        `;

            document.getElementById('toast-container').appendChild(toast);

            setTimeout(() => {
                toast.classList.add('hide');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000);

            // คืนสถานะเดิมของ checkbox
            checkbox.checked = !isChecked;
            detailsInput.disabled = !checkbox.checked;

            console.error('Error:', error);
        });
}

// ฟังก์ชันสำหรับบันทึกรายละเอียดเพิ่มเติม
function saveIntegrationDetails(input, projectId, categoryId) {
    // แสดงสถานะการบันทึก
    const statusElement = input.nextElementSibling;
    statusElement.style.display = 'block';

    // สร้าง toast notification container ถ้ายังไม่มี
    if (!document.getElementById('toast-container')) {
        const toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.style.position = 'fixed';
        toastContainer.style.bottom = '20px';
        toastContainer.style.right = '20px';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }

    // ส่งข้อมูลไปยัง server
    fetch(`/projects/${projectId}/update-integration-details`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                category_id: categoryId,
                details: input.value
            })
        })
        .then(response => response.json())
        .then(data => {
            // ซ่อนสถานะการบันทึก
            statusElement.style.display = 'none';

            if (data.success) {
                // สร้าง toast notification
                const toast = document.createElement('div');
                toast.className = 'toast-notification show';
                toast.style.backgroundColor = '#28a745';
                toast.innerHTML = `
                <i class='bx bx-check' style='margin-right:8px; font-size:20px;'></i> 
                บันทึกรายละเอียดเพิ่มเติมเรียบร้อยแล้ว
            `;

                document.getElementById('toast-container').appendChild(toast);

                // ซ่อนตัวเองหลังจาก 3 วินาที
                setTimeout(() => {
                    toast.classList.add('hide');
                    setTimeout(() => {
                        toast.remove();
                    }, 300);
                }, 3000);
            } else {
                // กรณีเกิดข้อผิดพลาด
                const toast = document.createElement('div');
                toast.className = 'toast-notification show error';
                toast.style.backgroundColor = '#dc3545';
                toast.innerHTML = `
                <i class='bx bx-error-circle' style='margin-right:8px; font-size:20px;'></i> 
                ${data.message || 'เกิดข้อผิดพลาดในการบันทึกข้อมูล'}
            `;

                document.getElementById('toast-container').appendChild(toast);

                setTimeout(() => {
                    toast.classList.add('hide');
                    setTimeout(() => {
                        toast.remove();
                    }, 300);
                }, 3000);

                // คืนค่าเดิม
                input.value = data.original_details || '';
            }
        })
        .catch(error => {
            // ซ่อนสถานะการบันทึก
            statusElement.style.display = 'none';

            // แสดงข้อความแจ้งเตือนข้อผิดพลาด
            const toast = document.createElement('div');
            toast.className = 'toast-notification show error';
            toast.style.backgroundColor = '#dc3545';
            toast.innerHTML = `
            <i class='bx bx-error-circle' style='margin-right:8px; font-size:20px;'></i> 
            เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์
        `;

            document.getElementById('toast-container').appendChild(toast);

            setTimeout(() => {
                toast.classList.add('hide');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000);

            console.error('Error:', error);
        });
}

// เพิ่ม Event Listener สำหรับการกด Enter บน input fields เมื่อโหลดหน้า
document.addEventListener('DOMContentLoaded', function() {
    // เพิ่ม event listener สำหรับทุก input ของรายละเอียดการบูรณาการ
    const detailsInputs = document.querySelectorAll('.additional-info');
    detailsInputs.forEach(input => {
        input.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault(); // ป้องกันการขึ้นบรรทัดใหม่หรือการ submit form
                this.blur(); // ทำให้ input เสียโฟกัส เพื่อให้ onblur ทำงาน
            }
        });
    });
});
</script>

<script>
// ฟังก์ชันสำหรับบันทึกข้อมูลหลักการและเหตุผล
function saveRationaleData(textarea, projectId) {
    // แสดงสถานะกำลังบันทึก
    const statusElement = document.getElementById('rationaleStatus');
    statusElement.style.display = 'block';

    // ค่าที่จะบันทึก
    const rationaleText = textarea.value;

    // สร้าง toast notification container ถ้ายังไม่มี
    if (!document.getElementById('toast-container')) {
        const toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.style.position = 'fixed';
        toastContainer.style.bottom = '20px';
        toastContainer.style.right = '20px';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }

    // ส่งข้อมูลไปยัง server
    fetch(`/projects/${projectId}/update-field`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                field: 'Principles_Reasons',
                value: rationaleText
            })
        })
        .then(response => response.json())
        .then(data => {
            // ซ่อนสถานะการบันทึก
            statusElement.style.display = 'none';

            if (data.success) {
                // สร้าง toast notification
                const toast = document.createElement('div');
                toast.className = 'toast-notification show';
                toast.style.backgroundColor = '#28a745';
                toast.innerHTML = `
                <i class='bx bx-check' style='margin-right:8px; font-size:20px;'></i> 
                บันทึกหลักการและเหตุผลเรียบร้อยแล้ว
            `;

                document.getElementById('toast-container').appendChild(toast);

                // ซ่อนตัวเองหลังจาก 3 วินาที
                setTimeout(() => {
                    toast.classList.add('hide');
                    setTimeout(() => {
                        toast.remove();
                    }, 300);
                }, 3000);
            } else {
                // กรณีเกิดข้อผิดพลาด
                const toast = document.createElement('div');
                toast.className = 'toast-notification show error';
                toast.style.backgroundColor = '#dc3545';
                toast.innerHTML = `
                <i class='bx bx-error-circle' style='margin-right:8px; font-size:20px;'></i> 
                ${data.message || 'เกิดข้อผิดพลาดในการบันทึกข้อมูล'}
            `;

                document.getElementById('toast-container').appendChild(toast);

                setTimeout(() => {
                    toast.classList.add('hide');
                    setTimeout(() => {
                        toast.remove();
                    }, 300);
                }, 3000);
            }
        })
        .catch(error => {
            // ซ่อนสถานะการบันทึก
            statusElement.style.display = 'none';

            // แสดงข้อความแจ้งเตือนข้อผิดพลาด
            const toast = document.createElement('div');
            toast.className = 'toast-notification show error';
            toast.style.backgroundColor = '#dc3545';
            toast.innerHTML = `
            <i class='bx bx-error-circle' style='margin-right:8px; font-size:20px;'></i> 
            เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์
        `;

            document.getElementById('toast-container').appendChild(toast);

            setTimeout(() => {
                toast.classList.add('hide');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000);

            console.error('Error:', error);
        });
}

// เพิ่มเสริม event listener สำหรับการ auto-save เมื่อมีการพิมพ์แล้วหยุดชั่วขณะ
document.addEventListener('DOMContentLoaded', function() {
    const rationaleTextarea = document.getElementById('Principles_Reasons');
    if (rationaleTextarea) {
        let typingTimer;
        const doneTypingInterval = 3000; // 3 วินาทีหลังจากหยุดพิมพ์

        rationaleTextarea.addEventListener('keyup', function() {
            clearTimeout(typingTimer);
            if (rationaleTextarea.value) {
                typingTimer = setTimeout(function() {
                    saveRationaleData(rationaleTextarea, '{{ $project->Id_Project }}');
                }, doneTypingInterval);
            }
        });

        // ยกเลิก timer เมื่อเริ่มพิมพ์ใหม่
        rationaleTextarea.addEventListener('keydown', function() {
            clearTimeout(typingTimer);
        });
    }
});
</script>


<!-- <script>
document.addEventListener('DOMContentLoaded', function() {
    // ปุ่มสลับการกรอกวัตถุประสงค์ด้วยตนเอง
    document.getElementById('toggleObjectiveInput').addEventListener('click', function() {
        const selectElement = document.getElementById('objective_select');
        const textareaElement = document.getElementById('objective_manual');

        if (textareaElement.style.display === 'none') {
            // แสดง textarea และซ่อน select
            textareaElement.style.display = 'block';
            selectElement.disabled = true;
            this.innerHTML = '<i class="bx bx-x"></i> ยกเลิกการกรอกด้วยตนเอง';
        } else {
            // ซ่อน textarea และแสดง select
            textareaElement.style.display = 'none';
            selectElement.disabled = false;
            this.innerHTML = '<i class="bx bx-edit"></i> กรอกวัตถุประสงค์ด้วยตนเอง';
        }
    });

    // คลิกปุ่มเพิ่มวัตถุประสงค์
    document.getElementById('addObjectiveBtn').addEventListener('click', function() {
        let objectiveValue;

        // รับค่าวัตถุประสงค์
        const objectiveTextarea = document.getElementById('objective_manual');
        const objectiveSelect = document.getElementById('objective_select');

        if (objectiveTextarea.style.display !== 'none' && objectiveTextarea.value.trim() !== '') {
            objectiveValue = objectiveTextarea.value.trim();
        } else if (!objectiveSelect.disabled && objectiveSelect.selectedIndex > 0) {
            objectiveValue = objectiveSelect.options[objectiveSelect.selectedIndex].text;
        } else {
            alert('กรุณาเลือกหรือกรอกวัตถุประสงค์โครงการ');
            return;
        }

        // สร้าง ID ชั่วคราว
        const tempId = 'new_' + Date.now();
        const projectId = document.getElementById('objectivesContainer').getAttribute(
            'data-project-id');

        // ส่งข้อมูลไป server เพื่อบันทึกทันที
        saveNewObjective(projectId, objectiveValue, tempId);
    });

    // เพิ่ม event listener สำหรับปุ่มลบที่มีอยู่แล้ว
    document.querySelectorAll('.delete-objective').forEach(button => {
        button.addEventListener('click', function() {
            const objectiveId = this.getAttribute('data-id');
            const projectId = document.getElementById('objectivesContainer').getAttribute(
                'data-project-id');

            if (confirm('ต้องการลบวัตถุประสงค์นี้ใช่หรือไม่?')) {
                deleteObjective(projectId, objectiveId, this);
            }
        });
    });

    // สร้าง toast container หากยังไม่มี
    if (!document.getElementById('toast-container')) {
        const toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.style.position = 'fixed';
        toastContainer.style.bottom = '20px';
        toastContainer.style.right = '20px';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }
});

// ฟังก์ชันสำหรับการเพิ่มวัตถุประสงค์ใหม่
function saveNewObjective(projectId, objectiveValue, tempId) {
    // แสดงสถานะกำลังบันทึก
    showToast('กำลังบันทึกวัตถุประสงค์...', 'info');

    // ส่งข้อมูลไป server เพื่อบันทึก
    fetch(`/projects/${projectId}/add-objective`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                description: objectiveValue,
                type: document.getElementById('objective_manual').style.display !== 'none' ? 'manual' :
                    'selected'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // สร้าง element จาก template
                const template = document.getElementById('objectiveItemTemplate').innerHTML;
                let newObjectiveHtml = template
                    .replace(/__ID__/g, data.objective_id)
                    .replace(/__OBJECTIVE__/g, objectiveValue)
                    .replace(/__OBJECTIVE_VALUE__/g, objectiveValue);

                // เพิ่ม element ใหม่
                document.getElementById('objectivesContainer').insertAdjacentHTML('beforeend', newObjectiveHtml);

                // เพิ่ม event listener สำหรับปุ่มลบ
                const deleteButton = document.querySelector(`.delete-objective[data-id="${data.objective_id}"]`);
                if (deleteButton) {
                    deleteButton.addEventListener('click', function() {
                        if (confirm('ต้องการลบวัตถุประสงค์นี้ใช่หรือไม่?')) {
                            deleteObjective(projectId, data.objective_id, this);
                        }
                    });
                }

                // เพิ่ม event listener สำหรับการ edit inline
                const editableElement = document.querySelector(
                    `.editable-objective[data-objective-id="${data.objective_id}"]`);
                if (editableElement) {
                    editableElement.addEventListener('click', function() {
                        makeEditable(this);
                    });
                }

                // แสดง toast message
                showToast('เพิ่มวัตถุประสงค์เรียบร้อยแล้ว', 'success');

                // รีเซ็ตฟอร์ม
                resetObjectiveForm();
            } else {
                showToast(data.message || 'เกิดข้อผิดพลาดในการบันทึกข้อมูล', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์', 'error');
        });
}

// ฟังก์ชันสำหรับการลบวัตถุประสงค์
function deleteObjective(projectId, objectiveId, buttonElement) {
    // แสดงสถานะกำลังลบ
    showToast('กำลังลบวัตถุประสงค์...', 'info');

    fetch(`/projects/${projectId}/delete-objective/${objectiveId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // ลบ element จาก DOM
                const objectiveItem = buttonElement.closest('.objective-item');
                if (objectiveItem) {
                    objectiveItem.remove();
                }

                // แสดง toast message
                showToast('ลบวัตถุประสงค์เรียบร้อยแล้ว', 'warning');
            } else {
                showToast(data.message || 'เกิดข้อผิดพลาดในการลบข้อมูล', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์', 'error');
        });
}

// ฟังก์ชันทำให้สามารถแก้ไขได้แบบ inline
function makeEditable(element) {
    // ตรวจสอบว่า element นี้กำลังอยู่ในโหมดแก้ไขหรือไม่
    if (element.getAttribute('contenteditable') === 'true') {
        return;
    }

    // บันทึกข้อความเดิมไว้ใช้กรณียกเลิกการแก้ไข
    element.dataset.originalText = element.innerText;

    // ทำให้ element เป็น contenteditable
    element.setAttribute('contenteditable', 'true');
    element.classList.add('editing');

    // เพิ่ม visual indicator ว่ากำลังแก้ไข
    element.style.backgroundColor = '#f8f9fa';
    element.style.border = '1px dashed #007bff';
    element.style.padding = '5px';

    // โฟกัสที่ element
    element.focus();

    // จัดการการกด Enter เพื่อยืนยันการแก้ไข
    element.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            this.blur();
        } else if (e.key === 'Escape') {
            e.preventDefault();
            cancelEdit(this);
        }
    });

    // จัดการการคลิกนอก element เพื่อยืนยันการแก้ไข
    element.addEventListener('blur', function() {
        saveEditedObjective(this);
    }, {
        once: true
    });
}

// ฟังก์ชันสำหรับยกเลิกการแก้ไข
function cancelEdit(element) {
    element.innerText = element.dataset.originalText;
    element.removeAttribute('contenteditable');
    element.classList.remove('editing');
    element.style.backgroundColor = '';
    element.style.border = '';
    element.style.padding = '';
}

// ฟังก์ชันสำหรับบันทึกการแก้ไขวัตถุประสงค์
function saveEditedObjective(element) {
    // ยกเลิกโหมด contenteditable
    element.removeAttribute('contenteditable');
    element.classList.remove('editing');
    element.style.backgroundColor = '';
    element.style.border = '';
    element.style.padding = '';

    const projectId = element.dataset.projectId;
    const objectiveId = element.dataset.objectiveId;
    const newValue = element.innerText.trim();
    const originalText = element.dataset.originalText;

    // ถ้าข้อความไม่เปลี่ยนแปลง ไม่ต้องส่งไป server
    if (newValue === originalText) {
        return;
    }

    // เพิ่มคลาส saving เพื่อแสดงว่ากำลังบันทึก
    element.classList.add('saving');

    // แสดงสถานะกำลังบันทึก
    showToast('กำลังบันทึกการแก้ไข...', 'info');

    // ส่งข้อมูลไปบันทึกที่ server
    fetch(`/projects/${projectId}/update-objective`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                objective_id: objectiveId,
                description: newValue
            })
        })
        .then(response => response.json())
        .then(data => {
            // ลบคลาส saving
            element.classList.remove('saving');

            if (data.success) {
                // อัปเดตค่าใน hidden input
                const hiddenInput = element.parentNode.querySelector('input[type="hidden"]');
                if (hiddenInput) {
                    hiddenInput.value = newValue;
                }

                // อัปเดตข้อความต้นฉบับ
                element.dataset.originalText = newValue;

                // แสดง toast notification สำเร็จ
                showToast('บันทึกการแก้ไขเรียบร้อยแล้ว', 'success');
            } else {
                // คืนค่าเป็นข้อความเดิม
                element.innerText = originalText;
                showToast(data.message || 'เกิดข้อผิดพลาดในการบันทึกข้อมูล', 'error');
            }
        })
        .catch(error => {
            // ลบคลาส saving
            element.classList.remove('saving');

            // คืนค่าเป็นข้อความเดิม
            element.innerText = originalText;

            console.error('Error:', error);
            showToast('เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์', 'error');
        });
}

// ฟังก์ชันรีเซ็ตฟอร์ม
function resetObjectiveForm() {
    const objectiveSelect = document.getElementById('objective_select');
    const objectiveManual = document.getElementById('objective_manual');

    objectiveSelect.selectedIndex = 0;
    objectiveManual.value = '';

    if (objectiveManual.style.display !== 'none') {
        // ถ้า textarea กำลังแสดงอยู่ ให้คลิกปุ่มสลับเพื่อกลับไปใช้ select
        document.getElementById('toggleObjectiveInput').click();
    }
}

// ฟังก์ชันแสดง toast notification
function showToast(message, type = 'success') {
    // สร้าง toast notification container ถ้ายังไม่มี
    if (!document.getElementById('toast-container')) {
        const toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.style.position = 'fixed';
        toastContainer.style.bottom = '20px';
        toastContainer.style.right = '20px';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }

    // สร้าง toast element
    const toast = document.createElement('div');
    toast.className = 'toast-notification show';

    // กำหนดสีตาม type
    if (type === 'success') {
        toast.style.backgroundColor = '#28a745';
        toast.innerHTML = `<i class='bx bx-check' style='margin-right:8px; font-size:20px;'></i> ${message}`;
    } else if (type === 'warning') {
        toast.style.backgroundColor = '#ffc107';
        toast.style.color = '#212529';
        toast.innerHTML = `<i class='bx bx-info-circle' style='margin-right:8px; font-size:20px;'></i> ${message}`;
    } else if (type === 'error') {
        toast.style.backgroundColor = '#dc3545';
        toast.innerHTML = `<i class='bx bx-error-circle' style='margin-right:8px; font-size:20px;'></i> ${message}`;
    } else if (type === 'info') {
        toast.style.backgroundColor = '#17a2b8';
        toast.innerHTML =
            `<i class='bx bx-loader-alt bx-spin' style='margin-right:8px; font-size:20px;'></i> ${message}`;
    }

    // เพิ่ม toast เข้าไปใน container
    document.getElementById('toast-container').appendChild(toast);

    // ซ่อน toast หลังจาก 3 วินาที
    setTimeout(() => {
        toast.classList.add('hide');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 3000);
}
</script> -->
@endsection