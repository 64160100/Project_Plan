<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>แก้ไขปีงบประมาณและไตรมาส</h1>
    <form action="<?php echo e(route('fiscalYearQuarter.update', $fiscalYearQuarter->Id_Quarter_Project)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div class="form-group">
            <label for="Fiscal_Year">ปีงบประมาณ</label>
            <input type="number" name="Fiscal_Year" class="form-control" value="<?php echo e($fiscalYearQuarter->Fiscal_Year); ?>" required>
        </div>
        <div class="form-group">
            <label for="Quarter">ไตรมาส</label>
            <input type="number" name="Quarter" class="form-control" value="<?php echo e($fiscalYearQuarter->Quarter); ?>" required min="1" max="4">
        </div>
        <button type="submit" class="btn btn-primary">อัปเดต</button>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('navbar.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/fiscalYearQuarter/edit.blade.php ENDPATH**/ ?>