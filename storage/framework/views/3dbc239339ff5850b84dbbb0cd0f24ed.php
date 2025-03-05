<?php $__env->startSection('content'); ?>
<div class="container mt-5">
    <h2 class="mb-4 d-flex align-items-center">
        <a href="<?php echo e(url()->previous()); ?>" class="text-dark me-3">
            <i class='bx bx-left-arrow-alt fs-3'></i>
        </a>
        ไฟล์จัดเก็บ
    </h2>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">อัปโหลดไฟล์ใหม่</h4>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('StorageFiles.store')); ?>" method="POST" enctype="multipart/form-data"
                        id="uploadForm">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="project_id" value="<?php echo e($project_id); ?>">
                        <div class="mb-3">
                            <label for="project_id_display" class="form-label">รหัสโครงการ</label>
                            <input type="text" id="project_id_display" class="form-control" value="<?php echo e($project_id); ?>"
                                readonly>
                        </div>
                        <div class="mb-3">
                            <label for="fileInput" class="form-label">เลือกไฟล์</label>
                            <input type="file" name="file" id="fileInput" class="form-control" accept=".pdf,image/*">
                            <small class="form-text text-muted">ประเภทไฟล์ที่อนุญาต: PDF, รูปภาพ (JPG, PNG, GIF, ฯลฯ).
                                ขนาดสูงสุด: 10 MB</small>
                        </div>
                        <div id="fileInfo" class="mb-3"></div>
                        <button type="submit" class="btn btn-primary">อัปโหลดไฟล์</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h4 class="mb-0">ไฟล์ที่อัปโหลดแล้ว</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ชื่อ</th>
                                    <th>ประเภท</th>
                                    <th>ขนาด</th>
                                    <th>การกระทำ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="text-truncate" style="max-width: 150px;">
                                        <?php echo e(Str::limit($file->Name_Storage_File, 20)); ?>

                                    </td>
                                    <td><?php echo e($file->Type_Storage_File); ?></td>
                                    <td><?php echo e($file->getHumanReadableSize()); ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo e(route('StorageFiles.view', $file->Id_Storage_File)); ?>"
                                                target="_blank" class="btn btn-sm btn-outline-primary">ดู</a>
                                            <a href="<?php echo e(route('StorageFiles.download', $file->Id_Storage_File)); ?>"
                                                class="btn btn-sm btn-outline-success">ดาวน์โหลด</a>
                                            <form action="<?php echo e(route('StorageFiles.destroy', $file->Id_Storage_File)); ?>"
                                                method="POST" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('คุณแน่ใจหรือไม่?')">ลบ</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('fileInput').addEventListener('change', function(event) {
    var file = event.target.files[0];
    var fileInfo = document.getElementById('fileInfo');
    if (file) {
        var fileSize = (file.size / (1024 * 1024)).toFixed(2);
        var fileType = file.type;
        var isValid = (fileType === 'application/pdf' || fileType.startsWith('image/')) && file.size <= 10 *
            1024 * 1024;

        fileInfo.innerHTML = `
            <div class="alert ${isValid ? 'alert-success' : 'alert-danger'}">
                <strong>ไฟล์:</strong> ${file.name}<br>
                <strong>ขนาด:</strong> ${fileSize} MB<br>
                <strong>ประเภท:</strong> ${fileType}
                ${!isValid ? '<br><strong>ข้อผิดพลาด:</strong> ไฟล์ต้องเป็น PDF หรือรูปภาพและไม่เกิน 10 MB.' : ''}
            </div>`;
    } else {
        fileInfo.innerHTML = '';
    }
});

document.getElementById('uploadForm').onsubmit = function(event) {
    var fileInput = document.getElementById('fileInput');
    var file = fileInput.files[0];
    if (file) {
        var fileType = file.type;
        var fileSize = file.size / (1024 * 1024);
        if ((fileType !== 'application/pdf' && !fileType.startsWith('image/')) || fileSize > 10) {
            event.preventDefault();
            alert('กรุณาอัปโหลดเฉพาะไฟล์ PDF หรือรูปภาพที่ไม่เกิน 10 MB.');
        }
    }
};
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('navbar.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/StorageFiles/index.blade.php ENDPATH**/ ?>