<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('css/listproject.css')); ?>">
</head>

<body>
    <?php $__env->startSection('content'); ?>

    <div class="container-vp">
        <h3 class="head-project">
            <b>รายการโครงการ</b>
        </h3>
    </div>
    <br>

    <?php $__currentLoopData = $quarters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quarter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div
        class="border rounded-lg hover:bg-gray-50 transition-colors cursor-pointer border-l-4 border-l-transparent hover:border-l-blue-500 bg-white">
        <div class="flex justify-between items-center p-4">
            <div class="flex items-center space-x-4">
                <div class="text-lg">
                    ปีงบประมาณ <?php echo e($quarter->Fiscal_Year); ?> ไตรมาส <?php echo e($quarter->Quarter); ?>

                </div>
            </div>
            <a href="#collapse<?php echo e($quarter->Id_Quarter_Project); ?>" class="inline-flex items-center"
                data-bs-toggle="collapse">
                <i class='bx bx-chevron-down mr-2'></i>
                ดูข้อมูล
            </a>
        </div>

        <div id="collapse<?php echo e($quarter->Id_Quarter_Project); ?>" class="collapse">
            <?php
            $hasStrategic = false;
            ?>

            <?php $__currentLoopData = $strategics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Strategic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($Strategic->quarterProjects->contains('Quarter_Project_Id', $quarter->Id_Quarter_Project)): ?>
            <?php
            $hasStrategic = true;
            ?>
            <details class="accordion" id="<?php echo e($Strategic->Id_Strategic); ?>">
                <summary class="accordion-btn">
                    <b><a> <?php echo e($Strategic->Name_Strategic_Plan); ?></a><br>จำนวนโครงการ : <?php echo e($Strategic->project_count); ?>

                        โครงการ</b>

                    <a href="<?php echo e(route('showCreateFirstForm', ['Strategic_Id' => $Strategic->Id_Strategic])); ?>"
                        class="btn-add">
                        <i class='bx bx-plus'></i>เพิ่มโครงการ
                    </a>

                    <!-- <a href="<?php echo e(route('showCreateProject', ['Strategic_Id' => $Strategic->Id_Strategic])); ?>"
                        class="btn-add">
                        <i class='bx bx-plus'></i>เพิ่มโครงการใหญ่
                    </a> -->
                </summary>
                <div class="accordion-content">
                    <?php if($Strategic->projects->isEmpty()): ?>
                    <p>ไม่มีโครงการที่เกี่ยวข้อง</p>
                    <?php else: ?>
                    <?php $__currentLoopData = $Strategic->projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="d-flex justify-content-between align-items-center">
                        <strong>
                            <a href="<?php echo e(route('viewProject', ['Id_Project' => $Project->Id_Project])); ?>">
                                <?php echo e($Project->Name_Project); ?>

                                <a href="<?php echo e(route('PDF.projectCtrlP', $Project->Id_Project)); ?>"
                                    class='bx bx-folder-open'
                                    style='color:#000; font-size: 20px; padding-right: 5px;'></a>
                            </a>
                             <!-- pdf ยาว -->
                             <a href="<?php echo e(route('PDF.generate', $Project->Id_Project)); ?>" class='bx bx-folder-open'
                                style='color:#f00; font-size: 20px; padding-right: 5px;'>
                            </a>
                        </strong>
                        <a
                            href="<?php echo e(route('editProject', ['Id_Project' => $Project->Id_Project, 'sourcePage' => 'listProject'])); ?>">
                            <i class='bx bx-edit' style="font-size: 24px;"></i>
                        </a>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>
            </details>
            <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php if(!$hasStrategic): ?>
            <div class="card p-3 m-3">ไม่พบข้อมูลแผนยุทธศาสตร์</div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <?php $__env->stopSection(); ?>

</body>

</html>
<?php echo $__env->make('navbar.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/Project/listProject.blade.php ENDPATH**/ ?>