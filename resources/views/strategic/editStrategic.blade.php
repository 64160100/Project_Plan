@extends('navbar.app')

@section('content')
    <div class="container mt-5">
        <h1>แก้ไขข้อมูลยุทธศาสตร์</h1>
        <form action="{{ route('strategic.update', $strategic->Id_Strategic) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="Name_Strategic_Plan">ชื่อแผนยุทธศาสตร์</label>
                <input type="text" class="form-control" id="Name_Strategic_Plan" name="Name_Strategic_Plan" value="{{ $strategic->Name_Strategic_Plan }}" required>
            </div>
            <div class="form-group">
                <label for="Goals_Strategic">เป้าหมายยุทธศาสตร์</label>
                <textarea class="form-control" id="Goals_Strategic" name="Goals_Strategic" required>{{ $strategic->Goals_Strategic }}</textarea>
            </div>
            <button type="submit" class="btn btn-success mt-3">บันทึก</button>
        </form>
    </div>
@endsection