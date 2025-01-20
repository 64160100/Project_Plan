Hi<!DOCTYPE html>
<html lang="th">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>ActionPlan</title>
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
                        <td class="strategy-cell" style="{{ $strategicPlanRenderedCounts[$strategicPlanName] < $strategicPlanCounts[$strategicPlanName] - 1 ? 'border-bottom: 1px solid blue;' : '' }}">
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
                        <!-- <td class="strategy-cell" style="{{ $strategyRenderedCounts[$strategyName] < $strategyCounts[$strategyName] - 1 ? 'border-bottom: 1px solid white;' : '' }}">
                            @if ($strategyRenderedCounts[$strategyName] == 0)
                                <div>{{$strategyName}}</div>
                            @endif
                            {{$strategyRenderedCounts[$strategyName]}}<br>
                        </td> -->
                        @if ($strategyRenderedCounts[$strategyName] < $strategyCounts[$strategyName] - 1)
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
                        @endif
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
                    <td>{{ $project->Target_Project }}</td>
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
    

    <h1>ค่าซ้ำของ Name_Strategy</h1>
    <ul>
        @foreach($strategyCounts as $strategyName => $count)
            <li>{{ $strategyName }}: {{ $count }}</li>
        @endforeach
    </ul>
    <h1>ค่าซ้ำของ Name_Strategic_Plan</h1>
    <ul>
        @foreach($strategicPlanCounts as $strategicPlanName => $count)
            <li>{{ $strategicPlanName }}: {{ $count }}</li>
        @endforeach
    </ul>
    <div class="footer"></div>
</body>
</html>