<!DOCTYPE html>
<html lang="th">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="<?php echo e(public_path('css/pdfReport.css')); ?>">
    <title>รายงานผลการดำเนินงาน</title>
    <style>
        b {
            margin-right:5px;
        }
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: normal;
            src: url('<?php echo e(public_path('fonts/THSarabunNew.ttf')); ?>') format('truetype');
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: bold;
            src: url('<?php echo e(public_path('fonts/THSarabunNew Bold.ttf')); ?>') format('truetype');
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: italic;
            font-weight: normal;
            src: url('<?php echo e(public_path('fonts/THSarabunNew Italic.ttf')); ?>') format('truetype');
        }
        @font-face {
            font-family: 'THSarabunNew';
            font-style: italic;
            font-weight: bold;
            src: url('<?php echo e(public_path('fonts/THSarabunNew BoldItalic.ttf')); ?>') format('truetype');
        }
    </style>
</head>
<body>

    <h1>รายงานผลการดำเนินงาน<br>
    <?php echo e(toThaiNumberOnly($project->Name_Project)); ?> <br>
    สำนักหอสมุด มหาวิทยาลัยบูรพา</h1>
    <div class="line" style="width: 100%; max-width: 590px; word-wrap: break-word;"></div>
    <p><b>๑. ชื่อโครงการ </b><?php echo e(toThaiNumberOnly($project->Name_Project)); ?></p>

    <p class="space"><b>๒. ผู้รับผิดชอบโครงการ </b>
        <?php echo e($project->employee->Prefix_Name ?? '-'); ?><?php echo e($project->employee->Firstname ?? ''); ?>

        <?php echo e($project->employee->Lastname ?? ''); ?>

    </p>

    <p class="space"><b>๓. วัตถุประสงค์โครงการ</b>
        <p class="paragraph-content">
            <?php echo e(toThaiNumberOnly($project->Objective_Project) ?? '-'); ?>

        </p>
    </p>

    <p class="space"><b>๔. กลุ่มเป้าหมาย</b>
        <p class="paragraph"><b>๔.๑ กลุ่มผู้รับบริการ</b>
            <table class="paragraph-two">
                <?php $__currentLoopData = $project->targets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $target): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td style="vertical-align: top; text-align: left; border: none;"><span><p>- <?php echo e($target->Name_Target); ?></p></span>
                    <td style="vertical-align: top; text-align: left; border: none;"><span><p>จำนวน <?php echo e(toThaiNumber($target->Quantity_Target)); ?> <?php echo e($target->Unit_Target); ?></p></span></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </table>
        </p>

        <p class="paragraph" style="margin-top: 20px;">
            <b>๔.๒ พื้นที่/ชุมชนเป้าหมาย (ถ้ามี ระบุ)</b>
        </p>
        <p class="paragraph-two">
            <?php $__currentLoopData = $project->targets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $target): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $__currentLoopData = $target->targetDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo e(toThaiNumberOnly($detail->Details_Target)); ?>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </p>
   
    </p>

    <p class="space">
        <span><b>๕. ระยะเวลาดำเนินโครงการ</b></span>
        <p class="paragraph-content">
            <?php if(!empty($project->First_Time) && !empty($project->End_Time)): ?>
                <span>กำหนดการจัดโครงการ 
                    <b><?php echo e($project->formatted_first_time); ?></b> ถึง <b><?php echo e($project->formatted_end_time); ?></b>
                </span>
            <?php else: ?>
                <span>-</span>
            <?php endif; ?>
        </p>
    </p>

    <p class="space">
        <span><b>๖. สถานที่ดำเนินงาน</b></span><br>
        <!-- <p class="paragraph">  -->
        <?php if($project->locations->isNotEmpty()): ?>
            <?php $__currentLoopData = $project->locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <span class="paragraph">
                <?php echo e(toThaiNumber($index + 1)); ?>. <?php echo e(toThaiNumberOnly($location->Name_Location)); ?> <br>
            </span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            - 
        <?php endif; ?>
        <!-- </p> -->
    </p>

    <p class="space">
        <span><b>๗. วิทยากร</b></span>
        <p class="paragraph"> 
            <?php echo e(toThaiNumberOnly($project->Speaker)); ?> <br> 
        </p>
    </p>

    <p class="space">
        <span><b>๘. รูปแบบกิจกรรมการดำเนินงาน</b></span> <br>
        <!-- โครงการระยะสั้น -->
        <b>วิธีการดำเนินงาน</b><br>
        <p> 
            <?php $__currentLoopData = $project->shortProjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shortProject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span class="paragraph">
                    <?php echo e(toThaiNumber($loop->iteration)); ?>. <?php echo e(toThaiNumberOnly($shortProject->Details_Short_Project)); ?>

                </span><br>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </p>

        <p><b>ขั้นตอนและแผนการดำเนินงาน(PDCA)</b><br></p>

        <!-- โครงการระยะยาว -->
        <table>
            <thead>
                <tr>
                    <th rowspan="2" style="width: 40%; line-height: 0.6;" >กิจกรรมและแผนการเบิกจ่ายงบประมาณ</th>
                    <th colspan="12">
                        <span>ปีงบประมาณ พ.ศ.</span>
                        <?php
                            $uniqueYears = $quarterProjects->pluck('quarterProject.Fiscal_Year')->unique();
                        ?>

                        <?php $__currentLoopData = $uniqueYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span><?php echo e($year); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </th>
                </tr>
                <tr>
                    <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <th><?php echo e($month); ?></th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tr>
            </thead>
            <tbody>
                <?php
                    $groupedPdcaDetails = $project->pdcaDetails->groupBy(function($pdcaDetail) {
                        return $pdcaDetail->pdca->Name_PDCA ?? 'N/A';
                    });
                ?>

                <?php $__currentLoopData = $groupedPdcaDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $namePDCA => $pdcaDetails): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td style="text-align: left;">
                            <strong><?php echo e($namePDCA); ?></strong><br>
                            <?php $__currentLoopData = $pdcaDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pdcaDetail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php echo e(toThaiNumber($loop->iteration)); ?>. <?php echo e($pdcaDetail->Details_PDCA); ?><br>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                        <?php for($month = 1; $month <= 12; $month++): ?>
                            <td style="text-align: center;">
                            <?php if($project->monthlyPlans->where('Months_Id', $month)->where('PDCA_Stages_Id', $pdcaDetail->PDCA_Stages_Id)->isNotEmpty()): ?>
                                /
                            <?php endif; ?>
                            </td>
                        <?php endfor; ?>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>


    </p>

  

    <!-- ตัวชี้วัด -->
    <p class="space">   
        <span><b>๙. ตัวชี้วัดความสำเร็จ</b></span>
        <?php if($project->projectHasIndicators->where('indicators.Type_Indicators', 'Quantitative')->isNotEmpty()): ?>
            <p class="paragraph"><b>เชิงปริมาณ</b></p>
            <?php $__currentLoopData = $project->projectHasIndicators->where('indicators.Type_Indicators', 'Quantitative')->values(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $projectIndicator): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <p class="paragraph-two"><?php echo e(toThaiNumber($index + 1)); ?>. <?php echo e(toThaiNumberOnly($projectIndicator->Details_Indicators)); ?></p>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>

        <?php if($project->projectHasIndicators->where('indicators.Type_Indicators', 'Qualitative')->isNotEmpty()): ?>
            <p class="paragraph"><b>เชิงคุณภาพ</b></p>
            <?php $__currentLoopData = $project->projectHasIndicators->where('indicators.Type_Indicators', 'Qualitative')->values(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $projectIndicator): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <p class="paragraph-two"><?php echo e(toThaiNumber($index + 1)); ?>. <?php echo e(toThaiNumberOnly($projectIndicator->Details_Indicators)); ?></p>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>

    </p>

    <p class="space">
        <span><b>๑๐. สรุปผลการดำเนินงาน</b></span>   
        <p class="paragraph-content">
            <?php echo e($project->Summary); ?>

        </p>

        <p class="paragraph-content">
            <b>ผลสำเร็จตามตัวชี้วัดของโครงการ</b><br>
            <?php if($project->projectHasIndicators->where('indicators.Type_Indicators', 'Quantitative')->isNotEmpty()): ?>
                <p class="paragraph"><b>ตัวชี้วัดเชิงปริมาณ</b></p>
                <?php $__currentLoopData = $project->projectHasIndicators->where('indicators.Type_Indicators', 'Quantitative')->values(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $projectIndicator): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <p class="paragraph-two"><?php echo e(toThaiNumber($index + 1)); ?>. <?php echo e(toThaiNumberOnly($projectIndicator->Details_Indicators)); ?></p>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>

            <?php if($project->projectHasIndicators->where('indicators.Type_Indicators', 'Qualitative')->isNotEmpty()): ?>
                <p class="paragraph"><b>ตัวชี้วัดเชิงคุณภาพ</b></p>
                <?php $__currentLoopData = $project->projectHasIndicators->where('indicators.Type_Indicators', 'Qualitative')->values(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $projectIndicator): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <p class="paragraph-two"><?php echo e(toThaiNumber($index + 1)); ?>. <?php echo e(toThaiNumberOnly($projectIndicator->Details_Indicators)); ?></p>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </p>
        
        
        <p class="paragraph-content mt-3">
            <b>การมีส่วนร่วมของหน่วยงานภายนอก/ชุมชน</b>
            <p class="paragraph-two"><?php echo e($project->External_Participation); ?> test</p>
        </p>
        <p class="paragraph-content mt-3">
            <b>งบประมาณ</b>
            <?php if(!empty($project) && $project->Status_Budget == 'Y'): ?>
                <?php $__currentLoopData = $project->budgetForm; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $budget): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    ใช้งบประมาณทั้งสิ้น <?php echo e(toThaiNumber($budget->Amount_Big)); ?> บาท
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                ไม่มีงบประมาณ
            <?php endif; ?>
        </p>

        <p class="paragraph-content mt-3">
            <b>ข้อเสนอแนะ</b><br>
            <p class="paragraph-two">
                <?php echo e($project->Suggestions); ?>

                ..................................................
                .....................................................
                ...................................... ............
                ....................................................
                ....................................................
                ......................... 


            </p>
        </p>
       
       
    </p>

</body>
</html><?php /**PATH /var/www/resources/views/PDF/PDFReportForm.blade.php ENDPATH**/ ?>