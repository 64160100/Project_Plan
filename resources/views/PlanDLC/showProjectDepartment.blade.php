@extends('navbar.app')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>showProjectDepartment</title>
    <link rel="stylesheet" href="{{ asset('css/report.css') }}">
</head>
<body>
@section('content')
    <h1>{{ $department->Name_Department }}</h1>
    @foreach($projects as $Projects)
    <a href="#" class="project-status mt-2">
        <div class="project-group">
            <div class="project-info">
                <div class="status-icon">
                    <i class='bx bxs-circle'></i>
                </div>
                <div>
                    <b>{{ $Projects->Name_Project }}</b>
                    <div>ชื่อฝ่าย??</div>
                </div>
            </div>

            <div class="energy-container">
                <div class="energy-text" id="energy-text">70%</div>
                <div class="energy-bar-container">
                    <div class="energy-bar" id="energy-bar"></div>
                </div>
            </div>
        </div>
    </a>
    @endforeach
@endsection
    
</body>
</html>