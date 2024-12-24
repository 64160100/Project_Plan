@foreach ( $platforms as $Platform )

<div class="modal fade" id="editPlatform{{$Platform->Id_Platform}}" tabindex="-1" aria-labelledby="editPlatformLabel{{$Platform->Id_Platform}}" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('editPlatform', $Platform->Id_Platform) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPlatformLabel{{$Platform->Id_Platform}}">เพิ่มชื่อ Platform</h5>
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


