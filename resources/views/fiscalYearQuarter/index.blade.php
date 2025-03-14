@extends('navbar.app')
<link rel="stylesheet" href="{{ asset('css/fiscalYearQuarter.css') }}">


@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex justify-content-start align-items-center">
            <a href="{{ route('setting') }}" class="back-btn">
                <i class='bx bxs-left-arrow-square'></i>
            </a>
            <h1 class="ms-3">ปีงบประมาณและไตรมาส</h1>
        </div>
        <form action="{{ route('fiscalYearQuarter.create') }}" method="GET">
            <button class="btn-add">สร้างปีงบประมาณ</button>
        </form>
    </div>
    
    <table class="table table-striped">
        <thead class="table-header">
            <tr>
                <th>ลำดับ</th>
                <th>ปีงบประมาณ</th>
                <th>ไตรมาส</th>
                <th>การดำเนินการ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fiscalYearsAndQuarters as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->Fiscal_Year }}</td>
                <td>{{ $item->Quarter }}</td>
                <td>
                    <div class="btn-manage">
                        <form action="{{ route('fiscalYearQuarter.edit', $item->Id_Quarter_Project) }}" method="GET">
                            <button class="btn-edit">
                                <i class='bx bx-edit'></i>&nbsp;แก้ไข
                            </button>
                        </form>
                        <form action="{{ route('fiscalYearQuarter.destroy', $item->Id_Quarter_Project) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete " onclick="return confirm('คุณยืนยันที่จะลบข้อมูลนี้หรือไม่');">
                                <i class='bx bx-trash'></i>&nbsp;ลบ
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection