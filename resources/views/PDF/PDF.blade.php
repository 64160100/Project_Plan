<!DOCTYPE html>
<html lang="th">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="{{ public_path('css/pdf.css') }}">
    <title>ตัวอย่าง PDF ภาษาไทย</title>
    <style>
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
    <p><b>๑. ชื่อโครงการ :</b></p>

    <p class="space"><b>๒. ลักษณะโครงการ : </b>
        <span class="checkbox" style="margin-left:25px;">&#9745;</span><span style="margin-left:5px;">โครงการใหม่</span>
        <span class="checkbox" style="margin-left:25px;">&#9744;</span><span style="margin-left:5px;">โครงการต่อเนื่อง</span>
    </p>

    <p class="space"><b>๓. ผู้รับผิดชอบโครงการ :</b>
        <p class='paragraph'>นายหมูเด้ง</p>
    </p>

    <p class="space"><b>๔. ความสอดคล้องกับยุทธศาสตร์มหาวิทยาลัย</b>
        <p>
            <span class="checkbox">&#9745;</span>
            <span>
                <b>แพลตฟอร์ม ๑ การยกระดับคุณภาพการศึกษาสู่มาตรฐานสากล และการสร้างบุคลากรคุณภาพ</b>
            </span>
        </p>
        <p class='paragraph'>
            <span class="checkbox">&#9745;</span>
            <span> โปรแกรม..................................................................................................................... </span>
        </p>
    </p>

    <p class="space"><b>๕. ความสอดคล้องกับยุทธศาสตร์ส่วนงาน</b>
        <p>
            <span class="checkbox">&#9745;</span>
            <span><b>ยุทธศาสตร์ที่ ๑ การพัฒนาความเป็นเลิศด้านการบริการด้วยมาตรฐานสากล</b></span>
        </p>
        <p class='paragraph'>
            <span class="checkbox">&#9745;</span>
            <span>กลยุทธ์ที่ ๑.๑ พัฒนากระบวนการปฏิบัติงานด้วยเกณฑ์คุณภาพ</span>
        </p>
    </p>

    <p class="space"><b>๖. สอดคล้องกับ (SDGs) (เลือกได้มากกว่า ๑ หัวข้อ)</b>
        <p class='paragraph'>
            <span class="checkbox">&#9745;</span>
            <span>กลยุทธ์ที่ ๑.๑ พัฒนากระบวนการปฏิบัติงานด้วยเกณฑ์คุณภาพ</span>
        </p>
    </p>

    <p class="space"><b>๗. การบูรณาการงานโครงการ/กิจกรรม กับ</b>
        <p class='paragraph'>๗.๑
            <span class="checkbox">&#9744;</span>
            <span>การบริการสารสนเทศ</span>
        </p>
        <p class='paragraph'>๗.๒
            <span class="checkbox">&#9744;</span>
            <span>การวิจัย (ระบุโครงการวิจัย)</span>
        </p>
        <p class='paragraph'>๗.๓
            <span class="checkbox">&#9744;</span>
            <span>การบริการวิชาการ (ระบุโครงการบริการวิชาการ)</span>
        </p>
        <p class='paragraph'>๗.๔
            <span class="checkbox">&#9744;</span>
            <span>ทำนุบำรุงศิลปวัฒนธรรม (ระบุโครงการทำนุบำรุงศิลปวัฒนธรรม)</span>
        </p>
    </p>

    <p class="space">
        <span><b>๘. หลักการและเหตุผล</b></span>
        <span>(ระบุที่มา  เหตุผล/ ปัญหา /ความจำเป็น/ ความสำคัญ / องค์ความรู้และความเชี่ยวชาญ ของสาขาวิชา)</span>
    </p>

    <p class="space"><b>๙. วัตถุประสงค์โครงการ</b></p>

    <p class="space"><b>๑๐. กลุ่มเป้าหมาย</b>
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

    
    <p class="space"><b>ตัวชี้วัดตามเป้าหมายการให้บริการหน่วยงานและเป้าหมายผลผลิตของมหาวิทยาลัย</b>
        <p class="paragraph">
            <span class="checkbox">&#9745;</span><span style="margin-left:5px;">โครงการใหม่</span>
            <span class="checkbox" style="margin-left:25px;">&#9744;</span><span style="margin-left:5px;">โครงการต่อเนื่อง</span>
        </p> 
    </p>

    <p class="space">
        <span><b>๑๑. สถานที่ดำเนินงาน</b></span>
        <span></span>
    </p>

    <p class="space">
        <span><b>๑๒. ระยะเวลาดำเนินโครงการ</b></span>
        <span></span>
    </p>

    <p class="space">
        <span><b>๑๓. ขั้นตอนและแผนการดำเนินงาน (PDCA)</b></span><br>
        <!-- <span class="checkbox" style="margin-left:25px;">&#9745;</span><span style="margin-left:5px;">โครงการระยะสั้น</span> -->
        <!-- <span class="checkbox" style="margin-left:25px;">&#9744;</span><span style="margin-left:5px;">โครงการระยะยาว</span> -->
    </p>
    
    @include('PDF.LongTerm')



</body>
</html>