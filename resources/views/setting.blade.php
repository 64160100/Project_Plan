@extends('navbar.app')

<head>
    <link rel="stylesheet" href="{{ asset('css/setting.css') }}">
</head>

@section('content')
<div class="container">
    <h1>ตั้งค่าระบบ</h1>
    <p class="text-style">จัดการตั้งค่าทั้งหมดของระบบ</p>

    <a href="{{ route('strategic.index') }}" class="link-style">
        <div class="card-style">
            <div class="icon-style">
                <i class='bx bx-target-lock'></i>
            </div>
            <div>
                <h2>ตั้งค่าแผนยุทธศาสตร์และกลยุทธ์</h2>
                <p class="text-style">กำหนดข้อมูลยุทธศาสตร์และกลยุทธ์</p>
            </div>
        </div>
    </a>

    <a href="#" class="link-style">
        <div class="card-style">
            <div class="icon-style">
                <i class='bx bx-world'></i> 
            </div>
            <div>
                <h2>ตั้งค่าบริบทเชิงกลยุทธ์</h2>
                <p class="text-style">กำหนดปัจจัยแวดล้อมที่เกี่ยวข้องกับการดำเนินกลยุทธ์</p>
            </div>
        </div>
    </a>

    <a href="{{ route('showSdg') }}" class="link-style">
        <div class="card-style">
            <div class="icon-style">
                <i class='bx bx-leaf'></i> 
            </div>
            <div>
                <h2>ตั้งค่าเป้าหมายการพัฒนาที่ยั่งยืน</h2>
                <p class="text-style">กำหนดเป้าหมายการพัฒนาองค์กรในระยะยาว</p>
            </div>
        </div>
    </a>

    <a href="#" class="link-style">
        <div class="card-style">
            <div class="icon-style">
                <i class='bx bxs-school'></i> 
            </div>
            <div>
                <h2>ตั้งค่ายุทธศาสตร์มหาลัย</h2>
                <p class="text-style">กำหนดยุทธศาสตร์ให้สอดคล้องกับแผนมหาวิทยาลัย</p>
            </div>
        </div>
    </a>
</div>
@endsection