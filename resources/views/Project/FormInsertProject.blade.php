@extends('navbar.app')

<!DOCTYPE html>
<html lang="en">
<head>
    <title>FormInsertProject</title>
    <link rel="stylesheet" href="{{ asset('css/project.css') }}">
    <script src="{{ asset('js/addField.js') }}" defer></script>
    <script src="{{ asset('js/radioButton.js') }}" defer></script>
    <script src="{{ asset('js/toggleDropdown.js') }}" defer></script>
    <script src="{{ asset('js/filterSearch.js') }}" defer></script>
    <script src="{{ asset('js/sdgForm.js') }}" defer></script>
    <script src="{{ asset('js/addKpiField.js') }}" defer></script>
    <script src="{{ asset('js/datetime.js') }}" defer></script>

</head>
<body>
    @section('content')
    <h3 class="card-header">กรอกข้อมูลโครงการใหม่</h3><br>
    <form action="{{ route('createProject', ['Strategic_Id' => $strategics->Id_Strategic]) }}" method="POST">
            @csrf 
            <!-- ชื่อโครงการ -->
            <details class="accordion">
                <summary class="accordion-btn"><b>ชื่อโครงการ</b></summary>
                <div class="accordion-content">
                    <div class="mb-3">
                        @include('Project.App.ProjectName')
                    </div>

                </div>
            </details>
            <!-- endชื่อโครงการ -->

            <!-- ความสอดคล้องกับยุทธศาสตร์มหาวิทยาลัย  -->
            <!-- <details class="accordion">
                <summary class="accordion-btn"><b>ความสอดคล้องกับยุทธศาสตร์มหาวิทยาลัย</b></summary>
                <div class="accordion-content">
                    <div class="mb-3">
                   
                    </div>
                </div>
            </details> -->
            <!-- end ความสอดคล้องกับยุทธศาสตร์มหาวิทยาลัย  -->
            
            
            <!-- ความสอดคล้องส่วนงาน -->
            <details class="accordion">
                <summary class="accordion-btn"><b>ความสอดคล้องกับยุทธศาสตร์ส่วนงาน</b></summary>
                <div class="accordion-content">
                    @include('Project.App.Strategic_Strategy')
                </div>
            </details>
            <!-- end ความสอดคล้องยุทศาสตร์ -->

            <details class="accordion">
                <summary class="accordion-btn"><b>ความสอดคล้องกับ (SDGs)</b></summary>
                <div class="accordion-content">
                    <div class="mb-3 col-md-6">
                        @include('Project.App.SDGs')
                    </div>
                </div>
            </details>

            <!-- วัตถุประสงค์โครงการ   -->
            <details class="accordion">
                <summary class="accordion-btn"><b>วัตถุประสงค์โครงการ</b></summary>
                <div class="accordion-content">
                    <div class="mb-3">
                    @include('Project.App.ProjectObjective')
                    </div>
                </div>
            </details>
        <!-- end วัตถุประสงค์โครงการ   -->

            <!-- ตัวชี้วัด   -->
            <details class="accordion">
                <summary class="accordion-btn"><b>ตัวชี้วัด</b></summary>
                <div class="accordion-content">
                    <div class="mb-3">
                    @include('Project.App.Kpi')
                    </div>
                </div>
            </details>

            <!-- กลุ่มเป้าหมาย   -->
            <details class="accordion">
                <summary class="accordion-btn"><b>กลุ่มเป้าหมาย</b></summary>
                <div class="accordion-content">
                    <div class="mb-3">
                        <div class="form-group">
                            <input type="text" name="Target_Project" id="Target_Project" placeholder="เพิ่มกลุ่มเป้าหมาย" required>
                        </div>
                    </div>
                </div>
            </details>

        <!-- ระยะเวลาดำเนินโครงการ   -->
        <details class="accordion">
            <summary class="accordion-btn"><b>ระยะเวลาดำเนินโครงการ</b></summary>
            <div class="accordion-content">
                <div class="mb-3">
                    <!-- <form id="dynamicForm"> -->
                        <div>
                            <label for="First_Time">วันที่เริ่มต้น:</label><br>
                            <input type="date" id="First_Time" name="First_Time" required>
                        </div>
                        <br>
                        <div>
                            <label for="End_Time">วันที่สิ้นสุด:</label><br>
                            <input type="date" id="End_Time" name="End_Time" required>
                        </div>
                    <!-- </form> -->
                </div>
            </div>
        </details>
        
        <button type="submit" class="btn btn-primary">บันทึก</button>
    </form> 
        

    @endsection
</body>
</html>