@extends('navbar.app')
<link rel="stylesheet" href="{{ asset('css/fiscalYearQuarter.css') }}">


@section('content')
<div class="container">
    <div class="d-flex justify-content-start align-items-center">
        <a href="{{ route('fiscalYearQuarter.index') }}" class="back-btn">
            <i class='bx bxs-left-arrow-square'></i>
        </a>
        <h1 class="ms-3">สร้างปีงบประมาณและไตรมาส</h1>
    </div>
    <form action="{{ route('fiscalYearQuarter.store') }}" method="POST">
        @csrf
        <div class="card p-3 mt-3">
            <div class="form-group">
                <label for="Fiscal_Year">ปีงบประมาณ</label>
                <input type="number" name="Fiscal_Year" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="Quarter">ไตรมาส</label>
                <input type="number" name="Quarter" class="form-control" required min="1" max="4">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">สร้าง</button>
    </form>
</div>
@endsection