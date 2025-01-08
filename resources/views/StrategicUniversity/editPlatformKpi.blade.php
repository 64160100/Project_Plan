@foreach ($platforms as $platform)
    @foreach ($platform->platformKpis as $platformKpi)
    <div class="modal fade" id="editPlatformKpi{{$platformKpi->Id_Platform_Kpi}}" tabindex="-1" aria-labelledby="editPlatformKpiLabel{{$platformKpi->Id_Platform_Kpi}}" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('editPlatformKpi', ['Id_Platform' => $platform->Id_Platform, 'Id_Platform_Kpi' => $platformKpi->Id_Platform_Kpi]) }}" method="POST">
                <div class="modal-content">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPlatformKpiLabel{{$platformKpi->Id_Platform_Kpi}}">แก้ไขรายการ Kpi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="Name_Platfrom_Kpi{{$platformKpi->Id_Platform_Kpi}}" class="col-form-label">ชื่อ Kpi :</label>
                            <input type="text" class="form-control" id="Name_Platfrom_Kpi" name="Name_Platfrom_Kpi" value="{{ $platformKpi->Name_Platfrom_Kpi }}" placeholder="กรอกชื่อ Kpi" required>
                        </div>

                        <div class="mb-3">
                            <label for="Description_Platfrom_Kpi{{$platformKpi->Id_Platform_Kpi}}" class="col-form-label">รายละเอียด Kpi :</label>
                            <textarea class="form-control" id="Description_Platfrom_Kpi" name="Description_Platfrom_Kpi" placeholder="กรอกรายละเอียด" required>{{ $platformKpi->Description_Platfrom_Kpi }}</textarea>
                        </div>

                        


                        <div id="budget-years-container">
                            <div class="mb-3">
                                <label for="Value_Platform_1{{$platformKpi->Id_Platform_Kpi}}">ปีงบประมาณที่ 1:</label>
                                <input type="number" class="form-control" id="Value_Platform_1" name="Value_Platform[]" 
                                        value="{{ $platformKpi->platformYears->first()->Value_Platform ?? '' }}"
                                        placeholder="ค่า KPI เช่น 4" required>
                            </div>

                            <div class="mb-3">
                                <label for="Value_Platform_2{{$platformKpi->Id_Platform_Kpi}}">ปีงบประมาณที่ 2:</label>
                                <input type="number" class="form-control" id="Value_Platform_2" name="Value_Platform[]" 
                                        value="{{ $platformKpi->platformYears->skip(1)->first()->Value_Platform ?? '' }}"  
                                        placeholder="ค่า KPI เช่น 4" required>
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
@endforeach