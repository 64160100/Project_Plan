<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/statusTracking.css')); ?>">

<h2>
    <a href="<?php echo e(url()->previous()); ?>" style="color: inherit; text-decoration: none;">
        <i class='bx bx-left-arrow-alt' style="cursor: pointer;"></i>
    </a>
    ติดตามสถานะโครงการ
</h2>

<div class="container">

    <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="project-card">
        <div class="project-header">
            <div>
                <h2 class="project-title"><?php echo e($project->Name_Project); ?></h2>
                <p class="project-subtitle"><?php echo e($project->departmentName); ?></p>
            </div>
            <div class="status-badge"><?php echo e($project->status); ?></div>
        </div>

        <div class="project-info">
            <div class="info-item">
                <span class="info-icon">📅</span>
                <div>
                    <div class="info-label">วันที่เริ่ม</div>
                    <div class="info-value"><?php echo e($project->formattedFirstTime); ?></div>
                </div>
            </div>
            <div class="info-item">
                <span class="info-icon">👤</span>
                <div>
                    <div class="info-label">ผู้รับผิดชอบ</div>
                    <div class="info-value"><?php echo e($project->employeeName); ?></div>
                </div>
            </div>
            <div class="info-item">
                <span class="info-icon">💰</span>
                <div>
                    <div class="info-label">งบประมาณ</div>
                    <div class="info-value"><?php echo e($project->budget); ?> บาท</div>
                </div>
            </div>
        </div>

        <div class="progress-section">
            <div class="progress-header">
                <span class="progress-label">ความคืบหน้า</span>
                <span class="progress-value"><?php echo e(round(($project->Count_Steps / 9) * 100)); ?>%</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: <?php echo e(($project->Count_Steps / 9) * 100); ?>%;"></div>
            </div>
        </div>

        <div class="details-link">
            <span>สถานะปัจจุบัน : <?php echo e($project->current_status); ?></span>
            <a href="<?php echo e(route('project.details', ['Id_Project' => $project->Id_Project])); ?>">
                <span>รายละเอียด →</span>
            </a>
        </div>

    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const progressBars = document.querySelectorAll('.progress-fill');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0';
        setTimeout(() => {
            bar.style.width = width;
        }, 100);
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('navbar.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/status/statusTracking.blade.php ENDPATH**/ ?>