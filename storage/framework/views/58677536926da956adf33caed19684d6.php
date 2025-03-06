<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/allProject.css')); ?>">
</head>
<body>
<?php $__env->startSection('content'); ?>
<h1>โครงการทั้งหมด</h1>
<div class="content-box">
    <?php $__currentLoopData = $allProject; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $allproject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="content-box-list mb-2 mt-2">
        <div class="project-group">
            <div class="project-info">
                <h5>
                    <b><?php echo e($allproject->Name_Project); ?></b><br>
                </h5>
            </div>
            <a href="<?php echo e(route('PDF.projectCtrlP', $allproject->Id_Project)); ?>" class="viewproject">ดูเอกสารโครงการ<i class='bx bx-right-arrow-alt icon'></i></a>
        </div>

        <div class="project-group">
            <p>เอกสารทั้งหมด</p>
            <p>count = 3</p>
        </div>

        <div class="project-group">
                <p>อัพเดตล่าสุด</p>
                <p>01/01/2568 (timestamp)</p>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

    <div class="menu-footer">
        <div>แสดง <?php echo e($allProject->firstItem()); ?> ถึง <?php echo e($allProject->lastItem()); ?> จาก <?php echo e($allProject->total()); ?> รายการ</div>
        <div class="pagination">
            <?php if($allProject->onFirstPage()): ?>
                <button class="pagination-btn" disabled>ก่อนหน้า</button>
            <?php else: ?>
                <a href="<?php echo e($allProject->previousPageUrl()); ?>" class="pagination-btn">ก่อนหน้า</a>
            <?php endif; ?>
    
            <span class="page-number">
                <span id="currentPage"><?php echo e($allProject->currentPage()); ?></span>
            </span>
    
            <?php if($allProject->hasMorePages()): ?>
                <a href="<?php echo e($allProject->nextPageUrl()); ?>" class="pagination-btn">ถัดไป</a>
            <?php else: ?>
                <button class="pagination-btn" disabled>ถัดไป</button>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?> 
</body>
</html>
<?php echo $__env->make('navbar.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/PlanDLC/allProject.blade.php ENDPATH**/ ?>