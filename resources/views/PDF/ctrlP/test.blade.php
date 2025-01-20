<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@400;700&display=swap');
        
        @font-face {
            font-family: 'THSarabunNew';
            src: url('{{ public_path('fonts/THSarabunNew.ttf') }}') format('truetype');
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
            body {
                margin: 0;
                padding: 0;
            }
            
            #content {
                padding: 0;
            }
            
            .button-container {
                display: none;
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
    }
        }
    </style>
</head>
<body>
    <div class="button-container">
        <button onclick="generatePDF()">Generate PDF</button>
    </div>
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

                    $strategyRenderedCounts = [];
                    $strategicPlanRenderedCounts = [];

                    $strategyCounts = [];
                    $strategicPlanCounts = [];

                    foreach ($projects as $project) {
                        $strategyName = $project->Name_Strategy ?? 'N/A';
                        $strategicPlanName = $project->strategic->Name_Strategic_Plan ?? 'N/A';

                        if (!isset($strategyCounts[$strategyName])) {
                            $strategyCounts[$strategyName] = 0;
                        }
                        $strategyCounts[$strategyName]++;

                        if (!isset($strategicPlanCounts[$strategicPlanName])) {
                            $strategicPlanCounts[$strategicPlanName] = 0;
                        }
                        $strategicPlanCounts[$strategicPlanName]++;
                    }
                @endphp
                @foreach($projects as $project)
                    <tr>
                        @php
                            $strategyName = $project->Name_Strategy ?? 'N/A';
                            $strategicPlanName = $project->strategic->Name_Strategic_Plan ?? 'N/A';
                        @endphp

                        <!-- strategic -->
                        @if (!isset($strategicPlanRenderedCounts[$strategicPlanName]))
                            @php
                                $strategicPlanRenderedCounts[$strategicPlanName] = 0;
                            @endphp
                        @endif
                            <td class="strategy-cell" style="{{ $strategicPlanRenderedCounts[$strategicPlanName] < $strategicPlanCounts[$strategicPlanName] - 1 ? 'border-bottom: 1px solid white;' : '' }}">
                                @if ($strategicPlanRenderedCounts[$strategicPlanName] == 0)
                                    <div>{{ $strategicPlanName }}</div>
                                @endif
                            </td>
                        @php
                            $strategicPlanRenderedCounts[$strategicPlanName]++;
                        @endphp

                        <!-- strategy -->
                        @if (!isset($strategyRenderedCounts[$strategyName]))
                            @php
                                $strategyRenderedCounts[$strategyName] = 0;
                            @endphp
                        @endif
                            <td class="strategy-cell" style="{{ $strategyRenderedCounts[$strategyName] < $strategyCounts[$strategyName] - 1 ? 'border-bottom: 1px solid white;' : '' }}">
                                @if ($strategyRenderedCounts[$strategyName] == 0)
                                    <div>{{$strategyName}}</div>
                                @endif
                                {{$strategyRenderedCounts[$strategyName]}}<br>
                            </td>
                            <!-- @if ($strategyRenderedCounts[$strategyName] < $strategyCounts[$strategyName] - 1)
                                <td class="strategy-cell" style="border-bottom: 1px solid red;">
                                    @if ($strategyRenderedCounts[$strategyName] == 0)
                                        <div>{{$strategyName}}</div>
                                    @endif
                                    {{$strategyRenderedCounts[$strategyName]}}<br>
                                </td>
                            @else
                                <td class="strategy-cell" >
                                    @if ($strategyRenderedCounts[$strategyName] == 0)
                                        <div>{{$strategyName}}</div>
                                    @endif
                                    {{$strategyRenderedCounts[$strategyName]}}<br>
                                </td>
                            @endif -->
                        @php
                            $strategyRenderedCounts[$strategyName]++;
                        @endphp
                        
                        <td>
                            <b>{{ $project->Name_Project }}</b><br>
                            @if($project->supProjects->isNotEmpty())
                                <b style="padding-left: 10px;">โครงการย่อย:</b><br>
                                @foreach($project->supProjects as $supProject)
                                    <a style="padding-left: 10px;">{{ $supProject->Name_Sup_Project }}</a><br>
                                @endforeach
                            @endif
                        </td>
                        <td>{{ $project->Objective_Project }}</td>
                        <td>{{ $project->Indicators_Project }}</td>
                        <td>
                        
                            <b>({{ strlen($project->Target_Project) }} characters)</b><br>
                            {{ $project->Target_Project }}
                        </td>
                        <td>
                            {{ Carbon::parse($project->First_Time)->translatedFormat('M')}}
                            {{ Carbon::parse($project->First_Time)->format('Y') + 543 }} -
                            {{ Carbon::parse($project->End_Time)->translatedFormat('M') }}
                            {{ Carbon::parse($project->End_Time)->format('Y') + 543 }}
                        </td>
                        <td style="text-align: center;">{{ number_format($project->Budget, 2) }}</td>
                        <td>{{ $project->Responsible_Person }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        function generatePDF() {
            const element = document.getElementById('content');
            const opt = {
                margin: [0.4, 0.5, 0.7, 0.5], // top, right, bottom, left margins in millimeters
                filename: 'project-table.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { 
                    scale: 2,
                    useCORS: true,
                    logging: true,
                    letterRendering: true,
                    windowWidth: 1920,
                    width: element.scrollWidth
                },
                jsPDF: { 
                    unit: 'mm',
                    format: 'a4',
                    orientation: 'landscape',
                    compress: true
                },
                pagebreak: { mode: ['avoid-all', 'css', 'legacy'], before: '.page-break', after: '.page-break' }
            };

            html2pdf().set(opt).from(element).save();
        }
    </script>
</body>
</html>