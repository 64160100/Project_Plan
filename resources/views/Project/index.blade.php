@extends('navbar.app')
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Index</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="{{ asset('css/project.css') }}">
</style>
</head>
<body>
@section('content')


<div class="container-vp">
  <h3 class="head-project">
    <b>รายการโครงการ ปีงบประมาณ พ.ศ. 2567</b>
  </h3>
  
</div>
<br>

  @foreach ( $strategics as $Strategic )
  <details class="accordion" id="{{ $Strategic->Id_Strategic }}">
    <summary class="accordion-btn">
    <b><a href="{{ route('viewProjectInStrategic', ['Id_Strategic' => $Strategic->Id_Strategic]) }}">
        {{ $Strategic->Name_Strategic_Plan }}</a><br>จำนวนโครงการ : {{ $Strategic->project_count }} โครงการ</b>
        
        <a href="{{ route('showCreateProject', ['Strategic_Id' => $Strategic->Id_Strategic]) }}" class="btn-add">
          <i class='bx bx-plus'></i>เพิ่มโครงการ
        </a>
    </summary>
    <div class="accordion-content">
          @if ($Strategic->projects->isEmpty())
            <p>ไม่มีโครงการที่เกี่ยวข้อง</p>
          @else
              @foreach ($Strategic->projects as $Project)
                <li><strong>{{ $Project->Name_Project }}</strong></li>
              @endforeach
          @endif
    </div>
  </details>
  @endforeach
@endsection


</body>
  
</html>
