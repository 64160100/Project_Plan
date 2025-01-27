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
            @foreach($lastestProject as $lastestproject)
            <a href="{{ route('editProject', $lastestproject->Id_Project) }}" class="project-status mt-2">
                <div class="project-group">
                    <div class="project-info">
                        <div class="status-icon">
                            <i class='bx bxs-circle'></i>
                        </div>
                        <div>
                            <b>{{$lastestproject->Name_Project}}</b>
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
            @endforeach
            <br>
        </div>
        <br>

        
        <div class="content-box">
            <h4>ผลการดำเนินงานแยกตามฝ่าย</h4>
            @foreach($department as $Department)
            <a href="{{ route('showProjectDepartment',$Department->Id_Department) }}" class="progress-container mt-2">
                <div class="progress-group">
                    <div class="progress-info">
                        <div>{{ $Department->Name_Department }}</div>
                    </div>
                    <div class="progress-stats" >
                        <div id="total">โครงการ : {{ $Department->projects_count }} </div>
                        <div id="done">เสร็จสิ้น : 0</div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        
    </div>

    <div class="menu-footer">
        <div>แสดง {{ $lastestProject->firstItem() }} ถึง {{ $lastestProject->lastItem() }} จาก {{ $lastestProject->total() }} รายการ</div>
        <div class="pagination">
            @if ($lastestProject->onFirstPage())
                <button class="pagination-btn" disabled>ก่อนหน้า</button>
            @else
                <a href="{{ $lastestProject->previousPageUrl() }}" class="pagination-btn">ก่อนหน้า</a>
            @endif
    
            <span class="page-number">
                <span id="currentPage">{{ $lastestProject->currentPage() }}</span>
            </span>
    
            @if ($lastestProject->hasMorePages())
                <a href="{{ $lastestProject->nextPageUrl() }}" class="pagination-btn">ถัดไป</a>
            @else
                <button class="pagination-btn" disabled>ถัดไป</button>
            @endif
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