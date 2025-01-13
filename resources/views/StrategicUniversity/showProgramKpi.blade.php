@foreach ($Platform->programs as $Program)
    <div class="modal fade" id="addProgramKpi{{$Program->Id_Program}}" tabindex="-1" aria-labelledby="addProgramKpiLabel{{$Program->Id_Program}}" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('createProgramKpi', ['Id_Program' => $Program->Id_Program]) }}" method="POST">
                <div class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addProgramKpiLabel{{$Program->Id_Program}}">แก้ไขรายการ Kpi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="Name_Program_Kpi" class="col-form-label">ชื่อ Kpi :</label>
                            <input type="text" class="form-control" id="Name_Program_Kpi" name="Name_Program_Kpi" placeholder="กรอกชื่อ Kpi" required>
                        </div>

                        <div class="mb-3">
                            <label for="Description_Program_Kpi" class="col-form-label">รายละเอียด Kpi :</label>
                            <textarea class="form-control" id="Description_Program_Kpi" name="Description_Program_Kpi" placeholder="กรอกรายละเอียด" required>{{ $Program->Description_Program_Kpi ?? '' }}</textarea>
                        </div>

                        <div id="budget-years-container">
                            <div class="mb-3">
                                <label for="Value_Program_1">ปีงบประมาณที่ 1:</label>
                                <input type="number" class="form-control" id="Value_Program_1" name="Value_Program[]" min="1" step="any" placeholder="ค่า KPI เช่น 4" required>
                            </div>
                            <div class="mb-3">
                                <label for="Value_Program_2">ปีงบประมาณที่ 2:</label>
                                <input type="number" class="form-control" id="Value_Program_2" name="Value_Program[]" min="1" step="any" placeholder="ค่า KPI เช่น 4" required>
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
@endforeach
