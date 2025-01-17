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
    <div class="content-box-list">
        <!-- <h5><b>ผลการดำเนินงานแยกตามฝ่าย</b></h5> -->
        <div class="project-group">
            <div class="project-info">
                <h5>
                    <b>โครงการพัฒนาระบบติดตามแผนงาน</b><br>
                </h5>
            </div>
            <a href="#" class="viewproject" >ดูเอกสารโครงการ<i class='bx bx-right-arrow-alt icon'></i></a>
        </div>
        <p class="project-department">ฝ่ายบริการสารสนเทศและนวัตกรรมการเรียนรู้</p>

        <div class="project-group">
            <p>เอกสารทั้งหมด</p>
            <p>3</p>
        </div>

        <div class="project-group">
                <p>อัพเดตล่าสุด</p>
                <p>01/01/2568</p>
        </div>
    </div>
</div>

    <div class="menu-footer">
    <div>แสดง 1 ถึง 5 จาก 5 รายการ</div>
        <div class="pagination">
            <button class="pagination-btn" id="prevBtn">ก่อนหน้า</button>
            <span class="page-number"><span id="currentPage">1</span></span>
            <button class="pagination-btn" id="nextBtn">ถัดไป</button>
        </div>
    </div>
@endsection 
</body>
</html>