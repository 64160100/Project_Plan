<!DOCTYPE html>
<html lang="th">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="<?php echo e(public_path('css/pdf.css')); ?>">
    <title>ตัวอย่าง PDF ภาษาไทย</title>
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
    
    <h1><?php echo e($project->Name_Project); ?></h1>
    <div class="line" style="width: 100%; max-width: 590px; word-wrap: break-word;"></div>
    <p><b>๑. ชื่อโครงการ :</b><?php echo e($project->Name_Project); ?></p>

    <p class="space"><b style="color:#f00">๒. ลักษณะโครงการ : (หาตารางไม่เจอ)</b>
        <span class="checkbox" style="margin-left:25px;">&#9745;</span><span style="margin-left:5px;">โครงการใหม่</span>
        <span class="checkbox" style="margin-left:25px;">&#9744;</span><span style="margin-left:5px;">โครงการต่อเนื่อง</span>
    </p>

    <p class="space"><b>๓. ผู้รับผิดชอบโครงการ :</b>
    
        <p class='paragraph'>
            <?php echo e($project->employee->Firstname ?? '-'); ?>

            <?php echo e($project->employee->Lastname ?? ''); ?>

        </p>
    </p>

    <p class="space"><b style="color:#f00">๔. ความสอดคล้องกับยุทธศาสตร์มหาวิทยาลัย</b>  (ข้าม)
        <p class='paragraph-tab'>
            <span class="checkbox">&#9745;</span>
            <span>
                <!-- <b>แพลตฟอร์ม ๑ การยกระดับคุณภาพการศึกษาสู่มาตรฐานสากล และการสร้างบุคลากรคุณภาพ</b> -->
            </span>
        </p>
        <p class='paragraph'>
            <span class="checkbox">&#9745;</span>
            <!-- <span> โปรแกรม..................................................................................................................... </span> -->
        </p>
    </p>

    <p class="space"><b>๕. ความสอดคล้องกับยุทธศาสตร์ส่วนงาน</b>
        <p class='paragraph-tab'>
            <span class="checkbox">&#9745;</span>
            <span><b><?php echo e($project->strategic->Name_Strategic_Plan); ?></b></span>
        </p>
        <p class='paragraph'>
            <span class="checkbox">&#9745;</span>
            <span><?php echo e($project->Name_Strategy); ?></span>
        </p>
    </p>

    <p class="space"><b>๖. สอดคล้องกับ (SDGs) (เลือกได้มากกว่า ๑ หัวข้อ)</b>  (ข้าม)
        
            <?php $__currentLoopData = $project->sdgs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sdg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <p class='paragraph'>
                    <span class="checkbox">&#9745;</span><span style="margin-left:5px;"><?php echo e($sdg->Name_SDGs); ?></span>
                </p>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        
    </p>

    <p class="space"><b style="color:#f00">๗. การบูรณาการงานโครงการ/กิจกรรม กับ</b>   (ข้าม)
        <p class='paragraph'>๗.๑
            <span class="checkbox">&#9744;</span>
            <span>การบริการสารสนเทศ</span>
        </p>
    </p>

    (ข้าม)<p class="space">
        <span><b>๘. หลักการและเหตุผล</b></span>   
        <span>(ระบุที่มา  เหตุผล/ ปัญหา /ความจำเป็น/ ความสำคัญ / องค์ความรู้และความเชี่ยวชาญ ของสาขาวิชา)</span>
        <p class="paragraph-content">
            <?php echo e($project->Principles_Reasons ?? '-'); ?>

        </p>
    </p>

    <p class="space"><b style="color:#f00">๙. วัตถุประสงค์โครงการ</b>
        <p class="paragraph-content">
            <?php echo e($project->Objective_Project ?? '-'); ?>

        </p>
    </p>

    <p class="space"><b style="color:#f00">๑๐. กลุ่มเป้าหมาย</b>
        <p class="paragraph"><b>๑๐.๑ กลุ่มผู้รับบริการ</b>
            <p class="paragraph-two">
                <span>- อาจารย์/บุคลากรภายใน</span>
                <span style="margin-left: 70px;">จำนวน </span><span class="line" style="width: 50px;">2</span> คน
            </p>   
        </p>
        <p class="paragraph" style="margin-top: 20px;"><b>๑๐.๒ พื้นที่/ชุมชนเป้าหมาย (ถ้ามี ระบุ)</b>
            <p class="paragraph-two">
            </p>
        </p>    
    </p>

    <p class="space"><b>ตัวชี้วัด</b>
        <p class="paragraph">
            <b>กลยุทธ์ที่เกี่ยวข้อง</b>
        </p> 
        <p class="paragraph">
            <b>ตัวชี้วัดความสำเร็จโครงการ</b>
        </p>
        <p class="paragraph">
            <b>ค่าเป้าหมาย</b>
        </p>
    </p>

    
    <p class="space"><b style="color:#f00">ตัวชี้วัดตามเป้าหมายการให้บริการหน่วยงานและเป้าหมายผลผลิตของมหาวิทยาลัย</b>
        <p class="paragraph">
            <span class="checkbox">&#9745;</span><span style="margin-left:5px;">โครงการใหม่</span>
            <span class="checkbox" style="margin-left:25px;">&#9744;</span><span style="margin-left:5px;">โครงการต่อเนื่อง</span>
        </p> 
    </p>

    <p class="space">
        <span><b>๑๑. สถานที่ดำเนินงาน</b></span>
        <p class="paragraph"> 
            <?php $__currentLoopData = $project->locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo e($location->Name_Location); ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </p>
    </p>
    
    <!-- ตัวชี้วัด -->
    <p class="space">   
        <span><b>๑๒. ตัวชี้วัด</b></span> (ให้รันเลขข้อเอง)
        <?php $__currentLoopData = $project->projectHasIndicators; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $projectIndicator): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($projectIndicator->indicators): ?>
                <?php if($projectIndicator->indicators->Type_Indicators === 'Quantitative'): ?>
                    <p class="paragraph"><b>เชิงปริมาณ</b>
                        <p class="paragraph-two"><?php echo e($projectIndicator->Details_Indicators); ?></p>
                    </p>
                <?php elseif($projectIndicator->indicators->Type_Indicators === 'Qualitative'): ?>
                    <p class="paragraph"><b>เชิงคุณภาพ</b>
                        <p class="paragraph-two"><?php echo e($projectIndicator->Details_Indicators); ?></p>
                    </p>
                <?php endif; ?>
            <?php else: ?>
                <p class="paragraph"><b>-</b></p>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </p>

    <p class="space">
        <span><b>๑๓. ระยะเวลาดำเนินโครงการ</b></span>
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
        <span><b style="color:#f00">๑๔. ขั้นตอนและแผนการดำเนินงาน (PDCA)</b></span><br>
        <!-- <span class="checkbox" style="margin-left:25px;">&#9745;</span><span style="margin-left:5px;">โครงการระยะสั้น</span> -->
        <!-- <span class="checkbox" style="margin-left:25px;">&#9744;</span><span style="margin-left:5px;">โครงการระยะยาว</span> -->
    </p>

    <p class="space">
        <span><b style="color:#f00">๑๕. แหล่งงบประมาณ</b></span>
        
        <p>
            <?php if($project->Status_Budget != 'Y'): ?>
                    <b class="paragraph">-</b>
            <?php else: ?>     
                <?php $__currentLoopData = $projectBudgetSources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $budget): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <p class="paragraph"> 
                        <span class="checkbox">&#9745;</span><span style="margin-left:5px;"><?php echo e($budget->budgetSource->Name_Budget_Source); ?> <b><?php echo e($budget->Amount_Total); ?></b> บาท</span>
                    </p>
                    <p class="paragraph-content"><?php echo e($budget->Details_Expense); ?></p>

                        <div class="head-table">รายละเอียดค่าใช้จ่าย (แตกตัวคูณโดยใช้อัตราตามหลักเกณฑ์อัตราค่าใช้จ่าย)</div>
                            <table>
                                <tr>
                                    <th>ลำดับ</th>
                                    <th>รายการ</th>
                                    <th>จำนวน (บาท)</th>
                                </tr>
                                <tr>
                                    <td rowspan="2">1</td>
                                    <td style="text-align: left;">$subBudgets->Name_Subtopic_Budget</td>
                                    <td>รวม Subtopic_Budget</td>
                                </tr>
                                <tr>
                                    <!-- foreach($subBudget as $subBudgets) -->
                                        <td style="text-align: left;">$subBudgets->Details_Subtopic_Form</td>
                                        <td>$subBudget->Amount_Sub</td>
                                    <!-- endforeach -->
                                </tr>

                                <tr>
                                    <td></td>
                                    <th>รวมทั้งสิ้น</th>
                                    <td>20000</td>
                                </tr>
                            </table> 
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>

        </p>
    </p>

    <p class="space">
        <span><b>๑๖. เป้าหมายเชิงผลผลิต (Output)</b></span>
        <p class="paragraph"> 
            <?php $__currentLoopData = $output; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $outputs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <p class="paragraph-content">๑๖.<?php echo e(toThaiNumber($loop->iteration)); ?> <?php echo e($outputs->Name_Output); ?></p>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
        </p>
    </p>


    <p class="space">
        <span><b>๑๗. เป้าหมายเชิงผลลัพธ์ (Outcome)</b></span>
        <p class="paragraph"> 
            <?php $__currentLoopData = $outcome; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $outcomes): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <p class="paragraph-content"><?php echo e($outcomes->Name_Outcome); ?></p>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </p>
    </p>

    <p class="space">
        <span><b>๑๘. ผลที่คาดว่าจะได้รับ</b></span>
        <p class="paragraph"> 
        <?php if(!empty($expectedResult->Name_Expected_Results)): ?>
            <?php $__currentLoopData = $expectedResult; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expectedResults): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <p class="paragraph-content"><?php echo e($expectedResults->Name_Expected_Results); ?></p>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
        -
        <?php endif; ?>
        </p>
    </p>

    <p class="space">
        <span><b>๑๙. ตัวชี้วัดความสำเร็จของโครงการ</b></span>
        <p class="paragraph-content"> 
            <span><?php echo e($project->Success_Indicators ?? '-'); ?></span>
        </p>
    </p>
    <p class="space">
        <span><b>๒๐. ค่าเป้าหมาย</b></span>
        <p class="paragraph-content"> 
            <span><?php echo e($project->Value_Target ?? '-'); ?></span>
        </p>
    </p>
    <p class="space">
        <span><b>๒๑. เอกสารเพิ่มเติม</b></span>
        <span></span>
    </p>
    
</body>
</html><?php /**PATH /var/www/resources/views/PDF/PDF.blade.php ENDPATH**/ ?>