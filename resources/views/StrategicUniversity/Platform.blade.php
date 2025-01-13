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
        <i class='bx bx-plus'></i>เพิ่ม Platform
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
                        <div class="mb-3">
                            <label for="Name_Platform" class="col-form-label">ชื่อ Platform :</label>
                            <input type="text" class="form-control" id="Name_Platform" name="Name_Platform" placeholder="กรอกชื่อ Platform" required>
                        </div>

                        <div class="mb-3">
                            <label for="Name_Object" class="col-form-label">ชื่อวัตถุประสงค์ :</label>
                            <input type="text" class="form-control" id="Name_Object" name="Name_Object" placeholder="กรอกชื่อวัตถุประสงค์" required>
                        </div>

                        <div id="budget-years-container">
                            <div class="mb-3">
                                <label for="Budget_Year_1">ปีงบประมาณที่ 1:</label>
                                <input type="number" class="form-control" id="Budget_Year_1" name="Budget_Year[]" placeholder="กรอกปีงบประมาณ เช่น 2566" required>
                            </div>
                            <div class="mb-3">
                                <label for="Budget_Year_2">ปีงบประมาณที่ 2:</label>
                                <input type="number" class="form-control" id="Budget_Year_2" name="Budget_Year[]" placeholder="กรอกปีงบประมาณ เช่น 2567" required>
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
    <!-- end modal -->

    @include('StrategicUniversity.editPlatform')

    @foreach ($platforms as $Platform)
       <!-- table header button -->
        <div class="bth-group">
            <button type="button" class="btn-addprogram" data-bs-toggle="modal" data-bs-target="#addProgram">
                <i class='bx bx-plus'></i>เพิ่ม Program
            </button>

            <button type="button" class="btn-add" data-bs-toggle="modal" data-bs-target="#addPlatformKpi{{$Platform->Id_Platform}}">
                <i class='bx bx-plus'></i>เพิ่ม KPI
            </button>

            <form action="{{ route('deletePlatform', $Platform->Id_Platform) }}" method="POST">
                @csrf
                @method('DELETE') 
                <button type="submit" class="btn-delete" onclick="return confirm('คุณต้องการลบ Platform นี้ใช่หรือไม่?')">
                    <i class='bx bx-trash'></i>ลบตาราง
                </button>
            </form>
        </div><br>

        <!-- modal -->
        @include('StrategicUniversity.showPlatformKpi')
        @include('StrategicUniversity.editPlatformKpi')

        <!-- table Platform -->
        <table style="width:100%">
            <tr>
                <th colspan="4">{{ $Platform->Name_Platform }}</th>
                <th rowspan="4">
                    <div class="bth-group">
                        <button type="button" class="btn-edit" data-bs-toggle="modal" data-bs-target="#editPlatform{{$Platform->Id_Platform}}" id="{{$Platform->Id_Platform}}">
                            <i class='bx bx-edit'></i>แก้ไข
                        </button>
                    </div>
                </th>
            </tr>
            <tr>
                <th colspan="4">{{ $Platform->Name_Object }}</th>
            </tr>
            <tr>
                <th colspan="2" rowspan="2">ผลสัมฤทธิ</th>
                <th colspan="2" class="center-text">ปีงบประมาณ</th>
            </tr>

            <tr class="center-text">
                @if($Platform->budgetYears->isNotEmpty())
                    @foreach($Platform->budgetYears as $budgetYear)
                        <th>{{ $budgetYear->Budget_Year }}</th>
                    @endforeach
                @else
                    <th colspan="2">ไม่มีข้อมูลปีงบประมาณ</th>
                @endif
            </tr>

            @foreach ($Platform->platformKpis as $PlatformKpi)
                <tr>
                    <td>{{ $PlatformKpi->Name_Platfrom_Kpi }}</td>
                    <td>{{ $PlatformKpi->Description_Platfrom_Kpi }}</td>

                    @foreach($Platform->budgetYears as $budgetYear)
                        @php
                            $platformYear = $PlatformKpi->platformYears()
                            ->where('Platform_Budget_Year_Id', $budgetYear->Id_Platform_Budget_Year)
                            ->where('Platform_Kpi_Id', $PlatformKpi->Id_Platform_Kpi)
                            ->first();
                        @endphp
                        <td>
                            @if($platformYear)
                                {{ $platformYear->Value_Platform }}
                            @else
                                -
                            @endif
                        </td>
                    @endforeach

                    <td>
                        <a href="{{ route('showPlatformKpi', ['Id_Platform' => $Platform->Id_Platform]) }}"></a>
                        <div class="bth-group">

                            <button type="button" class="btn-edit" data-bs-toggle="modal" data-bs-target="#editPlatformKpi{{$PlatformKpi->Id_Platform_Kpi}}" id="{{$PlatformKpi->Id_Platform_Kpi}}">
                                <i class='bx bx-edit'></i>แก้ไข
                            </button>
                            
                            <form action="{{ route('deletePlatformKpi', $PlatformKpi->Id_Platform_Kpi) }}" method="POST">
                                @csrf
                                @method('DELETE') 
                                <button type="submit" class="btn-delete" onclick="return confirm('คุณต้องการลบ KPI นี้ใช่หรือไม่?')">
                                    <i class='bx bx-trash'></i>ลบ
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>
            @endforeach
        </table>
        <!-- end table Platform -->
        <br><br>
        
        @include('StrategicUniversity.Program')
        @include('StrategicUniversity.editProgram')
        
        <hr>

    @endforeach

@endsection
</body>
</html>