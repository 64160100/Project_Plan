<!DOCTYPE html>
<html lang="th">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="<?php echo e(public_path('css/pdf.css')); ?>">
    <title><?php echo e($project->Name_Project); ?></title>
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
    
    <h1><?php echo e(toThaiNumber($project->Name_Project)); ?></h1>
    <div class="line" style="width: 100%; max-width: 590px; word-wrap: break-word;"></div>
    <!-- <p><b>๑. ชื่อโครงการ: </b><?php echo e($project->Name_Project); ?></p> -->
    <p><b>๑. ชื่อโครงการ: </b><?php echo e(toThaiNumber($project->Name_Project)); ?></p>

    
    <p class="space"><b style="color:#f00">๒. ลักษณะโครงการ:</b>
        <?php if($project->Description_Project == 'N'): ?>
            <span class="checkbox" style="margin-left:25px;">&#9745;</span><span style="margin-left:5px;">โครงการใหม่</span>
            <span class="checkbox" style="margin-left:25px;">&#9744;</span><span style="margin-left:5px;">โครงการต่อเนื่อง</span>
        <?php else: ?>
            <span class="checkbox" style="margin-left:25px;">&#9744;</span><span style="margin-left:5px;">โครงการใหม่</span>
            <span class="checkbox" style="margin-left:25px;">&#9745;</span><span style="margin-left:5px;">โครงการต่อเนื่อง</span>
        <?php endif; ?>
    </p>

    <p class="space"><b>๓. ผู้รับผิดชอบโครงการ</b>
        <p class='paragraph'>
            <?php echo e($project->employee->Firstname ?? '-'); ?>

            <?php echo e($project->employee->Lastname ?? ''); ?>

        </p>
    </p>

    <p class="space" style="color:#f00"> ขาดปีงบประมาณ
        <b>๔. ความสอดคล้องกับยุทธศาสตร์มหาวิทยาลัย</b>

        (ปีงบประมาณ พ.ศ. 
        

        )

        <?php $__currentLoopData = $project->platforms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $platform): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <p class="paragraph-tab">
                <span class="checkbox">&#9744;</span>
                <span><b>แพลตฟอร์ม <?php echo e(toThaiNumber($loop->iteration)); ?> <?php echo e(toThaiNumber($platform->Name_Platform)); ?></b></span>
            </p>

            <?php $__currentLoopData = $platform->programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <p class="paragraph">
                    <span class="checkbox">&#9745;</span>
                    <span><?php echo e(toThaiNumber($program->Name_Program)); ?></span>
                </p>

                <?php $__currentLoopData = $program->kpis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kpi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <p class="paragraph">
                        <span class="checkbox">&#9745;</span>
                        <span><?php echo e(toThaiNumber($kpi->Name_KPI)); ?></span>
                    </p>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </p>

    <p class="space"><b>๕. ความสอดคล้องกับยุทธศาสตร์ส่วนงาน</b>
        <p class='paragraph-tab'>
            <span class="checkbox">&#9745;</span>
            <span><b><?php echo e(toThaiNumber($project->strategic->Name_Strategic_Plan)); ?></b></span>
        </p>
        <p class='paragraph'>
            <span class="checkbox">&#9745;</span>
            <span><?php echo e(toThaiNumber($project->Name_Strategy)); ?></span>
        </p>
    </p>

    <p class="space"><b>๖. สอดคล้องกับ (SDGs) (เลือกได้มากกว่า ๑ หัวข้อ)</b>
        <?php $__currentLoopData = $project->projectHasSDGs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project_has_sdgs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <p class='paragraph'>
                <span class="checkbox">&#9745;</span><span style="margin-left:5px;">
                    <?php echo e(toThaiNumber($project_has_sdgs->sdg->Name_SDGs)); ?>

                </span>
            </p>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </p>

    <p class="space"><b>๗. การบูรณาการงานโครงการ/กิจกรรม กับ</b>
        <p class='paragraph'>
            <?php $__currentLoopData = $project->projectHasIntegrationCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $projectHasIntegrationCategorie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span>๗.<?php echo e(toThaiNumber($loop->iteration)); ?></span>
                <span class="checkbox">&#9745;</span>
                <span><b><?php echo e($projectHasIntegrationCategorie->integrationCategory->Name_Integration_Category); ?></b></span><br>
                
                <?php if($projectHasIntegrationCategorie->Integration_Details): ?>
                    <span class="paragraph">
                    <?php echo e($projectHasIntegrationCategorie->Integration_Details); ?>

                    </span><br>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </p>
    </p>

    <p class="space">
        <span><b>๘. หลักการและเหตุผล</b></span>   
        <span>(ระบุที่มา เหตุผล/ปัญหา/ความจำเป็น/ความสำคัญ/องค์ความรู้และความเชี่ยวชาญ ของสาขาวิชา)</span>
        <p class="paragraph-content">
            <?php echo e(toThaiNumber($project->Principles_Reasons ?? '-')); ?>

        </p>
    </p>

    <p class="space"><b>๙. วัตถุประสงค์โครงการ</b>
        <p class="paragraph-content">
            <?php echo e(toThaiNumber($project->Objective_Project ?? '-')); ?>

        </p>
    </p>

    <p class="space"><b>๑๐. กลุ่มเป้าหมาย</b>
        <p class="paragraph"><b>๑๐.๑ กลุ่มผู้รับบริการ</b>
            <?php $__currentLoopData = $project->targets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $target): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <table style="border-collapse: collapse; width:100%; border: none;">
                    <tr>
                        <td style="width: 18%; border: none;"></td>
                        <td style="width: 35%; text-align: left; padding: 5px; border: none;">- <?php echo e($target->Name_Target); ?></td>
                        <td style="text-align: left; padding: 5px; border: none;">
                            <span>จำนวน </span>
                            <span class="line" style="width: 50px; line-height: 0.8;">
                                <?php echo e(toThaiNumber($target->Quantity_Target)); ?>

                            </span> 
                            <?php echo e(toThaiNumber($target->Unit_Target)); ?>

                        </td>
                    </tr>
                </table>      
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </p>

        <p class="paragraph" style="margin-top: 20px;"><b>๑๐.๒ พื้นที่/ชุมชนเป้าหมาย (ถ้ามี ระบุ) </b>
            <?php $__currentLoopData = $project->targets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $target): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $__currentLoopData = $target->targetDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <p class="paragraph-two">
                        <?php echo e(toThaiNumber($detail->Details_Target)); ?>

                    </p>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </p>   
    </p>

    <p class="space">
        <span><b>๑๑. สถานที่ดำเนินงาน</b></span>
        <p class="paragraph-content"> 
            <?php $__currentLoopData = $project->locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            ๑๑.<?php echo e(toThaiNumber($loop->iteration)); ?> <?php echo e(toThaiNumber($location->Name_Location)); ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </p>
    </p>
    
    <!-- ตัวชี้วัด -->
    <p class="space">   
        <span><b>๑๒. ตัวชี้วัด</b></span>
        <?php
            $groupedIndicators = collect($project->projectHasIndicators)
                ->groupBy(fn($indicator) => $indicator->indicators->Type_Indicators);
        ?>

        <?php $__currentLoopData = ['Quantitative' => 'เชิงปริมาณ', 'Qualitative' => 'เชิงคุณภาพ']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(!empty($groupedIndicators[$type])): ?>
                <p class="paragraph"><b>๑๒.<?php echo e(toThaiNumber($loop->iteration)); ?>. <?php echo e($label); ?></b></p>
                <?php $__currentLoopData = $groupedIndicators[$type]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $indicator): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <p class="paragraph-two <?php echo e($loop->last ? 'loop_last' : ''); ?>">
                        (<?php echo e(toThaiNumber($loop->iteration)); ?>) <?php echo e(toThaiNumber($indicator->Details_Indicators)); ?>

                    </p>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </p>
    

    <p class="space">
        <span><b>๑๓. ระยะเวลาดำเนินโครงการ</b></span>
        <p class="paragraph">
            <?php if(!empty($project->First_Time) && !empty($project->End_Time)): ?>
                <span>
                    กำหนดการจัดโครงการ <b><?php echo e($project->formatted_first_time); ?></b><br>
                    ถึง <b style="margin-left: 6px"><?php echo e($project->formatted_end_time); ?></b>
                </span>
            <?php else: ?>
                <span>-</span>
            <?php endif; ?>
        </p>
    </p>

    <p class="space">
        <span><b>๑๔. ขั้นตอนและแผนการดำเนินงาน (PDCA)</b></span><br>
        <span class="checkbox" style="margin-left:25px;">&#9745;</span><span style="margin-left:5px; color:#f00">โครงการระยะสั้น</span>
        <span class="checkbox" style="margin-left:25px;">&#9744;</span><span style="margin-left:5px; color:#f00">โครงการระยะยาว</span>
        
        <!-- โครงการระยะยาว -->
        <table>
            <thead>
                <tr>
                    <th rowspan="2" style="width: 40%; line-height: 0.6;" >กิจกรรมและแผนการเบิกจ่าย งบประมาณ</th>
                    <th colspan="13">
                        <span style="color:#f00">ปีงบประมาณ พ.ศ.</span>
                        <span class="line" style="display: inline-block; padding: 0 10px; line-height: 0.8;">rwrr</span>
                    </th>
                </tr>
                <tr>
                    <th>ต.ค.</th><th>พ.ย.</th><th>ธ.ค.</th><th>ม.ค.</th><th>ก.พ.</th><th>มี.ค.</th>
                    <th>เม.ย.</th><th>พ.ค.</th><th>มิ.ย.</th><th>ก.ค.</th><th>ส.ค.</th><th>ก.ย.</th>
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
                        <td></td><td></td><td></td><td></td><td></td><td></td>
                        <td></td><td></td><td></td><td></td><td></td><td></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

    </p>

    <p class="space">
        <span><b style="color:#f00">๑๕. แหล่งงบประมาณ</b></span><br>
            <?php if($project->Status_Budget != 'Y'): ?>
                    <b class="paragraph">-</b>
            <?php else: ?>     
                <?php $__currentLoopData = $projectBudgetSources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $budget): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="checkbox" style="margin-left:25px;">&#9745;</span>
                    <span style="margin-left:5px;"><?php echo e($budget->budgetSource->Name_Budget_Source); ?>

                        <b class="line" style="display: inline-block; padding-left: 20px; padding-right: 20px; width: auto;">
                            <?php echo e($budget->Amount_Total); ?>

                        </b>บาท
                    </span>
                    <p class="paragraph-content"><?php echo e($budget->Details_Expense); ?></p>
                        <div class="head-table">รายละเอียดค่าใช้จ่าย (แตกตัวคูณโดยใช้อัตราตามหลักเกณฑ์อัตราค่าใช้จ่าย)</div>
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 10%;">ลำดับ</th>
                                    <th style="width: 70%;">รายการ</th>
                                    <th style="width: 20%;">จำนวน (บาท)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $index = 1; ?>
                                <?php $__currentLoopData = $subBudgets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subBudget): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td style="text-align: center;"><b><?php echo e(toThaiNumber($index)); ?></b></td>
                                        <td style="text-align: left;"><b><?php echo e($subBudget->Name_Subtopic_Budget); ?></b></td>
                                        <td></td>
                                    </tr>

                                    <?php $subIndex = 1; ?>
                                    <?php $__currentLoopData = $subBudget->subtopicBudgetForms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subBudgetForm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td ></td>
                                            <td style="text-align: left;">- <?php echo e($subBudgetForm->Details_Subtopic_Form); ?></td>
                                            <td style="text-align: center;"><?php echo e(toThaiNumber (number_format($subBudgetForm->Amount_Sub))); ?></td>
                                        </tr>
                                        <?php $subIndex++; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php $index++; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <th></th>
                                    <th style="text-align: center;">รวมทั้งสิ้น</th>
                                    <td style="text-align: center;"><b><?php echo e(toThaiNumber($subBudgets->sum(fn($b) => $b->subtopicBudgetForms->sum('Amount_Sub')))); ?></b></td>
                                </tr>
                            </tbody>
                        </table>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
    </p>

    <p class="space">
        <span><b>๑๖. เป้าหมายเชิงผลผลิต (Output)</b></span>
        <p class="paragraph"> 
            <?php $__currentLoopData = $output; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $outputs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <p class="paragraph-content">๑๖.<?php echo e(toThaiNumber($loop->iteration)); ?> <?php echo e(toThaiNumber($outputs->Name_Output)); ?></p>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
        </p>
    </p>


    <p class="space">
        <span><b>๑๗. เป้าหมายเชิงผลลัพธ์ (Outcome)</b></span>
        <p class="paragraph"> 
            <?php $__currentLoopData = $outcome; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $outcomes): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <p class="paragraph-content">๑๗.<?php echo e(toThaiNumber($loop->iteration)); ?> <?php echo e(toThaiNumber($outcomes->Name_Outcome)); ?></p>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </p>
    </p>

    <p class="space">
        <span><b>๑๘. ผลที่คาดว่าจะได้รับ</b></span>
        <p class="paragraph"> 
            <?php $__currentLoopData = $expectedResult; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expectedResults): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <p class="paragraph-content">๑๘.<?php echo e(toThaiNumber($loop->iteration)); ?> <?php echo e(toThaiNumber($expectedResults->Name_Expected_Results)); ?></p>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </p>
    </p>

    <p class="space">
        <span><b>๑๙. ตัวชี้วัดความสำเร็จของโครงการ</b></span>
        <p class="paragraph-content"> 
            <span><?php echo e(toThaiNumber($project->Success_Indicators ?? '-')); ?></span>
        </p>
    </p>
    <p class="space">
        <span><b>๒๐. ค่าเป้าหมาย</b></span>
        <p class="paragraph-content"> 
            <span><?php echo e(toThaiNumber($project->Value_Target ?? '-')); ?></span>
        </p>
    </p>

    
</body>
</html><?php /**PATH /var/www/resources/views/PDF/PDF.blade.php ENDPATH**/ ?>