@extends('navbar.app')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlatformKpi</title>

    <link rel="stylesheet" href="{{ asset('css/platformkpi.css') }}">

</head>
<body>
@section('content')

<div class="container">
  <h5 class="head-project">
    <b>{{$platforms->Name_Platform}}</b>
  </h5>
  <button type="button" class="btn-add" data-bs-toggle="modal" data-bs-target="#addPlatformObj">
        <i class='bx bx-plus'></i>เพิ่มข้อมูล
  </button>
</div>
<br>

<div class="modal fade" id="addPlatformObj" tabindex="-1" aria-labelledby="addPlatformObjLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('createPlatformKpi',['Id_Platform' => $platforms->Id_Platform]) }}" method="POST">
                <div class="modal-content">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="addPlatformObjLabel">เพิ่มรายการ Kpi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="mb-3">
                                    <label for="Name_Platfrom_Kpi" class="col-form-label">ชื่อ Kpi :</label>
                                    <input type="text" class="form-control" id="Name_Platfrom_Kpi" name="Name_Platfrom_Kpi" placeholder="กรอกชื่อ Kpi" required>
                                </div>

                                <div class="mb-3">
                                    <!-- <label for="Description_Platfrom_Kpi" class="col-form-label">รายละเอียด Kpi :</label>
                                    <input type="text" class="form-control" id="Description_Platfrom_Kpi" name="Description_Platfrom_Kpi" placeholder="กรอกชื่อวัตถุประสงค์" required> -->
                                    
                                    <label for="Description_Platfrom_Kpi" class="col-form-label">รายละเอียด Kpi :</label>
                                    <textarea class="form-control" id="Description_Platfrom_Kpi" name="Description_Platfrom_Kpi" placeholder="กรอกรายละเอียด" required></textarea>
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
    <!-- end create modal -->


    
    
@endsection
</body>
</html>