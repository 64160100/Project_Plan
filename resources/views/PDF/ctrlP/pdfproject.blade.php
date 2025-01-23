<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@400;700&display=swap');
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
                    <td>
                        @php
                            $objectives = explode("\n", $projects->Objective_Project);
                        @endphp

                        @foreach ($objectives as $objective)
                            {{ $objective }}<br>
                        @endforeach
                    </td>
                    
                    <td>
                        @php
                            $indicators = explode("\n", $projects->Indicators_Project);
                        @endphp

                        @foreach ($indicators as $indicator)
                            {{ $indicator }}<br>
                        @endforeach
                    </td>

                    <td>
                        @php
                            $targets = explode("\n", $projects->Target_Project);
                        @endphp

                        @foreach ($targets as $target)
                            {{ $target }}<br>
                        @endforeach
                    </td>

                    <td>
                        {{ Carbon::parse($projects->First_Time)->translatedFormat('M')}}
                        {{ Carbon::parse($projects->First_Time)->format('Y') + 543 }} -
                        {{ Carbon::parse($projects->End_Time)->translatedFormat('M') }}
                        {{ Carbon::parse($projects->End_Time)->format('Y') + 543 }}
                    </td>
                    <td style="text-align: center;">{{ number_format($projects->Budget, 2) }}</td>
                    
                    <td>{{ $projects->employee->position->Name_Position ?? 'ยังไม่ระบุ' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>