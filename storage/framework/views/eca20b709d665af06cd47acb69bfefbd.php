<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/checkBudget.css')); ?>">
</head>
<body>
<?php $__env->startSection('content'); ?>
    <div class="header">
        <h1>ตรวจสอบงบประมาณ</h1>
        <button type="button" class="btn-editBudget" data-bs-toggle="modal" data-bs-target="#editBudget">
            <i class='bx bx-plus'></i>แก้งบประมาณ
        </button>
    </div><br>
   
        <div class="content-box">
            <div class="all-budget">
                <b>งบประมาณทั้งหมด ปี พ.ศ.2567</b>
                <div class="budget-amount">฿10,000,000</div>
            </div><br>

            <div class="content-box-list">
                <h4>รายการใช้งบประมาณล่าสุด</h4>
                <table class="project-table">
                    <thead>
                        <tr>
                            <th>โครงการ</th>
                            <th>ประเภทงบ</th>
                            <th>งบประมาณที่ใช้</th>
                            <th>การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $budgetProject; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $budgetproject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($budgetproject->Name_Project); ?></td>
                            <td><b>งบดำเนินงาน</b></td>
                            <td>฿80000</td>
                            <td>
                                <a href="<?php echo e(route('editProject', $budgetproject->Id_Project)); ?>" class="btn-editBudget">
                                    <i class='bx bx-edit'></i>ปรับแก้งบประมาณ
                                </a> 
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php echo $__env->make('PlanDLC.editBudget', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        
        <div class="menu-footer">
            <div>แสดง <?php echo e($budgetProject->firstItem()); ?> ถึง <?php echo e($budgetProject->lastItem()); ?> จาก <?php echo e($budgetProject->total()); ?> รายการ</div>
            <div class="pagination">
                <?php if($budgetProject->onFirstPage()): ?>
                    <button class="pagination-btn" disabled>ก่อนหน้า</button>
                <?php else: ?>
                    <a href="<?php echo e($budgetProject->previousPageUrl()); ?>" class="pagination-btn">ก่อนหน้า</a>
                <?php endif; ?>
        
                <span class="page-number">
                    <span id="currentPage"><?php echo e($budgetProject->currentPage()); ?></span>
                </span>
        
                <?php if($budgetProject->hasMorePages()): ?>
                    <a href="<?php echo e($budgetProject->nextPageUrl()); ?>" class="pagination-btn">ถัดไป</a>
                <?php else: ?>
                    <button class="pagination-btn" disabled>ถัดไป</button>
                <?php endif; ?>
            </div>
        </div>
    

<?php $__env->stopSection(); ?>
</body>
</html>
<?php echo $__env->make('navbar.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/PlanDLC/checkBudget.blade.php ENDPATH**/ ?>