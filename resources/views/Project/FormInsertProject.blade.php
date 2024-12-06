@extends('navbar.app')

<!DOCTYPE html>
<html lang="en">
<head>
    <title>FormInsertProject</title>
    <link rel="stylesheet" href="{{ asset('css/project.css') }}">
</head>
<body>
    @section('content')
        <h3 class="card-header">กรอกข้อมูลโครงการใหม่</h3>
        <form id="formAuthentication" class="mb-3" action="{{ route('addProject') }}" medthod="POST">
    
            <div class="mb-3">
            @csrf
                <label for="formGroupExampleInput" class="form-label">ชื่อโครงการ</label>
                <input type="text" class="form-control" id="formGroupExampleInput" placeholder="กรอกชื่อโครงการ" fdprocessedid="2hlulq">
            </div>

            <!-- resources/views/Project/FormInsertProject.blade.php -->
            <div class="mb-3 col-md-6">
                <label for="Id_Strategic" class="form-label">เลือกยุทธศาสตร์</label>
                <select 
                    class="form-select @error('Id_Strategic') is-invalid @enderror" 
                    id="Id_Strategic"
                    name="Id_Strategic"
                    required>
                    
                    <!-- Option แรกสำหรับเลือก -->
                    <option select>เลือกยุทธศาสตร์</option>

                    <!-- แสดงข้อมูลจากฐานข้อมูล -->
                    @foreach($strategic['Strategic'] as $Strategic)
                        <option value="{{ $Strategic->Id_Strategic }}"
                            {{ old('Id_Strategic') == $Strategic->Id_Strategic ? 'selected' : '' }}>
                            {{ $Strategic->Name_Strategic_Plan }}
                        </option>
                    @endforeach
                </select>
                @error('Id_Strategic')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <br><button type="submit" class="btn btn-primary me-2">บันทึก</button>
        </form>

        
    @endsection
</body>
</html>