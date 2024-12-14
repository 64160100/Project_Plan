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
  <h3 class="head-project"><b>รายการโครงการ ปีงบประมาณ พ.ศ. 2567</b>
    <form action="{{ route('showCreateForm') }}" style="display: inline;">
      <a href="{{ route('showCreateForm') }}" class="btn-add">
        <i class='bx bx-plus'></i>เพิ่มข้อมูล
      </a>
    </form>
  </h3>


  @foreach ( $strategics as $Strategic )
  <details class="accordion" id="{{ $Strategic->Id_Strategic }}">
    <summary class="accordion-btn">
    <b><a href="{{ route('viewProjectInStrategic', ['Id_Strategic' => $Strategic->Id_Strategic]) }}">
        {{ $Strategic->Name_Strategic_Plan }}</a></b> 
        

    </summary>
    <div class="accordion-content">
      <hr>
        <!-- <p> -->
          @if ($Strategic->projects->isEmpty())
            <p>ไม่มีโครงการที่เกี่ยวข้อง</p>
          @else
              @foreach ($Strategic->projects as $Project)
                <p><strong>{{ $Project->Name_Project }}</strong><br></p>
              @endforeach
          @endif
        <!-- </p> -->
    </div>
  </details>
  @endforeach
@endsection


</body>
  
</html>
