@extends('navbar.app')

@section('content')
<div class="container">
    <h1>แก้ไขปีงบประมาณและไตรมาส</h1>
    <form action="{{ route('fiscalYearQuarter.update', $fiscalYearQuarter->Id_Quarter_Project) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="Fiscal_Year">ปีงบประมาณ</label>
            <input type="number" name="Fiscal_Year" class="form-control" value="{{ $fiscalYearQuarter->Fiscal_Year }}" required>
        </div>
        <div class="form-group">
            <label for="Quarter">ไตรมาส</label>
            <input type="number" name="Quarter" class="form-control" value="{{ $fiscalYearQuarter->Quarter }}" required min="1" max="4">
        </div>
        <button type="submit" class="btn btn-primary">อัปเดต</button>
    </form>
</div>
@endsection