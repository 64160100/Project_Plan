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
<!-- <h1>รายงานผล</h1> -->


<button type="button" class="btn-editBudget" data-bs-toggle="modal" data-bs-target="#reportResult">
    <i class='bx bx-plus'></i>รายงานผล
</button>


<div class="modal fade" id="reportResult" tabindex="-1" aria-labelledby="reportResultLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 80%; width: auto;">
        <form action="{{ route('reportResult') }}" method="POST">
            <div class="modal-content">
                @csrf
                    
                    <div class="modal-header">
                        <h3 class="modal-title" id="reportResultLabel">สรุปผลการดำเนินงาน {{$report->Name_Project}}</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    
                    <div class="modal-body">
                        <div class="mb-3">
                            <h2>ส่วนที่ 1</h2>
                            <textarea class="form-control" id="activity_summary" name="activity_summary" placeholder="กรอกรายละเอียดผลการดำเนินงาน" rows="20"></textarea>
                        </div>

                        <h2>ส่วนที่ 2</h2>
                        <div class="mb-3">
                            <div class="content-box">
                                <h5>ผลสำเร็จตามตัวชี้วัดของโครงการ</h5>
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
                            
                        <h2>ส่วนที่ 3</h2>
                        <div class="mb-3">
                            <div class="content-box">
                                <label for="external_participation" class="col-form-label">การมีส่วนร่วมของหน่วยงานภายนอก/ชุมชน</label>
                                <textarea class="form-control" id="external_participation" name="external_participation" rows="5" placeholder="กรอกข้อมูลเพิ่มเติม" require></textarea>
                            </div>
                        </div>


                        <h2>ส่วนที่ 4</h2>
                        <div class="content-box">
                            <div class="mb-3">
                                <h5>งบประมาณ</h5>

                                    @if (!empty($budgetProject) && !empty($budgetProject->Amount_Big))
                                        <div class="content-box">
                                            <div>หัวข้อใหญ่</div>
                                            <textarea class="form-control" rows="4" readonly>{{ $budgetProject->Big_Topic }}</textarea>
                                            
                                            <label for="budget" class="d-flex col-form-label">ใช้งบประมาณทั้งสิ้น</label>
                                            <div class="d-flex align-items-center gap-2">
                                            <input type="text" class="form-control fw-bold" value="{{ $budgetProject->Amount_Big }}" readonly style="max-width: 200px;">
                                            <span class="fw-bold text-muted">บาท</span>
                                            </div>

                                            @if (!empty($subBudgetProject) && !empty($subBudgetProject->Amount_Sub))
                                                <label for="budget" class="d-flex col-form-label">รายละเอียดค่าใช้จ่ายโครงการ</label>
                                                <div class="d-flex align-items-center">
                                                    <input type="text" class="form-control me-2" value="{{ $subBudgetProject->Details_Subtopic_Form }}" readonly style="max-width: 500px;">
                                                    <input type="text" class="form-control me-2" value="{{ $subBudgetProject->Amount_Sub }}" readonly style="max-width: 500px;">
                                                    <span>บาท</span>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="text-danger"><b>ไม่มีงบประมาณ</b></div>
                                    @endif
                                </div>
                            </div><br>


                        <h2>ส่วนที่ 5</h2>
                        <div class="content-box">
                            <div class="mb-3">
                                <label for="suggestions" class="col-form-label">ข้อเสนอแนะ</label>
                                <textarea class="form-control" id="suggestions" name="suggestions" placeholder="กรอกข้อเสนอแนะ" rows="3"></textarea>
                            </div>
                        </div><br>
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

        const kpiData = {
            @foreach($strategies as $strategy)
                "{{ $strategy->Id_Strategy }}": [
                    @foreach($strategy->kpis as $kpi)
                        { id: "{{ $kpi->Id_Kpi }}", name: "{{ $kpi->Name_Kpi }}", target: "{{ $kpi->Target_Value }}" },
                    @endforeach
                ],
            @endforeach
        };

        categoryDropdown.addEventListener("change", function () {
            updateKPIList();
        });

        function updateKPIList() {
            kpiList.innerHTML = "";

            const selectedCategoryId = categoryDropdown.value;

            if (selectedCategoryId && kpiData[selectedCategoryId]) {
                kpiData[selectedCategoryId].forEach(kpi => {
                    // สร้าง div สำหรับ checkbox และ KPI details
                    const div = document.createElement("div");
                    div.classList.add("d-flex", "align-items-center", "mb-2");

                    const checkbox = document.createElement("input");
                    checkbox.type = "checkbox";
                    checkbox.value = kpi.id;
                    checkbox.classList.add("me-2");

                    const inputKpi = document.createElement("input");
                    inputKpi.type = "text";
                    inputKpi.value = kpi.name;
                    inputKpi.readOnly = true;
                    inputKpi.classList.add("form-control", "me-2");
                    inputKpi.style.maxWidth = "auto";

                    const inputTarget = document.createElement("input");
                    inputTarget.type = "text";
                    inputTarget.value = kpi.target;
                    inputTarget.readOnly = true;
                    inputTarget.classList.add("form-control");
                    inputTarget.style.maxWidth = "auto";

                    div.appendChild(checkbox);
                    div.appendChild(inputKpi);
                    div.appendChild(inputTarget);
                    kpiList.appendChild(div);
                });
            }
        }
    });
</script>

@endsection

</body>
</html>