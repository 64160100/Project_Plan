@foreach ($platforms as $platform)
<div class="modal fade" id="addPlatformKpi{{$platform->Id_Platform}}" tabindex="-1" aria-labelledby="addPlatformKpiLabel{{$platform->Id_Platform}}" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('createPlatformKpi',['Id_Platform' => $platform->Id_Platform]) }}" method="POST">
                <div class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addPlatformKpiLabel{{$platform->Id_Platform}}">เพิ่มรายการ Kpi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="Name_Platfrom_Kpi" class="col-form-label">ชื่อ Kpi :</label>
                            <input type="text" class="form-control" id="Name_Platfrom_Kpi" name="Name_Platfrom_Kpi" placeholder="กรอกชื่อ Kpi" required>
                        </div>

                        <div class="mb-3">
                            <label for="Description_Platfrom_Kpi" class="col-form-label">รายละเอียด Kpi :</label>
                            <textarea class="form-control" id="Description_Platfrom_Kpi" name="Description_Platfrom_Kpi" placeholder="กรอกรายละเอียด" required></textarea>
                        </div>

                        <div id="budget-years-container">
                            <div class="mb-3">
                                <label for="Value_Platform_1">ปีงบประมาณที่ 1:</label>
                                <input type="number" class="form-control" id="Value_Platform_1" name="Value_Platform[]" placeholder="ค่า KPI เช่น 4" required>
                            </div>
                            <div class="mb-3">
                                <label for="Value_Platform_2">ปีงบประมาณที่ 2:</label>
                                <input type="number" class="form-control" id="Value_Platform_2" name="Value_Platform[]" placeholder="ค่า KPI เช่น 4" required>
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
