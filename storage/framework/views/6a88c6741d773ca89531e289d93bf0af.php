<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
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

    body {
        margin: 0;
        padding-top: 0.5in;
        padding-left: 1in;
        padding-right: 1in;
        padding-bottom: 1in;
        font-family: 'THSarabunNew', sans-serif;
    }

    .page-container {
        padding: 40px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        margin-bottom: 20px;
    }

    th,
    td {
        border: 1px solid #000;
        padding: 10px;
        font-size: 14px;
        vertical-align: top;
        word-wrap: break-word;
        background-color: white;
    }

    th {
        background-color: #c8e6c9 !important;
        font-weight: bold;
        text-align: center;
    }

    .red-text {
        color: red;
    }

    .sub-item {
        padding-left: 20px;
    }

    .button-container {
        margin-bottom: 20px;
        padding: 0;
        text-align: center;
    }

    button {
        background-color: #4CAF50;
        border: none;
        color: white;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
        border-radius: 4px;
    }

    #content {
        padding: 40px;
        border: 1px solid #ccc;
    }

    @media print {
        @page {
            size: A4 landscape;
        }

        .button-container {
            display: none;
        }

        body {
            padding: 0;
            margin: 0.4in 0.04in 0.4in 0.4in;
        }

        #content {
            padding: 0;
            border: none;
            width: 100%;
        }

        th {
            background-color: #c8e6c9 !important;
            color: black;
            text-align: center;
            padding: 10px;
            border: 1px solid black !important;
        }

        td {
            padding: 8px;
            word-wrap: break-word;
        }
    }
    </style>
</head>

<body>
    <div id="content">
        <table>
            <thead class="thead-repeat">
                <tr>
                    <th style="width:10%">ยุทธศาสตร์ สำนักหอสมุด</th>
                    <th style="width:10%">กลยุทธ์ สำนักหอสมุด</th>
                    <th style="width:14%">โครงการ</th>
                    <th style="width:10%">วัตถุประสงค์<br>โครงการ</th>
                    <th style="width:14%">ตัวชี้วัดความสำเร็จ ของโครงการ</th>
                    <th style="width:12%">ค่าเป้าหมาย</th>
                    <th style="width:10%">ระยะเวลา</th>
                    <th style="width:10%">งบประมาณ (บาท)</th>
                    <th style="width:12%">ผู้รับผิดชอบ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                use Carbon\Carbon;
                Carbon::setLocale('th');
                ?>
                <tr>
                    <td><?php echo e($project->strategic->Name_Strategic_Plan); ?></td>
                    <td><?php echo e($project->Name_Strategy ?? '-'); ?></td>
                    <td>
                        <b><?php echo e($project->Name_Project); ?></b><br>
                        <?php $__currentLoopData = $project->subProjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subProject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        - <?php echo e($subProject->Name_Sub_Project); ?><br>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </td>
                    <td><?php echo e($project->Objective_Project ?? '-'); ?></td>

                    <td>
                        <?php if($project->Success_Indicators): ?>
                        <?php echo nl2br(e($project->Success_Indicators)); ?>

                        <?php else: ?>
                        -
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($project->Value_Target): ?>
                        <?php echo nl2br(e($project->Value_Target)); ?>

                        <?php else: ?>
                        -
                        <?php endif; ?>
                    </td>

                    <td>
                        <?php if($project->First_Time === null && $project->End_Time === null): ?>
                        ยังไม่ได้กำหนดวันที่
                        <?php else: ?>
                        <?php echo e($project->First_Time ? Carbon::parse($project->First_Time)->translatedFormat('d M Y') : '-'); ?>

                        -
                        <?php echo e($project->End_Time ? Carbon::parse($project->End_Time)->translatedFormat('d M Y') : '-'); ?>

                        <?php endif; ?>
                    </td>
                    <?php
                    $totalBudget = $projectBudgetSources->sum('Amount_Total');
                    ?>
                    <td style="text-align: center;">
                        <?php if($totalBudget === null || $totalBudget == 0): ?>
                        ไม่ใช้งบประมาณ
                        <?php else: ?>
                        <?php echo e(number_format($totalBudget, 2)); ?>

                        <?php endif; ?>
                    </td>
                    <td>
                        <?php echo e($project->employee->Firstname_Employee ?? '-'); ?>

                        <?php echo e($project->employee->Lastname_Employee ?? ''); ?>

                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html><?php /**PATH /var/www/resources/views/PDF/ctrlP/pdfproject.blade.php ENDPATH**/ ?>