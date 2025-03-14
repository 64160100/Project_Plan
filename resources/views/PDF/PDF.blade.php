<!DOCTYPE html>
<html lang="th">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="{{ public_path('css/pdf.css') }}">
    <title>{{ $project->Name_Project }}</title>
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
    .line {
        width: 100%;
        max-width: 590px;
        word-wrap: break-word;
    }
    @media print {
        @page {
            size: A4 landscape;
            margin: 0.5in 1in 1in 1in;
        }

        body {
            padding: 0;
            margin: 0;
        }
    }
    </style>
</head>

<body>

    <h1>{{ toThaiNumber($project->Name_Project) }}</h1>
    <div class="line"></div>
    <p><b>๑. ชื่อโครงการ </b>{{ toThaiNumber($project->Name_Project) }}</p>

    <p class="space"><b>๒. ลักษณะโครงการ </b>
        @if ($project->Description_Project == 'N')
        <span class="checkbox">&#9745;</span><span style="margin-left:5px;">โครงการใหม่</span>
        <span class="checkbox">&#9744;</span><span style="margin-left:5px;">โครงการต่อเนื่อง</span>
        @else
        <span class="checkbox">&#9744;</span><span style="margin-left:5px;">โครงการใหม่</span>
        <span class="checkbox">&#9745;</span><span style="margin-left:5px;">โครงการต่อเนื่อง</span>
        @endif
    </p>

    <p class="space"><b>๓. ผู้รับผิดชอบโครงการ</b>
    <p class='paragraph'>
        {{ $project->employee->Firstname ?? '-' }}
        {{ $project->employee->Lastname ?? '' }}
    </p>
    </p>

    <p class="space">
        <b>๔. ความสอดคล้องกับยุทธศาสตร์มหาวิทยาลัย</b>
        (ปีงบประมาณ พ.ศ.
            @foreach($quarterProjects as $year)
                <span>{{ toThaiNumber($year) }}</span>
            @endforeach
        )

        @foreach ($project->platforms as $platform)
    <p class="paragraph-tab">
        <span class="checkbox">&#9745;</span>
        <span><b>แพลตฟอร์ม {{ toThaiNumber($loop->iteration) }} {{ toThaiNumber($platform->Name_Platform) }}</b></span>
    </p>

    @foreach ($platform->programs as $program)
    <p class="paragraph">
        <span class="checkbox">&#9745;</span>
        <span>{{ toThaiNumber($program->Name_Program) }}</span>
    </p>

    @foreach ($program->kpis as $kpi)
    <p class="paragraph">
        <span class="checkbox">&#9745;</span>
        <span>{{ toThaiNumber($kpi->Name_KPI) }}</span>
    </p>
    @endforeach
    @endforeach
    @endforeach
    </p>

    <p class="space"><b>๕. ความสอดคล้องกับยุทธศาสตร์ส่วนงาน</b>
        (ปีงบประมาณ พ.ศ.
            @foreach($quarterProjects as $year)
                <span>{{ toThaiNumber($year) }}</span>
            @endforeach
        )
    <p class='paragraph-tab'>
        <span class="checkbox">&#9745;</span>
        <span><b>{{ toThaiNumber($project->strategic->Name_Strategic_Plan)  }}</b></span>
    </p>
    <p class='paragraph'>
        <span class="checkbox">&#9745;</span>
        <span>{{ toThaiNumber($project->Name_Strategy) }}</span>
    </p>
    </p>

    <p class="space"><b>๖. สอดคล้องกับ (SDGs) (เลือกได้มากกว่า ๑ หัวข้อ)</b>
    @foreach($project->sdgs as $sdgs)
        <p class='paragraph'>
            <span class="checkbox">&#9745;</span>
            <span style="margin-left:5px;">
            {{ toThaiNumber($sdgs->Name_SDGs ?? 'Unknown SDG') }}
            </span>
        </p>
    @endforeach
    </p>

    <p class="space"><b>๗. การบูรณาการงานโครงการ/กิจกรรม กับ</b>
        <p class='paragraph'>
            @foreach ($project->projectHasIntegrationCategories->sortBy('integrationCategory.Id_Integration_Category') as $projectHasIntegrationCategorie)
            <span>๗.{{ toThaiNumber($loop->iteration) }}</span>
            <span class="checkbox">&#9745;</span>
            <span><b>{{ toThaiNumber($projectHasIntegrationCategorie->integrationCategory->Name_Integration_Category) }}</b></span><br>

            @if($projectHasIntegrationCategorie->Integration_Details)
                <span class="paragraph">
                    {{ toThaiNumber($projectHasIntegrationCategorie->Integration_Details) }}
                </span><br>
            @endif
        @endforeach
        </p>
    </p>

    <p class="space">
        <span><b>๘. หลักการและเหตุผล</b></span>
        <span>(ระบุที่มา เหตุผล/ปัญหา/ความจำเป็น/ความสำคัญ/องค์ความรู้และความเชี่ยวชาญ ของสาขาวิชา)</span>
    <p class="paragraph-content">
        {{ toThaiNumber($project->Principles_Reasons) }}
    </p>
    </p>

    <p class="space"><b>๙. วัตถุประสงค์</b>
    @foreach($project->objectives as $ObjectiveProject)
        <p class="paragraph-content">
        {{ toThaiNumber($loop->iteration) }}. {{ toThaiNumber($ObjectiveProject->Description_Objective) }}
        </p>
    @endforeach
    </p>

    <p class="space"><b>๑๐. กลุ่มเป้าหมาย</b>
    <p class="paragraph"><b>๑๐.๑ กลุ่มผู้รับบริการ</b>
        @foreach($project->targets as $target)
    <table style="border-collapse: collapse; width:100%; border: none;">
        <tr>
            <td style="width: 18%; border: none;"></td>
            <td style="width: 35%; text-align: left; padding: 5px; border: none;">- {{ $target->Name_Target }}</td>
            <td style="text-align: left; padding: 5px; border: none;">
                <span>จำนวน </span>
                <span class="line" style="width: 50px; line-height: 0.8;">
                    {{ toThaiNumber($target->Quantity_Target) }}
                </span>
                {{ toThaiNumber($target->Unit_Target) }}
            </td>
        </tr>
    </table>
    @endforeach
    </p>

    <p class="paragraph" style="margin-top: 20px;"><b>๑๐.๒ พื้นที่/ชุมชนเป้าหมาย (ถ้ามี ระบุ) </b>
        @foreach($project->targets as $target)
        @foreach($target->targetDetails as $detail)
    <p class="paragraph-two">
        {{ toThaiNumber($detail->Details_Target) }}
    </p>
    @endforeach
    @endforeach
    </p>
    </p>

    <p class="space">
        <span><b>๑๑. สถานที่ดำเนินงาน</b></span>
    <p class="paragraph-content">
        @foreach($project->locations as $location)
        {{ toThaiNumber($loop->iteration) }}. {{ toThaiNumber($location->Name_Location) }}
        @endforeach
    </p>
    </p>

    <!-- ตัวชี้วัด -->
    <p class="space">
        <span><b>๑๒. ตัวชี้วัด</b></span>
        @php
        $groupedIndicators = collect($project->projectHasIndicators)
        ->groupBy(fn($indicator) => $indicator->indicators->Type_Indicators);
        @endphp

        @foreach (['Quantitative' => 'เชิงปริมาณ', 'Qualitative' => 'เชิงคุณภาพ'] as $type => $label)
        @if (!empty($groupedIndicators[$type]))

    <p class="paragraph"><b>๑๒.{{ toThaiNumber($loop->iteration) }}. {{ $label }}</b></p>
    @foreach ($groupedIndicators[$type] as $index => $indicator)
    <p class="paragraph-two {{ $loop->last ? 'loop_last' : '' }}">
        ({{ toThaiNumber($loop->iteration) }}) {{ toThaiNumber($indicator->Details_Indicators) }}
    </p>
    @endforeach
    @endif
    @endforeach
    </p>


    <p class="space">
        <span><b>๑๓. ระยะเวลาดำเนินโครงการ</b></span>
    <p class="paragraph">
        @if (!empty($project->First_Time) && !empty($project->End_Time))
        <span>
            กำหนดการจัดโครงการ <b>{{ $project->formatted_first_time }}</b><br>
            ถึง <b style="margin-left: 6px">{{ $project->formatted_end_time }}</b>
        </span>
        @endif
    </p>
    </p>

    <p class="space">
        <span><b>๑๔. ขั้นตอนและแผนการดำเนินงาน (PDCA)</b></span><br>
            @if ($project->Project_Type == 'L')
            <!-- โครงการระยะยาว -->
            <span class="checkbox" style="margin-left:25px;">&#9744;</span><span
                style="margin-left:5px;">โครงการระยะสั้น</span>
            <span class="checkbox" style="margin-left:25px;">&#9745;</span><span
                style="margin-left:5px;">โครงการระยะยาว</span>
            <table>
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 40%; line-height: 0.6;">กิจกรรมและแผนการเบิกจ่าย งบประมาณ</th>
                        <th colspan="12">
                            <span>ปีงบประมาณ พ.ศ.</span>
                            <span class="line" style="display: inline-block; padding: 0 10px; line-height: 0.8; width: 40px;" >
                                @foreach($quarterProjects as $year)
                                    <span>{{ toThaiNumber($year) }}</span>
                                @endforeach
                            </span>
                        </th>
                    </tr>
                    <tr>
                        <th>ม.ค.</th><th>ก.พ.</th><th>มี.ค.</th><th>เม.ย.</th><th>พ.ค.</th><th>มิ.ย.</th>
                        <th>ก.ค.</th><th>ส.ค.</th><th>ก.ย.</th><th>ต.ค.</th><th>พ.ย.</th><th>ธ.ค.</th>
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
                                {{ toThaiNumber($loop->iteration) }}. {{ toThaiNumber($pdcaDetail->Details_PDCA) }}<br>
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
            @else
            <span class="checkbox" style="margin-left:25px;">&#9745;</span><span
                style="margin-left:5px;">โครงการระยะสั้น</span>
            <span class="checkbox" style="margin-left:25px;">&#9744;</span><span
                style="margin-left:5px;">โครงการระยะยาว</span>

                <br><b style="margin-left:25px;">วิธีการดำเนินงาน</b>
                <p>
                    @foreach($project->shortProjects as $shortProject)
                    <span class="paragraph">
                        {{ toThaiNumber($loop->iteration) }}. {{ toThaiNumber($shortProject->Details_Short_Project) }}
                    </span><br>
                    @endforeach
                </p>

            @endif



    </p>

    <p class="space">
        <span><b>๑๕. แหล่งงบประมาณ</b></span><br>
        @if($project->Status_Budget != 'Y')
            <b class="paragraph">-</b>
        @else
            @foreach($project->projectBudgetSources as $budget)
                <span class="checkbox" style="margin-left:25px;">&#9745;</span>
                <span style="margin-left:5px;">{{ toThaiNumber($budget->budgetSource->Name_Budget_Source) }}
                    <b class="line" style="display: inline-block; width: 90px;">
                        {{ digits($budget->budgetSourceTotal->Amount_Total) }}
                    </b>บาท
                </span><br>
            @endforeach

            <b>รายละเอียดค่าใช้จ่าย</b><br>
                @foreach($project->projectBudgetSources as $budgetDetail)
                    <span class="paragraph">{{ toThaiNumber($budgetDetail->Details_Expense) ?? '' }}</span><br>
                @endforeach
            <table style="margin-top:10px;">
                <thead>
                    <tr>
                    <th style="width: 8%;">ลำดับ</th>
                        <th style="width: 72%;">รายการ</th>
                        <th style="width: 20%;">จำนวน (บาท)</th>
                    </tr>
                </thead>
                <tbody>
                    @php  
                        $currentDate = null;
                    @endphp
                    
                    @foreach($project->expenseTypes as $expenseType)
                        @php
                            $allExpenses = collect($expenseType->expenses)
                                ->flatMap(fn($expense) => $expense->expenHasSubtopicBudgets->map(fn($item) => ['date' => $expense->Date_Expense, 'details' => $expense->Details_Expense, 'item' => $item]))
                                ->groupBy('date')
                                ->sortKeys();
                        @endphp
                        
                        @foreach($allExpenses as $date => $expensesByDate)
                            @php
                                $totalAmountPerDay = $expensesByDate->pluck('item')->sum('Amount_Expense_Budget');
                            @endphp
                            <tr>
                                <td colspan="2" style="text-align: left; font-weight: bold;">
                                    {{ formatDateThai($date) }}  {{ toThaiNumber($expensesByDate->first()['details']) }}
                                </td>
                                <td><b>{{ digits($totalAmountPerDay) }}</b></td>
                            </tr>
                            
                            @php
                                $groupedExpenses = $expensesByDate->pluck('item')->groupBy(fn($item) => optional($item->subtopicBudgets->first())->Name_Subtopic_Budget ?? 'N/A');
                            @endphp
                            @foreach($groupedExpenses as $subtopicName => $expenses)
                                
                                <tr>
                                    <td></td>
                                    <td style="text-align: left;"><b>{{ toThaiNumber($subtopicName) }}</b></td>
                                    <td></td>
                                </tr>

                                @foreach($expenses as $expense)
                                    <tr>
                                        <td></td>
                                        <td style="text-align: left; padding-left: 10px;">- {{ toThaiNumber($expense->Details_Expense_Budget) }}</td>
                                        <td style="text-align: center;">{{ digits($expense->Amount_Expense_Budget ?? 0) }}</td>
                                    </tr>
                                @endforeach
                                @php $index++; @endphp
                            @endforeach
                        @endforeach
                    @endforeach
                    <tr>
                        <th></th>
                        <th style="text-align: left;">รวม</th>
                        <th style="text-align: center;"><b>{{ digits($project->expenseTypes->flatMap->expenses->flatMap->expenHasSubtopicBudgets->sum('Amount_Expense_Budget')) }}</b></th>
                    </tr>
                </tbody>
            </table>


        @endif
    </p>


    <p class="space">
        <span><b>๑๖. เป้าหมายเชิงผลผลิต (Output)</b></span>
    <p class="paragraph">
        @foreach($output as $outputs)
    <p class="paragraph-content">๑๖.{{ toThaiNumber($loop->iteration) }} {{ toThaiNumber($outputs->Name_Output) }}</p>
    @endforeach
    </p>
    </p>


    <p class="space">
        <span><b>๑๗. เป้าหมายเชิงผลลัพธ์ (Outcome)</b></span>
    <p class="paragraph">
        @foreach($outcome as $outcomes)
    <p class="paragraph-content">๑๗.{{ toThaiNumber($loop->iteration) }} {{ toThaiNumber($outcomes->Name_Outcome) }}</p>
    @endforeach
    </p>
    </p>

    <p class="space">
        <span><b>๑๘. ผลที่คาดว่าจะได้รับ</b></span>
    <p class="paragraph">
        @foreach($expectedResult as $expectedResults)
    <p class="paragraph-content">{{ toThaiNumber($loop->iteration) }}.
        {{ toThaiNumber($expectedResults->Name_Expected_Results) }}</p>
    @endforeach
    </p>
    </p>

    <p class="space"> 
        <span><b>๑๙. ตัวชี้วัดความสำเร็จของโครงการ</b></span>
        @foreach($project->successIndicators as $successIndicator)
            <p class="paragraph-content">
                <span>
                        {{ toThaiNumber($successIndicator->Description_Indicators) }}</p>
                    
                </span>
            </p>
        @endforeach
    </p>
    <p class="space">
        <span><b>๒๐. ค่าเป้าหมาย</b></span>
        @foreach($project->valueTargets as $valueTarget)
            <p class="paragraph-content">
                <span>
                        {{ toThaiNumber($valueTarget->Value_Target) }}</p>
                </span>
            </p>
        @endforeach
    </p>


</body>

</html>