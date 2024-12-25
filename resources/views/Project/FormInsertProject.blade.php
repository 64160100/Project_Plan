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
            <!-- <details class="accordion">
                <summary class="accordion-btn"><b>ชื่อโครงการ</b></summary>
                <div class="accordion-content">
                    <div class="mb-3">
                        
                    </div>

                </div>
            </details> -->
            <!-- endชื่อโครงการ -->

                <div class="content-box"><b>ชื่อโครงการ</b>
                <hr>
                    @include('Project.App.ProjectName')
                </div><br>
                

            <!-- ความสอดคล้องกับยุทธศาสตร์มหาวิทยาลัย  -->
                <div class="content-box"><b>ความสอดคล้องกับยุทธศาสตร์มหาวิทยาลัย</b>

                </div><br>
            
            
            <!-- ความสอดคล้องส่วนงาน -->
                <div class="content-box"><b>ความสอดคล้องกับยุทธศาสตร์ส่วนงาน</b>
                    @include('Project.App.Strategic_Strategy')
                </div><br>

            <!-- SDGs -->
                <div class="content-box"><b>ความสอดคล้องกับ (SDGs)</b>
                    @include('Project.App.SDGs')
                </div><br>

            <!-- วัตถุประสงค์โครงการ   -->
                <div class="content-box"><b>วัตถุประสงค์โครงการ</b>
                    @include('Project.App.ProjectObjective')
                </div><br>
                

            <!-- ตัวชี้วัด   -->
                <div class="content-box"><b>ตัวชี้วัด</b>
                    @include('Project.App.Kpi')
                </div><br>
             

            <!-- ค่าเป้าหมาย   -->
                <div class="content-box"><b>ค่าเป้าหมาย</b>
                    <div id="targetProjectContainer">
                        <div>
                            <button type="button" id="add-targetProject" class="btn-addlist" onclick="addField('targetProjectContainer', 'Target_Project[]')"><i class='bx bx-plus-circle'></i>เพิ่มรายการ</button>
                        </div>
                    </div>
                </div><br>
                    
        <!-- ระยะเวลาดำเนินโครงการ   -->
                <div class="content-box"><b>ระยะเวลาดำเนินโครงการ</b>
                    <div>
                        <label for="First_Time">วันที่เริ่มต้น:</label><br>
                        <input type="date" id="First_Time" name="First_Time" required>
                    </div>
                    <br>
                    <div>
                        <label for="End_Time">วันที่สิ้นสุด:</label><br>
                        <input type="date" id="End_Time" name="End_Time" required>
                    </div>
                </div><br>
                
        <!-- save -->
        <div class="container">
            <button type="submit" class="btn-submit">บันทึก</button>
        </div>
    
    </form> 
        

    @endsection
</body>
</html>