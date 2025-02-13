@extends('navbar.app')

@section('content')
<div class="container">
    <h1>สร้างปีงบประมาณและไตรมาส</h1>
    <form action="{{ route('fiscalYearQuarter.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="Fiscal_Year">ปีงบประมาณ</label>
            <input type="number" name="Fiscal_Year" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="Quarter">ไตรมาส</label>
            <input type="number" name="Quarter" class="form-control" required min="1" max="4">
        </div>
        <button type="submit" class="btn btn-primary">สร้าง</button>
    </form>
</div>
@endsection