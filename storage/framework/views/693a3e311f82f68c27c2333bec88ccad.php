<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>ยินดีต้อนรับสู่ระบบติดตามแผนงาน</h1>

    <h1>Welcome, <?php echo e(session('employee') ? session('employee')->Firstname : 'Guest'); ?>

        <?php echo e(session('employee') ? session('employee')->Lastname : ''); ?></h1>

    <h1><?php echo e($message); ?></h1>

    <ul>

    </ul>

    <!-- Button 1 -->
    <button id="button1" class="btn btn-primary">Show Popup</button>
    <!-- Button 2 -->
    <button id="button2" class="btn btn-secondary">Another Action</button>
    <!-- Logout Button -->
    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: inline;">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn btn-danger">Logout</button>
    </form>
</div>

<!-- Popup Modal -->
<div id="popupModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Popup</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><?php echo e($message); ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Include jQuery and Bootstrap JS for the modal -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
$(document).ready(function() {
    $('#button1').click(function() {
        $('#popupModal').modal('show');
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('navbar.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/dashboard.blade.php ENDPATH**/ ?>