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
                    <div style="display: flex; flex-wrap: nowrap; gap: 10px;">
                        @foreach($employee->permissions as $permission)
                        <div style="border: 1px solid purple; padding: 5px; border-radius: 5px;">
                            {{ $permission->Name_Promission }}
                        </div>
                        @endforeach
                    </div>
                </td>
                <td>
                    <form action="{{ route('account.editUser', $employee->Id_Employee) }}" method="GET"
                        style="display:inline;">
                        <button type="submit" class="btn btn-warning">แก้ไข</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endsection
</body>

</html>