@extends('navbar.app')

@section('content')
    <div class="container">
        <h1>ยินดีต้อนรับสู่ระบบติดตามแผนงาน</h1>

        <h2>ข้อมูลยุทธศาสตร์</h2>
        <ul>
            @foreach($strategic as $item)
                <li>
                    <strong>{{ $item->Name_Strategic_Plan }}</strong><br>
                    {{ $item->Goals_Strategic }}<br>
                    <a href="{{ route('strategic.edit', $item->Id_Strategic) }}" class="btn btn-primary btn-sm">แก้ไข</a>
                </li>
            @endforeach
        </ul>
    </div>
@endsection