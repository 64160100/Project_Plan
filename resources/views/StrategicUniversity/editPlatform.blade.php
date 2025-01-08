@foreach ( $platforms as $Platform )

<div class="modal fade" id="editPlatform{{$Platform->Id_Platform}}" tabindex="-1" aria-labelledby="editPlatformLabel{{$Platform->Id_Platform}}" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('editPlatform', $Platform->Id_Platform) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPlatformLabel{{$Platform->Id_Platform}}">แก้ไขชื่อ Platform</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="Name_Platform{{$Platform->Id_Platform}}" class="col-form-label">ชื่อ Platform :</label>
                        <input type="text" class="form-control" id="Name_Platform{{$Platform->Id_Platform}}" name="Name_Platform" value="{{ $Platform->Name_Platform }}">
                    </div>

                    <div class="mb-3">
                        <label for="Name_Object{{$Platform->Id_Platform}}" class="col-form-label">ชื่อวัตถุประสงค์ :</label>
                        <input type="text" class="form-control" id="Name_Object{{$Platform->Id_Platform}}" name="Name_Object" value="{{ $Platform->Name_Object }}">
                    </div>

                    <div id="budget-years-container">
                        <div class="mb-3">
                            <label for="Budget_Year_1{{$Platform->Id_Platform}}">ปีงบประมาณที่ 1:</label>
                            <input type="number" class="form-control" id="Budget_Year_1" name="Budget_Year[]" 
                                    value="{{ $Platform->budgetYears->sortBy('Budget_Year')->first()->Budget_Year ?? '' }}" 
                                    placeholder="กรอกปีงบประมาณ เช่น 2566" required>
                        </div>

                        <div class="mb-3">
                            <label for="Budget_Year_2{{$Platform->Id_Platform}}">ปีงบประมาณที่ 2:</label>
                            <input type="number" class="form-control" id="Budget_Year_2" name="Budget_Year[]" 
                            value="{{ $Platform->budgetYears->sortBy('Budget_Year')->skip(1)->first()->Budget_Year ?? '' }}" 
                                    placeholder="กรอกปีงบประมาณ เช่น 2567" required>
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


