@extends('navbar.app')
<link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="{{ asset('css/button.css') }}">

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center">
        <h1>แผนยุทธศาสตร์</h1>
        <a href="#" class='btn-add' data-bs-toggle="modal" data-bs-target="#ModalAddStrategic">เพิ่มข้อมูล</a>
    </div>

    <form action="{{ route('strategic.index') }}" method="GET" class="mt-3">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="Fiscal_Year">ปีงบประมาณ</label>
                    <select name="Fiscal_Year" id="Fiscal_Year" class="form-control">
                        <option value="">เลือกปีงบประมาณ</option>
                        @foreach($fiscalYears as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="Quarter">ไตรมาส</label>
                    <select name="Quarter" id="Quarter" class="form-control">
                        <option value="">เลือกไตรมาส</option>
                        <option value="1">ไตรมาส 1</option>
                        <option value="2">ไตรมาส 2</option>
                        <option value="3">ไตรมาส 3</option>
                        <option value="4">ไตรมาส 4</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">ค้นหา</button>
            </div>
        </div>
    </form>

    @if ($strategic->isEmpty())
        <div class='card p-3 m-3'>ไม่พบข้อมูลแผนยุทธศาสตร์</div>
    @else
        @foreach ($strategic as $Strategic)
        <div class='card p-3 m-3'>
            <h3>
                <a href="{{ route('strategy.index', $Strategic->Id_Strategic) }}">{{ $Strategic->Name_Strategic_Plan }}</a>
            </h3>
            {{ $Strategic->Goals_Strategic }}

            <hr>
            <div class="d-flex ms-auto">
                <a href="#" class='bx bx-edit-alt' style='color:#bd7ff9; font-size: 20px;' data-bs-toggle="modal" data-bs-target="#ModalEditStrategic{{ $Strategic->Id_Strategic }}"></a>
                <form action="{{ route('strategic.destroy', $Strategic->Id_Strategic) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class='bx bx-trash' style='color:#bd7ff9; font-size: 20px; background:none; border:none; cursor:pointer;' onclick="return confirm('คุณยืนยันที่จะลบข้อมูลนี้หรือไม่');"></button>
                </form>
            </div>
        </div>
        @endforeach
    @endif
</div>
@include('strategic.addStrategic')
@include('strategic.editStrategic')
@endsection