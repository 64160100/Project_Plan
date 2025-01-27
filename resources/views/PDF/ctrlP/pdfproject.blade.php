<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <style>
    @font-face {
        font-family: 'THSarabunNew';
        font-style: normal;
        font-weight: normal;
        src: url('{{ storage_path('fonts/THSarabunNew.ttf') }}') format('truetype');
    }

    @font-face {
        font-family: 'THSarabunNew';
        font-style: normal;
        font-weight: bold;
        src: url('{{ storage_path('fonts/THSarabunNew Bold.ttf') }}') format('truetype');
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
                @php
                use Carbon\Carbon;
                Carbon::setLocale('th');
                @endphp
                <tr>
                    <td>{{ $project->strategic->Name_Strategic_Plan }}</td>
                    <td>{{ $project->Name_Strategy ?? '-'}}</td>
                    <td>
                        <b>{{ $project->Name_Project }}</b><br>
                        @foreach($project->subProjects as $subProject)
                        - {{ $subProject->Name_Sup_Project }}<br>
                        @endforeach
                    </td>
                    <td>{{ $project->Objective_Project ?? '-' }}</td>

                    <td>
                        @if($project->Success_Indicators)
                        {!! nl2br(e($project->Success_Indicators)) !!}
                        @else
                        -
                        @endif
                    </td>
                    <td>
                        @if($project->Value_Target)
                        {!! nl2br(e($project->Value_Target)) !!}
                        @else
                        -
                        @endif
                    </td>

                    <td>
                        @if($project->First_Time === null && $project->End_Time === null)
                        ยังไม่ได้กำหนดวันที่
                        @else
                        {{ $project->First_Time ? Carbon::parse($project->First_Time)->translatedFormat('d M Y') : '-' }}
                        -
                        {{ $project->End_Time ? Carbon::parse($project->End_Time)->translatedFormat('d M Y') : '-' }}
                        @endif
                    </td>
                    @php
                    $totalBudget = $projectBudgetSources->sum('Amount_Total');
                    @endphp
                    <td style="text-align: center;">
                        @if($totalBudget === null || $totalBudget == 0)
                        ไม่ใช้งบประมาณ
                        @else
                        {{ number_format($totalBudget, 2) }}
                        @endif
                    </td>
                    <td>
                        {{ $project->employee->Firstname_Employee ?? '-' }}
                        {{ $project->employee->Lastname_Employee ?? '' }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>