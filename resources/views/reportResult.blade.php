@extends('navbar.app')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>reportResult</title>
    <!-- <link rel="stylesheet" href="{{ asset('css/project.css') }}"> -->
    <link rel="stylesheet" href="{{ asset('css/reportResult.css') }}">

</head>

<body>
@section('content')
<button type="button" class="btn-editBudget" data-bs-toggle="modal" data-bs-target="#reportResult">
    <i class='bx bx-plus'></i>รายงานผล
</button>


<div class="modal fade" id="reportResult" tabindex="-1" aria-labelledby="reportResultLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 80%; width: auto;">
        <form action="{{ route('reportResult') }}" method="POST">
            <div class="modal-content">
                @csrf
                    
                    <div class="modal-header">
                        <h1 class="modal-title" id="reportResultLabel">สรุปผลการดำเนินงาน {{$report->Name_Project}}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    
                    <div class="modal-body">
                        <div class="mb-3">
                            <h2>ส่วนที่ 1 ผลการดำเนินงาน</h2>
                            <textarea class="form-control" placeholder="กรอกรายละเอียดผลการดำเนินงาน" rows="20"></textarea>
                        </div>

                        <h2>ส่วนที่ 2 ผลสำเร็จตามตัวชี้วัดของโครงการ</h2>
                        <div class="mb-3">
                            <div class="content-box">
                                <div>
                                    <label>กลยุทธ์</label>
                                    <select id="categoryDropdown" class="mb-2">
                                        <option value=""> เลือกกลยุทธ์ </option>
                                        @foreach ($strategies as $strategy)
                                            <option value="{{ $strategy->Id_Strategy }}">
                                                {{ $strategy->Name_Strategy }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="kpi-list" id="kpiList"></div><br>

                                    @foreach($projectIndicator as $projectIndicators)
                                        <div class="content-box mb-2">   
                                                <label class="">
                                                @if ($projectIndicators->indicators->Type_Indicators == 'Quantitative')
                                                    ตัวชี้วัดเชิงปริมาณ
                                                @elseif ($projectIndicators->indicators->Type_Indicators == 'Qualitative')
                                                    ตัวชี้วัดเชิงคุณภาพ
                                                @else
                                                    เชิงอื่นๆ
                                                @endif
                                            </label>

                                            <input type="text" class="form-control" value="{{$projectIndicators->Details_Indicators}}" readonly>
                                            
                                            <label class="mt-3">ผลสำเร็จตัวชี้วัด</label>
                                            <textarea class="form-control" rows="3" placeholder="เช่น มีผู้เข้าร่วม 100 คน" require></textarea>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                            
                        <h2>ส่วนที่ 3 การมีส่วนร่วมของหน่วยงานภายนอก/ชุมชน</h2>
                        <div class="mb-3">
                            <textarea class="form-control" id="external_participation" name="external_participation" rows="5" placeholder="กรอกข้อมูลเพิ่มเติม" require></textarea>
                        </div>


                        <h2>ส่วนที่ 4 งบประมาณ</h2>
                            <div class="mb-3">
                                <div class="content-box">
                                    <!-- <h5>งบประมาณ</h5> -->
                                    <label>แหล่งงบประมาณ</label>
                                    <div class="content-box mb-3">
                                            <div class="d-flex align-items-center">
                                                <p>{{ $projectBudgetSource->budgetSource->Name_Budget_Source }}
                                                <span><b>{{ $projectBudgetSource->Amount_Total }}</b></span>&nbsp;&nbsp; บาท <br>
                                                รายละเอียดค่าใช้จ่าย : <b>{{ $projectBudgetSource->Details_Expense }}</b>
                                                </p>
                                            </div>
                                    </div>

                                    @if (!empty($budgetProject) && !empty($budgetProject->Amount_Big))
                                        <div class="content-box">
                                            <label>หัวข้อใหญ่</label>
                                            <div class="content-box mb-3">
                                                <p>{{ $budgetProject->Big_Topic }}</p>
                                            </div>
                                            
                                            
                                            @if (!empty($subBudgetProject) && !empty($subBudgetProject->Amount_Sub))
                                                <label>รายละเอียดค่าใช้จ่ายโครงการ</label>
                                                <div class="content-box">
                                                    <!-- foreach ($subBudgetProject as $subBudgetProjects) -->
                                                        <div>{{ $subBudgetProject->subtopicBudget->Name_Subtopic_Budget }}</div>
                                                    <!-- endforeach -->
                                                    <div class="d-flex align-items-center">
                                                        <input type="text" class="form-control me-2" value="{{ $subBudgetProject->Details_Subtopic_Form }}" readonly style="max-width: 500px;">
                                                        <input type="text" class="form-control me-2" value="{{ $subBudgetProject->Amount_Sub }}" readonly style="max-width: 500px;">
                                                        <span>บาท</span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <h4 class="budget">
                                            ใช้งบประมาณทั้งสิ้น&nbsp;<span><strong>{{ $budgetProject->Amount_Big }}</strong></span>&nbsp;บาท
                                        </h4>
                                    @else
                                        <div class="text-danger"><b>ไม่มีงบประมาณ</b></div>
                                    @endif
                                </div>
                            </div>


                        <h2>ส่วนที่ 5 ข้อเสนอแนะ</h2>
                        <div class="mb-3">
                            <textarea class="form-control" placeholder="กรอกข้อเสนอแนะ" rows="8"></textarea>
                        </div>

                    </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                </div>
                
            </div>
        </form>
    </div>
</div>


<!-- เลือกกลยุท -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const categoryDropdown = document.getElementById("categoryDropdown");
        const kpiList = document.getElementById("kpiList");

        // แปลง PHP เป็น JSON โดยตรง
        const kpiData = @json($strategies);

        categoryDropdown.addEventListener("change", function () {
            const selectedId = this.value;
            const strategy = kpiData.find(s => s.Id_Strategy == selectedId);
            kpiList.innerHTML = ""; // ล้างค่าเก่าออก

            if (strategy && strategy.kpis.length > 0) {
                strategy.kpis.forEach(kpi => {
                    kpiList.innerHTML += `
                        <div class="d-flex align-items-center mb-2">
                            <input type="checkbox" value="${kpi.Id_Kpi}" class="me-2">
                            <textarea class="form-control me-2" readonly>${kpi.Name_Kpi}</textarea>
                            <textarea class="form-control" readonly>${kpi.Target_Value}</textarea>
                        </div>`;
                });
            } else {
                kpiList.innerHTML = "<p class='text-muted'>ไม่มีข้อมูล KPI</p>";
            }
        });
    });
</script>


@endsection

</body>
</html>