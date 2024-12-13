@extends('navbar.app')
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ViewProjectInStrategic</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="{{ asset('css/project.css') }}">
</style>
</head>
<body>
@section('content')
    <h3 class="head-project">
        <b>{{ $strategics->Name_Strategic_Plan }}</b>
    </h3>
  

    @foreach ($strategics->projects as $Project)
        <details class="accordion">
            <summary class="accordion-btn">
            <b><a href="{{ route('viewProject', ['Id_Project' => $Project->Id_Project]) }}">
                {{ $Project->Name_Project }}
                </a></b>
            </summary>
        </details>
    @endforeach

@endsection



</body>
  
</html>
