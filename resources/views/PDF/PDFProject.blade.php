Hi<!DOCTYPE html>
<html lang="th">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    @php
        $ProjectName = $projects->Name_Project;
    @endphp
    <title>{{$ProjectName}}</title>
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
        @page {
            size: A4 landscape;
            
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

        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 5px;
            text-align: center;
            font-size: 18px;
            word-wrap: break-word; 
            word-break: break-word;
        }

        th {
            background-color:rgba(197,224,179,255);
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
    <div class="header"></div>
    <table>
        <thead class="thead-repeat">
            <tr>
                <th style="width:10%">ยุทธศาสตร์ สำนักหอสมุด</th>
                <th style="width:10%">กลยุทธ์ สำนักหอสมุด</th>
                <th style="width:14%">โครงการ</th>
                <th style="width:10%">วัตถุประสงค์<br>โครงการ</th>
                <th style="width:14%">ตัวชี้วัดความสำเร็จ ของโครงการ</th>
                <th style="width:12%">ค่าเป้าหมาย</th>
                <th style="width:8%">ระยะเวลา</th>
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
                <td>{{ $projects->strategic->Name_Strategic_Plan }}</td>
                <td>{{ $projects->Name_Strategy }}</td>
                <td>
                    <b>{{ $projects->Name_Project }}</b><br>
                    @if($projects->supProjects->isNotEmpty())
                        <b style="padding-left: 10px;">โครงการย่อย:</b><br>
                        @foreach($projects->supProjects as $supProject)
                            <a style="padding-left: 10px;">{{ $supProject->Name_Sup_Project }}</a><br>
                        @endforeach
                    @endif
                </td>
                <td>{{ $projects->Objective_Project }}</td>
                <td>{{ $projects->Indicators_Project }}</td>
                <td>{{ $projects->Target_Project }}</td>
                <td>
                    {{ Carbon::parse($projects->First_Time)->translatedFormat('M')}}
                    {{ Carbon::parse($projects->First_Time)->format('Y') + 543 }} -
                    {{ Carbon::parse($projects->End_Time)->translatedFormat('M') }}
                    {{ Carbon::parse($projects->End_Time)->format('Y') + 543 }}
                </td>
                <td style="text-align: center;">{{ number_format($projects->Budget, 2) }}</td>
                <td>{{ $projects->Responsible_Person }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>