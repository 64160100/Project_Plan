<hade>
    <link rel="stylesheet" href="<?php echo e(asset('css/createFirstForm.css')); ?>">
</hade>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="card">
        <h3 class="card-header">แก้ไขข้อมูลโครงการ</h3>

        <div class="card-body">
            <form action="<?php echo e(route('updateProject', ['Id_Project' => $project->Id_Project])); ?>" method="POST"
                class="needs-validation" novalidate>
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>


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
                                    <?php $__currentLoopData = $strategics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $strategic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($strategic->Id_Strategic); ?>"
                                        <?php echo e($project->Strategic_Id == $strategic->Id_Strategic ? 'selected' : ''); ?>>
                                        <?php echo e($strategic->Name_Strategic_Plan); ?>

                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="Name_Strategy" class="form-label">กลยุทธ์</label>
                                <select class="form-select <?php $__errorArgs = ['Name_Strategy'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    name="Name_Strategy" id="Name_Strategy" required>
                                    <option value="" disabled>เลือกกลยุทธ์</option>
                                    <?php $__currentLoopData = $strategies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $strategy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($strategy->Id_Strategy); ?>"
                                        <?php echo e($project->Strategy_Id == $strategy->Id_Strategy ? 'selected' : ''); ?>>
                                        <?php echo e($strategy->Name_Strategy); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['Name_Strategy'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                                <?php echo e($project->Description_Project == 'N' ? 'checked' : ''); ?>>
                            <label for="newProject">โครงการใหม่</label>
                        </div>
                        <div class="radio-item">
                            <input type="radio" name="Description_Project" value="C" id="continuousProject"
                                onchange="toggleTextbox(this, 'textbox-projectType-')"
                                <?php echo e($project->Description_Project == 'C' ? 'checked' : ''); ?>>
                            <label for="continuousProject">โครงการต่อเนื่อง</label>
                        </div>
                    </div>
                    <div class="form-group"
                        style="<?php echo e($project->Description_Project == 'C' ? 'display: block;' : 'display: none;'); ?>">
                        <input type="text" id="textbox-projectType-2" class="form-control" data-group="projectType"
                            placeholder="กรอกชื่อโครงการเดิม" value="<?php echo e($project->oldProjectName); ?>">
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
                            <input type="text" class="form-control <?php $__errorArgs = ['Name_Project'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="Name_Project" name="Name_Project" placeholder="กรอกชื่อโครงการ"
                                value="<?php echo e($project->Name_Project); ?>" required>
                            <?php $__errorArgs = ['Name_Project'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div id="projectContainer">
                            <?php echo csrf_field(); ?>
                            <?php $__currentLoopData = $subProjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $subProject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="form-group mb-2 dynamic-field">
                                    <div class="input-group">
                                        <span class="input-group-text">1.<?php echo e($index + 1); ?></span>
                                        <input type="text" class="form-control" name="Name_Sub_Project[]" 
                                            value="<?php echo e($subProject->Name_Sub_Project); ?>" 
                                            placeholder="กรอกชื่อโครงการย่อย" required>
                                        <button type="button" class="btn btn-danger" onclick="removeField(this)">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div>
                        <button type="button" class="btn btn-primary" onclick="addField1('projectContainer', 'Name_Sub_Project[]')">
                            เพิ่มโครงการย่อย
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
                            <select class="form-control <?php $__errorArgs = ['Objective_Project'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="Objective_Project" name="Objective_Project" required>
                                <option value="" disabled>กรอกข้อมูลวัตถุประสงค์</option>
                                <?php $__currentLoopData = $strategicObjectives; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $objective): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($objective->Details_Strategic_Objectives); ?>"
                                    data-strategy-id="<?php echo e($objective->Strategy_Id_Strategy); ?>"
                                    <?php echo e($project->Objective_Project == $objective->Details_Strategic_Objectives ? 'selected' : ''); ?>>
                                    <?php echo e($objective->Details_Strategic_Objectives); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['Objective_Project'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <?php echo $__env->make('Project.App.ProjectObjective', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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
                            <select class="form-control <?php $__errorArgs = ['Success_Indicators'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="Success_Indicators" name="Success_Indicators">
                                <option value="" disabled>กรอกตัวชี้วัดความสำเร็จของโครงการ</option>
                                <?php $__currentLoopData = $kpis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kpi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($kpi->Name_Kpi); ?>" data-strategy-id="<?php echo e($kpi->Strategy_Id); ?>"
                                    data-target-value="<?php echo e($kpi->Target_Value); ?>"
                                    <?php echo e($project->Success_Indicators == $kpi->Name_Kpi ? 'selected' : ''); ?>>
                                    <?php echo e($kpi->Name_Kpi); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['Success_Indicators'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                            <select class="form-control <?php $__errorArgs = ['Value_Target'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="Value_Target"
                                name="Value_Target">
                                <option value="" disabled>กรอกค่าเป้าหมาย</option>
                                <option value="<?php echo e($project->Value_Target); ?>" selected><?php echo e($project->Value_Target); ?>

                                </option>
                            </select>
                            <?php $__errorArgs = ['Value_Target'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                                        value="<?php echo e($project->First_Time); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="End_Time">วันที่สิ้นสุด:</label>
                                    <input type="date" class="form-control" id="End_Time" name="End_Time"
                                        value="<?php echo e($project->End_Time); ?>" required>
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
                                    <?php echo e($project->Status_Budget == 'N' ? 'checked' : ''); ?>>
                                <label for="non_income">ไม่ใช้งบประมาณ</label>

                                <input type="radio" name="Status_Budget" value="Y" id="income_seeking"
                                    onchange="toggleIncomeForm(this)"
                                    <?php echo e($project->Status_Budget == 'Y' ? 'checked' : ''); ?>>
                                <label for="income_seeking">ใช้งบประมาณ</label>
                            </div>
                        </div>

                        <div id="incomeForm" class="income-form"
                            style="<?php echo e($project->Status_Budget == 'Y' ? 'display: block;' : 'display: none;'); ?>">
                            <div class="form-group">
                                <label>แหล่งงบประมาณ</label>
                                <div class="mb-4">
                                    <?php $__currentLoopData = $budgetSources ?? collect(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $source): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="form-check mb-2 d-flex align-items-center">
                                        <input type="radio" id="<?php echo e($source->Id_Budget_Source); ?>" name="budget_source"
                                            value="<?php echo e($source->Id_Budget_Source); ?>" class="form-check-input"
                                            data-id="<?php echo e($source->Id_Budget_Source); ?>"
                                            <?php echo e($project->budget_source == $source->Id_Budget_Source ? 'checked' : ''); ?>

                                            onchange="handleSourceSelect(this)">
                                        <label class="form-check-label d-flex align-items-center w-100"
                                            for="<?php echo e($source->Id_Budget_Source); ?>">
                                            <span class="label-text"><?php echo e($source->Name_Budget_Source); ?></span>
                                            <input type="number" name="amount_<?php echo e($source->Id_Budget_Source); ?>"
                                                class="form-control form-control-sm w-25 ml-2" placeholder="จำนวนเงิน"
                                                value="<?php echo e($project->{'amount_'.$source->Id_Budget_Source}); ?>"
                                                <?php echo e($project->budget_source == $source->Id_Budget_Source ? '' : 'disabled'); ?>>
                                            <span class="ml-2">บาท</span>
                                        </label>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>

                                <!-- รายละเอียดการเบิกจ่าย -->
                                <div id="sourceDetailForm">
                                    <div class="mb-3">
                                        <label class="form-label">รายละเอียดค่าใช้จ่าย</label>
                                        <textarea name="source_detail" class="form-control"
                                            placeholder="ระบุรายละเอียดค่าใช้จ่าย"><?php echo e($project->source_detail); ?></textarea>
                                    </div>
                                </div>

                                <div class="form-group-radio">
                                    <label>เลือกประเภท</label>
                                    <div class="radio-group">
                                        <input type="radio" name="date_type" value="single" id="single_day"
                                            onchange="toggleDateForm(this)"
                                            <?php echo e($project->date_type == 'single' ? 'checked' : ''); ?>>
                                        <label for="single_day">วันเดียว</label>

                                        <input type="radio" name="date_type" value="multiple" id="multiple_days"
                                            onchange="toggleDateForm(this)"
                                            <?php echo e($project->date_type == 'multiple' ? 'checked' : ''); ?>>
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
                            <select class="form-select <?php $__errorArgs = ['employee_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="employee_id"
                                name="employee_id">
                                <option value="" disabled>เลือกผู้รับผิดชอบ</option>
                                <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($employee->Id_Employee); ?>"
                                    <?php echo e($project->employee_id == $employee->Id_Employee ? 'selected' : ''); ?>>
                                    <?php echo e($employee->Firstname); ?> <?php echo e($employee->Lastname); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['employee_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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

    // ============ วัตถุประสงค์โครงการ ============
    const form = document.querySelector('form'); // Adjust the selector to target your form
    form.addEventListener('submit', function(event) {
        const select = document.getElementById('Objective_Project');
        const selectedOption = select.options[select.selectedIndex];
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'Details_Strategic_Objectives';
        hiddenInput.value = selectedOption.value;
        form.appendChild(hiddenInput);
        select.disabled = true; // Disable the select to avoid sending its value
    });

    // ============ ตัวชี้วัดความสำเร็จของโครงการ ============


    // ============ ค่าเป้าหมาย ============

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
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('navbar.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/Project/editProject.blade.php ENDPATH**/ ?>