<!-- createProgram -->
<div class="modal fade" id="addProgram" tabindex="-1" aria-labelledby="addProgramLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('createProgram', ['Id_Platform' => $Platform->Id_Platform]) }}" method="POST">
            <div class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addProgramLabel">เพิ่ม Program ใหม่</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="Name_Program" class="col-form-label">ชื่อ Program :</label>
                            <input type="text" class="form-control" id="Name_Program" name="Name_Program" placeholder="กรอกชื่อ Program" required>
                        </div>

                        <div class="mb-3">
                            <label for="Name_Object" class="col-form-label">ชื่อวัตถุประสงค์ :</label>
                            <input type="text" class="form-control" id="Name_Object" name="Name_Object" placeholder="กรอกชื่อวัตถุประสงค์" required>
                        </div>

                        <div id="budget-years-container">
                            <div class="mb-3">
                                <label for="Budget_Year_1">ปีงบประมาณที่ 1:</label>
                                <input type="number" class="form-control" id="Budget_Year_1" name="Budget_Year[]" placeholder="กรอกปีงบประมาณ เช่น 2566" required>
                            </div>
                            <div class="mb-3">
                                <label for="Budget_Year_2">ปีงบประมาณที่ 2:</label>
                                <input type="number" class="form-control" id="Budget_Year_2" name="Budget_Year[]" placeholder="กรอกปีงบประมาณ เช่น 2567" required>
                            </div>
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




@foreach ($Platform->programs as $Program)
    <!-- table header button -->
    <div class="bth-group">
        <button type="button" class="btn-add" data-bs-toggle="modal" data-bs-target="#addProgramKpi{{$Program->Id_Program}}">
            <i class='bx bx-plus'></i>เพิ่ม KPI
        </button>

        <form action="{{ route('deleteProgram', $Program->Id_Program) }}" method="POST">
            @csrf
            @method('DELETE') 
            <button type="submit" class="btn-delete" onclick="return confirm('คุณต้องการลบ Platform นี้ใช่หรือไม่?')">
                <i class='bx bx-trash'></i>ลบตาราง
            </button>
        </form>
    </div><br>

    @include('StrategicUniversity.showProgramKpi')
    @include('StrategicUniversity.editProgramKpi')

    <div class="container-program">
        <table style="width:100%">
            <tr>
                <th colspan="4">{{ $Program->Name_Program }}</th>
                <th rowspan="4">
                    <div class="bth-group">
                        <button type="button" class="btn-edit" data-bs-toggle="modal" data-bs-target="#editProgram{{$Program->Id_Program}}" id="{{$Program->Id_Program}}">
                            <i class='bx bx-edit'></i>แก้ไข
                        </button>
                    </div>
                </th>
            </tr>
            <tr>
                <th colspan="4">{{$Program->Name_Object}}</th>
            </tr>
            <tr>
                <th colspan="2" rowspan="2">ผลสัมฤทธิ</th>
                <th colspan="2" class="center-text">ปีงบประมาณ</th>
            </tr>


            <tr class="center-text">
                    @if($Program->budgetYears->isNotEmpty())
                        @foreach($Program->budgetYears as $budgetYear)
                            <th>{{ $budgetYear->Budget_Year }}</th>
                        @endforeach
                    @else
                        <th colspan="2">ไม่มีข้อมูลปีงบประมาณ</th>
                    @endif
            </tr>

            @foreach ($Program->programKpis as $ProgramKpi)
                <tr>
                    <td>{{ $ProgramKpi->Name_Program_Kpi }}</td>
                    <td>{{ $ProgramKpi->Description_Program_Kpi }}</td>

                    @foreach($Program->budgetYears as $budgetYear)
                        @php
                            $programYear = $ProgramKpi->programYears()
                                ->where('Program_Budget_Year_Id', $budgetYear->Id_Program_Budget_Year)
                                ->first();
                        @endphp
                        <td>
                            @if($programYear)
                                {{ $programYear->Value_Program }}
                            @else
                                -
                            @endif
                        </td> 
                    @endforeach
                    
                    
                    
                    <td>
                        <div class="bth-group">
                            <button type="button" class="btn-edit" data-bs-toggle="modal" data-bs-target="#editProgramKpi{{$ProgramKpi->Id_Program_Kpi}}" id="{{$ProgramKpi->Id_Program_Kpi}}">
                                <i class='bx bx-edit'></i>แก้ไข
                            </button>
                            
                            <form action="{{ route('deleteProgramKpi', $ProgramKpi->Id_Program_Kpi) }}" method="POST">
                                @csrf
                                @method('DELETE') 
                                <button type="submit" class="btn-delete" onclick="return confirm('คุณต้องการลบ KPI นี้ใช่หรือไม่?')">
                                    <i class='bx bx-trash'></i>ลบ
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>
            @endforeach
        </table>
    </div><br>
 @endforeach