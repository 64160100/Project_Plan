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
  <h3 class="head-project">รายการโครงการ ปีงบประมาณ พ.ศ. 2567
    <form action="{{ route('addProject') }}" method="get" style="display: inline;">
      <a href="{{ route('addProject') }}" class="btn-add">
        <i class='bx bx-plus'></i>เพิ่มข้อมูล
      </a>
    </form>
  </h3>

@foreach ( $strategic as $Strategic )
<button class="accordion">{{ $Strategic->Name_Strategic_Plan }}</button>
  <div class="panel">
 
    @if ($Strategic->projects->isEmpty())
      <p>ไม่มีโครงการที่เกี่ยวข้อง</p>
    @else
        @foreach ($Strategic->projects as $Project)
        <ul>
          <li>{{ $Project->Name_Project }}<br></li>
        </ul>
        @endforeach
    @endif

  </div>

<script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    // if (panel) { // ตรวจสอบว่ามี panel ถัดไป
    if (panel.style.display === "block" || panel.style.display === "") {
      panel.style.display = "none";
    } else {
      panel.style.display = "block";
    }
    // panel.classList.toggle("show");
    
  });
}

</script>

@endforeach
@endsection

</body>
  
</html>
