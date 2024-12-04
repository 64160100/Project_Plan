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
        <form action="{{ route('account.updateUser', $employee->Id_Employee) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-group">
                    <label for="Id_Employee">รหัสพนักงาน:</label>
                    <input type="text" class="form-control" id="Id_Employee" name="Id_Employee"
                        value="{{ $employee->Id_Employee }}" disabled>
                </div>
                <div class="form-group">
                    <label for="Name_Employee">ชื่อ-นามสกุล:</label>
                    <input type="text" class="form-control" id="Name_Employee" name="Name_Employee"
                        value="{{ $employee->Name_Employee }}" disabled>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="Position">ตำแหน่ง:</label>
                    <input type="text" class="form-control" id="Position" name="Position"
                        value="{{ $employee->Position }}" disabled>
                </div>
                <div class="form-group">
                    <label for="Department">ฝ่าย:</label>
                    <input type="text" class="form-control" id="Department" name="Department"
                        value="{{ $employee->Department }}" disabled>
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
                        <div>
                            {{ $permission->Name_Promission }}
                            <i class='bx bx-x' style="cursor: pointer;"
                                onclick="removePermission({{ $permission->id }})"></i>
                        </div>
                        @endforeach
                    </ul>
                    <select id="additionalPermissions" class="form-control">
                        <option value="">เลือกสิทธิ์เพิ่มเติม</option>
                        @foreach($unassignedPermissions as $permission)
                        <option value="{{ $permission->id }}">{{ $permission->Name_Promission }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>สิทธิ์ที่ได้รับ:</label>
                    <ul>
                        @foreach($employee->permissions as $permission)
                        <div><i class='bx bx-check'></i> {{ $permission->Name_Promission }}</div>
                        @endforeach
                    </ul>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
        </form>
    </div>
    @endsection
</body>

</html>