@extends('navbar.app')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เป้าหมายการพัฒนาที่ยั่งยืน</title>
    <link rel="stylesheet" href="{{ asset('css/sdg.css') }}">


</head>
<body>
@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('setting') }}" class="back-btn">
                <i class='bx bxs-left-arrow-square'></i>
            </a>
            <h1 class="ms-3">เป้าหมายการพัฒนาที่ยั่งยืน (Sustainable Development Goals: SDGs)</h1>
            <button type="button" class="btn-add" data-bs-toggle="modal" data-bs-target="#addSdg">
                เพิ่มข้อมูล
            </button>
        </div>
        
        <div>
            <table class="table table-striped">
                <thead class="table-header">
                    <tr>
                        <th>ลำดับ</th>
                        <th>ชื่อเป้าหมาย</th>
                        <th>การจัดการ</th>
                    </tr>
                </thead>
                @foreach ( $sdg as $Sdg )
                    <tr>
                        <td>{{$Sdg->id_SDGs}}</td>
                        <td>{{$Sdg->Name_SDGs}}</td>
                        <td class="btn-manage">
                            <button type="button" class="btn-edit" data-bs-toggle="modal" data-bs-target="#editSdg{{$Sdg->id_SDGs}}" id="{{$Sdg->id_SDGs}}">
                                <i class='bx bx-edit'></i>&nbsp;แก้ไข
                            </button>
                            <form action="{{ route('deleteSDG', $Sdg->id_SDGs) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE') 
                                <button type="submit" class="btn-delete" onclick="return confirm('คุณต้องการลบเป้าหมายการพัฒนา(SDGs)นี้ใช่หรือไม่?')">
                                    <i class='bx bx-trash'></i>&nbsp;ลบ
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>

    <!-- modal create -->
    <div class="modal fade" id="addSdg" tabindex="-1" aria-labelledby="addSdgLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('createSDG') }}" method="POST">
                <div class="modal-content">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="addSdgLabel">เพิ่มเป้าหมายการพัฒนา </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form>
                            <div class="mb-3">
                                <label for="Name_SDGs" class="col-form-label">ชื่อเป้าหมาย :</label>
                                <input type="text" class="form-control" id="Name_SDGs" name="Name_SDGs" placeholder="กรอกชื่อเป้าหมาย" required>
                            </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                        </div>
                </div>
            </form>
        </div>
    </div>
    <!-- end modal -->
    @include('SDG.editSDG')
    

    
@endsection    
</body>
</html>