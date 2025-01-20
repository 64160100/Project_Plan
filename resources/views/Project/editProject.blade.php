@extends('navbar.app')

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Project</title>
    <link rel="stylesheet" href="{{ asset('css/listproject.css') }}">
</head>
<body>
    @section('content')
    <h3 class="card-header">แก้ไขโครงการ : {{ $project->Name_Project }}</h3><br>
    <form action="{{ route('projects.update', ['id' => $project->Id_Project]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="content-box">
            <label for="Strategic_Id">รหัสยุทธศาสตร์:</label>
            <input type="number" class="form-control" id="Strategic_Id" name="Strategic_Id"
                value="{{ $project->Strategic_Id }}" required>
        </div><br>

        <div class="content-box">
            <label for="Name_Project">ชื่อโครงการ:</label>
            <input type="text" class="form-control" id="Name_Project" name="Name_Project"
                value="{{ $project->Name_Project }}" required>
        </div><br>

        <div class="content-box">
            <label for="Count_Steps">จำนวนขั้นตอน:</label>
            <input type="number" class="form-control" id="Count_Steps" name="Count_Steps"
                value="{{ $project->Count_Steps }}">
        </div><br>

        <div class="content-box">
            <label for="Employee_Id">รหัสพนักงาน:</label>
            <input type="number" class="form-control" id="Employee_Id" name="Employee_Id"
                value="{{ $project->Employee_Id }}" required>
        </div><br>

        <div class="container">
            <button type="submit" class="btn-submit">บันทึก</button>
        </div>
    </form>
    @endsection
</body>
</html>