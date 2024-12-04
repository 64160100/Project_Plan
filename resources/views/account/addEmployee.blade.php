@extends('navbar.app')
<!DOCTYPE html>
<html>

<head>
    <title>Employees</title>
    <link rel="stylesheet" href="{{ asset('css/employee.css') }}">
</head>

<body>
    @section('content')
    <div class="container mt-5">
        <h1>เพิ่มพนักงานใหม่</h1>
        <form action="{{ route('employees.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="Id_Employee">รหัสพนักงาน</label>
                <input type="text" class="form-control" id="Id_Employee" name="Id_Employee" required>
            </div>
            <div class="form-group">
                <label for="Name_Employee">ชื่อ-นามสกุล</label>
                <input type="text" class="form-control" id="Name_Employee" name="Name_Employee" required>
            </div>
            <div class="form-group">
                <label for="Email">อีเมล</label>
                <input type="email" class="form-control" id="Email" name="Email" required>
            </div>
            <div class="form-group">
                <label for="Password">รหัสผ่าน</label>
                <input type="password" class="form-control" id="Password" name="Password" required>
            </div>
            <button type="submit" class="btn btn-success mt-3">บันทึก</button>
        </form>
    </div>
    @endsection
</body>

</html>