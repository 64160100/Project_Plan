<div class="modal fade" id="ModalAddStrategic" tabindex="-1" aria-labelledby="ModalAddStrategicLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalAddStrategicLabel">เพิ่มข้อมูลแผนยุทธศาสตร์</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addstrategic" action="{{ route('strategic.add') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="mb-3">
                        <label for="Name_Strategic_Plan" class="form-label">ชื่อยุทธศาสตร์</label>
                        <input type="text" class="form-control" id="Name_Strategic_Plan" name="Name_Strategic_Plan" placeholder="กรอกชื่อยุทธศาสตร์" required>
                    </div>
                    <div class="mb-3">
                        <label for="Goals_Strategic" class="form-label">เป้าหมายเชิงยุทธศาสตร์</label>
                        <textarea class="form-control" id="Goals_Strategic" name="Goals_Strategic" placeholder="กรอกเป้าหมายเชิงยุทธศาสตร์" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="Fiscal_Year_Quarter_Add" class="form-label">ปีงบประมาณและไตรมาส</label>
                        <select class="form-control" id="Fiscal_Year_Quarter_Add" name="Fiscal_Year_Quarter_Add" required>
                            <option value="">เลือกปีงบประมาณและไตรมาส</option>
                            @foreach($quarters as $quarter)
                                <option value="{{ $quarter->Id_Quarter_Project }}">
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