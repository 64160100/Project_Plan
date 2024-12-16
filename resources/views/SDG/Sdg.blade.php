@extends('navbar.app')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/sdg.css') }}">


</head>
<body>
@section('content')
    <div class="container">
        <h3 class="head-project">
            <b>เป้าหมายการพัฒนาที่ยั่งยืน (SDGs)</b>
        </h3>
        <button type="button" class="btn-add" data-bs-toggle="modal" data-bs-target="#addSdg">
            <i class='bx bx-plus'></i>เพิ่มข้อมูล
        </button>
    </div>
    <br>

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

    <table id="sdg">
    <tr>
        <th>ลำดับ</th>
        <th>ชื่อเป้าหมาย</th>
        <th>จัดการ</th>
    </tr>
    @foreach ( $sdg as $Sdg )
    <tr>
        <td>{{$Sdg->id_SDGs}}</td>
        <td>{{$Sdg->Name_SDGs}}</td>
        <td>
        <button type="button" class="btn-edit" data-bs-toggle="modal" data-bs-target="#editSdg{{$Sdg->id_SDGs}}" id="{{$Sdg->id_SDGs}}">
            <i class='bx bx-edit'></i>แก้ไข
        </button>
        <!-- <button type="button" class="btn-delete"><i class='bx bx-trash'></i>ลบ</button> -->
        <form action="{{ route('deleteSDG', $Sdg->id_SDGs) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE') 
            <button type="submit" class="btn-delete" onclick="return confirm('คุณต้องการลบเป้าหมายการพัฒนานี้ใช่หรือไม่?')">
                <i class='bx bx-trash'></i>ลบ
            </button>
        </form>
        </td>
    </tr>


    @endforeach

    </table>
@endsection    
</body>
</html>