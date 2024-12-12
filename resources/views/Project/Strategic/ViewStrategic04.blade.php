@extends('navbar.app')
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ViewProject01</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="{{ asset('css/project.css') }}">
</style>
</head>
<body>
@section('content')
  <h3 class="head-project">
    <b>ยุทศาสตร์ที่ 4</b>
  </h3>
  
  <details class="accordion">
    <summary class="accordion-btn">
    <b><a href="{{ route('viewProject') }}">
        โครงการที่ 1</a></b>
    
    </summary>
  </details>


@endsection



</body>
  
</html>
