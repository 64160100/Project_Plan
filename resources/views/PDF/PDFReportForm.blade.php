<!DOCTYPE html>
<html lang="th">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="{{ public_path('css/pdf.css') }}">
    <title>{{ $project->Name_Project }}</title>
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

    body {
        font-family: 'THSarabunNew', sans-serif;
        overflow-wrap: break-word;
        margin-top: 0.3in;
        margin-left: 0.5in;
        margin-right: 0.05in;
        margin-bottom: 0.07in;
        line-height: 0.8;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        page-break-inside: avoid;
    }

    tr {
        page-break-inside: avoid;
    }

    table,
    th,
    td {
        border: 1px solid black;
    }

    th,
    td {
        padding: 5px;
        text-align: center;
        font-size: 18px;
        word-wrap: break-word;
        word-break: break-word;
    }

    th {
        background-color: rgba(197, 224, 179, 255);
    }

    td {
        text-align: left;
        vertical-align: top;
        word-break: break-word;
    }

    .thead-repeat {
        display: table-header-group;
    }

    .strategy-cell {
        position: relative;
    }

    .page-break {
        page-break-before: always;
    }

    .header {
        position: fixed;
        top: 0.01in;
        left: 0;
        right: 0;
        margin-bottom: 40px;
        margin-right: 0.7in;

        height: 30px;
        text-align: right;
        font-size: 18px;
    }

    .header:after {
        content: counter(page);
    }
    </style>
</head>

<body>

    <h1>รายงานผลการดำเนินงาน<br>
        {{ toThaiNumber($project->Name_Project) }} <br>
        สำนักหอสมุด มหาวิทยาลัยบูรพา</h1>
    <div class="line" style="width: 100%; max-width: 590px; word-wrap: break-word;"></div>
    <p><b>๑. ชื่อโครงการ </b>{{ toThaiNumber($project->Name_Project) }}</p>

    <p class="space"><b>๒. ผู้รับผิดชอบโครงการ </b>
        {{ $project->employee->Prefix_Name ?? '-' }}{{ $project->employee->Firstname ?? '' }}
        {{ $project->employee->Lastname ?? '' }}
    </p>

    <p class="space"><b>๓. วัตถุประสงค์โครงการ</b>
    <p class="paragraph-content">
        {{ toThaiNumber($project->Objective_Project) ?? '-' }}
    </p>
    </p>

    <p class="space"><b>๔. กลุ่มเป้าหมาย</b>
    <p class="paragraph"><b>๔.๑ กลุ่มผู้รับบริการ</b>
    <table class="paragraph-two">
        @foreach($project->targets as $target)
        <tr>
            <td style="vertical-align: top; text-align: left; border: none;"><span>
                    <p>- {{ $target->Name_Target }}</p>
                </span>
            <td style="vertical-align: top; text-align: left; border: none;"><span>
                    <p>จำนวน {{ toThaiNumber($target->Quantity_Target) }} {{ $target->Unit_Target }}</p>
                </span></td>
        </tr>
        @endforeach
    </table>
    </p>

    <p class="paragraph" style="margin-top: 20px;">
        <b>๔.๒ พื้นที่/ชุมชนเป้าหมาย (ถ้ามี ระบุ)</b>
    </p>
    <p class="paragraph-two">
        @foreach($project->targets as $target)
        @foreach($target->targetDetails as $detail)
        {{ toThaiNumber($detail->Details_Target) }}
        @endforeach
        @endforeach
    </p>

    </p>

    <p class="space">
        <span><b>๕. ระยะเวลาดำเนินโครงการ</b></span>
    <p class="paragraph-content">
        @if (!empty($project->First_Time) && !empty($project->End_Time))
        <span>กำหนดการจัดโครงการ
            <b>{{ $project->formatted_first_time }}</b> ถึง <b>{{ $project->formatted_end_time }}</b>
        </span>
        @else
        <span>-</span>
        @endif
    </p>
    </p>

    <p class="space">
        <span><b>๖. สถานที่ดำเนินงาน</b></span><br>
        <!-- <p class="paragraph">  -->
        @if($project->locations->isNotEmpty())
        @foreach($project->locations as $index => $location)
        <span class="paragraph">
            {{ toThaiNumber($index + 1) }}. {{ toThaiNumber($location->Name_Location) }} <br>
        </span>
        @endforeach
        @else
        -
        @endif
        <!-- </p> -->
    </p>

    <p class="space">
        <span><b>๗. วิทยากร</b></span>
    <p class="paragraph">
        {{ toThaiNumber($project->Speaker) }} <br>
    </p>
    </p>

    <p class="space">
        <span><b>๘. รูปแบบกิจกรรมการดำเนินงาน</b></span> <br>
        <!-- โครงการระยะสั้น -->
        <b>วิธีการดำเนินงาน</b><br>
    <p>
        @foreach($project->shortProjects as $shortProject)
        <span class="paragraph">
            {{ toThaiNumber($loop->iteration) }}. {{ toThaiNumber($shortProject->Details_Short_Project) }}
        </span><br>
        @endforeach
    </p>

    <p><b>ขั้นตอนและแผนการดำเนินงาน(PDCA)</b><br></p>

    <!-- โครงการระยะยาว -->
    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width: 40%; line-height: 0.6;">กิจกรรมและแผนการเบิกจ่ายงบประมาณ</th>
                <th colspan="12">
                    <span>ปีงบประมาณ พ.ศ.</span>
                    @php
                    $uniqueYears = $quarterProjects->pluck('quarterProject.Fiscal_Year')->unique();
                    @endphp

                    @foreach($uniqueYears as $year)
                    <span>{{ $year }}</span>
                    @endforeach
                </th>
            </tr>
            <tr>
                @foreach($months as $month)
                <th>{{ $month }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>


        </tbody>
    </table>


    </p>



    <!-- ตัวชี้วัด -->
    <p class="space">
        <span><b>๙. ตัวชี้วัดความสำเร็จ</b></span>
        @if($project->projectHasIndicators->where('indicators.Type_Indicators', 'Quantitative')->isNotEmpty())
    <p class="paragraph"><b>เชิงปริมาณ</b></p>
    @foreach($project->projectHasIndicators->where('indicators.Type_Indicators', 'Quantitative')->values() as $index =>
    $projectIndicator)
    <p class="paragraph-two">{{ toThaiNumber($index + 1) }}. {{ toThaiNumber($projectIndicator->Details_Indicators) }}
    </p>
    @endforeach
    @endif

    @if($project->projectHasIndicators->where('indicators.Type_Indicators', 'Qualitative')->isNotEmpty())
    <p class="paragraph"><b>เชิงคุณภาพ</b></p>
    @foreach($project->projectHasIndicators->where('indicators.Type_Indicators', 'Qualitative')->values() as $index =>
    $projectIndicator)
    <p class="paragraph-two">{{ toThaiNumber($index + 1) }}. {{ toThaiNumber($projectIndicator->Details_Indicators) }}
    </p>
    @endforeach
    @endif

    </p>

    <p class="space">
        <span><b>๑๐. สรุปผลการดำเนินงาน</b></span>
    <p class="paragraph-content">
        {{ $project->Summary }}
    </p>

    <p class="paragraph-content">
        <b>ผลสำเร็จตามตัวชี้วัดของโครงการ</b><br>
        @if($project->projectHasIndicators->where('indicators.Type_Indicators', 'Quantitative')->isNotEmpty())
    <p class="paragraph"><b>ตัวชี้วัดเชิงปริมาณ</b></p>
    @foreach($project->projectHasIndicators->where('indicators.Type_Indicators', 'Quantitative')->values() as $index =>
    $projectIndicator)
    <p class="paragraph-two">{{ toThaiNumber($index + 1) }}. {{ toThaiNumber($projectIndicator->Details_Indicators) }}
    </p>
    @endforeach
    @endif

    @if($project->projectHasIndicators->where('indicators.Type_Indicators', 'Qualitative')->isNotEmpty())
    <p class="paragraph"><b>ตัวชี้วัดเชิงคุณภาพ</b></p>
    @foreach($project->projectHasIndicators->where('indicators.Type_Indicators', 'Qualitative')->values() as $index =>
    $projectIndicator)
    <p class="paragraph-two">{{ toThaiNumber($index + 1) }}. {{ toThaiNumber($projectIndicator->Details_Indicators) }}
    </p>
    @endforeach
    @endif
    </p>


    <p class="paragraph-content mt-3">
        <b>การมีส่วนร่วมของหน่วยงานภายนอก/ชุมชน</b>
    <p class="paragraph-two">{{ $project->External_Participation }} test</p>
    </p>


    <p class="paragraph-content mt-3">
        <b>ข้อเสนอแนะ</b><br>
    <p class="paragraph-two">
        {{ $project->Suggestions }}
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

</html>