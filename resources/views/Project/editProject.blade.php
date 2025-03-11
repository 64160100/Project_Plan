@extends('navbar.app')

<hade>
    <link rel="stylesheet" href="{{ asset('css/createFirstForm.css') }}">
    <style>
    /* Project Search List Styling */
    #project-list {
        position: absolute;
        width: 100%;
        max-height: 200px;
        overflow-y: auto;
        background-color: white;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-top: 5px;
        z-index: 1000;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 0;
    }

    #project-list li {
        padding: 8px 12px;
        list-style: none;
        cursor: pointer;
        border-bottom: 1px solid #f0f0f0;
    }

    #project-list li:hover {
        background-color: #f8f9fa;
    }

    #project-list li.loading,
    #project-list li.error,
    #project-list li.no-results {
        color: #6c757d;
        font-style: italic;
        cursor: default;
    }

    #project-list li.error {
        color: #dc3545;
    }

    .indicator-item {
        transition: all 0.3s ease;
    }

    .indicator-item:hover {
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    .content-box {
        background-color: #fff;
        border-radius: 0.25rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }
    </style>
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
                                <label for="Name_Strategic_Plan" class="form-label">ชื่อแผนยุทธศาสตร์</label>
                                <input type="text" class="form-control" id="Name_Strategic_Plan"
                                    name="Name_Strategic_Plan" value="{{ $nameStrategicPlan }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="Name_Strategy" class="form-label">ชื่อกลยุทธ์<span class="text-danger">
                                        *</span></label>
                                <select class="form-select @error('Name_Strategy') is-invalid @enderror"
                                    name="Name_Strategy" id="Name_Strategy" required>
                                    <option value="" selected disabled>เลือกกลยุทธ์</option>
                                    @if($strategies->isNotEmpty())
                                    @foreach($strategies as $strategy)
                                    <option value="{{ $strategy->Id_Strategy }}"
                                        {{ $project->Strategy_Id == $strategy->Id_Strategy ? 'selected' : '' }}>
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
                                onchange="toggleTextbox(this, 'textbox-projectType-')"
                                {{ $project->Description_Project == 'N' ? 'checked' : '' }} disabled>
                            <label for="newProject">โครงการใหม่</label>
                        </div>
                        <div class="radio-item">
                            <input type="radio" name="Description_Project" value="C" id="continuousProject"
                                onchange="toggleTextbox(this, 'textbox-projectType-')"
                                {{ $project->Description_Project == 'C' ? 'checked' : '' }} disabled>
                            <label for="continuousProject">โครงการต่อเนื่อง</label>
                        </div>
                    </div>
                    <div class="form-group"
                        style="{{ $project->Description_Project == 'C' ? 'display: block;' : 'display: none;' }}">
                        <label for="textbox-projectType-2" class="form-label">ค้นหาโครงการเดิม</label>
                        <input type="text" id="textbox-projectType-2" class="form-control" data-group="projectType"
                            placeholder="กรอกชื่อโครงการเดิม" value="{{ $project->oldProjectName }}" readonly>
                        <ul id="project-list" style="display: none;"></ul>
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
                            <label for="Name_Project" class="form-label">สร้างชื่อโครงการ <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('Name_Project') is-invalid @enderror"
                                id="Name_Project" name="Name_Project" placeholder="กรอกชื่อโครงการ" required
                                value="{{ $project->Name_Project }}" title="กรุณากรอกชื่อโครงการ">
                            @error('Name_Project')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @else
                            <div class="invalid-feedback">กรุณากรอกชื่อโครงการ</div>
                            @enderror
                        </div>

                        <div id="projectContainer">
                            @csrf
                            @foreach($project->subProjects as $index => $subProject)
                            <div class="form-group mb-2 dynamic-field">
                                <div class="input-group">
                                    <span class="input-group-text">1.{{ $index + 1 }}</span>
                                    <input type="text" class="form-control" name="Name_Sub_Project[]"
                                        value="{{ $subProject->Name_Sub_Project }}" readonly>
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

                <!-- ตัวชี้วัดความสำเร็จของโครงการและค่าเป้าหมาย (รวมเป็นหัวข้อเดียว) -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            4. ตัวชี้วัดความสำเร็จของโครงการและค่าเป้าหมาย
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

                <!-- แหล่งงบประมาณ -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            5. แหล่งงบประมาณ
                        </h4>
                    </div>
                    <div id="budgetDetails">
                        <div class="form-group-radio mb-3">
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
                                                onchange="handleSourceCheckbox(this)"
                                                {{ in_array($source->Id_Budget_Source, $project->projectBudgetSources->pluck('Budget_Source_Id')->toArray()) ? 'checked' : '' }}>
                                            <label class="form-check-label d-flex align-items-center w-100"
                                                for="{{ $source->Id_Budget_Source }}">
                                                <span class="label-text me-2">{{ $source->Name_Budget_Source }}</span>
                                                <div class="input-group" style="max-width: 200px;">
                                                    <input type="number" name="amount_{{ $source->Id_Budget_Source }}"
                                                        class="form-control form-control-sm" placeholder="จำนวนเงิน"
                                                        value="{{ $project->projectBudgetSources->firstWhere('Budget_Source_Id', $source->Id_Budget_Source)->budgetSourceTotal->Amount_Total ?? '' }}"
                                                        {{ in_array($source->Id_Budget_Source, $project->projectBudgetSources->pluck('Budget_Source_Id')->toArray()) ? '' : 'disabled' }}>
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
                                                <input type="radio" name="Expense_Status" value="O" id="single_day"
                                                    onchange="toggleDateForm(this)"
                                                    {{ $expenseStatus == 'O' ? 'checked' : '' }}>
                                                <label for="single_day">วันเดียว</label>

                                                <input type="radio" name="Expense_Status" value="M" id="multiple_days"
                                                    onchange="toggleDateForm(this)"
                                                    {{ $expenseStatus == 'M' ? 'checked' : '' }}>
                                                <label for="multiple_days">หลายวัน</label>
                                            </div>
                                        </div>

                                        <!-- แบบฟอร์มงบประมาณตามวันที่ (อยู่ในกรอบเดียวกัน) -->
                                        <div class="mt-4">
                                            <div id="budgetFormsContainer">
                                                @foreach($expenses as $expense)
                                                <div class="budget-form mb-4 pb-3 border-bottom"
                                                    data-form-index="{{ $loop->index }}">
                                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                                        <div style="flex: 1;">
                                                            <div class="d-flex align-items-center">
                                                                <div class="me-3">
                                                                    <label class="form-label">วันที่ดำเนินการ</label>
                                                                    <input type="date" name="date[]"
                                                                        class="form-control" style="width: 200px;"
                                                                        value="{{ $expense->Date_Expense }}">
                                                                </div>
                                                                <div class="flex-grow-1">
                                                                    <label
                                                                        class="form-label">รายละเอียดค่าใช้จ่ายสำหรับวันนี้</label>
                                                                    <textarea name="budget_details[]"
                                                                        class="form-control"
                                                                        placeholder="ระบุรายละเอียดค่าใช้จ่ายสำหรับวันที่นี้"
                                                                        rows="2">{{ $expense->Details_Expense }}</textarea>
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
                                                                <tr class="category-row"
                                                                    id="category-row-{{ $loop->index }}"
                                                                    data-row-id="{{ $loop->index }}">
                                                                    <td class="align-middle text-center">1</td>
                                                                    <td class="align-middle">
                                                                        <select class="form-select category-select"
                                                                            name="budget_category[{{ $loop->index }}][]"
                                                                            data-row-id="{{ $loop->index }}"
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
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- ปุ่มเพิ่มแบบฟอร์ม (สำหรับหลายวัน) -->
                                        <div class="mt-3 mb-3" id="addBudgetFormBtnContainer"
                                            style="{{ $expenseStatus == 'M' ? 'display: block;' : 'display: none;' }}">
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

                <!-- ผู้รับผิดชอบโครงการ -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            6. ผู้รับผิดชอบโครงการ
                        </h4>
                    </div>
                    <div id="responsibleDetails">
                        <div class="form-group">
                            <label for="employee_id" class="form-label">เลือกผู้รับผิดชอบ</label>
                            <select class="form-select @error('employee_id') is-invalid @enderror" id="employee_id"
                                name="employee_id">
                                <option value="" selected disabled>เลือกผู้รับผิดชอบ</option>
                                @foreach($employees as $employee)
                                <option value="{{ $employee->Id_Employee }}"
                                    {{ $project->Employee_Id == $employee->Id_Employee ? 'selected' : '' }}>
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

                <!-- ปุ่มเสร็จสิ้น -->
                <div class="mt-3 mb-3">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="bx bx-check-circle"></i> เสร็จสิ้น
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/createFirstForm.js') }}"></script>
<script src="{{ asset('js/editProjectBugget.js') }}"></script>


@endsection