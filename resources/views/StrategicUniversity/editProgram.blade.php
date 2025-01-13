@foreach ( $Platform->programs as $Program )
    <div class="modal fade" id="editProgram{{$Program->Id_Program}}" tabindex="-1" aria-labelledby="editProgramLabel{{$Program->Id_Program}}" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('editProgram', ['Id_Platform' => $Platform->Id_Platform, 'Id_Program' => $Program->Id_Program]) }}" method="POST">
                <div class="modal-content">
                    @csrf
                    @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title" id="editProgramLabel{{$Program->Id_Program}}">แก้ไขชื่อ Program</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="Name_Program{{$Program->Id_Program}}" class="col-form-label">ชื่อ Program :</label>
                                <input type="text" class="form-control" id="Name_Program_{{$Program->Id_Program}}" name="Name_Program" value="{{ $Program->Name_Program }}" placeholder="กรอกชื่อ Platform" required>
                            </div>

                            <div class="mb-3">
                                <label for="Name_Object{{$Program->Id_Program}}" class="col-form-label">ชื่อวัตถุประสงค์ :</label>
                                <input type="text" class="form-control" id="Name_Object_{{$Program->Id_Program}}" name="Name_Object" value="{{ $Program->Name_Object }}" placeholder="กรอกชื่อวัตถุประสงค์" required>
                            </div>

                            <div id="budget-years-container">
                                <div class="mb-3">
                                    <label for="Budget_Year_1{{$Program->Id_Program}}">ปีงบประมาณที่ 1:</label>
                                    <input type="number" class="form-control" id="Budget_Year_1" name="Budget_Year[]" 
                                    value="{{ $Program->budgetYears->sortBy('Budget_Year')->first()->Budget_Year ?? '' }}"  
                                    placeholder="กรอกปีงบประมาณ เช่น 2566" required>
                                </div>

                                <div class="mb-3">
                                    <label for="Budget_Year_2{{$Program->Id_Program}}">ปีงบประมาณที่ 2:</label>
                                    <input type="number" class="form-control" id="Budget_Year_2" name="Budget_Year[]" 
                                    value="{{ $Program->budgetYears->sortBy('Budget_Year')->skip(1)->first()->Budget_Year ?? '' }}" 
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

