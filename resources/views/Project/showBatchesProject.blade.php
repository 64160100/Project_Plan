<!-- View: resources/views/Project/showBatchesProject.blade.php -->
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายละเอียดโครงการ</title>
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

        th, td {
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

        .button-container {
            margin: 20px 0;
            text-align: center;
        }

        .print-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        @media print {
            .button-container {
                display: none;
            }

            @page {
                size: A4 landscape;
            }
        }
    </style>
</head>
<body>
    <div class="button-container">
        <button onclick="window.print()" class="print-button">
            <i class="bx bx-printer"></i> พิมพ์เอกสาร
        </button>
    </div>

    <div id="content">
        <table>
            <thead>
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
                    <td>{{ $project->strategic->Name_Strategic_Plan ?? '-' }}</td>
                    <td>{{ $project->Name_Strategy ?? '-' }}</td>
                    <td>
                        <b>{{ $project->Name_Project }}</b><br>
                        @foreach($project->subProjects as $subProject)
                            - {{ $subProject->Name_Sup_Project }}<br>
                        @endforeach
                    </td>
                    <td>{{ $project->Objective_Project ?? '-' }}</td>
                    <td>{!! $project->Success_Indicators ? nl2br(e($project->Success_Indicators)) : '-' !!}</td>
                    <td>{!! $project->Value_Target ? nl2br(e($project->Value_Target)) : '-' !!}</td>
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

    <script>
        // ถ้าต้องการเพิ่ม JavaScript เพิ่มเติม
    </script>
</body>
</html>