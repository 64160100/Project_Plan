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


<div class="container">
  <h3 class="head-project">
    <b>รายการโครงการ ปีงบประมาณ พ.ศ. 2567</b>
  </h3>
  <a href="{{ route('showCreateProject') }}" class="btn-add">
    <i class='bx bx-plus'></i>เพิ่มข้อมูล
  </a>
</div>

  @foreach ( $strategics as $Strategic )
  <details class="accordion" id="{{ $Strategic->Id_Strategic }}">
    <summary class="accordion-btn">
    <b><a href="{{ route('viewProjectInStrategic', ['Id_Strategic' => $Strategic->Id_Strategic]) }}">
        {{ $Strategic->Name_Strategic_Plan }}</a></b> 
        

    </summary>
    <div class="accordion-content">
      <hr>
          @if ($Strategic->projects->isEmpty())
            <p>ไม่มีโครงการที่เกี่ยวข้อง</p>
          @else
              @foreach ($Strategic->projects as $Project)
                <p><strong>{{ $Project->Name_Project }}</strong><br></p>
              @endforeach
              
              @foreach ($Project->sdgs as $Sdgs)
                <p>{{ $Sdgs->Name_SDGs }}</p>
              @endforeach
          @endif
    </div>
  </details>
  @endforeach
@endsection


</body>
  
</html>
