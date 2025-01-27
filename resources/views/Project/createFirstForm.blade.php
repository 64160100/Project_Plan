@extends('navbar.app')

<hade>
    <link rel="stylesheet" href="{{ asset('css/createProject.css') }}">
</hade>

@section('content')
<div class="container py-4">
    <div class="card">
        <h3 class="card-header">กรอกข้อมูลโครงการ</h3>

        <div class="card-body">
            <form action="{{ route('createFirstForm', ['Strategic_Id' => $strategics->Id_Strategic]) }}" method="POST"
                class="needs-validation" novalidate>
                @csrf

                <!-- ความสอดคล้องกับยุทธศาสตร์ส่วนงาน -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            1. ความสอดคล้องกับยุทธศาสตร์ส่วนงาน
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

                <!-- ชื่อโครงการ -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            3. ชื่อโครงการ
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

                <!-- วัตถุประสงค์โครงการ -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            4. วัตถุประสงค์โครงการ
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

                <!-- ตัวชี้วัดความสำเร็จของโครงการ -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            5. ตัวชี้วัดความสำเร็จของโครงการ
                            <i class='bx bx-chevron-up' id="toggleIconSuccessIndicators"
                                style="cursor: pointer; font-size: 1.5em;" onclick="toggleSuccessIndicators()"></i>
                        </h4>
                    </div>
                    <div id="successIndicatorsDetails" style="display: none;">
                        <div class="form-group">
                            <label for="Success_Indicators"></label>
                            <textarea class="form-control @error('Success_Indicators') is-invalid @enderror"
                                id="Success_Indicators" name="Success_Indicators" rows="4"
                                placeholder="กรอกตัวชี้วัดความสำเร็จของโครงการ">{{ old('Success_Indicators') }}</textarea>
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
                            <i class='bx bx-chevron-up' id="toggleIconValueTarget"
                                style="cursor: pointer; font-size: 1.5em;" onclick="toggleValueTarget()"></i>
                        </h4>
                    </div>
                    <div id="valueTargetDetails" style="display: none;">
                        <div class="form-group">
                            <label for="Value_Target"></label>
                            <textarea class="form-control @error('Value_Target') is-invalid @enderror" id="Value_Target"
                                name="Value_Target" rows="4"
                                placeholder="กรอกค่าเป้าหมาย">{{ old('Value_Target') }}</textarea>
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

                <!-- แหล่งงบประมาณ -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            8. แหล่งงบประมาณ
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
                                <div class="mb-4">
                                    @foreach($budgetSources as $source)
                                    <div class="form-check mb-2 d-flex align-items-center">
                                        <input type="radio" id="{{ $source->Id_Budget_Source }}" name="budget_source"
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
                                    <label>กรอกแบบฟอร์มงบประมาณ</label>
                                    <div class="radio-group">
                                        <input type="radio" name="fill_budget_form" value="yes" id="fill_yes"
                                            onchange="toggleBudgetForm(this)">
                                        <label for="fill_yes">กรอกแบบฟอร์มงบประมาณ</label>

                                        <input type="radio" name="fill_budget_form" value="no" id="fill_no"
                                            onchange="toggleBudgetForm(this)" checked>
                                        <label for="fill_no">ไม่กรอกแบบฟอร์มงบประมาณ</label>
                                    </div>
                                </div>

                                <!-- แบบฟอร์มงบประมาณ -->
                                <div id="budgetFormsContainer" style="display: none;">
                                    <div id="budgetFormTemplate" class="budget-form card mb-3">
                                        <div class="card-body">
                                            <h5>แบบฟอร์มที่ 1</h5>
                                            <button type="button" class="btn btn-danger btn-sm remove-form-btn"
                                                onclick="removeBudgetForm(this)"
                                                style="display: none;">ลบแบบฟอร์ม</button>
                                            <div class="mb-3 d-flex align-items-center">
                                                <div style="flex: 3;">
                                                    <label class="form-label">หัวข้อใหญ่</label>
                                                    <textarea name="activity[]" class="form-control"
                                                        placeholder="เช่น กิจกรรมการประชุมคณะกรรมการและอนุกรรมการ"></textarea>
                                                </div>
                                                <div style="flex: 1; margin-left: 1rem;">
                                                    <label class="form-label">จำนวนเงินทั้งหมด</label>
                                                    <div class="input-group">
                                                        <input type="number" name="total_amount[]" class="form-control"
                                                            placeholder="จำนวนเงิน">
                                                        <span class="input-group-text">บาท</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="subActivityContainer">
                                                <div class="sub-activity mb-3">
                                                    <label class="form-label">หัวข้อย่อย</label>
                                                    <select name="subActivity[0][]" class="form-control">
                                                        <option value="" disabled selected>เลือกหัวข้อย่อย</option>
                                                        @foreach($subtopBudgets as $subtop)
                                                        <option value="{{ $subtop->Id_Subtopic_Budget }}">
                                                            {{ $subtop->Name_Subtopic_Budget }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="detailsContainer">
                                                        <div class="mb-3 d-flex align-items-center detail-item">
                                                            <div style="flex: 3;">
                                                                <label class="form-label">รายละเอียด</label>
                                                                <textarea name="description[0][]" class="form-control"
                                                                    placeholder="เช่น ค่าอาหารว่างสำหรับการจัดประชุมคณะกรรมการจัดการความรู้"></textarea>
                                                            </div>
                                                            <div style="flex: 1; margin-left: 1rem;">
                                                                <label class="form-label">จำนวนเงิน</label>
                                                                <div class="input-group">
                                                                    <input type="number" name="amount[0][]"
                                                                        class="form-control" placeholder="880">
                                                                    <span class="input-group-text">บาท</span>
                                                                </div>
                                                            </div>
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm ml-2 remove-btn"
                                                                onclick="removeDetail(this)">ลบ</button>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn btn-success btn-sm"
                                                        onclick="addDetail(this)">เพิ่มรายละเอียด</button>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-secondary btn-sm"
                                                onclick="addSubActivity(this)">เพิ่มหัวข้อย่อย</button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary btn-sm"
                                    onclick="addBudgetForm()">เพิ่มแบบฟอร์ม</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ผู้รับผิดชอบโครงการ -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            9. ผู้รับผิดชอบโครงการ
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

    // ============ ค่าเป้าหมาย ============
    window.toggleValueTarget = function() {
        const details = document.getElementById('valueTargetDetails');
        const icon = document.getElementById('toggleIconValueTarget');
        if (details.style.display === 'none' || details.style.display === '') {
            details.style.display = 'block';
            icon.classList.remove('bx-chevron-up');
            icon.classList.add('bx-chevron-down');
        } else {
            details.style.display = 'none';
            icon.classList.remove('bx-chevron-down');
            icon.classList.add('bx-chevron-up');
        }
    }

    // ============ แหล่งงบประมาณ ============
    window.toggleIncomeForm = function(radio) {
        const incomeForm = document.getElementById('incomeForm');
        incomeForm.style.display = radio.value === 'Y' ? 'block' : 'none';
    }

    window.toggleBudgetDetails = function() {
        const budgetDetails = document.getElementById('budgetDetails');
        const toggleIcon = document.getElementById('toggleIconBudget');

        const isHidden = budgetDetails.style.display === 'none' || budgetDetails.style.display === '';
        budgetDetails.style.display = isHidden ? 'block' : 'none';
        toggleIcon.classList.toggle('bx-chevron-up', !isHidden);
        toggleIcon.classList.toggle('bx-chevron-down', isHidden);
    }

    window.handleSourceSelect = function(radio) {
        const selectedId = radio.getAttribute('data-id');
        const budgetSources = document.querySelectorAll('input[name="budget_source"]');
        budgetSources.forEach(source => {
            const amountInput = document.querySelector(`input[name="amount_${source.value}"]`);
            const isSelected = source.value === selectedId;
            amountInput.disabled = !isSelected;
            if (!isSelected) amountInput.value = '';
        });
    }

    window.toggleBudgetForm = function(radio) {
        const budgetFormsContainer = document.getElementById('budgetFormsContainer');
        budgetFormsContainer.style.display = radio.value === 'yes' ? 'block' : 'none';
    }

    window.addBudgetForm = function() {
        const budgetFormsContainer = document.getElementById('budgetFormsContainer');
        const budgetFormTemplate = document.getElementById('budgetFormTemplate');
        const newBudgetForm = budgetFormTemplate.cloneNode(true);
        const formCount = budgetFormsContainer.getElementsByClassName('budget-form').length;

        newBudgetForm.style.display = 'block';
        newBudgetForm.id = '';
        newBudgetForm.querySelector('h5').innerText = `แบบฟอร์มที่ ${formCount + 1}`;
        newBudgetForm.querySelector('.remove-form-btn').style.display = 'inline-block';

        newBudgetForm.querySelectorAll('textarea').forEach(textarea => textarea.value = '');
        newBudgetForm.querySelectorAll('input[type="number"]').forEach(input => input.value = '');
        newBudgetForm.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

        newBudgetForm.querySelectorAll('select[name="subActivity[]"]').forEach((select, index) => {
            select.name = `subActivity[${formCount}][]`;
        });
        newBudgetForm.querySelectorAll('textarea[name="description[]"]').forEach((textarea, index) => {
            textarea.name = `description[${formCount}][]`;
        });
        newBudgetForm.querySelectorAll('input[name="amount[]"]').forEach((input, index) => {
            input.name = `amount[${formCount}][]`;
        });

        budgetFormsContainer.appendChild(newBudgetForm);
    }

    window.addDetail = function(button) {
        const detailsContainer = button.closest('.sub-activity').querySelector('.detailsContainer');
        const formCount = button.closest('.budget-form').querySelector('h5').innerText.split(' ')[1] - 1;
        const newDetail = document.createElement('div');
        newDetail.className = 'mb-3 d-flex align-items-center detail-item';
        newDetail.innerHTML = `
        <div style="flex: 3;">
            <label class="form-label">รายละเอียด</label>
            <textarea name="description[${formCount}][]" class="form-control" placeholder="เช่น ค่าอาหารว่างสำหรับการจัดประชุมคณะกรรมการจัดการความรู้"></textarea>
        </div>
        <div style="flex: 1; margin-left: 1rem;">
            <label class="form-label">จำนวนเงิน</label>
            <div class="input-group">
                <input type="number" name="amount[${formCount}][]" class="form-control" placeholder="880">
                <span class="input-group-text">บาท</span>
            </div>
        </div>
        <button type="button" class="btn btn-danger btn-sm ml-2 remove-btn" onclick="removeDetail(this)">ลบ</button>
    `;
        detailsContainer.appendChild(newDetail);
        updateRemoveButtons(detailsContainer);
    }

    window.addSubActivity = function(button) {
        const subActivityContainer = button.closest('.budget-form').querySelector('#subActivityContainer');
        const formCount = button.closest('.budget-form').querySelector('h5').innerText.split(' ')[1] - 1;
        const newSubActivity = document.createElement('div');
        newSubActivity.className = 'sub-activity mb-3';
        newSubActivity.innerHTML = `
        <label class="form-label">หัวข้อย่อย</label>
        <select name="subActivity[${formCount}][]" class="form-control">
            <option value="" disabled selected>เลือกหัวข้อย่อย</option>
            @foreach($subtopBudgets as $subtop)
            <option value="{{ $subtop->Id_Subtopic_Budget }}">{{ $subtop->Name_Subtopic_Budget }}</option>
            @endforeach
        </select>
        <div class="detailsContainer">
            <div class="mb-3 d-flex align-items-center detail-item">
                <div style="flex: 3;">
                    <label class="form-label">รายละเอียด</label>
                    <textarea name="description[${formCount}][]" class="form-control" placeholder="เช่น ค่าอาหารว่างสำหรับการจัดประชุมคณะกรรมการจัดการความรู้"></textarea>
                </div>
                <div style="flex: 1; margin-left: 1rem;">
                    <label class="form-label">จำนวนเงิน</label>
                    <div class="input-group">
                        <input type="number" name="amount[${formCount}][]" class="form-control" placeholder="880">
                        <span class="input-group-text">บาท</span>
                    </div>
                </div>
                <button type="button" class="btn btn-danger btn-sm ml-2 remove-btn" onclick="removeDetail(this)">ลบ</button>
            </div>
        </div>
        <button type="button" class="btn btn-success btn-sm" onclick="addDetail(this)">เพิ่มรายละเอียด</button>
    `;
        subActivityContainer.appendChild(newSubActivity);
    }

    window.removeDetail = function(button) {
        const detail = button.parentElement;
        const detailsContainer = detail.parentElement;
        detail.remove();
        updateRemoveButtons(detailsContainer);
    }

    window.updateRemoveButtons = function(detailsContainer) {
        const detailItems = detailsContainer.querySelectorAll('.detail-item');
        const removeButtons = detailsContainer.querySelectorAll('.remove-btn');
        removeButtons.forEach(button => button.style.display = detailItems.length > 1 ? 'inline-block' :
            'none');
    }

    window.removeBudgetForm = function(button) {
        const budgetForm = button.closest('.budget-form');
        budgetForm.remove();
        updateFormTitles();
    }

    window.updateFormTitles = function() {
        const budgetForms = document.querySelectorAll('.budget-form');
        budgetForms.forEach((form, index) => {
            form.querySelector('h5').innerText = `แบบฟอร์มที่ ${index + 1}`;
        });
    }

    document.querySelectorAll('.detailsContainer').forEach(container => updateRemoveButtons(container));

    // ============ แหล่งงบประมาณ ============
    window.toggleIncomeForm = function(radio) {
        const incomeForm = document.getElementById('incomeForm');
        incomeForm.style.display = radio.value === 'Y' ? 'block' : 'none';
    }

    window.toggleBudgetDetails = function() {
        const budgetDetails = document.getElementById('budgetDetails');
        const toggleIcon = document.getElementById('toggleIconBudget');

        const isHidden = budgetDetails.style.display === 'none' || budgetDetails.style.display === '';
        budgetDetails.style.display = isHidden ? 'block' : 'none';
        toggleIcon.classList.toggle('bx-chevron-up', !isHidden);
        toggleIcon.classList.toggle('bx-chevron-down', isHidden);
    }

    window.handleSourceSelect = function(radio) {
        const selectedId = radio.getAttribute('data-id');
        const budgetSources = document.querySelectorAll('input[name="budget_source"]');
        budgetSources.forEach(source => {
            const amountInput = document.querySelector(`input[name="amount_${source.value}"]`);
            const isSelected = source.value === selectedId;
            amountInput.disabled = !isSelected;
            if (!isSelected) amountInput.value = '';
        });
    }

    window.toggleBudgetForm = function(radio) {
        const budgetFormsContainer = document.getElementById('budgetFormsContainer');
        budgetFormsContainer.style.display = radio.value === 'yes' ? 'block' : 'none';
    }

    window.addBudgetForm = function() {
        const budgetFormsContainer = document.getElementById('budgetFormsContainer');
        const budgetFormTemplate = document.getElementById('budgetFormTemplate');
        const newBudgetForm = budgetFormTemplate.cloneNode(true);
        const formCount = budgetFormsContainer.getElementsByClassName('budget-form').length;

        newBudgetForm.style.display = 'block';
        newBudgetForm.id = '';
        newBudgetForm.querySelector('h5').innerText = `แบบฟอร์มที่ ${formCount + 1}`;
        newBudgetForm.querySelector('.remove-form-btn').style.display = 'inline-block';

        newBudgetForm.querySelectorAll('textarea').forEach(textarea => textarea.value = '');
        newBudgetForm.querySelectorAll('input[type="number"]').forEach(input => input.value = '');
        newBudgetForm.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

        newBudgetForm.querySelectorAll('select[name="subActivity[]"]').forEach((select, index) => {
            select.name = `subActivity[${formCount}][]`;
        });
        newBudgetForm.querySelectorAll('textarea[name="description[]"]').forEach((textarea, index) => {
            textarea.name = `description[${formCount}][]`;
        });
        newBudgetForm.querySelectorAll('input[name="amount[]"]').forEach((input, index) => {
            input.name = `amount[${formCount}][]`;
        });

        budgetFormsContainer.appendChild(newBudgetForm);
    }

    window.addDetail = function(button) {
        const detailsContainer = button.closest('.sub-activity').querySelector('.detailsContainer');
        const formCount = button.closest('.budget-form').querySelector('h5').innerText.split(' ')[1] - 1;
        const newDetail = document.createElement('div');
        newDetail.className = 'mb-3 d-flex align-items-center detail-item';
        newDetail.innerHTML = `
        <div style="flex: 3;">
            <label class="form-label">รายละเอียด</label>
            <textarea name="description[${formCount}][]" class="form-control" placeholder="เช่น ค่าอาหารว่างสำหรับการจัดประชุมคณะกรรมการจัดการความรู้"></textarea>
        </div>
        <div style="flex: 1; margin-left: 1rem;">
            <label class="form-label">จำนวนเงิน</label>
            <div class="input-group">
                <input type="number" name="amount[${formCount}][]" class="form-control" placeholder="880">
                <span class="input-group-text">บาท</span>
            </div>
        </div>
        <button type="button" class="btn btn-danger btn-sm ml-2 remove-btn" onclick="removeDetail(this)">ลบ</button>
    `;
        detailsContainer.appendChild(newDetail);
        updateRemoveButtons(detailsContainer);
    }

    window.addSubActivity = function(button) {
        const subActivityContainer = button.closest('.budget-form').querySelector('#subActivityContainer');
        const formCount = button.closest('.budget-form').querySelector('h5').innerText.split(' ')[1] - 1;
        const newSubActivity = document.createElement('div');
        newSubActivity.className = 'sub-activity mb-3';
        newSubActivity.innerHTML = `
        <label class="form-label">หัวข้อย่อย</label>
        <select name="subActivity[${formCount}][]" class="form-control">
            <option value="" disabled selected>เลือกหัวข้อย่อย</option>
            @foreach($subtopBudgets as $subtop)
            <option value="{{ $subtop->Id_Subtopic_Budget }}">{{ $subtop->Name_Subtopic_Budget }}</option>
            @endforeach
        </select>
        <div class="detailsContainer">
            <div class="mb-3 d-flex align-items-center detail-item">
                <div style="flex: 3;">
                    <label class="form-label">รายละเอียด</label>
                    <textarea name="description[${formCount}][]" class="form-control" placeholder="เช่น ค่าอาหารว่างสำหรับการจัดประชุมคณะกรรมการจัดการความรู้"></textarea>
                </div>
                <div style="flex: 1; margin-left: 1rem;">
                    <label class="form-label">จำนวนเงิน</label>
                    <div class="input-group">
                        <input type="number" name="amount[${formCount}][]" class="form-control" placeholder="880">
                        <span class="input-group-text">บาท</span>
                    </div>
                </div>
                <button type="button" class="btn btn-danger btn-sm ml-2 remove-btn" onclick="removeDetail(this)">ลบ</button>
            </div>
        </div>
        <button type="button" class="btn btn-success btn-sm" onclick="addDetail(this)">เพิ่มรายละเอียด</button>
    `;
        subActivityContainer.appendChild(newSubActivity);
    }

    window.removeDetail = function(button) {
        const detail = button.parentElement;
        const detailsContainer = detail.parentElement;
        detail.remove();
        updateRemoveButtons(detailsContainer);
    }

    window.updateRemoveButtons = function(detailsContainer) {
        const detailItems = detailsContainer.querySelectorAll('.detail-item');
        const removeButtons = detailsContainer.querySelectorAll('.remove-btn');
        removeButtons.forEach(button => button.style.display = detailItems.length > 1 ? 'inline-block' :
            'none');
    }

    window.removeBudgetForm = function(button) {
        const budgetForm = button.closest('.budget-form');
        budgetForm.remove();
        updateFormTitles();
    }

    window.updateFormTitles = function() {
        const budgetForms = document.querySelectorAll('.budget-form');
        budgetForms.forEach((form, index) => {
            form.querySelector('h5').innerText = `แบบฟอร์มที่ ${index + 1}`;
        });
    }

    document.querySelectorAll('.detailsContainer').forEach(container => updateRemoveButtons(container));

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
});
</script>
@endsection