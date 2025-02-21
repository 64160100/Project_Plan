<!DOCTYPE html>
<html>

<head>
    <title>Employees</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/employee.css')); ?>">
</head>

<body>
    <?php $__env->startSection('content'); ?>
    <h1>ข้อมูลพนักงาน</h1>
    <div class="mb-3">
        <!-- <a href="<?php echo e(route('account.create')); ?>" class="btn btn-success">เพิ่มพนักงาน</a> -->
    </div>
    <table class="table table-striped">
        <thead class="table-header">
            <tr>
                <th>รหัสพนักงาน</th>
                <th>ชื่อ</th>
                <th>นามสกุล</th>
                <th>ตำแหน่ง</th>
                <th>ฝ่าย</th>
                <th>การจัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($employee->Id_Employee); ?></td>
                <td><?php echo e($employee->Firstname_Employee); ?></td>
                <td><?php echo e($employee->Lastname_Employee); ?></td>
                <td>
                    <?php echo e($employee->position ? $employee->position->Name_Position : 'N/A'); ?>

                    <?php if($employee->IsManager === 'Y'): ?>
                    <span> (เป็นหัวหน้าฝ่าย)</span>
                    <?php endif; ?>
                    <?php if($employee->IsDirector === 'Y'): ?>
                    <span> (เป็นผู้บริหาร)</span>
                    <?php endif; ?>
                </td>
                <td><?php echo e($employee->department ? $employee->department->Name_Department : 'N/A'); ?></td>
                <td>
                    <a href="<?php echo e(route('account.showemployee', $employee->Id_Employee)); ?>" class="btn btn-primary">
                        ดูรายละเอียด
                    </a>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php $__env->stopSection(); ?>
</body>

</html>
<?php echo $__env->make('navbar.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/account/employee.blade.php ENDPATH**/ ?>