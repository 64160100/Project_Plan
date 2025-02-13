@extends('navbar.app')

<hade>
    <link rel="stylesheet" href="{{ asset('css/createFirstForm.css') }}">
</hade>

@section('content')
<div class="container py-4">
    <div class="card">
        <h3 class="card-header">กรอกข้อมูลโครงการ</h3>

        <div class="card-body">
            <form action="{{ route('createProject', ['Strategic_Id' => $strategics->Id_Strategic]) }}" method="POST"
                class="needs-validation" novalidate>
                @csrf

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
                                    <option value="{{ $strategy->Id_Strategy }}"
                                        {{ isset($project) && $project->Name_Strategy == $strategy->Id_Strategy ? 'selected' : '' }}>
                                        {{ $strategy->Name_Strategy }}
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
                        </h4>
                    </div>
                    <div class="form-group-radio">
                        <div class="radio-item">
                            <input type="radio" name="Description_Project" value="N" id="newProject"
                                onchange="toggleTextbox(this, 'textbox-projectType-')" checked>
                            <label for="newProject">โครงการใหม่</label>
                        </div>
                        <div class="radio-item">
                            <input type="radio" name="Description_Project" value="C" id="continuousProject"
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
                        </h4>
                    </div>
                    <div id="projectDetails">
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

                <!-- ตัวชี้วัดความสำเร็จของโครงการ -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            4. ตัวชี้วัดความสำเร็จของโครงการ
                        </h4>
                    </div>
                    <div id="successIndicatorsDetails">
                        <div class="form-group">
                            <div class="d-flex align-items-center">
                                <select class="form-control @error('Success_Indicators') is-invalid @enderror"
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
                            5. ค่าเป้าหมาย
                        </h4>
                    </div>
                    <div id="valueTargetDetails">
                        <div class="form-group">
                            <div class="d-flex align-items-center">
                                <select class="form-control @error('Value_Target') is-invalid @enderror"
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

                <!-- ผู้รับผิดชอบโครงการ -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            7. ผู้รับผิดชอบโครงการ
                        </h4>
                    </div>
                    <div id="responsibleDetails">
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

    const strategySelect = document.getElementById('Name_Strategy');
    const objectiveSelect = document.getElementById('Objective_Project');
    const kpiSelect = document.getElementById('Success_Indicators');
    const targetSelect = document.getElementById('Value_Target');

    strategySelect.addEventListener('change', function() {
        const selectedStrategyId = this.value;

        Array.from(objectiveSelect.options).forEach(option => {
            option.style.display = option.getAttribute('data-strategy-id') ===
                selectedStrategyId ? 'block' : 'none';
        });

        Array.from(kpiSelect.options).forEach(option => {
            option.style.display = option.getAttribute('data-strategy-id') ===
                selectedStrategyId ? 'block' : 'none';
        });

        targetSelect.innerHTML = '<option value="" disabled selected>กรอกค่าเป้าหมาย</option>';
    });

    kpiSelect.addEventListener('change', function() {
        const selectedOption = kpiSelect.options[kpiSelect.selectedIndex];
        const targetValue = selectedOption.getAttribute('data-target-value');

        targetSelect.innerHTML = '<option value="" disabled selected>กรอกค่าเป้าหมาย</option>';

        if (targetValue) {
            const option = document.createElement('option');
            option.value = targetValue;
            option.textContent = targetValue;
            targetSelect.appendChild(option);
        }
    });

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

    // ====== ลักษณะโครงการ =======
    window.toggleTextbox = function(radio, prefix) {
        const textboxContainer = document.querySelector(`#${prefix}2`).closest('.form-group');
        const textbox = document.getElementById(`${prefix}2`);

        if (radio.value === 'C') {
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
        const continuousProjectRadio = document.getElementById('continuousProject');
        const textbox = document.getElementById('textbox-projectType-2');

        if (newProjectRadio.checked) {
            textbox.classList.add('hidden');
            textbox.closest('.form-group').style.display = 'none';
        }

        if (continuousProjectRadio.checked) {
            textbox.classList.remove('hidden');
            textbox.closest('.form-group').style.display = 'block';
            textbox.required = true;
        }

        const projectTypeDetails = document.querySelector('.form-group-radio');
        const toggleIcon = document.getElementById('toggleIconProjectType');
        projectTypeDetails.style.display = 'none';
        toggleIcon.classList.remove('bx-chevron-down');
        toggleIcon.classList.add('bx-chevron-up');
    });

    // ============ จัดการความสอดคล้องกับยุทธศาสตร์มหาวิทยาลัย============

    // ============ ความสอดคล้องกับยุทธศาสตร์ส่วนงาน ============

    // ============ แหล่งงบประมาณ ============
    window.toggleIncomeForm = function(radio) {
        const incomeForm = document.getElementById('incomeForm');
        incomeForm.style.display = radio.value === 'Y' ? 'block' : 'none';
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

    window.toggleDateForm = function(radio) {
        // Find the "เพิ่มแบบฟอร์ม" button
        const addBudgetFormBtn = document.querySelector(
            '.btn.btn-primary.btn-sm[onclick="addBudgetForm()"]');

        if (radio.value === 'single') {
            // Hide the add form button for single day
            if (addBudgetFormBtn) {
                addBudgetFormBtn.style.display = 'none';
            }

            // Keep only the first form and remove others when switching to single day
            const budgetForms = document.querySelectorAll('.budget-form');
            budgetForms.forEach((form, index) => {
                if (index === 0) {
                    form.style.display = 'block';
                } else {
                    form.remove();
                }
            });
        } else {
            // Show the add form button for multiple days
            if (addBudgetFormBtn) {
                addBudgetFormBtn.style.display = 'block';
            }
        }
    }

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
    // ============ ผู้รับผิดชอบโครงการ============

});

document.addEventListener('DOMContentLoaded', function() {
    // ============ วัตถุประสงค์โครงการ ตัวชี้วัดความสำเร็จของโครงการ ค่าเป้าหมาย=================
    const objectiveCheckbox = document.getElementById('Objective_Project_Other_Checkbox');
    const objectiveSelect = document.getElementById('Objective_Project');
    const objectiveOther = document.getElementById('Objective_Project_Other');
    objectiveCheckbox.addEventListener('change', function() {
        if (this.checked) {
            objectiveOther.style.display = 'block';
            objectiveOther.required = true;
            objectiveSelect.disabled = true;
        } else {
            objectiveOther.style.display = 'none';
            objectiveOther.required = false;
            objectiveOther.value = '';
            objectiveSelect.disabled = false;
        }
    });

    const successIndicatorsCheckbox = document.getElementById('Success_Indicators_Other_Checkbox');
    const successIndicatorsSelect = document.getElementById('Success_Indicators');
    const successIndicatorsOther = document.getElementById('Success_Indicators_Other');
    successIndicatorsCheckbox.addEventListener('change', function() {
        if (this.checked) {
            successIndicatorsOther.style.display = 'block';
            successIndicatorsOther.required = true;
            successIndicatorsSelect.disabled = true;
        } else {
            successIndicatorsOther.style.display = 'none';
            successIndicatorsOther.required = false;
            successIndicatorsOther.value = '';
            successIndicatorsSelect.disabled = false;
        }
    });

    const valueTargetCheckbox = document.getElementById('Value_Target_Other_Checkbox');
    const valueTargetSelect = document.getElementById('Value_Target');
    const valueTargetOther = document.getElementById('Value_Target_Other');
    valueTargetCheckbox.addEventListener('change', function() {
        if (this.checked) {
            valueTargetOther.style.display = 'block';
            valueTargetOther.required = true;
            valueTargetSelect.disabled = true;
        } else {
            valueTargetOther.style.display = 'none';
            valueTargetOther.required = false;
            valueTargetOther.value = '';
            valueTargetSelect.disabled = false;
        }
    });

    const form = document.querySelector('form');
    form.addEventListener('submit', function(event) {
        // ============ วัตถุประสงค์โครงการ ============
        if (objectiveCheckbox.checked) {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'Objective_Project';
            hiddenInput.value = objectiveOther.value;
            form.appendChild(hiddenInput);
            objectiveOther.disabled = false;
        } else {
            const selectedOption = objectiveSelect.options[objectiveSelect.selectedIndex];
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'Objective_Project';
            hiddenInput.value = selectedOption.value;
            form.appendChild(hiddenInput);
            objectiveSelect.disabled = true;
        }

        // ============ ตัวชี้วัดความสำเร็จของโครงการ ============
        if (successIndicatorsCheckbox.checked) {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'Success_Indicators';
            hiddenInput.value = successIndicatorsOther.value;
            form.appendChild(hiddenInput);
            successIndicatorsOther.disabled = false;
        } else {
            const selectedOption = successIndicatorsSelect.options[successIndicatorsSelect
                .selectedIndex];
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'Success_Indicators';
            hiddenInput.value = selectedOption.value;
            form.appendChild(hiddenInput);
            successIndicatorsSelect.disabled = true;
        }

        // ============ ค่าเป้าหมาย ============
        if (valueTargetCheckbox.checked) {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'Value_Target';
            hiddenInput.value = valueTargetOther.value;
            form.appendChild(hiddenInput);
            valueTargetOther.disabled = false;
        } else {
            const selectedOption = valueTargetSelect.options[valueTargetSelect.selectedIndex];
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'Value_Target';
            hiddenInput.value = selectedOption.value;
            form.appendChild(hiddenInput);
            valueTargetSelect.disabled = true;
        }
    });
});
</script>
@endsection