<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="header">
        <button class="back-button" onclick="history.back()">←</button>
        <h2>รายละเอียดโครงการ</h2>
    </div>

    <div class="progress-tracker">
        <!-- Project Name Form -->
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <label for="projectName">ชื่อโครงการ:</label>
        <input id="projectName" name="Name_Project" value="<?php echo e($project->Name_Project); ?>" class="form-control">

        <!-- Row 1: Steps 1-4 -->
        <div class="steps-row">
            <?php for($index = 0; $index < 4; $index++): ?> <?php $statusClass='status-I' ; if ($project->Count_Steps > $index) {
                $statusClass = 'status-Y';
                } elseif ($project->Count_Steps == $index) {
                $statusClass = 'status-B';
                } else {
                $statusClass = 'status-P';
                }
                ?>
                <div class="step <?php echo e($statusClass); ?>">
                    <span class="step-number"><?php echo e($index + 1); ?></span>
                    <span class="step-text"><?php echo e($stepTexts[$index]); ?></span>
                    <?php if($index < 3): ?> <div class="step-line <?php echo e($statusClass); ?>">
                </div>
                <?php endif; ?>
        </div>
        <?php endfor; ?>
    </div>

    <!-- Row 2: Steps 5-8 -->
    <div class="steps-row">
        <?php for($index = 4; $index < 8; $index++): ?> <?php $statusClass='status-I' ; if ($project->Count_Steps > $index) {
            $statusClass = 'status-Y';
            } elseif ($project->Count_Steps == $index) {
            $statusClass = 'status-B';
            } else {
            $statusClass = 'status-P';
            }
            ?>
            <div class="step <?php echo e($statusClass); ?>">
                <span class="step-number"><?php echo e($index + 1); ?></span>
                <span class="step-text"><?php echo e($stepTexts[$index]); ?></span>
                <?php if($index < 7): ?> <div class="step-line <?php echo e($statusClass); ?>">
            </div>
            <?php endif; ?>
    </div>
    <?php endfor; ?>
</div>

<!-- Row 3: Steps 9-11 -->
<div class="steps-row">
    <?php for($index = 8; $index < 11; $index++): ?> <?php $statusClass='status-I' ; if ($project->Count_Steps > $index) {
        $statusClass = 'status-Y';
        } elseif ($project->Count_Steps == $index) {
        $statusClass = 'status-B';
        } else {
        $statusClass = 'status-P';
        }
        ?>
        <div class="step <?php echo e($statusClass); ?>">
            <span class="step-number"><?php echo e($index + 1); ?></span>
            <span class="step-text"><?php echo e($stepTexts[$index]); ?></span>
            <?php if($index < 10): ?> <div class="step-line <?php echo e($statusClass); ?>">
        </div>
        <?php endif; ?>
</div>
<?php endfor; ?>
</div>

<!-- Status Text Section -->
<div class="status-detail status-<?php echo e($project->approvals->last()->Status_Record ?? 'I'); ?>">
    <?php switch($project->Count_Steps):
    case (0): ?>
    <div class="status-text">ขั้นตอนที่ 1: เริ่มต้นการเสนอโครงการ</div>
    <div class="status-subtext">ถึง: ผู้บริหารพิจารณาเบื้องต้น</div>
    <?php break; ?>
    <?php case (1): ?>
    <div class="status-text">ขั้นตอนที่ 2: อยู่ระหว่างการพิจารณาเบื้องต้น</div>
    <div class="status-subtext">สถานะ: รอการพิจารณาจากผู้บริหาร</div>
    <?php break; ?>
    <?php case (2): ?>
    <div class="status-text">ขั้นตอนที่ 3: การพิจารณาด้านงบประมาณ</div>
    <div class="status-subtext">ถึง: ฝ่ายการเงินตรวจสอบงบประมาณ</div>
    <?php break; ?>
    <?php case (3): ?>
    <div class="status-text">ขั้นตอนที่ 4: การตรวจสอบความเหมาะสมด้านงบประมาณ</div>
    <div class="status-subtext">สถานะ: อยู่ระหว่างการตรวจสอบโดยฝ่ายการเงิน</div>
    <?php break; ?>
    <?php case (4): ?>
    <div class="status-text">ขั้นตอนที่ 5: การพิจารณาโดยหัวหน้าฝ่าย</div>
    <div class="status-subtext">สถานะ: อยู่ระหว่างการตรวจสอบโดยหัวหน้าฝ่าย</div>
    <?php break; ?>
    <?php case (5): ?>
    <div class="status-text">ขั้นตอนที่ 6: การพิจารณาขั้นสุดท้าย</div>
    <div class="status-subtext">สถานะ: อยู่ระหว่างการพิจารณาโดยผู้บริหาร</div>
    <?php break; ?>
    <?php case (6): ?>
    <div class="status-text">ขั้นตอนที่ 7: การดำเนินโครงการ</div>
    <div class="status-subtext">สถานะ: อยู่ระหว่างการดำเนินงาน</div>
    <?php break; ?>
    <?php case (7): ?>
    <div class="status-text">ขั้นตอนที่ 8: การตรวจสอบผลการดำเนินงาน</div>
    <div class="status-subtext">สถานะ: รอการตรวจสอบจากหัวหน้าฝ่าย</div>
    <?php break; ?>
    <?php case (8): ?>
    <div class="status-text">ขั้นตอนที่ 9: การรับรองผลการดำเนินงาน</div>
    <div class="status-subtext">สถานะ: รอการรับรองจากผู้บริหาร</div>
    <?php break; ?>
    <?php case (9): ?>
    <div class="status-text">ขั้นตอนที่ 10: การปิดโครงการ</div>
    <div class="status-subtext">สถานะ: ดำเนินการเสร็จสิ้นสมบูรณ์</div>
    <?php break; ?>
    <?php case (10): ?>
    <div class="status-text">สถานะพิเศษ: การดำเนินการล่าช้า</div>
    <div class="status-subtext">สถานะ: รอการพิจารณาจากผู้บริหาร</div>
    <?php break; ?>
    <?php default: ?>
    <div class="status-text"><?php echo e($project->approvals->first()->Status ?? 'รอการพิจารณา'); ?></div>
    <?php endswitch; ?>
</div>
</div>

<!-- Timeline Items -->
<?php if(isset($project->approvals) && count($project->approvals) > 0): ?>
<?php $__currentLoopData = $project->approvals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $approval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php if(isset($approval->record_history) && count($approval->record_history) > 0): ?>
<?php $__currentLoopData = $approval->record_history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="task-item status-<?php echo e($history->Status_Record); ?>">
    <div class="task-icon status-<?php echo e($history->Status_Record); ?>">
        <?php if($history->Status_Record == 'Y'): ?>
        <i class="fas fa-check"></i>
        <?php elseif($history->Status_Record == 'N'): ?>
        <i class="fas fa-times"></i>
        <?php else: ?>
        <i class="fas fa-clock"></i>
        <?php endif; ?>
    </div>
    <div class="task-content">
        <div class="task-title"><?php echo e($history->Name_Record); ?></div>
        <div class="task-subtitle"><?php echo e($history->formattedDateTime); ?></div>
        <div class="task-comment"><?php echo e($history->comment); ?></div>
    </div>
    <div class="status-badge status-<?php echo e($history->Status_Record); ?>">
        <?php if($history->Status_Record == 'Y'): ?>
        อนุมัติแล้ว
        <?php elseif($history->Status_Record == 'N'): ?>
        ไม่อนุมัติ
        <?php else: ?>
        รอดำเนินการ
        <?php endif; ?>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php else: ?>
<div class="no-data">ไม่พบข้อมูลประวัติการดำเนินการ</div>
<?php endif; ?>

<div class="button-container">
    <button class="btn btn-back" onclick="history.back()">ย้อนกลับ</button>
    <button class="btn btn-next">ดำเนินการต่อ</button>
</div>
</div>

<style>
/* Base styles */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.progress-tracker {
    background: #fff;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    margin: 20px 0;
}

/* Step styles */
.steps-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    position: relative;
    padding: 0 20px;
}

.step {
    flex: 1;
    text-align: center;
    position: relative;
    padding: 0 10px;
}

.step-number {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: #f0f0f0;
    color: #666;
    font-weight: 600;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
    position: relative;
    z-index: 2;
    transition: all 0.3s ease;
    border: 2px solid #e0e0e0;
}

.step-line {
    position: absolute;
    top: 22px;
    left: 50%;
    width: 100%;
    height: 2px;
    background: #e0e0e0;
    z-index: 1;
    transition: all 0.3s ease;
}

/* Status colors */
.status-Y .step-number,
.status-Y .step-line {
    background: #4CAF50;
    color: white;
    border-color: #4CAF50;
}

.status-N .step-number,
.status-N .step-line {
    background: #f44336;
    color: white;
    border-color: #f44336;
}

.status-I .step-number,
.status-I .step-line {
    background: #ff9800;
    color: white;
    border-color: #ff9800;
}

.status-B .step-number,
.status-B .step-line {
    background: #2196F3;
    color: white;
    border-color: #2196F3;
}

.status-P .step-number,
.status-P .step-line {
    background: #e0e0e0;
    color: #666;
    border-color: #e0e0e0;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 15px;
    font-size: 0.8em;
    font-weight: 500;
}

.status-badge.status-Y {
    background: #e8f5e9;
    color: #4CAF50;
}

.status-badge.status-N {
    background: #ffebee;
    color: #f44336;
}

.status-badge.status-I {
    background: #fff3e0;
    color: #ff9800;
}

.status-badge.status-B {
    background: #e3f2fd;
    color: #2196F3;
}

.status-badge.status-P {
    background: #f0f0f0;
    color: #666;
}

/* Task items */
.task-item {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    transition: all 0.3s ease;
}

.task-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.task-item.status-Y {
    border-left: 4px solid #4CAF50;
}

.task-item.status-N {
    border-left: 4px solid #f44336;
}

.task-item.status-I {
    border-left: 4px solid #ff9800;
}

.task-item.status-B {
    border-left: 4px solid #2196F3;
}

.task-item.status-P {
    border-left: 4px solid #e0e0e0;
}

.task-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    font-size: 20px;
}

.task-icon.status-Y {
    background: #e8f5e9;
    color: #4CAF50;
}

.task-icon.status-N {
    background: #ffebee;
    color: #f44336;
}

.task-icon.status-I {
    background: #fff3e0;
    color: #ff9800;
}

.task-icon.status-B {
    background: #e3f2fd;
    color: #2196F3;
}

.task-icon.status-P {
    background: #f0f0f0;
    color: #666;
}

/* Responsive design */
@media (max-width: 768px) {
    .steps-row {
        flex-direction: column;
        margin-bottom: 40px;
    }

    .step {
        width: 100%;
        margin-bottom: 20px;
    }

    .task-item {
        flex-direction: column;
        text-align: center;
    }

    .task-icon {
        margin: 0 0 10px 0;
    }

    .status-badge {
        margin-top: 10px;
    }
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('navbar.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/statusDetails.blade.php ENDPATH**/ ?>