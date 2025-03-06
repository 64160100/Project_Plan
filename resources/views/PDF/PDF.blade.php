<!DOCTYPE html>
<html lang="th">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
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
    </style>
</head>
<body>
    
    <h1>{{ toThaiNumber($project->Name_Project) }}</h1>
    <div class="line" style="width: 100%; max-width: 590px; word-wrap: break-word;"></div>
    <!-- <p><b>๑. ชื่อโครงการ: </b>{{ $project->Name_Project }}</p> -->
    <p><b>๑. ชื่อโครงการ: </b>{{ toThaiNumber($project->Name_Project) }}</p>

    
    <p class="space"><b style="color:#f00">๒. ลักษณะโครงการ:</b>
        @if ($project->Description_Project == 'N')
            <span class="checkbox" style="margin-left:25px;">&#9745;</span><span style="margin-left:5px;">โครงการใหม่</span>
            <span class="checkbox" style="margin-left:25px;">&#9744;</span><span style="margin-left:5px;">โครงการต่อเนื่อง</span>
        @else
            <span class="checkbox" style="margin-left:25px;">&#9744;</span><span style="margin-left:5px;">โครงการใหม่</span>
            <span class="checkbox" style="margin-left:25px;">&#9745;</span><span style="margin-left:5px;">โครงการต่อเนื่อง</span>
        @endif
    </p>

    <p class="space"><b>๓. ผู้รับผิดชอบโครงการ</b>
        <p class='paragraph'>
            {{ $project->employee->Firstname ?? '-' }}
            {{ $project->employee->Lastname ?? '' }}
        </p>
    </p>

    <p class="space" style="color:#f00"> ขาดปีงบประมาณ
        <b>๔. ความสอดคล้องกับยุทธศาสตร์มหาวิทยาลัย</b>

        (ปีงบประมาณ พ.ศ. 
        

        )

        @foreach ($project->platforms as $platform)
            <p class="paragraph-tab">
                <span class="checkbox">&#9744;</span>
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
        @foreach($project->projectHasSDGs as $project_has_sdgs)
            <p class='paragraph'>
                <span class="checkbox">&#9745;</span><span style="margin-left:5px;">
                    {{ toThaiNumber($project_has_sdgs->sdg->Name_SDGs) }}
                </span>
            </p>
        @endforeach
    </p>

    <p class="space"><b>๗. การบูรณาการงานโครงการ/กิจกรรม กับ</b>
        <p class='paragraph'>
            @foreach ($project->projectHasIntegrationCategories as $projectHasIntegrationCategorie)
                <span>๗.{{ toThaiNumber($loop->iteration) }}</span>
                <span class="checkbox">&#9745;</span>
                <span><b>{{ $projectHasIntegrationCategorie->integrationCategory->Name_Integration_Category }}</b></span><br>
                
                @if($projectHasIntegrationCategorie->Integration_Details)
                    <span class="paragraph">
                    {{ $projectHasIntegrationCategorie->Integration_Details }}
                    </span><br>
                @endif
            @endforeach
        </p>
    </p>

    <p class="space">
        <span><b>๘. หลักการและเหตุผล</b></span>   
        <span>(ระบุที่มา เหตุผล/ปัญหา/ความจำเป็น/ความสำคัญ/องค์ความรู้และความเชี่ยวชาญ ของสาขาวิชา)</span>
        <p class="paragraph-content">
            {{ toThaiNumber($project->Principles_Reasons ?? '-') }}
        </p>
    </p>

    <p class="space"><b>๙. วัตถุประสงค์โครงการ</b>
        <p class="paragraph-content">
            {{ toThaiNumber($project->Objective_Project ?? '-') }}
        </p>
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
            ๑๑.{{ toThaiNumber($loop->iteration) }} {{ toThaiNumber($location->Name_Location) }}
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
            @else
                <span>-</span>
            @endif
        </p>
    </p>

    <p class="space">
        <span><b>๑๔. ขั้นตอนและแผนการดำเนินงาน (PDCA)</b></span><br>
        <span class="checkbox" style="margin-left:25px;">&#9745;</span><span style="margin-left:5px; color:#f00">โครงการระยะสั้น</span>
        <span class="checkbox" style="margin-left:25px;">&#9744;</span><span style="margin-left:5px; color:#f00">โครงการระยะยาว</span>
        
        <!-- โครงการระยะยาว -->
        <table>
            <thead>
                <tr>
                    <th rowspan="2" style="width: 40%; line-height: 0.6;" >กิจกรรมและแผนการเบิกจ่าย งบประมาณ</th>
                    <th colspan="13">
                        <span style="color:#f00">ปีงบประมาณ พ.ศ.</span>
                        <span class="line" style="display: inline-block; padding: 0 10px; line-height: 0.8;">rwrr</span>
                    </th>
                </tr>
                <tr>
                    <th>ต.ค.</th><th>พ.ย.</th><th>ธ.ค.</th><th>ม.ค.</th><th>ก.พ.</th><th>มี.ค.</th>
                    <th>เม.ย.</th><th>พ.ค.</th><th>มิ.ย.</th><th>ก.ค.</th><th>ส.ค.</th><th>ก.ย.</th>
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
                        <td></td><td></td><td></td><td></td><td></td><td></td>
                        <td></td><td></td><td></td><td></td><td></td><td></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </p>

    <p class="space">
        <span><b style="color:#f00">๑๕. แหล่งงบประมาณ</b></span><br>
            @if($project->Status_Budget != 'Y')
                    <b class="paragraph">-</b>
            @else     
                @foreach($projectBudgetSources as $budget)
                    <span class="checkbox" style="margin-left:25px;">&#9745;</span>
                    <span style="margin-left:5px;">{{ $budget->budgetSource->Name_Budget_Source }}
                        <b class="line" style="display: inline-block; padding-left: 20px; padding-right: 20px; width: auto;">
                            {{ $budget->Amount_Total }}
                        </b>บาท
                    </span>
                    <p class="paragraph-content">{{ $budget->Details_Expense }}</p>
                        <div class="head-table">รายละเอียดค่าใช้จ่าย (แตกตัวคูณโดยใช้อัตราตามหลักเกณฑ์อัตราค่าใช้จ่าย)</div>
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 10%;">ลำดับ</th>
                                    <th style="width: 70%;">รายการ</th>
                                    <th style="width: 20%;">จำนวน (บาท)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $index = 1; @endphp
                                @foreach($subBudgets as $subBudget)
                                    <tr>
                                        <td style="text-align: center;"><b>{{ toThaiNumber($index) }}</b></td>
                                        <td style="text-align: left;"><b>{{ $subBudget->Name_Subtopic_Budget }}</b></td>
                                        <td></td>
                                    </tr>

                                    @php $subIndex = 1; @endphp
                                    @foreach($subBudget->subtopicBudgetForms as $subBudgetForm)
                                        <tr>
                                            <td ></td>
                                            <td style="text-align: left;">- {{ $subBudgetForm->Details_Subtopic_Form }}</td>
                                            <td style="text-align: center;">{{ toThaiNumber (number_format($subBudgetForm->Amount_Sub)) }}</td>
                                        </tr>
                                        @php $subIndex++; @endphp
                                    @endforeach
                                    @php $index++; @endphp
                                @endforeach
                                <tr>
                                    <th></th>
                                    <th style="text-align: center;">รวมทั้งสิ้น</th>
                                    <td style="text-align: center;"><b>{{ toThaiNumber($subBudgets->sum(fn($b) => $b->subtopicBudgetForms->sum('Amount_Sub'))) }}</b></td>
                                </tr>
                            </tbody>
                        </table>
                @endforeach
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
                <p class="paragraph-content">๑๘.{{ toThaiNumber($loop->iteration) }} {{ toThaiNumber($expectedResults->Name_Expected_Results) }}</p>
            @endforeach
        </p>
    </p>

    <p class="space">
        <span><b>๑๙. ตัวชี้วัดความสำเร็จของโครงการ</b></span>
        <p class="paragraph-content"> 
            <span>{{ toThaiNumber($project->Success_Indicators ?? '-') }}</span>
        </p>
    </p>
    <p class="space">
        <span><b>๒๐. ค่าเป้าหมาย</b></span>
        <p class="paragraph-content"> 
            <span>{{ toThaiNumber($project->Value_Target ?? '-') }}</span>
        </p>
    </p>

    
</body>
</html>