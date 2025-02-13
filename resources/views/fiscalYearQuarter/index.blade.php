@extends('navbar.app')

@section('content')
<div class="container">
    <h1>ปีงบประมาณและไตรมาส</h1>
    <a href="{{ route('fiscalYearQuarter.create') }}" class="btn btn-primary">สร้างปีงบประมาณ</a>
    <table class="table mt-3">
        <thead>
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
                    <a href="{{ route('fiscalYearQuarter.edit', $item->Id_Quarter_Project) }}" class="btn btn-warning btn-sm">แก้ไข</a>
                    <form action="{{ route('fiscalYearQuarter.destroy', $item->Id_Quarter_Project) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');">ลบ</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection