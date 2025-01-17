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
        <a href="#" class="btn-editBudget">
            <i class='bx bx-edit'></i>ปรับแก้งบประมาณ
        </a> 
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

                        <tr>
                            <td>โครงการพัฒนาระบบติดตามแผนงาน</td>
                            <td><b>งบดำเนินงาน</b></td>
                            <td>฿80000</td>
                            <td>
                            <a href="#" class="btn-editBudget">
                                <i class='bx bx-edit'></i>แก้งบประมาณ
                            </a>
                            </td>
                        </tr>

                    </tbody>
                </table>
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

<!-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const currentPageSpan = document.getElementById('currentPage');
        const totalPagesSpan = document.getElementById('totalPages');

        let currentPage = 1;
        const totalPages = 5; // สมมติว่ามี 3 หน้า

        updatePageDisplay();

        prevBtn.addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                updatePageDisplay();
            }
        });

        nextBtn.addEventListener('click', function() {
            if (currentPage < totalPages) {
                currentPage++;
                updatePageDisplay();
            }
        });

        function updatePageDisplay() {
            currentPageSpan.textContent = currentPage;
            totalPagesSpan.textContent = totalPages;
            prevBtn.disabled = currentPage === 1;
            nextBtn.disabled = currentPage === totalPages;
        }
    });
</script> -->
</body>
</html>