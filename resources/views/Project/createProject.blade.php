@extends('navbar.app')

<hade>
    <link rel="stylesheet" href="{{ asset('css/createProject.css') }}">
</hade>

@section('content')
<div class="container py-4">
    <div class="card">
        <h3 class="card-header">กรอกข้อมูลโครงการ</h3>

        <div class="card-body">
            <form action="{{ route('createProject', ['Strategic_Id' => $strategics->Id_Strategic]) }}" method="POST"
                class="needs-validation" novalidate>
                @csrf

                <!-- ชื่อโครงการ -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            1. ชื่อโครงการ
                            <i class='bx bx-chevron-up' id="toggleIcon" style="cursor: pointer; font-size: 1.5em;"
                                onclick="toggleProjectDetails()"></i>
                        </h4>
                    </div>
                    <div id="projectDetails" class="toggle-content">
                        <div class="form-group">
                            <label for="Name_Project" class="form-label">สร้างชื่อโครงการ</label>
                            <input type="text" class="form-control @error('Name_Project') is-invalid @enderror"
                                id="Name_Project" name="Name_Project" placeholder="กรอกชื่อโครงการ" required>
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

                <!-- ลักษณะโครงการ -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            2. ลักษณะโครงการ
                            <i class='bx bx-chevron-up' id="toggleIconProjectType"
                                style="cursor: pointer; font-size: 1.5em;" onclick="toggleProjectTypeDetails()"></i>
                        </h4>
                    </div>
                    <div class="form-group-radio" style="display: none;">
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
                        <input type="text" id="textbox-projectType-2" class="hidden form-control"
                            data-group="projectType" placeholder="กรอกชื่อโครงการเดิม">
                    </div>
                </div>

                <!-- ผู้รับผิดชอบโครงการ -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            3. ผู้รับผิดชอบโครงการ
                            <i class='bx bx-chevron-up' id="toggleIconResponsible"
                                style="cursor: pointer; font-size: 1.5em;" onclick="toggleResponsibleDetails()"></i>
                        </h4>
                    </div>
                    <div id="responsibleDetails" style="display: none;">
                        <div class="form-group">
                            <label for="employee_id" class="form-label">เลือกผู้รับผิดชอบ</label>
                            <select class="form-select @error('employee_id') is-invalid @enderror" id="employee_id"
                                name="employee_id">
                                <option value="" selected disabled>เลือกผู้รับผิดชอบ</option>
                                @foreach($employees as $employee)
                                <option value="{{ $employee->Id_Employee }}">
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
                            <i class='bx bx-chevron-up' id="toggleIconStrategic"
                                style="cursor: pointer; font-size: 1.5em;" onclick="toggleStrategicDetails()"></i>
                        </h4>
                    </div>
                    <div id="strategicDetails" style="display: none;">
                        <div id="platform-container">
                            <div class="platform-card">
                                <div class="card-header">
                                    <h3 class="card-title">แพลตฟอร์มที่ 1</h3>
                                    <button type="button" class="btn btn-danger" onclick="removePlatform(this)"
                                        style="display: none;">
                                        <i class='bx bx-trash'></i> ลบแพลตฟอร์ม
                                    </button>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">ชื่อแพลตฟอร์ม</label>
                                    <input type="text" name="platforms[0][name]" class="form-control"
                                        placeholder="กรุณากรอกชื่อแพลตฟอร์ม" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">โปรแกรม</label>
                                    <input type="text" name="platforms[0][program]" class="form-control"
                                        placeholder="กรุณากรอกชื่อโปรแกรม" required>
                                </div>

                                <div class="form-group kpi-container">
                                    <div class="kpi-header">
                                        <label class="form-label">KPI</label>
                                        <button type="button" class="btn-add" onclick="addKpi(this)"
                                            style="font-size: 1.25rem">+</button>
                                    </div>
                                    <div class="kpi-group">
                                        <div class="input-group">
                                            <input type="text" name="platforms[0][kpis][]" class="form-control"
                                                placeholder="กรุณากรอก KPI" required>
                                            <button type="button" class="btn btn-danger" onclick="removeKpi(this)"
                                                style="display: none;">
                                                <i class='bx bx-trash'></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn-add" onclick="addPlatform()">เพิ่มแพลตฟอร์ม</button>
                    </div>
                </div>

                <!-- ความสอดคล้องกับยุทธศาสตร์ส่วนงาน -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            5. ความสอดคล้องกับยุทธศาสตร์ส่วนงาน
                            <i class='bx bx-chevron-up' id="toggleIconDepartmentStrategic"
                                style="cursor: pointer; font-size: 1.5em;"
                                onclick="toggleDepartmentStrategicDetails()"></i>
                        </h4>
                    </div>
                    <div id="departmentStrategicDetails" style="display: none;">
                        <div class="mb-3 col-md-6">
                            <div class="mb-3">
                                <label for="Name_Strategic_Plan" class="form-label">ชื่อแผนยุทธศาสตร์</label>
                                <input type="text" class="form-control" id="Name_Strategic_Plan"
                                    name="Name_Strategic_Plan" value="{{ $nameStrategicPlan }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="Name_Strategy" class="form-label">กลยุทธ์</label>
                                <select class="form-select @error('Name_Strategy') is-invalid @enderror"
                                    name="Name_Strategy" id="Name_Strategy" required>
                                    <option value="" selected disabled>เลือกกลยุทธ์</option>
                                    @if($strategies->isNotEmpty())
                                    @foreach($strategies as $strategy)
                                    <option value="{{ $strategy->Name_Strategy }}">{{ $strategy->Name_Strategy }}
                                    </option>
                                    @endforeach
                                    @else
                                    <option value="" disabled>ไม่มีกลยุทธ์ที่เกี่ยวข้อง</option>
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
                            <i class='bx bx-chevron-up' id="toggleIconSDGs" style="cursor: pointer; font-size: 1.5em;"
                                onclick="toggleSDGsDetails()"></i>
                        </h4>
                    </div>
                    <div id="sdgsDetails" style="display: none;">
                        <div class="sdgs-grid">
                            @foreach ($sdgs as $sdg)
                            <div class="form-group-sdgs">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="sdgs[]"
                                        value="{{ $sdg->id_SDGs }}" id="sdg_{{ $sdg->id_SDGs }}">
                                    <label class="form-check-label"
                                        for="sdg_{{ $sdg->id_SDGs }}">{{ $sdg->Name_SDGs }}</label>
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
                            <i class='bx bx-chevron-up' id="toggleIconIntegration"
                                style="cursor: pointer; font-size: 1.5em;" onclick="toggleIntegrationDetails()"></i>
                        </h4>
                    </div>
                    <div id="integrationDetails" style="display: none;">
                        <div class="dropdown-container">
                            <div class="dropdown-options">
                                @foreach ($integrationCategories as $category)
                                <div class="option-item">
                                    <label>
                                        <input type="checkbox"
                                            name="integrationCategories[{{ $category->Id_Integration_Category }}][checked]"
                                            onchange="toggleSelectTextbox(this)">
                                        {{ $category->Name_Integration_Category }}
                                    </label>
                                    @if ($category->Name_Integration_Category !== 'การบริการสารสนเทศ')
                                    <input type="text" class="additional-info"
                                        name="integrationCategories[{{ $category->Id_Integration_Category }}][details]"
                                        placeholder="ระบุข้อมูลเพิ่มเติม" disabled style="width: 100%;">
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
                            <i class='bx bx-chevron-up' id="toggleIconRationale"
                                style="cursor: pointer; font-size: 1.5em;" onclick="toggleRationaleDetails()"></i>
                        </h4>
                    </div>
                    <div id="rationaleDetails" style="display: none;">
                        <div class="form-group">
                            <textarea class="form-control @error('Principles_Reasons') is-invalid @enderror" rows="15"
                                name="Principles_Reasons" placeholder="กรอกข้อมูล"></textarea>
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
                            <i class='bx bx-chevron-up' id="toggleIconObjective"
                                style="cursor: pointer; font-size: 1.5em;" onclick="toggleObjectiveDetails()"></i>
                        </h4>
                    </div>
                    <div id="objectiveDetails" style="display: none;">
                        <div class="form-group">
                            <textarea class="form-control @error('Objective_Project') is-invalid @enderror"
                                id="Objective_Project" name="Objective_Project" rows="15" placeholder="กรอกข้อมูล"
                                required></textarea>
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
                            <i class='bx bx-chevron-up' id="toggleIconTargetGroup"
                                style="cursor: pointer; font-size: 1.5em;" onclick="toggleTargetGroupDetails()"></i>
                        </h4>
                    </div>
                    <div id="targetGroupDetails" style="display: none;">
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
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="targetAreaCheckbox"
                                    onchange="toggleTargetAreaDetails()">
                                <label class="form-check-label"
                                    for="targetAreaCheckbox">เลือกพื้นที่/ชุมชนเป้าหมาย</label>
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
                            <i class='bx bx-chevron-up' id="toggleIconLocation"
                                style="cursor: pointer; font-size: 1.5em;" onclick="toggleLocationDetails()"></i>
                        </h4>
                    </div>
                    <div id="locationDetails" style="display: none;">
                        <div id="locationContainer">
                            <div class="form-group location-item">
                                <input type="text" class="form-control small-input" name="location[]"
                                    placeholder="กรอกสถานที่">
                                <button type="button" class="btn btn-danger btn-sm remove-location"
                                    style="display: none;">
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
                            <i class='bx bx-chevron-up' id="toggleIconIndicators"
                                style="cursor: pointer; font-size: 1.5em;" onclick="toggleIndicatorsDetails()"></i>
                        </h4>
                    </div>
                    <div id="indicatorsDetails" style="display: none;">
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
                                <div class="form-group mt-2">
                                    <label>ข้อที่ 1</label>
                                    <input type="text" class="form-control" name="quantitative[]"
                                        placeholder="เพิ่มรายการ">
                                    <button type="button" class="btn btn-danger btn-sm remove-quantitative-item mt-2"
                                        style="display: none;">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="btn-addlist" onclick="addQuantitativeItem()">
                                <i class='bx bx-plus-circle'></i>เพิ่มรายการ
                            </button>
                        </div>
                        <div id="qualitative-inputs" class="goal-inputs" style="display: none;">
                            <h6>เชิงคุณภาพ</h6>
                            <div id="qualitative-items" class="mt-3">
                                <div class="form-group mt-2">
                                    <label>ข้อที่ 1</label>
                                    <input type="text" class="form-control" name="qualitative[]"
                                        placeholder="เพิ่มข้อความ">
                                    <button type="button" class="btn btn-danger btn-sm remove-qualitative-item mt-2"
                                        style="display: none;">
                                        <i class='bx bx-trash'></i>
                                    </button>
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
                            <i class='bx bx-chevron-up' id="toggleIconProjectDuration"
                                style="cursor: pointer; font-size: 1.5em;" onclick="toggleProjectDurationDetails()"></i>
                        </h4>
                    </div>
                    <div id="projectDurationDetails" style="display: none;">
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
                            14. ขั้นตอนและแผนการดำเนินงาน
                            <i class='bx bx-chevron-up' id="toggleIconPlan" style="cursor: pointer; font-size: 1.5em;"
                                onclick="togglePlanDetails()"></i>
                        </h4>
                    </div>
                    <div id="planDetails" style="display: none;">
                        <div class="form-group-radio mb-4">
                            <input type="radio" name="planType" value="1" id="shortTermProject" checked>
                            <label for="shortTermProject">โครงการระยะสั้น</label>
                            &nbsp;&nbsp;
                            <input type="radio" name="planType" value="2" id="longTermProject">
                            <label for="longTermProject">โครงการระยะยาว</label>
                        </div>

                        <!-- วิธีการดำเนินงาน -->
                        <div id="textbox-planType-1" data-group="planType">
                            <div class="method-form">
                                <div class="form-label">วิธีการดำเนินงาน</div>
                                <div id="methodContainer" class="method-items">
                                    <div class="form-group mt-2">
                                        <input type="text" class="form-control" name="methods[]"
                                            placeholder="เพิ่มรายการ">
                                        <button type="button" class="btn btn-danger btn-sm remove-method mt-2"
                                            style="display: none;">
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
                                    @foreach(['Plan' => 'ขั้นวางแผนงาน',
                                    'Do' => 'ขั้นดำเนินการ',
                                    'Check' => 'ขั้นสรุปและประเมินผล',
                                    'Act' => 'ขั้นปรับปรุงตามผลการประเมิน'] as $key => $step)
                                    <tr>
                                        <td class="PDCA">
                                            <div class="plan-text">{{ $step }}({{ $key }})</div>
                                            <textarea class="plan-textarea auto-expand"
                                                name="pdca_{{ strtolower($key) }}"
                                                placeholder="เพิ่มรายละเอียด"></textarea>
                                        </td>
                                        @for($i = 1; $i <= 12; $i++) <td class="checkbox-container">
                                            <input type="checkbox" name="pdca_{{ strtolower($key) }}_month[]"
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
                            <i class='bx bx-chevron-up' id="toggleIconBudget" style="cursor: pointer; font-size: 1.5em;"
                                onclick="toggleBudgetDetails()"></i>
                        </h4>
                    </div>
                    <div id="budgetDetails" style="display: none;">
                        <div class="form-group-radio">
                            <label>ประเภทโครงการ</label>
                            <div class="radio-group">
                                <input type="radio" name="Status_Budget" value="N" id="non_income"
                                    onchange="toggleIncomeForm(this)" checked>
                                <label for="non_income">ไม่แสวงหารายได้</label>

                                <input type="radio" name="Status_Budget" value="Y" id="income_seeking"
                                    onchange="toggleIncomeForm(this)">
                                <label for="income_seeking">แสวงหารายได้</label>
                            </div>
                        </div>

                        <div id="incomeForm" class="income-form" style="display: none;">
                            <div class="form-group">
                                <label>แหล่งงบประมาณ</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="budget_source"
                                        placeholder="เงินรายได้">
                                    <input type="number" class="form-control" name="budget_amount" placeholder="จำนวน">
                                    <input type="text" class="form-control" name="budget_unit"
                                        placeholder="หน่วย เช่น บาท">
                                </div>
                            </div>

                            <div class="form-group mt-3">
                                <label>แผนงาน</label>
                                <div id="funcAreaContainer">
                                    <button type="button" class="btn-addlist"
                                        onclick="addField('funcAreaContainer', 'functionalArea[]')">
                                        <i class='bx bx-plus-circle'></i>เพิ่มรายการ
                                    </button>
                                </div>
                            </div>

                            <div class="form-group mt-3">
                                <label>หน่วยงาน</label>
                                <input type="text" class="form-control" name="department" placeholder="หน่วยงาน">
                            </div>

                            <div class="form-group mt-3">
                                <label>ศูนย์ต้นทุน</label>
                                <input type="text" class="form-control" name="cost_center" placeholder="ศูนย์ต้นทุน">
                            </div>

                            <!-- หมวดรายจ่าย -->
                            <div class="expense-category mt-4">
                                <div class="section-subheader">
                                    <h5>หมวดรายจ่าย</h5>
                                </div>
                                <div class="form-group">
                                    <select class="form-select" name="expenseCategory" id="expenseCategory" required>
                                        <option value="" selected disabled>เลือกหมวดรายจ่าย</option>
                                        <option value="1">ค่าตอบแทน</option>
                                        <option value="2">งบบุคลากร</option>
                                    </select>
                                </div>

                                <div id="expenseContentBox" class="expense-content mt-3" style="display: none;">
                                    <div id="selectedExpenses" class="selected-expenses"></div>
                                    <button type="button" id="addExpenseBtn" class="btn-addlist">
                                        <i class='bx bx-plus-circle'></i>เพิ่มรายการ
                                    </button>
                                </div>

                                <div class="form-group mt-4">
                                    <label>รวมค่าใช้จ่าย</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="total_amount"
                                            placeholder="จำนวน">
                                        <input type="text" class="form-control" name="total_unit" placeholder="หน่วย">
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
                            <i class='bx bx-chevron-up' id="toggleIconOutput" style="cursor: pointer; font-size: 1.5em;"
                                onclick="toggleOutputDetails()"></i>
                        </h4>
                    </div>
                    <div id="outputDetails" style="display: none;">
                        <div id="outputContainer" class="dynamic-container">
                            <div class="form-group mt-2">
                                <input type="text" class="form-control" name="output[]" placeholder="เพิ่มรายการ">
                                <button type="button" class="btn btn-danger btn-sm remove-field mt-2"
                                    style="display: none;">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" class="btn-addlist" onclick="addField('outputContainer', 'output[]')">
                            <i class='bx bx-plus-circle'></i>เพิ่มรายการ
                        </button>
                    </div>
                </div>

                <!-- Outcome -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            17. เป้าหมายเชิงผลลัพธ์ (Outcome)
                            <i class='bx bx-chevron-up' id="toggleIconOutcome"
                                style="cursor: pointer; font-size: 1.5em;" onclick="toggleOutcomeDetails()"></i>
                        </h4>
                    </div>
                    <div id="outcomeDetails" style="display: none;">
                        <div id="outcomeContainer" class="dynamic-container">
                            <div class="form-group mt-2">
                                <input type="text" class="form-control" name="outcome[]" placeholder="เพิ่มรายการ">
                                <button type="button" class="btn btn-danger btn-sm remove-field mt-2"
                                    style="display: none;">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" class="btn-addlist" onclick="addField('outcomeContainer', 'outcome[]')">
                            <i class='bx bx-plus-circle'></i>เพิ่มรายการ
                        </button>
                    </div>
                </div>

                <!-- ผลที่คาดว่าจะได้รับ -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            18. ผลที่คาดว่าจะได้รับ
                            <i class='bx bx-chevron-up' id="toggleIconResult" style="cursor: pointer; font-size: 1.5em;"
                                onclick="toggleResultDetails()"></i>
                        </h4>
                    </div>
                    <div id="resultDetails" style="display: none;">
                        <div id="resultContainer" class="dynamic-container">
                            <div class="form-group mt-2">
                                <input type="text" class="form-control" name="result[]" placeholder="เพิ่มรายการ">
                                <button type="button" class="btn btn-danger btn-sm remove-field mt-2"
                                    style="display: none;">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" class="btn-addlist" onclick="addField('resultContainer', 'result[]')">
                            <i class='bx bx-plus-circle'></i>เพิ่มรายการ
                        </button>
                    </div>
                </div>

                <!-- เอกสารเพิ่มเติม -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>19. เอกสารเพิ่มเติม</h4>
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
            <input type="text" class="form-control" name="${fieldName}" placeholder="กรอกชื่อโครงการย่อย" required>
            <button type="button" class="btn btn-danger" onclick="removeField(this)">
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

    window.toggleProjectDetails = function() {
        const projectDetails = document.getElementById('projectDetails');
        const toggleIcon = document.getElementById('toggleIcon');

        if (projectDetails.classList.contains('show')) {
            projectDetails.classList.remove('show');
            toggleIcon.classList.remove('bx-chevron-down');
            toggleIcon.classList.add('bx-chevron-up');
        } else {
            projectDetails.classList.add('show');
            toggleIcon.classList.remove('bx-chevron-up');
            toggleIcon.classList.add('bx-chevron-down');
        }
    };

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

    window.toggleProjectTypeDetails = function() {
        const projectTypeDetails = document.querySelector('.form-group-radio');
        const toggleIcon = document.getElementById('toggleIconProjectType');

        if (projectTypeDetails.style.display === 'none' || projectTypeDetails.style.display === '') {
            projectTypeDetails.style.display = 'block';
            toggleIcon.classList.remove('bx-chevron-up');
            toggleIcon.classList.add('bx-chevron-down');
        } else {
            projectTypeDetails.style.display = 'none';
            toggleIcon.classList.remove('bx-chevron-down');
            toggleIcon.classList.add('bx-chevron-up');
        }
    };

    // ============ ผู้รับผิดชอบโครงการ============
    window.toggleResponsibleDetails = function() {
        const responsibleDetails = document.getElementById('responsibleDetails');
        const toggleIcon = document.getElementById('toggleIconResponsible');

        if (responsibleDetails.style.display === 'none' || responsibleDetails.style.display === '') {
            responsibleDetails.style.display = 'block';
            toggleIcon.classList.remove('bx-chevron-up');
            toggleIcon.classList.add('bx-chevron-down');
        } else {
            responsibleDetails.style.display = 'none';
            toggleIcon.classList.remove('bx-chevron-down');
            toggleIcon.classList.add('bx-chevron-up');
        }
    };

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

        kpiContainer.appendChild(kpiGroup);
    }

    window.removeKpi = function(btn) {
        const kpiGroup = btn.closest('.kpi-group');
        const kpiContainer = kpiGroup.closest('.kpi-container');

        if (kpiContainer.querySelectorAll('.kpi-group').length > 1) {
            kpiGroup.remove();
        }
    }

    window.toggleStrategicDetails = function() {
        const strategicDetails = document.getElementById('strategicDetails');
        const toggleIcon = document.getElementById('toggleIconStrategic');

        if (strategicDetails.style.display === 'none' || strategicDetails.style.display === '') {
            strategicDetails.style.display = 'block';
            toggleIcon.classList.remove('bx-chevron-up');
            toggleIcon.classList.add('bx-chevron-down');
        } else {
            strategicDetails.style.display = 'none';
            toggleIcon.classList.remove('bx-chevron-down');
            toggleIcon.classList.add('bx-chevron-up');
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
    window.toggleDepartmentStrategicDetails = function() {
        const departmentStrategicDetails = document.getElementById('departmentStrategicDetails');
        const toggleIcon = document.getElementById('toggleIconDepartmentStrategic');

        if (departmentStrategicDetails.style.display === 'none' || departmentStrategicDetails.style
            .display === '') {
            departmentStrategicDetails.style.display = 'block';
            toggleIcon.classList.remove('bx-chevron-up');
            toggleIcon.classList.add('bx-chevron-down');
        } else {
            departmentStrategicDetails.style.display = 'none';
            toggleIcon.classList.remove('bx-chevron-down');
            toggleIcon.classList.add('bx-chevron-up');
        }
    }

    // ============ ความสอดคล้องกับ (SDGs) ============
    window.toggleSDGsDetails = function() {
        const sdgsDetails = document.getElementById('sdgsDetails');
        const toggleIcon = document.getElementById('toggleIconSDGs');

        if (sdgsDetails.style.display === 'none' || sdgsDetails.style.display === '') {
            sdgsDetails.style.display = 'block';
            toggleIcon.classList.remove('bx-chevron-up');
            toggleIcon.classList.add('bx-chevron-down');
        } else {
            sdgsDetails.style.display = 'none';
            toggleIcon.classList.remove('bx-chevron-down');
            toggleIcon.classList.add('bx-chevron-up');
        }
    }

    // ============ การบูรณาการงานโครงการ ============
    window.toggleIntegrationDetails = function() {
        const integrationDetails = document.getElementById('integrationDetails');
        const toggleIcon = document.getElementById('toggleIconIntegration');

        if (integrationDetails.style.display === 'none' || integrationDetails.style.display === '') {
            integrationDetails.style.display = 'block';
            toggleIcon.classList.remove('bx-chevron-up');
            toggleIcon.classList.add('bx-chevron-down');
        } else {
            integrationDetails.style.display = 'none';
            toggleIcon.classList.remove('bx-chevron-down');
            toggleIcon.classList.add('bx-chevron-up');
        }
    }

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
    }

    // ============ หลักการและเหตุผล ============
    window.toggleRationaleDetails = function() {
        const rationaleDetails = document.getElementById('rationaleDetails');
        const toggleIcon = document.getElementById('toggleIconRationale');

        if (rationaleDetails.style.display === 'none' || rationaleDetails.style.display === '') {
            rationaleDetails.style.display = 'block';
            toggleIcon.classList.remove('bx-chevron-up');
            toggleIcon.classList.add('bx-chevron-down');
        } else {
            rationaleDetails.style.display = 'none';
            toggleIcon.classList.remove('bx-chevron-down');
            toggleIcon.classList.add('bx-chevron-up');
        }
    }

    // ============ วัตถุประสงค์โครงการ ============
    window.toggleObjectiveDetails = function() {
        const objectiveDetails = document.getElementById('objectiveDetails');
        const toggleIcon = document.getElementById('toggleIconObjective');

        if (objectiveDetails.style.display === 'none' || objectiveDetails.style.display === '') {
            objectiveDetails.style.display = 'block';
            toggleIcon.classList.remove('bx-chevron-up');
            toggleIcon.classList.add('bx-chevron-down');
        } else {
            objectiveDetails.style.display = 'none';
            toggleIcon.classList.remove('bx-chevron-down');
            toggleIcon.classList.add('bx-chevron-up');
        }
    }

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

    window.toggleTargetGroupDetails = function() {
        const targetGroupDetails = document.getElementById('targetGroupDetails');
        const toggleIcon = document.getElementById('toggleIconTargetGroup');

        if (targetGroupDetails.style.display === 'none' || targetGroupDetails.style.display === '') {
            targetGroupDetails.style.display = 'block';
            toggleIcon.classList.remove('bx-chevron-up');
            toggleIcon.classList.add('bx-chevron-down');
        } else {
            targetGroupDetails.style.display = 'none';
            toggleIcon.classList.remove('bx-chevron-down');
            toggleIcon.classList.add('bx-chevron-up');
        }
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
            <input type="text" class="form-control" name="quantitative[]" placeholder="เพิ่มรายการ">
            <button type="button" class="btn btn-danger btn-sm remove-quantitative-item mt-2">
                <i class='bx bx-trash'></i>
            </button>
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
            <input type="text" class="form-control" name="qualitative[]" placeholder="เพิ่มข้อความ">
            <button type="button" class="btn btn-danger btn-sm remove-qualitative-item mt-2">
                <i class='bx bx-trash'></i>
            </button>
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

    // Add method item for short term project
    const methodContainer = document.getElementById('methodContainer');
    const addMethodBtn = document.querySelector('.btn-addlist');

    window.addMethodItem = function() {
        const newMethod = document.createElement('div');
        newMethod.className = 'form-group mt-2';
        newMethod.innerHTML = `
            <input type="text" class="form-control" name="methods[]" placeholder="เพิ่มรายการ">
            <button type="button" class="btn btn-danger btn-sm remove-method mt-2">
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

    // Toggle plan details
    window.togglePlanDetails = function() {
        const planDetails = document.getElementById('planDetails');
        const toggleIcon = document.getElementById('toggleIconPlan');

        if (planDetails.style.display === 'none' || planDetails.style.display === '') {
            planDetails.style.display = 'block';
            toggleIcon.classList.remove('bx-chevron-up');
            toggleIcon.classList.add('bx-chevron-down');
        } else {
            planDetails.style.display = 'none';
            toggleIcon.classList.remove('bx-chevron-down');
            toggleIcon.classList.add('bx-chevron-up');
        }
    }

    // ============ แหล่งงบประมาณ ============
    // Toggle income form based on project type
    window.toggleIncomeForm = function(radio) {
        const incomeForm = document.getElementById('incomeForm');
        if (radio.value === 'Y') {
            incomeForm.style.display = 'block';
        } else {
            incomeForm.style.display = 'none';
        }
    }

    // Add new field for functional area
    window.addField = function(containerId, fieldName) {
        const container = document.getElementById(containerId);
        const newField = document.createElement('div');
        newField.className = 'form-group mt-2';
        newField.innerHTML = `
            <input type="text" class="form-control" name="${fieldName}" placeholder="เพิ่มรายการ">
            <button type="button" class="btn btn-danger btn-sm remove-field mt-2">
                <i class='bx bx-trash'></i>
            </button>
        `;
        container.appendChild(newField);
        updateFieldButtons(containerId);
    }

    // Remove field
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-field')) {
            e.target.closest('.form-group').remove();
        }
    });

    // Update visibility of remove buttons
    function updateFieldButtons(containerId) {
        const container = document.getElementById(containerId);
        const buttons = container.querySelectorAll('.remove-field');
        buttons.forEach(btn => {
            btn.style.display = buttons.length > 1 ? 'block' : 'none';
        });
    }

    // Toggle expense content box based on selected category
    const expenseCategory = document.getElementById('expenseCategory');
    const expenseContentBox = document.getElementById('expenseContentBox');

    expenseCategory.addEventListener('change', function() {
        if (expenseCategory.value) {
            expenseContentBox.style.display = 'block';
        } else {
            expenseContentBox.style.display = 'none';
        }
    });

    // Add new expense item
    document.getElementById('addExpenseBtn').addEventListener('click', function() {
        const selectedExpenses = document.getElementById('selectedExpenses');
        const newExpense = document.createElement('div');
        newExpense.className = 'form-group mt-2';
        newExpense.innerHTML = `
            <input type="text" class="form-control" name="expenses[]" placeholder="เพิ่มรายการ">
            <button type="button" class="btn btn-danger btn-sm remove-expense mt-2">
                <i class='bx bx-trash'></i>
            </button>
        `;
        selectedExpenses.appendChild(newExpense);
        updateExpenseButtons();
    });

    // Remove expense item
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-expense')) {
            e.target.closest('.form-group').remove();
            updateExpenseButtons();
        }
    });

    // Update visibility of remove buttons for expenses
    function updateExpenseButtons() {
        const selectedExpenses = document.getElementById('selectedExpenses');
        const buttons = selectedExpenses.querySelectorAll('.remove-expense');
        buttons.forEach(btn => {
            btn.style.display = buttons.length > 1 ? 'block' : 'none';
        });
    }

    // Toggle budget details
    window.toggleBudgetDetails = function() {
        const budgetDetails = document.getElementById('budgetDetails');
        const toggleIcon = document.getElementById('toggleIconBudget');

        if (budgetDetails.style.display === 'none' || budgetDetails.style.display === '') {
            budgetDetails.style.display = 'block';
            toggleIcon.classList.remove('bx-chevron-up');
            toggleIcon.classList.add('bx-chevron-down');
        } else {
            budgetDetails.style.display = 'none';
            toggleIcon.classList.remove('bx-chevron-down');
            toggleIcon.classList.add('bx-chevron-up');
        }
    }

    // ============ เป้าหมายเชิงผลผลิต (Output) ============
    window.addField = function(containerId, fieldName) {
        const container = document.getElementById(containerId);
        const newField = document.createElement('div');
        newField.className = 'form-group mt-2';
        newField.innerHTML = `
            <input type="text" class="form-control" name="${fieldName}" placeholder="เพิ่มรายการ">
            <button type="button" class="btn btn-danger btn-sm remove-field mt-2">
                <i class='bx bx-trash'></i>
            </button>
        `;
        container.appendChild(newField);
        updateFieldButtons(containerId);
    }

    // Remove field
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-field')) {
            e.target.closest('.form-group').remove();
        }
    });

    // Update visibility of remove buttons
    function updateFieldButtons(containerId) {
        const container = document.getElementById(containerId);
        const buttons = container.querySelectorAll('.remove-field');
        buttons.forEach(btn => {
            btn.style.display = buttons.length > 1 ? 'block' : 'none';
        });
    }

    // Toggle output details
    window.toggleOutputDetails = function() {
        const outputDetails = document.getElementById('outputDetails');
        const toggleIcon = document.getElementById('toggleIconOutput');

        if (outputDetails.style.display === 'none' || outputDetails.style.display === '') {
            outputDetails.style.display = 'block';
            toggleIcon.classList.remove('bx-chevron-up');
            toggleIcon.classList.add('bx-chevron-down');
        } else {
            outputDetails.style.display = 'none';
            toggleIcon.classList.remove('bx-chevron-down');
            toggleIcon.classList.add('bx-chevron-up');
        }
    }

    // Toggle outcome details
    window.toggleOutcomeDetails = function() {
        const outcomeDetails = document.getElementById('outcomeDetails');
        const toggleIcon = document.getElementById('toggleIconOutcome');

        if (outcomeDetails.style.display === 'none' || outcomeDetails.style.display === '') {
            outcomeDetails.style.display = 'block';
            toggleIcon.classList.remove('bx-chevron-up');
            toggleIcon.classList.add('bx-chevron-down');
        } else {
            outcomeDetails.style.display = 'none';
            toggleIcon.classList.remove('bx-chevron-down');
            toggleIcon.classList.add('bx-chevron-up');
        }
    }

    // Toggle result details
    window.toggleResultDetails = function() {
        const resultDetails = document.getElementById('resultDetails');
        const toggleIcon = document.getElementById('toggleIconResult');

        if (resultDetails.style.display === 'none' || resultDetails.style.display === '') {
            resultDetails.style.display = 'block';
            toggleIcon.classList.remove('bx-chevron-up');
            toggleIcon.classList.add('bx-chevron-down');
        } else {
            resultDetails.style.display = 'none';
            toggleIcon.classList.remove('bx-chevron-down');
            toggleIcon.classList.add('bx-chevron-up');
        }
    }
});
</script>
@endsection