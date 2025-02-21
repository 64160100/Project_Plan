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
    </div>
    <table class="table table-striped">
        <thead class="table-header">
            <tr>
                <th>รหัสพนักงาน</th>
                <th>ชื่อ</th>
                <th>นามสกุล</th>
                <th>สิทธิ์การเข้าถึง</th>
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
                    <div style="display: flex; flex-wrap: nowrap; gap: 10px;">
                        <?php $__currentLoopData = $employee->permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div style="border: 1px solid purple; padding: 5px; border-radius: 5px;">
                            <?php echo e($permission->Name_Permission); ?>

                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </td>
                <td>
                    <form action="<?php echo e(route('account.editUser', $employee->Id_Employee)); ?>" method="GET"
                        style="display:inline;">
                        <button type="submit" class="btn btn-warning">แก้ไข</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php $__env->stopSection(); ?>
</body>

</html>
<?php echo $__env->make('navbar.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/account/user.blade.php ENDPATH**/ ?>