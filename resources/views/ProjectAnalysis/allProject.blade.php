@extends('navbar.app')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/allProject.css') }}">
</head>
<body>
@section('content')
<h1>โครงการทั้งหมด</h1>
<div class="content-box">
    @foreach($allProject as $allproject)
    <div class="content-box-list mb-2 mt-2">
        <div class="project-group">
            <div class="project-info">
                <h5>
                    <b>{{$allproject->Name_Project}}</b><br>
                </h5>
            </div>
            <a href="{{ route('PDF.projectCtrlP', $allproject->Id_Project) }}" class="viewproject">ดูเอกสารโครงการ<i class='bx bx-right-arrow-alt icon'></i></a>
        </div>
        <p class="project-department">{{ $allproject->employee->department->Name_Department }}</p>

        <div class="project-group">
            <p>เอกสารทั้งหมด</p>
            <p>count = 3</p>
        </div>

        <div class="project-group">
                <p>อัพเดตล่าสุด</p>
                <p>01/01/2568 (timestamp)</p>
        </div>
    </div>
    @endforeach
</div>

    <div class="menu-footer">
        <div>แสดง {{ $allProject->firstItem() }} ถึง {{ $allProject->lastItem() }} จาก {{ $allProject->total() }} รายการ</div>
        <div class="pagination">
            @if ($allProject->onFirstPage())
                <button class="pagination-btn" disabled>ก่อนหน้า</button>
            @else
                <a href="{{ $allProject->previousPageUrl() }}" class="pagination-btn">ก่อนหน้า</a>
            @endif
    
            <span class="page-number">
                <span id="currentPage">{{ $allProject->currentPage() }}</span>
            </span>
    
            @if ($allProject->hasMorePages())
                <a href="{{ $allProject->nextPageUrl() }}" class="pagination-btn">ถัดไป</a>
            @else
                <button class="pagination-btn" disabled>ถัดไป</button>
            @endif
        </div>
    </div>
@endsection 
</body>
</html>