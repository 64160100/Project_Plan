@extends('navbar.app')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/strategicUni.css') }}">
    

</head>
<body>
@section('content')

<div class="container">
    <h3 class="head-project">
        <b>แผนยุทธศาสตร์มหาวิทยาลัยบูรพา <br>
            ประจำปีงบประมาณ พ.ศ. 2564 – 2567 (ฉบับปรับปรุง พ.ศ. 2566 – 2567)</b>
    </h3>
    <button type="button" class="btn-add" data-bs-toggle="modal" data-bs-target="#addPlatform">
        <i class='bx bx-plus'></i>เพิ่มข้อมูล
    </button>
</div>
<br> 

<!-- modal create -->
    <div class="modal fade" id="addPlatform" tabindex="-1" aria-labelledby="addPlatformLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('createPlatform') }}" method="POST">
                <div class="modal-content">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="addPlatformLabel">เพิ่มชื่อ Platform</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="mb-3">
                                    <label for="Name_Platform" class="col-form-label">ชื่อ Platform :</label>
                                    <input type="text" class="form-control" id="Name_Platform" name="Name_Platform" placeholder="กรอกชื่อ Platform" required>
                                </div>

                                <div class="mb-3">
                                    <label for="Name_Object" class="col-form-label">ชื่อวัตถุประสงค์ :</label>
                                    <input type="text" class="form-control" id="Name_Object" name="Name_Object" placeholder="กรอกชื่อวัตถุประสงค์" required>
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

    @include('StrategicUniversity.editPlatform')


    @foreach ($platforms as $Platform)
        <details class="accordion">
            <summary class="accordion-btn">
                <div class="platform-container">
                    <a href="{{ route('showPlatformObj', $Platform->Id_Platform) }}"><b>{{$Platform->Name_Platform}}</b> </a>
                    <div class="bth-group">
                        <button type="button" class="btn-edit" data-bs-toggle="modal" data-bs-target="#editPlatform{{$Platform->Id_Platform}}" id="{{$Platform->Id_Platform}}">
                            <i class='bx bx-edit'></i>แก้ไข
                        </button>
                        <form action="{{ route('deletePlatform', $Platform->Id_Platform) }}" method="POST">
                            @csrf
                            @method('DELETE') 
                            <button type="submit" class="btn-delete" onclick="return confirm('คุณต้องการลบ Platform นี้ใช่หรือไม่?')">
                                <i class='bx bx-trash'></i>ลบ
                            </button>
                        </form>
                    </div>
                </div>   
            </summary>
            
            <div class="accordion-content">
                <li>{{$Platform->Name_Object}}</li> 
            </div>
        </details>
    @endforeach

 
@endsection
</body>
</html>