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
  @foreach ( $strategics as $Strategic )
    <h3 class="head-project">
      <b>{{ $Strategic->Id_Strategic->Name_Strategic_Plan }}</b>
    </h3>
    
    <details class="accordion">
      <summary class="accordion-btn">
     
      
      </summary>
    </details>
  @endforeach


@endsection



</body>
  
</html>
