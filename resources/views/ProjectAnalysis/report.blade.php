@extends('navbar.app')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>report</title>
    <link rel="stylesheet" href="{{ asset('css/report.css') }}">

</head>
<body>
@section('content')
    <h1>รายงานผล</h1>
    <div class="content-box">
        <div class="content-box">
            <h4>โครงการล่าสุด</h4>
            <!-- link -->
            <a href="#" class="project-status">
                <div class="project-group">
                    <div class="project-info">
                        <div class="status-icon">
                            <i class='bx bxs-circle'></i>
                        </div>
                        <div>
                            <b>โครงการพัฒนาระบบติดตามแผนงาน</b>
                            <div>ฝ่ายบริการสารสนเทศและนวัตกรรมการเรียนรู้</div>
                        </div>
                    </div>
        
                    <div class="energy-container">
                        <div class="energy-text" id="energy-text">70%</div>
                        <div class="energy-bar-container">
                            <div class="energy-bar" id="energy-bar"></div>
                        </div>
                    </div>
                </div>
            </a>
            <br>
        </div>
        <br>

        <div class="content-box">
            <h4>ผลการดำเนินงานแยกตามฝ่าย</h4>
            <a href="#" class="progress-container">
                <div class="progress-group">
                    <div class="progress-info">
                        <div>ฝ่ายบริการสารสนเทศและนวัตกรรมการเรียนรู้</div>
                    </div>
                    <div class="progress-stats" >
                        <div id="total">โครงการ : 12</div>
                        <div id="done">เสร็จสิ้น : 8</div>
                    </div>
                </div>
            </a>
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

<script>
    function setEnergyLevel(level) {
      const energyBar = document.getElementById('energy-bar');
      const energyText = document.getElementById('energy-text');

      // ตรวจสอบให้ค่าอยู่ในช่วง 0% - 100%
      level = Math.max(0, Math.min(100, level));

      // ปรับขนาดบาร์และอัปเดตข้อความ
      energyBar.style.width = level + '%';
      energyText.textContent = level + '%';
    }
    // ตัวอย่างการเปลี่ยนระดับพลังงาน
    // setTimeout(() => setEnergyLevel(50), 1000); // เปลี่ยนเป็น 50% หลังจาก 1 วินาที
    // setTimeout(() => setEnergyLevel(90), 3000); // เปลี่ยนเป็น 90% หลังจาก 3 วินาที
    // setTimeout(() => setEnergyLevel(20), 5000); // เปลี่ยนเป็น 20% หลังจาก 5 วินาที
</script>
    
</body>
</html>