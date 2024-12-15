@extends('navbar.app')
<!DOCTYPE html>
<html>

<head>
    <title>Employees</title>
    <link rel="stylesheet" href="{{ asset('css/employee.css') }}">
</head>

<body>
    @section('content')
    @foreach ($permissions as $permission)
    <h1>ข้อมูลพนักงาน</h1>
    <div class="mb-3">
        <!-- <a href="{{ route('account.create') }}" class="btn btn-success">เพิ่มพนักงาน</a> -->
    </div>
    <table class="table table-striped">
        <thead class="table-header">
            <tr>
                <th>รหัสพนักงาน</th>
                <th>ชื่อ</th>
                <th>นามสกุล</th>
                <th>ตำแหน่ง</th>
                <th>ฝ่าย</th>
                <th>การจัดการ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employees as $employee)
            <tr>
                <td>{{ $employee->Id_Employee }}</td>
                <td>{{ $employee->Firstname_Employee }}</td>
                <td>{{ $employee->Lastname_Employee }}</td>
                <td>{{ $employee->Position }}</td>
                <td>{{ $employee->Department }}</td>
                <td>
                    <a href="{{ route('account.showemployee', $employee->Id_Employee) }}" class="btn btn-primary">
                        ดูรายละเอียด
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endsection
    @endforeach

</body>

</html>