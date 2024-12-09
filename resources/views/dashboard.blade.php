@extends('navbar.app')

@section('content')
<div class="container">
    <h1>ยินดีต้อนรับสู่ระบบติดตามแผนงาน</h1>

    <h1>Welcome, {{ session('employee') ? session('employee')->Firstname_Employee : 'Guest' }} {{ session('employee') ? session('employee')->Lastname_Employee : '' }}</h1>
    <p>Your permissions:</p>
    <ul>
        @foreach (session('permissions', []) as $permission)
        <li>{{ $permission->Name_Permission }}</li>
        @endforeach
    </ul>
</div>
@endsection