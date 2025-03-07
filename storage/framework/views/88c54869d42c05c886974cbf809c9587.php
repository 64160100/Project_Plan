<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>reportResult</title>
    <!-- <link rel="stylesheet" href="<?php echo e(asset('css/project.css')); ?>"> -->
    <link rel="stylesheet" href="<?php echo e(asset('css/reportResult.css')); ?>">

</head>

<body>
<?php $__env->startSection('content'); ?>
<button type="button" class="btn-editBudget" data-bs-toggle="modal" data-bs-target="#reportResult">
    <i class='bx bx-plus'></i>รายงานผล
</button>


<div class="modal fade" id="reportResult" tabindex="-1" aria-labelledby="reportResultLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 80%; width: auto;">
        <form action="<?php echo e(route('reportResult')); ?>" method="POST">
            <div class="modal-content">
                <?php echo csrf_field(); ?>
                    
                    <div class="modal-header">
                        <h1 class="modal-title" id="reportResultLabel">สรุปผลการดำเนินงาน <?php echo e($report->Name_Project); ?></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    
                    <div class="modal-body">
                        <div class="mb-3">
                            <h2>ส่วนที่ 1 ผลการดำเนินงาน</h2>
                            <textarea class="form-control" placeholder="กรอกรายละเอียดผลการดำเนินงาน" rows="20"></textarea>
                        </div>

                        <h2>ส่วนที่ 2 ผลสำเร็จตามตัวชี้วัดของโครงการ</h2>
                        <div class="mb-3">
                            <div class="content-box">
                                <div>
                                    <label>กลยุทธ์</label>
                                    <select id="categoryDropdown" class="mb-2">
                                        <option value=""> เลือกกลยุทธ์ </option>
                                        <?php $__currentLoopData = $strategies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $strategy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($strategy->Id_Strategy); ?>">
                                                <?php echo e($strategy->Name_Strategy); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <div class="kpi-list" id="kpiList"></div><br>

                                    <?php $__currentLoopData = $projectIndicator; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $projectIndicators): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="content-box mb-2">   
                                                <label class="">
                                                <?php if($projectIndicators->indicators->Type_Indicators == 'Quantitative'): ?>
                                                    ตัวชี้วัดเชิงปริมาณ
                                                <?php elseif($projectIndicators->indicators->Type_Indicators == 'Qualitative'): ?>
                                                    ตัวชี้วัดเชิงคุณภาพ
                                                <?php else: ?>
                                                    เชิงอื่นๆ
                                                <?php endif; ?>
                                            </label>

                                            <input type="text" class="form-control" value="<?php echo e($projectIndicators->Details_Indicators); ?>" readonly>
                                            
                                            <label class="mt-3">ผลสำเร็จตัวชี้วัด</label>
                                            <textarea class="form-control" rows="3" placeholder="เช่น มีผู้เข้าร่วม 100 คน" require></textarea>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                            
                        <h2>ส่วนที่ 3 การมีส่วนร่วมของหน่วยงานภายนอก/ชุมชน</h2>
                        <div class="mb-3">
                            <textarea class="form-control" id="external_participation" name="external_participation" rows="5" placeholder="กรอกข้อมูลเพิ่มเติม" require></textarea>
                        </div>


                        <h2>ส่วนที่ 4 งบประมาณ</h2>
                            <div class="mb-3">
                                <div class="content-box">
                                    <!-- <h5>งบประมาณ</h5> -->
                                    <label>แหล่งงบประมาณ</label>
                                    <div class="content-box mb-3">
                                            <div class="d-flex align-items-center">
                                                <p><?php echo e($projectBudgetSource->budgetSource->Name_Budget_Source); ?>

                                                <span><b><?php echo e($projectBudgetSource->Amount_Total); ?></b></span>&nbsp;&nbsp; บาท <br>
                                                รายละเอียดค่าใช้จ่าย : <b><?php echo e($projectBudgetSource->Details_Expense); ?></b>
                                                </p>
                                            </div>
                                    </div>

                                    <?php if(!empty($budgetProject) && !empty($budgetProject->Amount_Big)): ?>
                                        <div class="content-box">
                                            <label>หัวข้อใหญ่</label>
                                            <div class="content-box mb-3">
                                                <p><?php echo e($budgetProject->Big_Topic); ?></p>
                                            </div>
                                            
                                            
                                            <?php if(!empty($subBudgetProject) && !empty($subBudgetProject->Amount_Sub)): ?>
                                                <label>รายละเอียดค่าใช้จ่ายโครงการ</label>
                                                <div class="content-box">
                                                    <!-- foreach ($subBudgetProject as $subBudgetProjects) -->
                                                        <div><?php echo e($subBudgetProject->subtopicBudget->Name_Subtopic_Budget); ?></div>
                                                    <!-- endforeach -->
                                                    <div class="d-flex align-items-center">
                                                        <input type="text" class="form-control me-2" value="<?php echo e($subBudgetProject->Details_Subtopic_Form); ?>" readonly style="max-width: 500px;">
                                                        <input type="text" class="form-control me-2" value="<?php echo e($subBudgetProject->Amount_Sub); ?>" readonly style="max-width: 500px;">
                                                        <span>บาท</span>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <h4 class="budget">
                                            ใช้งบประมาณทั้งสิ้น&nbsp;<span><strong><?php echo e($budgetProject->Amount_Big); ?></strong></span>&nbsp;บาท
                                        </h4>
                                    <?php else: ?>
                                        <div class="text-danger"><b>ไม่มีงบประมาณ</b></div>
                                    <?php endif; ?>
                                </div>
                            </div>


                        <h2>ส่วนที่ 5 ข้อเสนอแนะ</h2>
                        <div class="mb-3">
                            <textarea class="form-control" placeholder="กรอกข้อเสนอแนะ" rows="8"></textarea>
                        </div>

                    </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                </div>
                
            </div>
        </form>
    </div>
</div>


<!-- เลือกกลยุท -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const categoryDropdown = document.getElementById("categoryDropdown");
        const kpiList = document.getElementById("kpiList");

        // แปลง PHP เป็น JSON โดยตรง
        const kpiData = <?php echo json_encode($strategies, 15, 512) ?>;

        categoryDropdown.addEventListener("change", function () {
            const selectedId = this.value;
            const strategy = kpiData.find(s => s.Id_Strategy == selectedId);
            kpiList.innerHTML = ""; // ล้างค่าเก่าออก

            if (strategy && strategy.kpis.length > 0) {
                strategy.kpis.forEach(kpi => {
                    kpiList.innerHTML += `
                        <div class="d-flex align-items-center mb-2">
                            <input type="checkbox" value="${kpi.Id_Kpi}" class="me-2">
                            <textarea class="form-control me-2" readonly>${kpi.Name_Kpi}</textarea>
                            <textarea class="form-control" readonly>${kpi.Target_Value}</textarea>
                        </div>`;
                });
            } else {
                kpiList.innerHTML = "<p class='text-muted'>ไม่มีข้อมูล KPI</p>";
            }
        });
    });
</script>


<?php $__env->stopSection(); ?>

</body>
</html>
<?php echo $__env->make('navbar.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/reportResult.blade.php ENDPATH**/ ?>