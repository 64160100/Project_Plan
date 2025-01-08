@extends('navbar.app')

<head>
    <link rel="stylesheet" href="{{ asset('css/statusDetails.css') }}">
</head>

@section('content')

<div class="container">
    <h1 class="page-title">รายละเอียดโครงการ: {{ $project->Name_Project }}</h1>

    <div class="steps">
        <div class="step {{ $project->Count_Steps >= 0 ? 'completed' : 'pending' }}">
            <div class="step-icon">{{ $project->Count_Steps >= 0 ? '✓' : '○' }}</div>
            <span>เสนอโครงการ</span>
        </div>
        <div class="step {{ $project->Count_Steps >= 1 ? 'completed' : 'pending' }}">
            <div class="step-icon">{{ $project->Count_Steps >= 1 ? '✓' : '○' }}</div>
            <span>ผู้บริหารอนุมัติโครงการ</span>
            <div class="step-condition">---> ไม่อนุมัติโครงการ</div>
        </div>
        <div class="step {{ $project->Count_Steps >= 2 ? 'completed' : 'pending' }}">
            <div class="step-icon">{{ $project->Count_Steps >= 2 ? '✓' : '○' }}</div>
            <span>กรอกข้อมูลเพิ่มเติม</span>
        </div>
        <div class="step {{ $project->Count_Steps >= 3 ? 'completed' : 'pending' }}">
            <div class="step-icon">{{ $project->Count_Steps >= 3 ? '✓' : '○' }}</div>
            <span>เสนอโครงการ</span>
        </div>
        <div class="step {{ $project->Count_Steps >= 4 ? 'completed' : 'pending' }}">
            <div class="step-icon">{{ $project->Count_Steps >= 4 ? '✓' : '○' }}</div>
            <span>งบประมาณ</span>
            <div class="step-condition">---> เงือนไข ใช้อยู่ที่ Step 2-->3 หรือไม่ ใช้ข้ามไป Step 2-->4</div>
            <div class="step-condition">---> ไม่อนุมัติโครงการ</div>
        </div>
        <div class="step {{ $project->Count_Steps >= 5 ? 'completed' : 'pending' }}">
            <div class="step-icon">{{ $project->Count_Steps >= 5 ? '✓' : '○' }}</div>
            <span>หัวหน้าฝ่าย</span>
            <div class="step-condition">---> ไม่อนุมัติโครงการ</div>
        </div>
        <div class="step {{ $project->Count_Steps >= 6 ? 'completed' : 'pending' }}">
            <div class="step-icon">{{ $project->Count_Steps >= 6 ? '✓' : '○' }}</div>
            <span>ผู้บริหาร</span>
            <div class="step-condition">---> ไม่อนุมัติโครงการ</div>
        </div>
        <div class="step {{ $project->Count_Steps >= 7 ? 'completed' : 'pending' }}">
            <div class="step-icon">{{ $project->Count_Steps >= 7 ? '✓' : '○' }}</div>
            <span>รายงานผลการดำเนินงาน</span>
            <div class="step-condition">---> ตรงตามกำหนดเวลาหรือไม่</div>
            <div class="step-condition">---> รายงาน/ยืนยันผลการดำเนินงาน</div>
        </div>
        <div class="step {{ $project->Count_Steps >= 8 ? 'completed' : 'pending' }}">
            <div class="step-icon">{{ $project->Count_Steps >= 8 ? '✓' : '○' }}</div>
            <span>หัวหน้าฝ่าย</span>
            <div class="step-condition">---> ไม่อนุมัติโครงการ</div>
        </div>
        <div class="step {{ $project->Count_Steps >= 9 ? 'completed' : 'pending' }}">
            <div class="step-icon">{{ $project->Count_Steps >= 9 ? '✓' : '○' }}</div>
            <span>ผู้บริหาร</span>
            <div class="step-condition">---> ไม่อนุมัติโครงการ</div>
        </div>
    </div>
</div>
@endsection