<!DOCTYPE html>
<html lang="th">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="{{ public_path('css/pdf.css') }}">
    <title>ตัวอย่าง PDF ภาษาไทย</title>
    <style>
        b {
            margin-right:5px;
        }
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: normal;
            src: url('{{ public_path('fonts/THSarabunNew.ttf') }}') format('truetype');
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: bold;
            src: url('{{ public_path('fonts/THSarabunNew Bold.ttf') }}') format('truetype');
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: italic;
            font-weight: normal;
            src: url('{{ public_path('fonts/THSarabunNew Italic.ttf') }}') format('truetype');
        }
        @font-face {
            font-family: 'THSarabunNew';
            font-style: italic;
            font-weight: bold;
            src: url('{{ public_path('fonts/THSarabunNew BoldItalic.ttf') }}') format('truetype');
        }
    </style>
</head>
<body>
    
    <h1>หัวข้อเรื่อง</h1>
    <div class="line" style="width: 100%; max-width: 590px; word-wrap: break-word;"></div>
    <p><b>๑. ชื่อโครงการ :</b>{{ $project->Name_Project }}</p>

    <p class="space"><b style="color:#f00">๒. ลักษณะโครงการ : (หาตารางไม่เจอ)</b>
        <span class="checkbox" style="margin-left:25px;">&#9745;</span><span style="margin-left:5px;">โครงการใหม่</span>
        <span class="checkbox" style="margin-left:25px;">&#9744;</span><span style="margin-left:5px;">โครงการต่อเนื่อง</span>
    </p>

    <p class="space"><b>๓. ผู้รับผิดชอบโครงการ :</b>
    
        <p class='paragraph'>
            {{ $project->employee->Firstname_Employee ?? '-' }}
            {{ $project->employee->Lastname_Employee ?? '' }}
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
            <span><b>{{ $project->strategic->Name_Strategic_Plan  }}</b></span>
        </p>
        <p class='paragraph'>
            <span class="checkbox">&#9745;</span>
            <span>{{ $project->Name_Strategy }}</span>
        </p>
    </p>

    <p class="space"><b style="color:#f00">๖. สอดคล้องกับ (SDGs) (เลือกได้มากกว่า ๑ หัวข้อ)</b>  (ข้าม)
        <p class='paragraph'>
            <span class="checkbox">&#9745;</span>
            <span></span>
        </p>
    </p>

    <p class="space"><b style="color:#f00">๗. การบูรณาการงานโครงการ/กิจกรรม กับ</b>   (ข้าม)
        <p class='paragraph'>๗.๑
            <span class="checkbox">&#9744;</span>
            <span>การบริการสารสนเทศ</span>
        </p>
    </p>

    (ข้าม)<p class="space">
        <span><b style="color:#f00">๘. หลักการและเหตุผล</b></span>   
        <span>(ระบุที่มา  เหตุผล/ ปัญหา /ความจำเป็น/ ความสำคัญ / องค์ความรู้และความเชี่ยวชาญ ของสาขาวิชา)</span>
    </p>

    <p class="space"><b style="color:#f00">๙. วัตถุประสงค์โครงการ</b>
        {{ $project->Objective_Project ?? '-' }}
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
            @foreach($project->locations as $location)
                {{ $location->Name_Location }}
            @endforeach
        </p>
    </p>
    
    <!-- ตัวชี้วัด -->
    <p class="space">   
        <span><b>๑๒. ตัวชี้วัด</b></span> (ให้รันเลขข้อเอง)
        @foreach($project->projectHasIndicators as $projectIndicator)
            @if($projectIndicator->indicators)
                @if($projectIndicator->indicators->Type_Indicators === 'Quantitative')
                    <p class="paragraph"><b>เชิงปริมาณ</b>
                        <p class="paragraph-two">{{ $projectIndicator->Details_Indicators }}</p>
                    </p>
                @elseif($projectIndicator->indicators->Type_Indicators === 'Qualitative')
                    <p class="paragraph"><b>เชิงคุณภาพ</b>
                        <p class="paragraph-two">{{ $projectIndicator->Details_Indicators }}</p>
                    </p>
                @endif
            @else
                <p class="paragraph"><b>-</b></p>
            @endif
        @endforeach
    </p>

    <p class="space">
        <span><b style="color:#f00">๑๓. ระยะเวลาดำเนินโครงการ</b></span>
        <span></span>
    </p>

    <p class="space">
        <span><b style="color:#f00">๑๔. ขั้นตอนและแผนการดำเนินงาน (PDCA)</b></span><br>
        <!-- <span class="checkbox" style="margin-left:25px;">&#9745;</span><span style="margin-left:5px;">โครงการระยะสั้น</span> -->
        <!-- <span class="checkbox" style="margin-left:25px;">&#9744;</span><span style="margin-left:5px;">โครงการระยะยาว</span> -->
    </p>

    <p class="space">
        <span><b style="color:#f00">๑๕. แหล่งงบประมาณ</b></span>
        <p class="paragraph"> 
            
        </p>
    </p>
    <p class="space">
        <span><b>๑๖. เป้าหมายเชิงผลผลิต (Output)</b></span>
        <p class="paragraph"> 
            @foreach($project->outputs as $output)
                {{ $output->Name_Output }}
            @endforeach
        </p>
    </p>
    <p class="space">
        <span><b>๑๗. เป้าหมายเชิงผลลัพธ์ (Outcome)</b></span>
        <p class="paragraph"> 
            @foreach($project->outcomes as $outcome)
                {{ $outcome->Name_Outcome }}
            @endforeach
        </p>
    </p>
    <p class="space">
        <span><b>๑๘. ผลที่คาดว่าจะได้รับ</b></span>
        <p class="paragraph"> 
            @foreach($project->expectedResults as $result)
                {{ $result->Name_Expected_Results }}
            @endforeach
        </p>
    </p>
    <p class="space">
        <span><b style="color:#f00">๑๙. ตัวชี้วัดความสำเร็จของโครงการ</b></span>
        <span></span>
    </p>
    <p class="space">
        <span><b style="color:#f00">๒๐. ค่าเป้าหมาย</b></span>
        <span></span>
    </p>
    <p class="space">
        <span><b>๒๑. เอกสารเพิ่มเติม</b></span>
        <span></span>
    </p>
    




</body>
</html>