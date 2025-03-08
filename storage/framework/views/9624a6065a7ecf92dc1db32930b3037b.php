<hade>
    <link rel="stylesheet" href="<?php echo e(asset('css/createProject.css')); ?>">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/i18n/datepicker-th.min.js"></script>

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

    .sdgs-grid {
        display: block;
        width: 100%;
    }

    .form-group-sdgs {
        margin-bottom: 5px;
        padding: 5px 5px;
        border-bottom: 1px solid #f0f0f0;
    }

    .form-check {
        display: flex;
        align-items: center;
        padding: 0;
    }

    .form-check-input {
        width: 10px;
        height: 10px;
        margin-right: 5px;
        cursor: pointer;
    }

    .form-check-label {
        font-size: 1.05rem;
        margin-bottom: 0;
        cursor: pointer;
        flex: 1;
    }

    .form-group-sdgs:hover {
        background-color: #f8f9fa;
        transition: background-color 0.2s;
    }

    #sdgsDetails {
        padding: 15px;
        border-radius: 5px;
        background-color: #fff;
        margin-bottom: 20px;
    }
    </style>
</hade>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="card">
        <h3 class="card-header">กรอกข้อมูลโครงการ</h3>

        <div class="card-body">
            <form action="<?php echo e(route('projects.resetStatus', ['id' => $project->Id_Project])); ?>" method="POST"
                class="needs-validation" novalidate>
                <?php echo csrf_field(); ?>

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
                                onblur="saveData(this, '<?php echo e($project->Id_Project); ?>', 'Name_Project')"
                                onkeypress="checkEnter(event, this)">
                                <?php echo e($project->Name_Project); ?>

                            </div>
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
                            <select class="form-select <?php $__errorArgs = ['employee_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="employee_id"
                                name="employee_id">
                                <option value="" selected disabled>เลือกผู้รับผิดชอบ</option>
                                <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($employee->Id_Employee); ?>"
                                    <?php echo e($employee->Id_Employee == $project->Employee_Id ? 'selected' : ''); ?>>
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
                                    name="Name_Strategic_Plan" value="<?php echo e($nameStrategicPlan); ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="Name_Strategy" class="form-label">กลยุทธ์</label>
                                <input type="text" class="form-control" id="Name_Strategy" name="Name_Strategy"
                                    value="<?php echo e($project->Name_Strategy); ?>" readonly>
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
                            <div class="platform-card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h3 class="card-title m-0">แพลตฟอร์มที่ 1</h3>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="removePlatform(this)">
                                        <i class='bx bx-trash'></i> <span class="d-none d-md-inline">ลบแพลตฟอร์ม</span>
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
                                            <button type="button" class="btn btn-danger" onclick="removeKpi(this)">
                                                <i class='bx bx-trash'></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn-add" onclick="addPlatform()">เพิ่มแพลตฟอร์ม</button>
                </div>

                <!-- SDGs -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            5. ความสอดคล้องกับ (SDGs)
                        </h4>
                    </div>
                    <div id="sdgsDetails">
                        <div class="sdgs-grid">
                            <?php $__currentLoopData = $sdgs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sdg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="form-group-sdgs">
                                <div class="form-check d-flex align-items-center">
                                    <input class="form-check-input" type="checkbox" name="sdgs[]"
                                        value="<?php echo e($sdg->id_SDGs); ?>" id="sdg_<?php echo e($sdg->id_SDGs); ?>"
                                        <?php echo e(in_array($sdg->id_SDGs, $selectedSdgs ?? []) ? 'checked' : ''); ?>>
                                    <label class="form-check-label ml-2" for="sdg_<?php echo e($sdg->id_SDGs); ?>">
                                        <?php echo e($sdg->Name_SDGs); ?>

                                    </label>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                <?php $__currentLoopData = $integrationCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="option-item">
                                    <label>
                                        <input type="checkbox"
                                            name="integrationCategories[<?php echo e($category->Id_Integration_Category); ?>][checked]"
                                            onchange="toggleSelectTextbox(this)">
                                        <?php echo e($category->Name_Integration_Category); ?>

                                    </label>
                                    <input type="text" class="additional-info"
                                        name="integrationCategories[<?php echo e($category->Id_Integration_Category); ?>][details]"
                                        placeholder="ระบุข้อมูลเพิ่มเติม" disabled style="width: 100%;">
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                    <div id="rationaleDetails">
                        <div class="form-group">
                            <textarea class="form-control <?php $__errorArgs = ['Principles_Reasons'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="15"
                                name="Principles_Reasons" placeholder="กรอกข้อมูล" style="resize: vertical;"></textarea>
                            <?php $__errorArgs = ['Principles_Reasons'];
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

                <!-- วัตถุประสงค์โครงการ -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            8. วัตถุประสงค์โครงการ
                        </h4>
                    </div>
                    <div id="objectiveDetails">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label fw-bold">วัตถุประสงค์โครงการ:</label>
                                    <div class="d-flex flex-column">
                                        <select class="form-control <?php $__errorArgs = ['objective_select'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="objective_select" name="objective_select">
                                            <option value="" disabled selected>เลือกวัตถุประสงค์โครงการ</option>
                                            <?php $__currentLoopData = $strategicObjectives; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $objective): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($objective->id); ?>">
                                                <?php echo e($objective->Details_Strategic_Objectives); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                        <?php $__errorArgs = ['objective_select'];
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

                        <button type="button" id="addObjectiveBtn" class="btn btn-primary">
                            <i class='bx bx-plus-circle me-1'></i> เพิ่มวัตถุประสงค์
                        </button>

                        <div id="objectivesContainer" class="mt-3">
                        </div>
                    </div>
                </div>

                <!-- Template สำหรับสร้างรายการวัตถุประสงค์ใหม่ (ซ่อนไว้) -->
                <template id="objectiveItemTemplate">
                    <div class="objective-item mb-2 p-3 border rounded bg-light position-relative">
                        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 delete-objective"
                            data-id="__ID__"></button>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="fw-bold mb-1">วัตถุประสงค์:</div>
                                <div class="objective-text">__OBJECTIVE__</div>
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
                                            placeholder="กรอกสถานที่" style="min-width: 1000px;">
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
                                    <div class="form-group mt-2">
                                        <input type="text" class="form-control" name="Details_Short_Project[]"
                                            placeholder="เพิ่มรายการ">
                                        <button type="button" class="btn btn-danger btn-sm remove-method mt-2">
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
                                        <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <th><?php echo e($month); ?></th>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $pdcaStages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="PDCA">
                                            <div class="plan-text"><?php echo e($stage->Name_PDCA); ?>

                                            </div>
                                            <textarea class="plan-textarea auto-expand"
                                                name="pdca[<?php echo e($stage->Id_PDCA_Stages); ?>][detail]"
                                                placeholder="เพิ่มรายละเอียด"></textarea>
                                        </td>
                                        <?php for($i = 1; $i <= 12; $i++): ?> <td class="checkbox-container">
                                            <input type="checkbox" name="pdca[<?php echo e($stage->Id_PDCA_Stages); ?>][months][]"
                                                value="<?php echo e($i); ?>">
                                            </td>
                                            <?php endfor; ?>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

                        <div id="incomeForm" class="income-form">
                            <div class="form-group">
                                <label>แหล่งงบประมาณ</label>
                                <div class="mb-4">
                                    <?php $__currentLoopData = $budgetSources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $source): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="form-check mb-2 d-flex align-items-center">
                                        <input type="radio" id="<?php echo e($source->Id_Budget_Source); ?>" name="budget_source"
                                            value="<?php echo e($source->Id_Budget_Source); ?>" class="form-check-input"
                                            data-id="<?php echo e($source->Id_Budget_Source); ?>"
                                            onchange="handleSourceSelect(this)">
                                        <label class="form-check-label d-flex align-items-center w-100"
                                            for="<?php echo e($source->Id_Budget_Source); ?>">
                                            <span class="label-text"><?php echo e($source->Name_Budget_Source); ?></span>
                                            <input type="number" name="amount_<?php echo e($source->Id_Budget_Source); ?>"
                                                class="form-control form-control-sm w-25 ml-2" placeholder="จำนวนเงิน"
                                                disabled>
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
                                <div id="budgetFormsContainer">
                                    <div id="budgetFormTemplate" class="budget-form card mb-3">
                                        <div class="card-body">
                                            <h5>แบบฟอร์มที่ 1</h5>
                                            <button type="button" class="btn btn-danger btn-sm remove-form-btn"
                                                onclick="removeBudgetForm(this)">ลบแบบฟอร์ม</button>
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
                                                        <option value="" disabled selected>
                                                            เลือกหัวข้อย่อย</option>
                                                        <?php $__currentLoopData = $subtopBudgets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subtop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($subtop->Id_Subtopic_Budget); ?>">
                                                            <?php echo e($subtop->Name_Subtopic_Budget); ?>

                                                        </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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


                <!-- Output -->
                <div class="content-box">
                    <div class="section-header">
                        <h4>
                            16. เป้าหมายเชิงผลผลิต (Output)
                        </h4>
                    </div>
                    <div id="outputDetails">
                        <div id="outputContainer" class="dynamic-container">
                            <div class="form-group mt-2">
                                <input type="text" class="form-control" name="outputs[]" placeholder="เพิ่มรายการ">
                                <button type="button" class="btn btn-danger btn-sm remove-field mt-2">
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
                            <div class="form-group mt-2">
                                <input type="text" class="form-control" name="outcomes[]" placeholder="เพิ่มรายการ">
                                <button type="button" class="btn btn-danger btn-sm remove-field mt-2">
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
                            <div class="form-group mt-2">
                                <input type="text" class="form-control" name="expected_results[]"
                                    placeholder="เพิ่มรายการ">
                                <button type="button" class="btn btn-danger btn-sm remove-field mt-2">
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
                            <label for="Success_Indicators"></label>
                            <textarea class="form-control <?php $__errorArgs = ['Success_Indicators'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="Success_Indicators" name="Success_Indicators" rows="4"
                                placeholder="กรอกตัวชี้วัดความสำเร็จของโครงการ"><?php echo e(old('Success_Indicators')); ?></textarea>
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
                            20. ค่าเป้าหมาย
                        </h4>
                    </div>
                    <div id="valueTargetDetails">
                        <div class="form-group">
                            <label for="Value_Target"></label>
                            <textarea class="form-control <?php $__errorArgs = ['Value_Target'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="Value_Target"
                                name="Value_Target" rows="4"
                                placeholder="กรอกค่าเป้าหมาย"><?php echo e(old('Value_Target')); ?></textarea>
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
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class='bx bx-save'></i> บันทึกข้อมูล
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?php echo e(asset('js/editBigFormProject.js')); ?>"></script>


<script>
document.addEventListener('DOMContentLoaded', function() {
    $.datepicker.setDefaults($.datepicker.regional['th']);
    $("#First_Time").datepicker({
        dateFormat: 'dd MM yy',
        changeMonth: true,
        changeYear: true,
        yearRange: "c-100:c+10",
        onSelect: function(selectedDate) {
            $("#End_Time").datepicker("option", "minDate", selectedDate);
        }
    });
    $("#End_Time").datepicker({
        dateFormat: 'dd MM yy',
        changeMonth: true,
        changeYear: true,
        yearRange: "c-100:c+10"
    });
});
</script>

<script>
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

    // เพิ่มวัตถุประสงค์ใหม่
    document.getElementById('addObjectiveBtn').addEventListener('click', function() {
        let objectiveText, objectiveValue;

        // รับค่าวัตถุประสงค์
        const objectiveTextarea = document.getElementById('objective_manual');
        const objectiveSelect = document.getElementById('objective_select');

        if (objectiveTextarea.style.display !== 'none' && objectiveTextarea.value.trim() !== '') {
            objectiveText = objectiveTextarea.value.trim();
            objectiveValue = 'manual:' + objectiveText; // เพิ่มคำนำหน้าเพื่อระบุว่าเป็นการกรอกเอง
        } else if (!objectiveSelect.disabled && objectiveSelect.selectedIndex > 0) {
            objectiveText = objectiveSelect.options[objectiveSelect.selectedIndex].text;
            objectiveValue = objectiveSelect.value;
        } else {
            alert('กรุณาเลือกหรือกรอกวัตถุประสงค์โครงการ');
            return;
        }

        // สร้างรายการวัตถุประสงค์ใหม่จาก template
        const template = document.getElementById('objectiveItemTemplate').innerHTML;
        const newId = Date.now(); // ใช้เวลาปัจจุบันเป็น ID ชั่วคราว

        let newObjectiveHtml = template
            .replace('__ID__', newId)
            .replace('__OBJECTIVE__', objectiveText)
            .replace('__OBJECTIVE_VALUE__', objectiveValue);

        // เพิ่มรายการใหม่เข้าไปใน container
        document.getElementById('objectivesContainer').insertAdjacentHTML('beforeend',
            newObjectiveHtml);

        // เพิ่ม event listener สำหรับปุ่มลบ
        document.querySelectorAll('.delete-objective[data-id="' + newId + '"]').forEach(function(
            button) {
            button.addEventListener('click', function() {
                this.closest('.objective-item').remove();
            });
        });

        // รีเซ็ตฟอร์ม
        resetObjectiveForm();
    });

    // ฟังก์ชันรีเซ็ตฟอร์ม
    function resetObjectiveForm() {
        // รีเซ็ตวัตถุประสงค์
        document.getElementById('objective_select').selectedIndex = 0;
        document.getElementById('objective_select').disabled = false;
        document.getElementById('objective_manual').value = '';
        document.getElementById('objective_manual').style.display = 'none';
        document.getElementById('toggleObjectiveInput').innerHTML =
            '<i class="bx bx-edit"></i> กรอกวัตถุประสงค์ด้วยตนเอง';
    }

    // เพิ่มการตรวจสอบก่อนส่งฟอร์ม
    document.querySelector('form').addEventListener('submit', function(event) {
        // ตรวจสอบว่ามีการกรอกข้อมูลในฟอร์มวัตถุประสงค์แต่ยังไม่ได้กดปุ่มเพิ่ม
        const objectiveSelect = document.getElementById('objective_select');
        const objectiveTextarea = document.getElementById('objective_manual');

        // ตรวจสอบว่ามีการกรอกข้อมูลวัตถุประสงค์ (ทั้งจาก select หรือ textarea)
        let hasObjectiveValue = false;

        if ((objectiveSelect.selectedIndex > 0 && !objectiveSelect.disabled)) {
            hasObjectiveValue = true;
        } else if (objectiveTextarea.style.display !== 'none' && objectiveTextarea.value.trim() !==
            '') {
            hasObjectiveValue = true;
        }

        // ถ้ามีการกรอกวัตถุประสงค์ แต่ยังไม่ได้กดปุ่มเพิ่ม
        if (hasObjectiveValue) {
            event.preventDefault(); // หยุดการส่งฟอร์ม

            if (confirm(
                    'คุณได้กรอกข้อมูลวัตถุประสงค์โครงการแล้ว แต่ยังไม่ได้กดปุ่ม "เพิ่มวัตถุประสงค์" ต้องการเพิ่มข้อมูลนี้ก่อนบันทึกหรือไม่?'
                )) {
                // กดปุ่มเพิ่มวัตถุประสงค์ให้อัตโนมัติ
                document.getElementById('addObjectiveBtn').click();

                // ส่งฟอร์มหลังจากเพิ่มวัตถุประสงค์
                setTimeout(() => {
                    // ล้างค่าฟอร์ม
                    document.getElementById('objective_manual').value = '';

                    this.submit();
                }, 300);
            } else {
                // ล้างค่าฟอร์ม
                document.getElementById('objective_manual').value = '';

                this.submit();
            }
        }
    });

    // ติดตั้ง event listener สำหรับปุ่มลบที่มีอยู่แล้ว
    document.querySelectorAll('.delete-objective').forEach(function(button) {
        button.addEventListener('click', function() {
            this.closest('.objective-item').remove();
        });
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('navbar.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/Project/editBigFormProject.blade.php ENDPATH**/ ?>