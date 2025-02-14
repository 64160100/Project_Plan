@foreach ($strategic as $strategic)
<div class="modal fade" id="ModalEditStrategic{{ $strategic->Id_Strategic }}" tabindex="-1" aria-labelledby="ModalEditStrategicLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalEditStrategicLabel">แก้ไขข้อมูลแผนยุทธศาสตร์</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('strategic.update', $strategic->Id_Strategic) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="Name_Strategic_Plan">ชื่อแผนยุทธศาสตร์</label>
                        <input type="text" class="form-control" id="Name_Strategic_Plan" name="Name_Strategic_Plan" value="{{ $strategic->Name_Strategic_Plan }}" required>
                    </div>
                    <div class="form-group">
                        <label for="Goals_Strategic">เป้าหมายยุทธศาสตร์</label>
                        <textarea class="form-control" id="Goals_Strategic" name="Goals_Strategic" required>{{ $strategic->Goals_Strategic }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="Fiscal_Year_Quarter_Add">ปีงบประมาณและไตรมาส</label>
                        <select class="form-control" id="Fiscal_Year_Quarter_Add" name="Fiscal_Year_Quarter_Add" required>
                            <option value="">เลือกปีงบประมาณและไตรมาส</option>
                            @foreach($quarters as $quarter)
                                <option value="{{ $quarter->Id_Quarter_Project }}" {{ optional($strategic->quarterProjects->first())->Quarter_Project_Id == $quarter->Id_Quarter_Project ? 'selected' : '' }}>
                                    ปีงบประมาณ {{ $quarter->Fiscal_Year }} ไตรมาส {{ $quarter->Quarter }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach