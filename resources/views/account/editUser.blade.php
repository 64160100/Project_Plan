@extends('navbar.app')
<!DOCTYPE html>
<html>

<head>
    <title>แก้ไขข้อมูลผู้ใช้งาน</title>
    <link rel="stylesheet" href="{{ asset('css/editUser.css') }}">
</head>

<body>
    @section('content')
    <h1>แก้ไขข้อมูลผู้ใช้งาน</h1>

    <!-- Form for editing employee information -->
    <div class="form-container">
        @csrf
        @method('PUT')

        <div class="form-row">
            <div class="form-group">
                <label for="Id_Employee">รหัสพนักงาน:</label>
                <input type="text" class="form-control" id="Id_Employee" name="Id_Employee"
                    value="{{ $employee->Id_Employee }}" disabled>
            </div>

            <div class="form-group">
                <label for="Fullname_Employee">ชื่อ-นามสกุล:</label>
                <input type="text" class="form-control" id="Fullname_Employee" name="Fullname_Employee"
                    value="{{ $employee->Firstname_Employee . ' ' . $employee->Lastname_Employee }}" disabled>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="Position">ตำแหน่ง:</label>
                <input type="text" class="form-control" id="Position" name="Position" 
                value="{{ $employee->position ? $employee->position->Name_Position : 'N/A' }}" disabled>
            </div>
            <div class="form-group">
                <label for="Department">ฝ่าย:</label>
                <input type="text" class="form-control" id="Department" name="Department"
                    value="{{ $employee->department ? $employee->department->Name_Department : 'N/A' }}" disabled>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="Email">อีเมล:</label>
                <input type="email" class="form-control" id="Email" name="Email" value="{{ $employee->Email }}"
                    disabled>
            </div>
            <div class="form-group">
                <label for="Password">รหัสผ่าน:</label>
                <input type="password" class="form-control" id="Password" name="Password"
                    value="{{ $employee->Password }}" disabled>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>สิทธิ์การเข้าถึง:</label>
                <ul style="list-style-type: none; padding-left: 0;">
                    @foreach($employee->permissions as $permission)
                    <div
                        style="border: 1px solid #ccc; padding: 5px; border-radius: 5px; margin-bottom: 5px; display: inline-block;">
                        {{ $permission->Name_Permission }}
                        <form
                            action="{{ route('account.removePermission', ['Id_Employee' => $employee->Id_Employee, 'Id_Permission' => $permission->Id_Permission]) }}"
                            method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="border: none; background: none; cursor: pointer;">
                                <i class='bx bx-x'></i>
                            </button>
                        </form>
                    </div>
                    @endforeach
                </ul>
                <form action="{{ route('account.updateUser', ['Id_Employee' => $employee->Id_Employee]) }}"
                    method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="Id_Employee" value="{{ $employee->Id_Employee }}">
                    <div class="form-group">
                        <label for="additionalPermissions">เลือกสิทธิ์เพิ่มเติม</label>
                        <select id="additionalPermissions" name="Id_Permission" class="form-control">
                            <option value="">เลือกสิทธิ์เพิ่มเติม</option>
                            @foreach($unassignedPermissions as $permission)
                            <option value="{{ $permission->Id_Permission }}">{{ $permission->Name_Permission }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">มอบสิทธิ์</button>
                </form>
            </div>

            <div class="form-group">
                <label>สิทธิ์ที่ได้รับ:</label>
                <ul>
                    @foreach($employee->permissions as $permission)
                    <div><i class='bx bx-check'></i> {{ $permission->Name_Permission }}</div>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endsection
</body>

</html>