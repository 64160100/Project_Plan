<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>ปีงบประมาณและไตรมาส</h1>
    <a href="<?php echo e(route('fiscalYearQuarter.create')); ?>" class="btn btn-primary">สร้างปีงบประมาณ</a>
    <table class="table mt-3">
        <thead>
            <tr>
                <th>ลำดับ</th>
                <th>ปีงบประมาณ</th>
                <th>ไตรมาส</th>
                <th>การดำเนินการ</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $fiscalYearsAndQuarters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($index + 1); ?></td>
                <td><?php echo e($item->Fiscal_Year); ?></td>
                <td><?php echo e($item->Quarter); ?></td>
                <td>
                    <a href="<?php echo e(route('fiscalYearQuarter.edit', $item->Id_Quarter_Project)); ?>" class="btn btn-warning btn-sm">แก้ไข</a>
                    <form action="<?php echo e(route('fiscalYearQuarter.destroy', $item->Id_Quarter_Project)); ?>" method="POST" style="display:inline;">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');">ลบ</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('navbar.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/fiscalYearQuarter/index.blade.php ENDPATH**/ ?>