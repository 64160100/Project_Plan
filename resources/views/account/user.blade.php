@extends('navbar.app')
<!DOCTYPE html>
<html>

<head>
    <title>Employees</title>
    <link rel="stylesheet" href="{{ asset('css/employee.css') }}">
</head>

<body>
    @section('content')
    <h1>ข้อมูลพนักงาน</h1>
    <div class="mb-3">
    </div>
    <table class="table table-striped">
        <thead class="table-header">
            <tr>
                <th>รหัสพนักงาน</th>
                <th>ชื่อ-นามสกุล</th>
                <th>สิทธิ์การเข้าถึง</th>
                <th>การจัดการ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
            <tr>
                <td>{{ $employee->Id_Employee }}</td>
                <td>{{ $employee->Name_Employee }}</td>
                <td>
                    @foreach($employee->permissions as $permission)
                    {{ $permission->Name_Promission }}<br>
                    @endforeach
                </td>
                <td>
                    <form action="{{ route('employee.editUser', $employee->Id_Employee) }}" method="GET"
                        style="display:inline;">
                        <button type="submit" class="btn btn-warning">แก้ไข</button>
                    </form>
                    <form action="{{ route('employee.destroy', $employee->Id_Employee) }}" method="POST"
                        style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">ลบ</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endsection
</body>

</html>