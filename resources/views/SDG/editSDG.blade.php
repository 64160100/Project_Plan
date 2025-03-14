@foreach ( $sdg as $Sdg )

<div class="modal fade" id="editSdg{{$Sdg->id_SDGs}}" tabindex="-1" aria-labelledby="editSdgLabel{{$Sdg->id_SDGs}}" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('editSDG', $Sdg->id_SDGs) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSdgLabel{{$Sdg->id_SDGs}}">แก้ไขเป้าหมายการพัฒนา</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="Name_SDGs{{$Sdg->id_SDGs}}" class="col-form-label">ชื่อเป้าหมาย:</label>
                        <input type="text" class="form-control" id="Name_SDGs{{$Sdg->id_SDGs}}" name="Name_SDGs" value="{{ $Sdg->Name_SDGs }}">
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

