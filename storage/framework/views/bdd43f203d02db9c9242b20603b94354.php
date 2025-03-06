<hade>
    <link rel="stylesheet" href="<?php echo e(asset('css/viewProject.css')); ?>">

    <style>
    .budget-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #ddd;
        padding-bottom: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .budget-item strong {
        flex: 1;
    }

    .budget-item select,
    .budget-item textarea,
    .budget-item input {
        flex: 2;
    }

    .budget-item .amount {
        text-align: right;
    }
    </style>
</hade>

<?php $__env->startSection('content'); ?>
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
                        <?php $__currentLoopData = $project->subProjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $subProject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-group mt-2">
                            <label for="Name_Sup_Project_<?php echo e($index + 1); ?>" class="form-label">โครงการย่อยที่
                                <?php echo e($index + 1); ?></label>
                            <input type="text" class="form-control" id="Name_Sup_Project_<?php echo e($index + 1); ?>"
                                name="Name_Sup_Project[]" value="<?php echo e($subProject->Name_Sup_Project); ?>"
                                placeholder="กรอกชื่อโครงการย่อย">
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                        <select class="form-select <?php $__errorArgs = ['employee_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="employee_id"
                            name="employee_id" disabled>
                            <option value="" selected disabled>เลือกผู้รับผิดชอบ</option>
                            <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($employee->Id_Employee); ?>"
                                <?php echo e($employee->Id_Employee == $project->Employee_Id ? 'selected' : ''); ?>>
                                <?php echo e($employee->Firstname_Employee); ?> <?php echo e($employee->Lastname_Employee); ?>

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

            <!-- ความสอดคล้องกับยุทธศาสตร์มหาวิทยาลัย -->
            <div class="content-box">
                <div class="section-header">
                    <h4>
                        4. ความสอดคล้องกับยุทธศาสตร์มหาวิทยาลัย
                    </h4>
                </div>
                <div id="strategicDetails">
                    <div id="platform-container">
                        <?php $__currentLoopData = $project->platforms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $platform): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="platform-card">
                            <div class="card-header">
                                <h3 class="card-title"><?php echo e($platform->Name_Platform); ?></h3>
                            </div>
                            <div class="form-group">
                                <label class="form-label">ชื่อแพลตฟอร์ม</label>
                                <input type="text" name="platforms[<?php echo e($loop->index); ?>][name]" class="form-control"
                                    value="<?php echo e($platform->Name_Platform); ?>" disabled>
                            </div>

                            <?php $__currentLoopData = $platform->programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="form-group">
                                <label class="form-label">โปรแกรม</label>
                                <input type="text"
                                    name="platforms[<?php echo e($loop->parent->index); ?>][programs][<?php echo e($loop->index); ?>][name]"
                                    class="form-control" value="<?php echo e($program->Name_Program); ?>" disabled>
                            </div>

                            <div class="form-group kpi-container">
                                <div class="kpi-header">
                                    <label class="form-label">KPI</label>
                                </div>
                                <div class="kpi-group">
                                    <?php $__currentLoopData = $program->kpis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kpi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="input-group mt-2">
                                        <input type="text"
                                            name="platforms[<?php echo e($loop->parent->parent->index); ?>][programs][<?php echo e($loop->index); ?>][kpis][<?php echo e($loop->index); ?>][name]"
                                            class="form-control" value="<?php echo e($kpi->Name_KPI); ?>" disabled>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                value="<?php echo e($strategics->Name_Strategic_Plan); ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="Name_Strategy" class="form-label">กลยุทธ์</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['Name_Strategy'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                name="Name_Strategy" id="Name_Strategy" value="<?php echo e($project->Name_Strategy ?? '-'); ?>"
                                readonly>
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

            <!-- SDGs -->
            <div class="content-box">
                <div class="section-header">
                    <h4>
                        6. SDGs ที่เกี่ยวข้อง
                    </h4>
                </div>
                <div id="sdgDetails">
                    <div class="form-group">
                        <?php if($project->sdgs->isEmpty()): ?>
                        <div class="input-group mt-2">
                            <input type="text" class="form-control" value="ไม่มี SDGs ที่เกี่ยวข้อง" disabled>
                        </div>
                        <?php else: ?>
                        <?php $__currentLoopData = $project->sdgs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sdg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="input-group mt-2">
                            <input type="text" class="form-control" value="<?php echo e($sdg->Name_SDGs); ?>" disabled>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
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
                        <?php if($project->projectHasIntegrationCategories->isEmpty()): ?>
                        <div class="input-group mt-2">
                            <input type="text" class="form-control" value="ไม่มีการบูรณาการที่เกี่ยวข้อง" disabled>
                        </div>
                        <?php else: ?>
                        <?php $__currentLoopData = $project->projectHasIntegrationCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $integration): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="input-group mt-2">
                            <input type="text" class="form-control"
                                value="<?php echo e($integration->integrationCategory->Name_Integration_Category); ?>" disabled>
                            <input type="text" class="form-control" value="<?php echo e($integration->Integration_Details); ?>"
                                disabled>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
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
                            disabled><?php echo e($project->Principles_Reasons); ?></textarea>
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
                            placeholder="กรอกข้อมูล" disabled><?php echo e($project->Objective_Project); ?></textarea>
                    </div>
                </div>
            </div>

            <!-- กลุ่มเป้าหมาย -->
            <div class="content-box">
                <div class="section-header">
                    <h4>
                        10. กลุ่มเป้าหมาย
                    </h4>
                </div>
                <div id="targetGroupDetails" style="display: block;">
                    <div id="targetGroupContainer">
                        <?php $__currentLoopData = $project->targets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $target): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="target-group-item">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" name="target_group[]" class="form-control"
                                        placeholder="กรอกกลุ่มเป้าหมาย" value="<?php echo e($target->Name_Target); ?>" disabled>
                                    <input type="number" name="target_count[]" class="form-control" placeholder="จำนวน"
                                        value="<?php echo e($target->Quantity_Target); ?>" disabled>
                                    <input type="text" name="target_unit[]" class="form-control" placeholder="หน่วย"
                                        value="<?php echo e($target->Unit_Target); ?>" disabled>
                                </div>
                            </div>
                            <?php $__currentLoopData = $target->targetDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="form-group mt-3">
                                <label>รายละเอียดกลุ่มเป้าหมาย</label>
                                <textarea class="form-control" name="target_details[]" placeholder="กรอกรายละเอียด"
                                    disabled><?php echo e($detail->Details_Target); ?></textarea>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>

            <!-- สถานที่ดำเนินงาน -->
            <div class="content-box">
                <div class="section-header">
                    <h4>
                        11. สถานที่ดำเนินงาน
                    </h4>
                </div>
                <div id="locationDetails" style="display: block;">
                    <div id="locationContainer">
                        <?php if($project->locations->isNotEmpty()): ?>
                        <?php $__currentLoopData = $project->locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-group location-item">
                            <input type="text" class="form-control small-input" name="location[]"
                                value="<?php echo e($location->Name_Location); ?>" disabled>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                        <div class="form-group location-item">
                            <input type="text" class="form-control small-input" value="-" disabled>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- ตัวชี้วัด -->
            <div class="content-box">
                <div class="section-header">
                    <h4>
                        12. ตัวชี้วัด
                    </h4>
                </div>
                <div id="indicatorsDetails" style="display: block;">
                    <?php
                    $quantitativeIndicators = $project->projectHasIndicators->filter(function($indicator) {
                    return $indicator->indicators->Type_Indicators === 'Quantitative';
                    });
                    $qualitativeIndicators = $project->projectHasIndicators->filter(function($indicator) {
                    return $indicator->indicators->Type_Indicators === 'Qualitative';
                    });
                    ?>

                    <?php if($quantitativeIndicators->isNotEmpty()): ?>
                    <div class="form-group">
                        <h6>เชิงปริมาณ</h6>
                        <?php $__currentLoopData = $quantitativeIndicators; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $indicator): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-group">
                            <textarea class="form-control" name="indicators_details[]" placeholder="กรอกรายละเอียด"
                                disabled><?php echo e($indicator->Details_Indicators); ?></textarea>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php else: ?>
                    <div class="form-group">
                        <h6>เชิงปริมาณ</h6>
                        <div class="form-group">
                            <textarea class="form-control" placeholder="-" disabled>-</textarea>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($qualitativeIndicators->isNotEmpty()): ?>
                    <div class="form-group">
                        <h6>เชิงคุณภาพ</h6>
                        <?php $__currentLoopData = $qualitativeIndicators; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $indicator): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-group">
                            <textarea class="form-control" name="indicators_details[]" placeholder="กรอกรายละเอียด"
                                disabled><?php echo e($indicator->Details_Indicators); ?></textarea>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php else: ?>
                    <div class="form-group">
                        <h6>เชิงคุณภาพ</h6>
                        <div class="form-group">
                            <textarea class="form-control" placeholder="-" disabled>-</textarea>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ระยะเวลาดำเนินโครงการ -->
            <div class="content-box">
                <div class="section-header">
                    <h4>
                        13. ระยะเวลาดำเนินโครงการ
                    </h4>
                </div>
                <div id="projectDurationDetails" style="display: block;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="First_Time">วันที่เริ่มต้น:</label>
                                <input type="text" class="form-control" id="First_Time" name="First_Time"
                                    value="<?php echo e($firstTime ?? '-'); ?>" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="End_Time">วันที่สิ้นสุด:</label>
                                <input type="text" class="form-control" id="End_Time" name="End_Time"
                                    value="<?php echo e($endTime ?? '-'); ?>" disabled>
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
                        <i class='bx bx-chevron-up' id="toggleIconPlan" style="cursor: pointer; font-size: 1.5em;"
                            onclick="togglePlanDetails()"></i>
                    </h4>
                </div>
                <div id="planDetails">
                    <div class="form-group-radio mb-4">
                        <input type="radio" name="Project_Type" value="S" id="shortTermProject"
                            <?php echo e($project->Project_Type == 'S' ? 'checked' : ''); ?> disabled>
                        <label for="shortTermProject">โครงการระยะสั้น</label>
                        &nbsp;&nbsp;
                        <input type="radio" name="Project_Type" value="L" id="longTermProject"
                            <?php echo e($project->Project_Type == 'L' ? 'checked' : ''); ?> disabled>
                        <label for="longTermProject">โครงการระยะยาว</label>
                    </div>

                    <?php if($project->Project_Type == 'S'): ?>
                    <!-- วิธีการดำเนินงาน -->
                    <div id="textbox-planType-1" data-group="planType">
                        <div class="method-form">
                            <div class="form-label">วิธีการดำเนินงาน</div>
                            <div id="methodContainer" class="method-items">
                                <?php $__currentLoopData = $shortProjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shortProject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="form-group mt-2">
                                    <input type="text" class="form-control"
                                        value="<?php echo e($shortProject->Details_Short_Project); ?>" readonly>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($project->Project_Type == 'L'): ?>
                    <div id="textbox-planType-2" data-group="planType">
                        <table class="table-PDCA">
                            <thead>
                                <tr>
                                    <th rowspan="2">กิจกรรมและแผนการเบิกจ่ายงบประมาณ</th>
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
                                        <div class="plan-text"><?php echo e($stage->Name_PDCA); ?></div>
                                        <?php
                                        $details = $pdcaDetails->where('PDCA_Stages_Id',
                                        $stage->Id_PDCA_Stages)->first();
                                        ?>
                                        <textarea class="plan-textarea auto-expand"
                                            name="pdca[<?php echo e($stage->Id_PDCA_Stages); ?>][detail]"
                                            placeholder="เพิ่มรายละเอียด"><?php echo e($details ? $details->Details_PDCA : ''); ?></textarea>
                                    </td>
                                    <?php for($i = 1; $i <= 12; $i++): ?> <td class="checkbox-container">
                                        <input type="checkbox" name="pdca[<?php echo e($stage->Id_PDCA_Stages); ?>][months][]"
                                            value="<?php echo e($i); ?>"
                                            <?php echo e($monthlyPlans->where('PDCA_Stages_Id', $stage->Id_PDCA_Stages)->where('Months_Id', $i)->isNotEmpty() ? 'checked' : ''); ?>>
                                        </td>
                                        <?php endfor; ?>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
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
                                <?php echo e($project->Status_Budget == 'N' ? 'checked' : ''); ?> disabled>
                            <label for="non_income">ไม่แสวงหารายได้</label>

                            <input type="radio" name="Status_Budget" value="Y" id="income_seeking"
                                <?php echo e($project->Status_Budget == 'Y' ? 'checked' : ''); ?> disabled>
                            <label for="income_seeking">แสวงหารายได้</label>
                        </div>
                    </div>

                    <div id="incomeForm" class="income-form"
                        style="<?php echo e($project->Status_Budget == 'Y' ? 'display: block;' : 'display: none;'); ?>">
                        <div class="form-group">
                            <label>แหล่งงบประมาณ</label>
                            <div class="mb-4">
                                <?php $__currentLoopData = $projectBudgetSources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $projectBudgetSource): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                $source = $budgetSources->firstWhere('Id_Budget_Source',
                                $projectBudgetSource->Budget_Source_Id);
                                ?>
                                <div class="form-check mb-2 d-flex align-items-center">
                                    <input type="radio" id="<?php echo e($source->Id_Budget_Source); ?>" name="budget_source"
                                        value="<?php echo e($source->Id_Budget_Source); ?>" class="form-check-input"
                                        data-id="<?php echo e($source->Id_Budget_Source); ?>"
                                        <?php echo e($projectBudgetSource->Budget_Source_Id == $source->Id_Budget_Source ? 'checked' : ''); ?>

                                        disabled>
                                    <label class="form-check-label d-flex align-items-center w-100"
                                        for="<?php echo e($source->Id_Budget_Source); ?>">
                                        <span class="label-text"><?php echo e($source->Name_Budget_Source); ?></span>
                                        <input type="number" name="amount_<?php echo e($source->Id_Budget_Source); ?>"
                                            class="form-control form-control-sm w-25 ml-2" placeholder="จำนวนเงิน"
                                            value="<?php echo e($projectBudgetSource->Amount_Total); ?>" disabled>
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
                                        placeholder="ระบุรายละเอียดค่าใช้จ่าย"
                                        disabled><?php echo e($projectBudgetSources->first()->Details_Expense ?? ''); ?></textarea>
                                </div>
                            </div>

                            <div class="form-group-radio">
                                <label>กรอกแบบฟอร์มงบประมาณ</label>
                                <div class="radio-group">
                                    <input type="radio" name="fill_budget_form" value="yes" id="fill_yes"
                                        <?php echo e($budgetForms->isNotEmpty() ? 'checked' : ''); ?> disabled>
                                    <label for="fill_yes">กรอกแบบฟอร์มงบประมาณ</label>

                                    <input type="radio" name="fill_budget_form" value="no" id="fill_no"
                                        <?php echo e($budgetForms->isEmpty() ? 'checked' : ''); ?> disabled>
                                    <label for="fill_no">ไม่กรอกแบบฟอร์มงบประมาณ</label>
                                </div>
                            </div>

                            <!-- แบบฟอร์มงบประมาณ -->
                            <div id="budgetFormsContainer"
                                style="<?php echo e($budgetForms->isNotEmpty() ? 'display: block;' : 'display: none;'); ?>">
                                <table class="min-w-full border-collapse">
                                    <thead>
                                        <tr>
                                            <th class="border px-4 py-2 text-center">รายการ</th>
                                            <th class="border px-4 py-2 text-center">จำนวนเงิน (บาท)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $budgetForms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $budgetForm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="border px-4 py-2">
                                                <div class="budget-item">
                                                    <strong>หัวข้อใหญ่:</strong> <?php echo e($budgetForm->Big_Topic); ?>

                                                </div>
                                                <div class="budget-item">
                                                    <strong>หัวข้อย่อย:</strong>
                                                    <select name="subActivity[]" class="form-control" disabled>
                                                        <option value="" disabled selected>เลือกหัวข้อย่อย</option>
                                                        <?php $__currentLoopData = $subtopBudgets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subtop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($subtop->Id_Subtopic_Budget); ?>"
                                                            <?php echo e($subtopicBudgetForms->where('Subtopic_Budget_Id', $subtop->Id_Subtopic_Budget)->where('Budget_Form_Id', $budgetForm->Id_Budget_Form)->isNotEmpty() ? 'selected' : ''); ?>>
                                                            <?php echo e($subtop->Name_Subtopic_Budget); ?>

                                                        </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                                <?php $__currentLoopData = $subtopicBudgetForms->where('Budget_Form_Id',
                                                $budgetForm->Id_Budget_Form); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subtopicBudgetForm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="budget-item">
                                                    <strong>รายละเอียด:</strong>
                                                    <textarea name="description[]" class="form-control"
                                                        placeholder="เช่น ค่าอาหารว่างสำหรับการจัดประชุมคณะกรรมการจัดการความรู้"
                                                        disabled><?php echo e($subtopicBudgetForm->Details_Subtopic_Form); ?></textarea>
                                                </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </td>
                                            <td class="border px-4 py-2 text-right">
                                                <div class="budget-item">
                                                    <strong>จำนวนเงินทั้งหมด:</strong>
                                                    <span
                                                        class="amount"><?php echo e(number_format($budgetForm->Amount_Big, 2)); ?></span>
                                                </div>
                                                <?php $__currentLoopData = $subtopicBudgetForms->where('Budget_Form_Id',
                                                $budgetForm->Id_Budget_Form); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subtopicBudgetForm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="budget-item">
                                                    <strong>จำนวนเงิน:</strong>
                                                    <input type="number" name="amount[]" class="form-control amount"
                                                        placeholder="880" value="<?php echo e($subtopicBudgetForm->Amount_Sub); ?>"
                                                        disabled>
                                                </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
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
                        <?php if($outputs->isEmpty()): ?>
                        <div class="form-group mt-2">
                            <input type="text" class="form-control" value="-" readonly>
                        </div>
                        <?php else: ?>
                        <?php $__currentLoopData = $outputs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $output): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-group mt-2">
                            <input type="text" class="form-control" name="outputs[]" value="<?php echo e($output->Name_Output); ?>"
                                readonly>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </div>
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
                        <?php if($outcomes->isEmpty()): ?>
                        <div class="form-group mt-2">
                            <input type="text" class="form-control" value="-" readonly>
                        </div>
                        <?php else: ?>
                        <?php $__currentLoopData = $outcomes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $outcome): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-group mt-2">
                            <input type="text" class="form-control" name="outcomes[]"
                                value="<?php echo e($outcome->Name_Outcome); ?>" readonly>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </div>
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
                        <?php if($expectedResults->isEmpty()): ?>
                        <div class="form-group mt-2">
                            <input type="text" class="form-control" value="-" readonly>
                        </div>
                        <?php else: ?>
                        <?php $__currentLoopData = $expectedResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expectedResult): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-group mt-2">
                            <input type="text" class="form-control" name="expected_results[]"
                                value="<?php echo e($expectedResult->Name_Expected_Results); ?>" readonly>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // หลักการและเหตุผล&&วัตถุประสงค์โครงการ
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        const lineHeight = parseInt(window.getComputedStyle(textarea).lineHeight);
        const rows = Math.ceil(textarea.scrollHeight / lineHeight);
        textarea.setAttribute('rows', rows);
    });

    // ตัวชี้วัด
    const textareas = document.querySelectorAll('#indicatorsDetails textarea');
    textareas.forEach(textarea => {
        const lineHeight = parseInt(window.getComputedStyle(textarea).lineHeight);
        const rows = Math.ceil(textarea.scrollHeight / lineHeight);
        textarea.setAttribute('rows', rows);
    });

});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('navbar.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/Project/viewProject.blade.php ENDPATH**/ ?>