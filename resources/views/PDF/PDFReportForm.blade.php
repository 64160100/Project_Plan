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

    <h1>รายงานผลการดำเนินงานโครงการ<br>สำนักหอสมุด มหาวิทยาลัยบูรพา</h1>
    <div class="line" style="width: 100%; max-width: 590px; word-wrap: break-word;"></div>
    <p><b>๑. ชื่อโครงการ </b>{{ toThaiNumber($project->Name_Project) }}</p>

    <p class="space"><b>๒. ผู้รับผิดชอบโครงการ </b>
        {{ $project->employee->Prefix_Name ?? '-' }}{{ $project->employee->Firstname ?? '' }}
        {{ $project->employee->Lastname ?? '' }}
    </p>

    <p class="space"><b>๓. วัตถุประสงค์โครงการ</b>
        @foreach($project->objectives as $objective)
            <p style="text-indent: 70px; margin-top: 0;">
                {{ toThaiNumber($objective->Description_Objective) ?? '-' }}
            </p>    
        @endforeach
    </p>

    <p class="space"><b>๔. กลุ่มเป้าหมาย</b>
        <p class="paragraph"><b>๔.๑ กลุ่มผู้รับบริการ</b>
        @foreach($project->targets as $index => $target)
            <table style="border-collapse: collapse; width:100%; border: none;">
                <tr>
                    <td style="width: 18%; border: none;"></td>
                    <td style="width: 35%; text-align: left; padding: 5px; border: none;"><p>{{ toThaiNumber($index + 1) }}. {{ $target->Name_Target }}</p></td>
                    <td style="text-align: left; padding: 5px; border: none;">
                        <p class="paragraph-two">
                            <span>จำนวน </span>
                            <span style="width: 50px; margin-top: 0;">
                                {{ toThaiNumber($target->Quantity_Target) }}
                            </span>
                            {{ toThaiNumber($target->Unit_Target) }}
                        </p>
                    </td>
                </tr>
            </table>
        @endforeach
        </p>

        @if($project->targets->isNotEmpty()) 
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
        @else
            <span>-</span>
        @endif
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
        @if($project->locations->isNotEmpty())
            @foreach($project->locations as $index => $location)
            <span class="paragraph">
                {{ toThaiNumber($index + 1) }}. {{ toThaiNumber($location->Name_Location) }} <br>
            </span>
            @endforeach
        @else
            -
        @endif
    </p>

    <p class="space">
        <span><b>๗. วิทยากร</b></span>
        <p class="paragraph-content">
            @if($project->Name_Speaker != null)
                {{ toThaiNumber($project->Name_Speaker) }}
            @else
                -
            @endif
        </p>
    </p>

    <p class="space">
        <span><b>๘. รูปแบบกิจกรรมการดำเนินงาน</b></span> <br>
        @if ($project->Project_Type == 'S')
            <!-- โครงการระยะสั้น -->
            <p>วิธีการดำเนินการ</p>
            <p>
                @foreach($project->shortProjects as $shortProject)
                <span class="paragraph">
                    {{ toThaiNumber($loop->iteration) }}. {{ toThaiNumber($shortProject->Details_Short_Project) }}
                </span><br>
                @endforeach
            </p>

        @else

            <p>ขั้นตอนและแผนการดำเนินงาน(PDCA)<br></p>
            <!-- โครงการระยะยาว -->
            <table>
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 40%; line-height: 0.6;">กิจกรรมและแผนการเบิกจ่ายงบประมาณ</th>
                        <th colspan="12">
                            <span>ปีงบประมาณ พ.ศ.</span>
                            @foreach($quarterProjects as $year)
                                <span>{{ toThaiNumber($year) }}</span>
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
                    @php
                        $groupedPdcaDetails = $project->pdcaDetails->groupBy(function($pdcaDetail) {
                            return $pdcaDetail->pdca->Name_PDCA ?? 'N/A';
                        });
                    @endphp

                    @foreach($groupedPdcaDetails as $namePDCA => $pdcaDetails)
                        <tr>
                            <td style="text-align: left;">
                                <strong>{{ $namePDCA }}</strong><br>
                                @foreach($pdcaDetails as $pdcaDetail)
                                    {{ toThaiNumber($loop->iteration) }}. {{ $pdcaDetail->Details_PDCA }}<br>
                                @endforeach
                            </td>
                            @for($month = 1; $month <= 12; $month++)
                                <td style="text-align: center;">
                                @if($project->monthlyPlans->where('Months_Id', $month)->where('PDCA_Stages_Id', $pdcaDetail->PDCA_Stages_Id)->isNotEmpty())
                                    /
                                @endif
                                </td>
                            @endfor
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </p>

    <!-- ตัวชี้วัด -->
    <p class="space">
        <span><b>๙. ตัวชี้วัดความสำเร็จ</b></span>
        @if($project->projectHasIndicators->where('indicators.Type_Indicators', 'Quantitative')->isNotEmpty())
            <p class="paragraph">เชิงปริมาณ</p>
            @foreach($project->projectHasIndicators->where('indicators.Type_Indicators', 'Quantitative')->values() as $index =>$projectIndicator)
                <p class="paragraph-two" style="margin-top: 0;">{{ toThaiNumber($index + 1) }}. {{ toThaiNumber($projectIndicator->Details_Indicators) }}</p>
            @endforeach
        @endif

        @if($project->projectHasIndicators->where('indicators.Type_Indicators', 'Qualitative')->isNotEmpty())
            <p class="paragraph">เชิงคุณภาพ</p>
            @foreach($project->projectHasIndicators->where('indicators.Type_Indicators', 'Qualitative')->values() as $index =>$projectIndicator)
                <p class="paragraph-two" style="margin-top: 0;">{{ toThaiNumber($index + 1) }}. {{ toThaiNumber($projectIndicator->Details_Indicators) }}</p>
            @endforeach
        @endif
    </p>

    <p class="space">
        <span><b>๑๐. สรุปผลการดำเนินงาน</b></span>
    <p style="text-indent: 70px; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; margin-top: 0;">
        <span>{{ $project->Summary }}</span>
    </p>

    <p class="paragraph-content">
        <b>ผลสำเร็จตามตัวชี้วัดของโครงการ</b><br>
        @if($project->projectHasIndicators->where('indicators.Type_Indicators', 'Quantitative')->isNotEmpty())
            <p style="margin-left: 120px; margin-top: 0;">ตัวชี้วัดเชิงปริมาณ</p>
                @foreach($project->projectHasIndicators->where('indicators.Type_Indicators', 'Quantitative')->values() as $index => $projectIndicator)
                    <p style="margin-left: 170px; margin-top: 0;">{{ toThaiNumber($index + 1) }}. {{ toThaiNumber($projectIndicator->Details_Indicators) }}</p>
                @endforeach
        @endif

        @if($project->projectHasIndicators->where('indicators.Type_Indicators', 'Qualitative')->isNotEmpty())
            <p style="margin-left: 120px; margin-top: 0;">ตัวชี้วัดเชิงคุณภาพ</p>
            @foreach($project->projectHasIndicators->where('indicators.Type_Indicators', 'Qualitative')->values() as $index =>$projectIndicator)
                <p style="margin-left: 170px; margin-top: 0;">{{ toThaiNumber($index + 1) }}. {{ toThaiNumber($projectIndicator->Details_Indicators) }}
                </p>
            @endforeach
        @endif
    </p><br>

    
    <p class="paragraph-content">
        <b>การมีส่วนร่วมของหน่วยงานภายนอก/ชุมชน (ถ้ามี)</b>
        <p style="text-indent: 70px; margin: 0;">
            การดำเนินงาน <span>{{ toThaiNumber($project->Name_Project) }}</span> ได้มีหน่วยงานภายนอกและชุมชนเข้ามามีส่วนร่วม ในการดำเนินงานในด้านต่างๆ ดังนี้
        </p>
        <p style="text-indent: 70px; margin: 0;">
            {{ $project->External_Participation }}
        </p>
    </p><br>

    <p class="paragraph-content">
        <b>งบประมาณ</b>
        <p style="text-indent: 70px; margin-top: 0;">
            @if (!empty($project) && $project->Status_Budget == 'Y')
                ใช้งบประมาณทั้งสิ้น {{ digits($totalExpense) }} บาท
            @else
                ไม่มีงบประมาณ
            @endif
        </p>
        
    </p><br>

    <p class="paragraph-content mt-3">
        <b>ข้อเสนอแนะ</b><br>
        <p style="text-indent: 70px; margin-top: 0;">
            {{ $project->Suggestions }}
        </p>
    </p>


</p>

</body>

</html>