    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="<?php echo e(asset('css/button.css')); ?>">

    <style>
        .header-bar {
            background-color: #7C3AED; 
            color: white;
            padding: 10px 15px 3;
            font-weight: bold; 
            border-radius: 15px;
            display: flex;
            justify-content: space-between; 
            align-items: center; 
            width: fit-content;
            margin: 0 auto;
        }
        .text-box {
            border: 1px solid #7C3AED;
            border-radius: 15px;
            background-color: white;
            color: #7C3AED;
            padding: 10px 15px;
            font-size: 14px;
            line-height: 1.5;
            width: fit-content;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
        }

        table {
            width: 100%;
            margin: 0 auto;
            margin-top: 15px;
            border-radius: 15px; 
            overflow: hidden;
        }

        th,td {
            border: 1px solid #7C3AED;
            background: #fff;
            padding: 15px;
            color: #7C3AED;
        }
        .header-bar a.bx-table {
            transition: transform 0.2s ease-in-out;
        }
        .header-bar a.bx-table:hover {
            transform: scale(1.2);
        }

    </style>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div class="header-bar">
                <h3><?php echo e($strategic->Name_Strategic_Plan); ?></h3>
                <a class='bx bxs-down-arrow ms-3' style='color:#ffffff' data-bs-toggle="collapse" href="#collapseExample"
                role="button" aria-expanded="false" aria-controls="collapseExample"></a>
                <a class='bx bx-table ms-3' style='color:#ffffff; cursor: pointer;' data-bs-toggle="modal"
                data-bs-target="#strategicAnalysisModal" title="แสดงการวิเคราะห์บริบทเชิงกลยุทธ์"></a>
            </div>
            <div>
                <a href="#" class='btn-add' data-bs-toggle="modal" data-bs-target="#ModalAddStrategy">เพิ่มข้อมูล</a>
            </div>
        </div>
        <div class="collapse" id="collapseExample">
            <div class="text-box mt-2">
                <?php echo e($strategic->Goals_Strategic); ?>

            </div>
        </div>

        <div>
            <table>
                <tr style="text-align: center;">
                    <th style="width: 15%">กลยุทธ์</th>
                    <th style="width: 25%">วัตถุประสงค์เชิงกลยุทธ์ <br>(Strategic Objectives : SO)</th>
                    <th style="width: 25%">ตัวชี้วัดกลยุทธ์</th>
                    <th style="20">ค่าเป้าหมาย</th>
                    <th style="15">จัดการ</th>
                </tr>
                <?php $__currentLoopData = $strategy; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $strategy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr style="vertical-align: top;">
                    <?php if( $strategic->Id_Strategic == $strategy->Strategic_Id ): ?>
                        <td><?php echo e($strategy->Name_Strategy); ?></td>
                        <td>
                            <?php $__currentLoopData = $strategy->strategicObjectives; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $objective): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php echo e($objective->Details_Strategic_Objectives); ?> <br><br>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                        <td>
                            <?php $__currentLoopData = $strategy->kpis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kpi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php echo e($kpi->Name_Kpi); ?> <br><br>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                        <td>
                            <?php $__currentLoopData = $strategy->kpis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kpi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                               <?php echo e($kpi->Target_Value); ?> <br><br>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                        <td>
                            <a href="<?php echo e(route('strategy.edit', $strategy->Id_Strategy)); ?>" class="btn-edit"><i class='bx bx-edit'></i>แก้ไข</a>
                            <form action="<?php echo e(route('strategy.destroy', $strategy->Id_Strategy)); ?>" method="POST" style="display:inline;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn-delete" onclick="return confirm('คุณยืนยันที่จะลบข้อมูลนี้หรือไม่');">ลบ</button>

                            </form>
                        </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </table>
        </div>
    </div>
    <?php echo $__env->make('strategy.modelStrategy', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('strategy.addStrategy', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('navbar.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/strategy/viewStrategy.blade.php ENDPATH**/ ?>