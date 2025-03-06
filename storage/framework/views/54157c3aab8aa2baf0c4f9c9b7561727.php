<!DOCTYPE html>
<html>

<head>
    <title>แก้ไขข้อมูลผู้ใช้งาน</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/editUser.css')); ?>">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
    .header-container {
        display: flex;
        align-items: center;
    }

    .header-container a {
        margin-right: 10px;
    }

    .back-btn {
        background: linear-gradient(180deg, #8729DA 0%, #AC2BDD 100%);
        border: 1px solid #ccc;
        padding: 10px 20px;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        width: auto;
        max-width: 300px;
        text-decoration: none;
    }

    .back-btn:hover {
        transform: translateX(-5px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
    }

    .back-btn:active {
        transform: translateX(-2px) scale(0.98);
    }

    .back-btn i {
        color: white;
        font-size: 24px;
    }
    </style>
</head>

<body>
    <?php $__env->startSection('content'); ?>
    <div class="header-container">
        <a href="<?php echo e(route('account.employee')); ?>" class="back-btn">
            <i class='bx bxs-left-arrow-square'></i>
        </a>
        <h1>แก้ไขข้อมูลผู้ใช้งาน</h1>
    </div>

    <!-- Form for editing employee information -->
    <div class="form-container">
        <div class="form-row">
            <div class="form-group">
                <label for="Id_Employee">รหัสพนักงาน:</label>
                <input type="text" class="form-control" id="Id_Employee" name="Id_Employee"
                    value="<?php echo e($employee->Id_Employee); ?>" disabled>
            </div>

            <div class="form-group">
                <label for="Fullname_Employee">ชื่อ-นามสกุล:</label>
                <input type="text" class="form-control" id="Fullname_Employee" name="Fullname_Employee"
                    value="<?php echo e($employee->Prefix_Name . ' ' . $employee->Firstname . ' ' . $employee->Lastname); ?>"
                    disabled>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="Position_Name">ตำแหน่ง:</label>
                <input type="text" class="form-control" id="Position_Name" name="Position_Name"
                    value="<?php echo e($employee->Position_Name); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="Department_Name">ฝ่าย:</label>
                <input type="text" class="form-control" id="Department_Name" name="Department_Name"
                    value="<?php echo e($employee->Department_Name); ?>" disabled>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="Email">อีเมล:</label>
                <input type="email" class="form-control" id="Email" name="Email" value="<?php echo e($employee->Email); ?>"
                    disabled>
            </div>
            <div class="form-group">
                <label for="Phone">เบอร์โทร:</label>
                <input type="text" class="form-control" id="Phone" name="Phone" value="<?php echo e($employee->Phone); ?>" disabled>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>สิทธิ์การเข้าถึง:</label>
                <ul style="list-style-type: none; padding-left: 0;">
                    <li>
                        <input type="checkbox" id="IsManager" name="IsManager" value="Y"
                            <?php echo e($employee->IsManager === 'Y' ? 'checked' : ''); ?>

                            onchange="updatePermission('IsManager', this.checked)">
                        <label for="IsManager">Manager</label>
                    </li>
                    <li>
                        <input type="checkbox" id="IsDirector" name="IsDirector" value="Y"
                            <?php echo e($employee->IsDirector === 'Y' ? 'checked' : ''); ?>

                            onchange="updatePermission('IsDirector', this.checked)">
                        <label for="IsDirector">Director</label>
                    </li>
                    <li>
                        <input type="checkbox" id="IsFinance" name="IsFinance" value="Y"
                            <?php echo e($employee->IsFinance === 'Y' ? 'checked' : ''); ?>

                            onchange="updatePermission('IsFinance', this.checked)">
                        <label for="IsFinance">Finance</label>
                    </li>
                    <li>
                        <input type="checkbox" id="IsResponsible" name="IsResponsible" value="Y"
                            <?php echo e($employee->IsResponsible === 'Y' ? 'checked' : ''); ?>

                            onchange="updatePermission('IsResponsible', this.checked)">
                        <label for="IsResponsible">Responsible</label>
                    </li>
                    <li>
                        <input type="checkbox" id="IsAdmin" name="IsAdmin" value="Y"
                            <?php echo e($employee->IsAdmin === 'Y' ? 'checked' : ''); ?>

                            onchange="updatePermission('IsAdmin', this.checked)">
                        <label for="IsAdmin">Admin</label>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <script>
    function updatePermission(field, value) {
        $.ajax({
            url: '<?php echo e(route("account.updateUserPermissions", $employee->Id_Employee)); ?>',
            type: 'PUT',
            data: {
                _token: '<?php echo e(csrf_token()); ?>',
                field: field,
                value: value
            },
            success: function(response) {
                console.log('Permission updated successfully');
            },
            error: function(xhr) {
                console.log('Error updating permission');
            }
        });
    }
    </script>
    <?php $__env->stopSection(); ?>
</body>

</html>
<?php echo $__env->make('navbar.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/account/editUser.blade.php ENDPATH**/ ?>