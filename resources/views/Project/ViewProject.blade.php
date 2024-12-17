@extends('navbar.app')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ViewProject</title>
    <link rel="stylesheet" href="{{ asset('css/project.css') }}">

</head>
<body>
@section('content')

    <div class="container">
        <h3 class="head-project">
            <b>รายละเอียดโครงการ : {{ $projects->Name_Project }}</b>
        </h3>
        <a href="{{ route('editProject', ['Id_Project' => $projects->Id_Project]) }}" class="btn-edit">
            <i class='bx bx-edit'></i></i> แก้ไข
        </a>
    </div>

    <br><b><p>หน้าฟอร์มโครงการ</p></b>
    



    
    
        
      

@endsection
</body>
</html>