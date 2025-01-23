@extends('navbar.app')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/checkBudget.css') }}">
</head>
<body>
@section('content')
    <div class="header">
        <h1>ตรวจสอบงบประมาณ</h1>
        <button type="button" class="btn-editBudget" data-bs-toggle="modal" data-bs-target="#editBudget">
            <i class='bx bx-plus'></i>แก้งบประมาณ
        </button>
    </div><br>
   
        <div class="content-box">
            <div class="all-budget">
                <b>งบประมาณทั้งหมด ปี พ.ศ.2567</b>
                <div class="budget-amount">฿10,000,000</div>
            </div><br>

            <div class="content-box-list">
                <h4>รายการใช้งบประมาณล่าสุด</h4>
                <table class="project-table">
                    <thead>
                        <tr>
                            <th>โครงการ</th>
                            <th>ประเภทงบ</th>
                            <th>งบประมาณที่ใช้</th>
                            <th>การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($budgetProject as $budgetproject)
                        <tr>
                            <td>{{ $budgetproject->Name_Project }}</td>
                            <td><b>งบดำเนินงาน</b></td>
                            <td>฿80000</td>
                            <td>
                                <a href="{{ route('editProject', $budgetproject->Id_Project) }}" class="btn-editBudget">
                                    <i class='bx bx-edit'></i>ปรับแก้งบประมาณ
                                </a> 
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @include('ProjectAnalysis.editBudget')
        
        <div class="menu-footer">
            <div>แสดง {{ $budgetProject->firstItem() }} ถึง {{ $budgetProject->lastItem() }} จาก {{ $budgetProject->total() }} รายการ</div>
            <div class="pagination">
                @if ($budgetProject->onFirstPage())
                    <button class="pagination-btn" disabled>ก่อนหน้า</button>
                @else
                    <a href="{{ $budgetProject->previousPageUrl() }}" class="pagination-btn">ก่อนหน้า</a>
                @endif
        
                <span class="page-number">
                    <span id="currentPage">{{ $budgetProject->currentPage() }}</span>
                </span>
        
                @if ($budgetProject->hasMorePages())
                    <a href="{{ $budgetProject->nextPageUrl() }}" class="pagination-btn">ถัดไป</a>
                @else
                    <button class="pagination-btn" disabled>ถัดไป</button>
                @endif
            </div>
        </div>
    

@endsection
</body>
</html>