<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>report</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/report.css')); ?>">

</head>
<body>
<?php $__env->startSection('content'); ?>
    <h1>รายงานผล</h1>
    <div class="content-box">
        <div class="content-box">
            <h4>โครงการล่าสุด</h4>
            <!-- link -->
            <?php $__currentLoopData = $lastestProject; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lastestproject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('editProject', $lastestproject->Id_Project)); ?>" class="project-status mt-2">
                <div class="project-group">
                    <div class="project-info">
                        <div class="status-icon">
                            <i class='bx bxs-circle'></i>
                        </div>
                        <div>
                            <b><?php echo e($lastestproject->Name_Project); ?></b>
                        </div>
                    </div>
        
                    <div class="energy-container">
                        <div class="energy-text" id="energy-text">70%</div>
                        <div class="energy-bar-container">
                            <div class="energy-bar" id="energy-bar"></div>
                        </div>
                    </div>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <br>
        </div>
        <br>

        
        <div class="content-box">
            <h4>ผลการดำเนินงานแยกตามฝ่าย</h4>
            <?php $__currentLoopData = $department; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('showProjectDepartment',$Department->Id_Department)); ?>" class="progress-container mt-2">
                <div class="progress-group">
                    <div class="progress-info">
                        <div><?php echo e($Department->Name_Department); ?></div>
                    </div>
                    <div class="progress-stats" >
                        <div id="total">โครงการ : <?php echo e($Department->projects_count); ?> </div>
                        <div id="done">เสร็จสิ้น : 0</div>
                    </div>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        
    </div>

    <div class="menu-footer">
        <div>แสดง <?php echo e($lastestProject->firstItem()); ?> ถึง <?php echo e($lastestProject->lastItem()); ?> จาก <?php echo e($lastestProject->total()); ?> รายการ</div>
        <div class="pagination">
            <?php if($lastestProject->onFirstPage()): ?>
                <button class="pagination-btn" disabled>ก่อนหน้า</button>
            <?php else: ?>
                <a href="<?php echo e($lastestProject->previousPageUrl()); ?>" class="pagination-btn">ก่อนหน้า</a>
            <?php endif; ?>
    
            <span class="page-number">
                <span id="currentPage"><?php echo e($lastestProject->currentPage()); ?></span>
            </span>
    
            <?php if($lastestProject->hasMorePages()): ?>
                <a href="<?php echo e($lastestProject->nextPageUrl()); ?>" class="pagination-btn">ถัดไป</a>
            <?php else: ?>
                <button class="pagination-btn" disabled>ถัดไป</button>
            <?php endif; ?>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<script>
    function setEnergyLevel(level) {
      const energyBar = document.getElementById('energy-bar');
      const energyText = document.getElementById('energy-text');

      // ตรวจสอบให้ค่าอยู่ในช่วง 0% - 100%
      level = Math.max(0, Math.min(100, level));

      // ปรับขนาดบาร์และอัปเดตข้อความ
      energyBar.style.width = level + '%';
      energyText.textContent = level + '%';
    }
    // ตัวอย่างการเปลี่ยนระดับพลังงาน
    // setTimeout(() => setEnergyLevel(50), 1000); // เปลี่ยนเป็น 50% หลังจาก 1 วินาที
    // setTimeout(() => setEnergyLevel(90), 3000); // เปลี่ยนเป็น 90% หลังจาก 3 วินาที
    // setTimeout(() => setEnergyLevel(20), 5000); // เปลี่ยนเป็น 20% หลังจาก 5 วินาที
</script>
    
</body>
</html>
<?php echo $__env->make('navbar.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/PlanDLC/report.blade.php ENDPATH**/ ?>