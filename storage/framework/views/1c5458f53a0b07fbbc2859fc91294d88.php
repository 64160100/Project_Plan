<?php
use Carbon\Carbon;
Carbon::setLocale('th');
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Index</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('css/proposeProject.css')); ?>">
    <style>
    @font-face {
        font-family: 'THSarabunNew';
        font-style: normal;
        font-weight: normal;
        src: url('<?php echo e(storage_path('fonts/THSarabunNew.ttf')); ?>') format('truetype');
    }

    @font-face {
        font-family: 'THSarabunNew';
        font-style: normal;
        font-weight: bold;
        src: url('<?php echo e(storage_path('fonts/THSarabunNew Bold.ttf')); ?>') format('truetype');
    }
    </style>
</head>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>เสนอโครงการเพื่อพิจารณา</h1>
        
    <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if($project->Count_Steps !== 0): ?>
    <div class="outer-container">
        <div class="container">
            <div class="header">
                <div class="project-title"><?php echo e($project->Name_Project); ?></div>
                <div class="project-subtitle">
                    <?php echo e($project->employee->department->Name_Department ?? 'ยังไม่มีผู้รับผิดชอบโครงการ'); ?>

                </div>
                <div class="project-info">
                    <div class="info-item">
                        <span class="info-icon">
                            <i class='bx bx-calendar' style="width: 20px; height: 0px;"></i>
                        </span>
                        <div>
                            <div class="info-label">วันที่เริ่ม</div>
                            <div class="info-value"><?php echo e($project->formattedFirstTime); ?></div>
                        </div>
                    </div>
                    <div class="info-item">
                        <span class="info-icon">
                            <i class='bx bx-user' style="width: 20px; height: 0px;"></i>
                        </span>
                        <div>
                            <div class="info-label">ผู้รับผิดชอบ</div>
                            <div class="info-value">
                                <?php if($project->employee && ($project->employee->Firstname_Employee ||
                                    $project->employee->Lastname_Employee)): ?>
                                    <?php echo e($project->employee->Firstname_Employee ?? ''); ?>

                                    <?php echo e($project->employee->Lastname_Employee ?? ''); ?>

                                <?php else: ?>
                                -
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="info-item">
                        <span class="info-icon">
                            <i class='bx bx-wallet-alt' style="width: 20px; height: 0px;"></i>
                        </span>
                        <div>
                            <div class="info-label">งบประมาณ</div>
                            <div class="info-value">
                            <?php if($project->Status_Budget === 'Y'): ?>
                            <?php
                            $totalBudget = $project->projectBudgetSources->sum('Amount_Total');
                            ?>
                            <?php echo e(number_format($totalBudget, 2)); ?> บาท
                            <?php else: ?>
                            ไม่ใช้งบประมาณ
                            <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="project-actions">
                <div>
                    <a href="<?php echo e(route('StorageFiles.index', ['project_id' => $project->Id_Project])); ?>" class="action-link">
                        <i class='bx bx-info-circle'></i>
                        ดูรายละเอียดโครงการ
                    </a>
                </div>
                <div class="dropdown">
                    <a href="#" class="action-link dropdown-toggle" id="commentsDropdown-<?php echo e($project->Id_Project); ?>"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class='bx bx-message'></i>
                        ข้อเสนอแนะ(<?php echo e($project->approvals->first()->recordHistory->where('Status_Record', 'N')->count()); ?>)
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="commentsDropdown-<?php echo e($project->Id_Project); ?>"
                        style="max-height: 400px; overflow-y: auto; width: 400px;">
                        <?php
                        $filteredRecords = $project->approvals->first()->recordHistory->where('Status_Record', 'N');
                        ?>
                        <?php if($filteredRecords->count() > 0): ?>
                        <?php $__currentLoopData = $filteredRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="p-2 border-bottom">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="font-weight-bold"><?php echo e($record->Name_Record ?? 'Unknown'); ?></span>
                                <span class="text-muted small"><?php echo e($record->formattedDateTime ?? 'N/A'); ?></span>
                            </div>
                            <p class="mb-0"><?php echo e($record->comment ?? 'No Comment'); ?></p>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                        <li class="p-2 text-center text-muted">ไม่มีข้อเสนอแนะ</li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div>
                    <a href="#" class="action-link">
                        <i class='bx bx-error warning-icon'></i>
                        แจ้งเตือน
                    </a>
                </div>
            </div>

            <div class="status-section">
                <div class="status-header">
                    สถานะการพิจารณา
                    <i class='bx bxs-chevron-right toggle-icon' id="toggle-icon-<?php echo e($project->Id_Project); ?>"></i>
                </div>
                <div class="project-status" id="project-status-<?php echo e($project->Id_Project); ?>">
                    <?php if($project->approvals->isNotEmpty() && $project->approvals->first()->recordHistory->isNotEmpty()): ?>
                    <?php $__currentLoopData = $project->approvals->first()->recordHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="status-card">
                        <div class="status-left">
                            <i class='bx bx-envelope' style="width: 40px;"></i>
                            <div>
                                <div class="status-text">
                                    <?php echo e($history->comment ?? 'No Comment'); ?>

                                </div>
                                <div class="status-text">
                                    อนุมัติโดย: <?php echo e($history->Name_Record ?? 'Unknown'); ?>

                                </div>
                                <div class="status-text">
                                    ตำแหน่ง: <?php echo e($history->Permission_Record ?? 'Unknown'); ?>

                                </div>
                            </div>
                        </div>
                        <div class="status-right">
                            <div>
                                <span class="status-date">
                                    <?php echo e($history->formattedDateTime ?? 'N/A'); ?>

                                </span>
                            </div>
                            <div>
                                <?php if($history->Status_Record === 'Y'): ?>
                                <button class="status-button approval-status approved">
                                    เสร็จสิ้น
                                </button>
                                <?php elseif($history->Status_Record === 'N'): ?>
                                <a href="<?php echo e(route('approveProject', ['id' => $history->Approve_Id])); ?>"
                                    class="status-button approval-status not-approved">
                                    ไม่อนุมัติ
                                </a>
                                <?php else: ?>
                                <button class="status-button approval-status pending">
                                    รอการอนุมัติ
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>

                    <?php if($project->approvals->first()->Status !== 'N'): ?>
                    <div class="status-card">
                        <div class="status-left">
                            <i class='bx bx-envelope' style="width: 40px;"></i>
                            <div>
                                <div class="status-text">
                                    <?php if($project->Count_Steps === 0): ?>
                                    <div class="status-text">
                                        ขั้นตอนที่ 1: เริ่มต้นการเสนอโครงการ
                                    </div>
                                    <div class="status-text">
                                        ถึง: ผู้อำนวยการพิจารณาเบื้องต้น
                                    </div>
                                    <?php elseif($project->Count_Steps === 1): ?>
                                    <div class="status-text">
                                        ขั้นตอนที่ 2: อยู่ระหว่างการพิจารณาเบื้องต้น
                                    </div>
                                    <div class="status-text">
                                        สถานะ: รอการพิจารณาจากผู้อำนวยการ
                                    </div>
                                    <?php elseif($project->Count_Steps === 2): ?>
                                    <?php if($project->approvals->first()->Status === 'Y'): ?>
                                    <div class="status-text">
                                        กรอกข้อมูลของโครงการทั้งหมด
                                    </div>
                                    <?php else: ?>
                                    <?php if($project->Status_Budget === 'N'): ?>
                                    <div class="status-text">
                                        ขั้นตอนที่ 3: การพิจารณาโดยหัวหน้าฝ่าย
                                    </div>
                                    <div class="status-text">
                                        สถานะ: อยู่ระหว่างการพิจารณาโดยหัวหน้าฝ่าย
                                    </div>
                                    <?php else: ?>
                                    <div class="status-text">
                                        ขั้นตอนที่ 3: การพิจารณาด้านงบประมาณ
                                    </div>
                                    <div class="status-text">
                                        ถึง: ฝ่ายการเงินตรวจสอบงบประมาณ
                                    </div>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                    <?php elseif($project->Count_Steps === 3): ?>
                                    <div class="status-text">
                                        ขั้นตอนที่ 4: การตรวจสอบความเหมาะสมด้านงบประมาณ
                                    </div>
                                    <div class="status-text">
                                        สถานะ: อยู่ระหว่างการตรวจสอบโดยฝ่ายการเงิน
                                    </div>
                                    <?php elseif($project->Count_Steps === 4): ?>
                                    <div class="status-text">
                                        ขั้นตอนที่ 5: การพิจารณาโดยหัวหน้าฝ่าย
                                    </div>
                                    <div class="status-text">
                                        สถานะ: อยู่ระหว่างการตรวจสอบโดยหัวหน้าฝ่าย
                                    </div>
                                    <?php elseif($project->Count_Steps === 5): ?>
                                    <div class="status-text">
                                        ขั้นตอนที่ 6: การพิจารณาขั้นสุดท้าย
                                    </div>
                                    <div class="status-text">
                                        สถานะ: อยู่ระหว่างการพิจารณาโดยผู้อำนวยการ
                                    </div>
                                    <?php elseif($project->Count_Steps === 6): ?>
                                    <div class="status-text">
                                        ขั้นตอนที่ 7: การดำเนินโครงการ
                                    </div>
                                    <?php if(\Carbon\Carbon::now()->lte(\Carbon\Carbon::parse($project->End_Time))): ?>
                                    <div class="status-text text-success">
                                        สถานะ: เสร็จทันเวลา
                                    </div>
                                    <?php else: ?>
                                    <div class="status-text text-danger">
                                        สถานะ: เสร็จไม่ทันเวลา
                                    </div>
                                    <?php endif; ?>
                                    <?php elseif($project->Count_Steps === 7): ?>
                                    <div class="status-text">
                                        ขั้นตอนที่ 8: การตรวจสอบผลการดำเนินงาน
                                    </div>
                                    <div class="status-text">
                                        สถานะ: รอการตรวจสอบจากหัวหน้าฝ่าย
                                    </div>
                                    <?php elseif($project->Count_Steps === 8): ?>
                                    <div class="status-text">
                                        ขั้นตอนที่ 9: การรับรองผลการดำเนินงาน
                                    </div>
                                    <div class="status-text">
                                        สถานะ: รอการรับรองจากผู้อำนวยการ
                                    </div>
                                    <?php elseif($project->Count_Steps === 9): ?>
                                    <div class="status-text">
                                        ขั้นตอนที่ 10: การปิดโครงการ
                                    </div>
                                    <div class="status-text">
                                        สถานะ: ดำเนินการเสร็จสิ้นสมบูรณ์
                                    </div>
                                    <?php elseif($project->Count_Steps === 11): ?>
                                    <div class="status-text">
                                        สถานะพิเศษ: การดำเนินการล่าช้า
                                    </div>
                                    <div class="status-text">
                                        สถานะ: รอการพิจารณาจากผู้อำนวยการ
                                    </div>
                                    <?php else: ?>
                                    <div class="status-text">
                                        <?php echo e($project->approvals->first()->Status ?? 'รอการพิจารณา'); ?>

                                    </div>
                                    <?php endif; ?>
                                </div>
                                <?php if($project->Count_Steps === 6 || $project->Count_Steps === 11): ?>
                                <div class="status-text">
                                    วันที่สิ้นสุด: <?php echo e($project->End_Time); ?>

                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="status-right">
                            <div>
                                <span class="status-date">
                                    <?php if($project->approvals->first()->recordHistory->isNotEmpty()): ?>
                                    <?php echo e($project->approvals->first()->recordHistory->first()->formattedTimeRecord); ?>

                                    <?php else: ?>
                                    N/A
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div>
                                <button class="status-button approval-status pending">
                                    <?php if($project->Count_Steps === 0): ?>
                                    ส่ง Email
                                    <?php elseif($project->Count_Steps === 1): ?>
                                    กำลังพิจารณา
                                    <?php elseif($project->Count_Steps === 2): ?>
                                    กำลังพิจารณา
                                    <?php elseif($project->Count_Steps === 3): ?>
                                    กำลังพิจารณา
                                    <?php elseif($project->Count_Steps === 4): ?>
                                    กำลังพิจารณา
                                    <?php elseif($project->Count_Steps === 5): ?>
                                    กำลังพิจารณา
                                    <?php elseif($project->Count_Steps === 6): ?>
                                    กำลังพิจารณา
                                    <?php elseif($project->Count_Steps === 7): ?>
                                    กำลังพิจารณา
                                    <?php elseif($project->Count_Steps === 8): ?>
                                    เสร็จสิ้น
                                    <?php elseif($project->Count_Steps === 9): ?>
                                    สิ้นสุดโครงการ
                                    <?php elseif($project->Count_Steps === 11): ?>
                                    โครงการเสร็จไม่ทันเวลา
                                    <?php else: ?>
                                    <?php echo e($project->approvals->first()->Status ?? 'รอการอนุมัติ'); ?>

                                    <?php endif; ?>
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="button-container">
                        <?php if(in_array($project->Count_Steps, [0, 2, 6])): ?>
                        <?php if($project->Count_Steps === 6): ?>
                        <?php if(\Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($project->End_Time))): ?>
                        <form action="<?php echo e(route('projects.submitForApproval', ['id' => $project->Id_Project])); ?>"
                            method="POST" style="display:inline;">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-log-in-circle'></i> ส่งให้ผู้อำนวยการตรวจสอบ
                            </button>
                        </form>
                        <?php else: ?>
                        <a href="<?php echo e(route('reportForm', ['id' => $project->Id_Project])); ?>" class="btn btn-success">
                            <i class='bx bx-file'></i> ปุ่มรายงานโครงการ
                        </a>
                        <?php endif; ?>
                        <?php else: ?>
                        <?php if($project->Count_Steps === 2 && $project->approvals->first()->Status === 'Y'): ?>
                        <a href="<?php echo e(route('projects.edit', ['id' => $project->Id_Project ])); ?>" class="btn btn-warning">
                            <i class='bx bx-edit'></i> แก้ไขฟอร์ม
                        </a>
                        <?php else: ?>
                        <form action="<?php echo e(route('projects.submitForApproval', ['id' => $project->Id_Project])); ?>"
                            method="POST" style="display:inline;">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-log-in-circle'></i> เสนอเพื่อพิจารณา
                            </button>
                        </form>
                        <?php endif; ?>
                        <?php endif; ?>
                        <?php elseif($project->Count_Steps === 9): ?>
                        <form action="<?php echo e(route('projects.submitForApproval', ['id' => $project->Id_Project])); ?>"
                            method="POST" style="display:inline;">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-secondary">สิ้นสุดโครงการ</button>
                        </form>
                        <?php else: ?>
                        <button type="button" class="btn btn-secondary" disabled>
                            <i class='bx bx-log-in-circle'></i> เสนอเพื่อพิจารณา
                        </button>
                        <?php endif; ?>
                    </div>

                </div>
            </div>

        </div>
    </div>
    <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <!-- <div class="mb-3">
        <a href="<?php echo e(route('createSetProject')); ?>" class="btn btn-primary">
            <i class='bx bx-plus'></i> สร้างชุดโครงการ (<?php echo e($countStepsZero); ?> โครงการ)
        </a>
    </div> -->

    <?php $__currentLoopData = $quartersByFiscalYear; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fiscalYear => $yearQuarters): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php $__currentLoopData = $yearQuarters->sortBy('Quarter'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quarter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
    $quarterProjects = $filteredStrategics->filter(function($strategic) use ($quarter) {
    return $strategic->quarterProjects->contains('Quarter_Project_Id', $quarter->Id_Quarter_Project);
    });
    $quarterStyle = $quarterStyles[$quarter->Quarter] ?? 'border-gray-200';

    $hasIncompleteStrategies = isset($incompleteStrategiesByYear[$fiscalYear]) &&
    $incompleteStrategiesByYear[$fiscalYear]->isNotEmpty();
    $missingStrategies = [];
    $logDataIncompleteStrategies = [];

    foreach ($logData as $logEntry) {
    if (strpos($logEntry, "Fiscal Year: $fiscalYear, Quarter: $quarter->Quarter") !== false) {
    if (strpos($logEntry, 'Status: No strategies created') !== false || strpos($logEntry, 'Status: No projects created')
    !== false) {
    $logDataIncompleteStrategies[] = preg_replace('/Fiscal Year: \d{4}, Quarter: \d, Status: (No strategies created|No
    projects created)/', '', $logEntry);
    }
    if (strpos($logEntry, 'Missing Strategy:') !== false) {
    preg_match('/Missing Strategy: (.*?),/', $logEntry, $matches);
    if (isset($matches[1])) {
    $missingStrategies[] = $matches[1];
    }
    }
    }
    }

    // Format logDataIncompleteStrategies to display only the required parts
    $logDataIncompleteStrategies = array_map(function($logEntry) {
    preg_match('/Strategy: (.*?), Strategic: (.*?),/', $logEntry, $matches);
    if (isset($matches[1], $matches[2])) {
    return "{$matches[2]}, {$matches[1]}";
    } elseif (preg_match('/Strategic: (.*?),/', $logEntry, $matches)) {
    return "{$matches[1]}, ไม่มีกลยุทธ์";
    } else {
    return $logEntry;
    }
    }, $logDataIncompleteStrategies);

    foreach ($quarterProjects as $Strategic) {
    foreach ($Strategic->projects as $project) {
    $strategyName = $project->Name_Strategy;
    $strategyProjects = $Strategic->projects->filter(function($p) use ($strategyName) {
    return $p->Name_Strategy === $strategyName;
    });

    $allProjectsStatusN = $strategyProjects->every(function($p) {
    return in_array('N', $p->approvalStatuses) || !isset($p->approvalStatuses);
    });

    $hasProjectStatusI = $strategyProjects->contains(function($p) {
    return in_array('I', $p->approvalStatuses);
    });

    if ($allProjectsStatusN && !$hasProjectStatusI) {
    $missingStrategies[] = $strategyName;
    }
    }
    }

    $missingStrategies = array_unique($missingStrategies);
    $allStrategiesComplete = empty($missingStrategies) && empty($logDataIncompleteStrategies);
    ?>

    <?php if($quarterProjects->isNotEmpty()): ?>
    <div class="card mb-4 border-2 <?php echo e($quarterStyle); ?>">
        <div class="card-body">
            <h5 class="card-title">เสนอหาผู้อำนวยการ ปีงบประมาณ <?php echo e($fiscalYear); ?> ไตรมาส <?php echo e($quarter->Quarter); ?></h5>

            <?php if($hasIncompleteStrategies || !empty($missingStrategies) || !empty($logDataIncompleteStrategies)): ?>
            <div class="alert alert-warning">
                <strong>กลยุทธ์ยังไม่ครบสำหรับปีงบประมาณ <?php echo e($fiscalYear); ?> ไตรมาส <?php echo e($quarter->Quarter); ?></strong>
                <ul>
                    <?php if($hasIncompleteStrategies): ?>
                    <?php $__currentLoopData = $incompleteStrategiesByYear[$fiscalYear]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $strategy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($strategy); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <?php $__currentLoopData = $missingStrategies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $strategy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($strategy); ?>: ไม่มีกลยุทธ์</li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php $__currentLoopData = $logDataIncompleteStrategies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $logEntry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($logEntry); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <?php else: ?>
            <div class="alert alert-success">
                <strong>กลยุทธ์ครบแล้วสำหรับปีงบประมาณ <?php echo e($fiscalYear); ?> ไตรมาส <?php echo e($quarter->Quarter); ?></strong>
            </div>
            <?php endif; ?>

            <div class="mb-3">
                <?php $__currentLoopData = $quarterProjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Strategic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                $filteredProjects = $Strategic->projects->filter(function($project) {
                return $project->Count_Steps == 0;
                });
                $filteredProjectCount = $filteredProjects->count();
                $firstStrategicPlanName = $Strategic->Name_Strategic_Plan;

                // Check for missing strategies
                $hasStrategies = !$Strategic->strategies->isEmpty();
                $hasProjects = $filteredProjectCount > 0;

                $totalStrategicBudget = $filteredProjects->sum(function($project) {
                return $project->projectBudgetSources ? $project->projectBudgetSources->sum('Amount_Total') : 0;
                });
                $projectsByStrategy = $filteredProjects->groupBy('Name_Strategy');
                ?>

                <details class="accordion" id="<?php echo e($Strategic->Id_Strategic); ?>">
                    <summary class="accordion-btn">
                        <b>
                            <a><?php echo e($firstStrategicPlanName); ?></a>
                            <?php if(!$hasStrategies): ?>
                            <br><span class="badge bg-danger">ยังไม่มีกลยุทธ์</span>
                            <?php elseif(!$hasProjects): ?>
                            <br><span class="badge bg-warning">มีกลยุทธ์แต่ยังไม่มีโครงการ</span>
                            <br>กลยุทธ์ที่มี:
                            <ul class="strategy-list">
                                <?php $__currentLoopData = $Strategic->strategies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $strategy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($strategy->Name_Strategy); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                            <?php endif; ?>
                            <br>จำนวนโครงการ : <?php echo e($filteredProjectCount); ?> โครงการ
                            <br>งบประมาณรวม: <?php echo e(number_format($totalStrategicBudget, 2)); ?> บาท
                        </b>
                    </summary>
                    <?php if($filteredProjectCount > 0): ?>
                    <div class="accordion-content">
                        <div class="table-responsive">
                            <table class="summary-table">
                                <thead>
                                    <tr>
                                        <th style="width:10%; text-align: center;">ยุทธศาสตร์ สำนักหอสมุด</th>
                                        <th style="width:10%; text-align: center;">กลยุทธ์ สำนักหอสมุด</th>
                                        <th style="width:14%; text-align: center;">โครงการ</th>
                                        <th style="width:14%; text-align: center;">ตัวชี้วัดความสำเร็จ<br>ของโครงการ</th>
                                        <th style="width:12%; text-align: center;">ค่าเป้าหมาย</th>
                                        <th style="width:10%; text-align: center;">งบประมาณ (บาท)</th>
                                        <th style="width:12%; text-align: center;">ผู้รับผิดชอบ</th>
                                        <th style="width:18%; text-align: center;">การจัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $projectsByStrategy; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $strategyName => $projects): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                    $strategyCount = $projects->count();
                                    ?>
                                    <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $Project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                    $isStatusN = in_array('N', $Project->approvalStatuses);
                                    $isStatusI = in_array('I', $Project->approvalStatuses);
                                    $allProjectsDeleted = $projects->every(function($project) {
                                    return in_array('N', $project->approvalStatuses);
                                    });
                                    ?>
                                    <tr>
                                        <?php if($index === 0 && $loop->parent->first): ?>
                                        <td rowspan="<?php echo e($filteredProjectCount); ?>"><?php echo e($firstStrategicPlanName); ?></td>
                                        <?php endif; ?>
                                        <?php if($index === 0): ?>
                                        <td class="<?php echo e($allProjectsDeleted ? 'text-gray' : ''); ?>"
                                            rowspan="<?php echo e($strategyCount); ?>">
                                            <?php echo e($strategyName ?? '-'); ?>

                                        </td>
                                        <?php endif; ?>
                                        <td class="<?php echo e($isStatusN && !$isStatusI ? 'text-gray' : ''); ?>">
                                            <b><?php echo e($Project->Name_Project); ?></b><br>
                                            <?php $__currentLoopData = $Project->subProjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subProject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            - <?php echo e($subProject->Name_Sub_Project); ?><br>                                            - <?php echo e($subProject->Name_Sup_Project); ?><br>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                        <td class="<?php echo e($isStatusN && !$isStatusI ? 'text-gray' : ''); ?>">
                                            <?php echo $Project->Success_Indicators ? nl2br(e($Project->Success_Indicators)) : '-'; ?>

                                        </td>
                                        <td class="<?php echo e($isStatusN && !$isStatusI ? 'text-gray' : ''); ?>">
                                            <?php echo $Project->Value_Target ? nl2br(e($Project->Value_Target)) : '-'; ?>

                                        </td>
                                        <td class="<?php echo e($isStatusN && !$isStatusI ? 'text-gray' : ''); ?>"
                                            style="text-align: center;">
                                            <?php if($Project->Status_Budget === 'N'): ?>
                                            ไม่ใช้งบประมาณ
                                            <?php else: ?>
                                            <?php
                                            $totalBudget = $Project->projectBudgetSources ?
                                            $Project->projectBudgetSources->sum('Amount_Total') : 0;
                                            ?>
                                            <?php echo e(number_format($totalBudget, 2)); ?>

                                            <?php endif; ?>
                                        </td>
                                        <td class="<?php echo e($isStatusN && !$isStatusI ? 'text-gray' : ''); ?>">
                                            <?php echo e($Project->employee->Firstname_Employee ?? '-'); ?>

                                            <?php echo e($Project->employee->Lastname_Employee ?? ''); ?>

                                        </td>
                                        <td class="<?php echo e($isStatusN && !$isStatusI ? 'text-gray' : ''); ?>"
                                            style="text-align: center;">
                                            <a href="<?php echo e(route('editProject', ['Id_Project' => $Project->Id_Project, 'sourcePage' => 'proposeProject'])); ?>"
                                                class="btn btn-warning btn-sm">แก้ไข</a>
                                            <?php if(!$isStatusN || $isStatusI): ?>
                                            <form
                                                action="<?php echo e(route('projects.updateStatus', ['id' => $Project->Id_Project])); ?>"
                                                method="POST" style="display:inline;">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to update the status of this project?');">ลบ</button>
                                            </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="summary-row">
                                        <td colspan="2" style="text-align: left; font-weight: bold;">รวมรายได้ทั้งหมด:</td>
                                        <td colspan="6" style="text-align: center; font-weight: bold;">
                                            <?php echo e(number_format($totalStrategicBudget, 2)); ?> บาท
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="accordion-content">
                        <p>ไม่มีโครงการที่เกี่ยวข้อง</p>
                    </div>
                    <?php endif; ?>
                </details>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="button-container mt-3">
                <form action="<?php echo e(route('projects.submitForAllApproval')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="quarter" value="<?php echo e($quarter->Quarter); ?>">
                    <input type="hidden" name="fiscal_year" value="<?php echo e($fiscalYear); ?>">
                    <?php $__currentLoopData = $quarterProjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Strategic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $__currentLoopData = $Strategic->projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <input type="hidden" name="project_ids[]" value="<?php echo e($project->Id_Project); ?>">
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <button type="submit" class="btn btn-primary w-full"
                        <?php echo e($allStrategiesComplete && !$hasStatusN ? '' : 'disabled'); ?>>
                        เสนอโครงการทั้งหมด ไตรมาส <?php echo e($quarter->Quarter); ?>

                    </button>
                </form>
            </div>

        </div>
    </div>
    <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusHeaders = document.querySelectorAll('.status-header');

    statusHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const projectStatus = this.nextElementSibling;
            const toggleIcon = this.querySelector('.toggle-icon');

            if (projectStatus.classList.contains('show')) {
                projectStatus.style.maxHeight = 0;
                projectStatus.classList.remove('show');
                toggleIcon.classList.remove('rotate');
            } else {
                projectStatus.style.maxHeight = projectStatus.scrollHeight + 'px';
                projectStatus.classList.add('show');
                toggleIcon.classList.add('rotate');
            }
        });
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('navbar.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/proposeProject.blade.php ENDPATH**/ ?>