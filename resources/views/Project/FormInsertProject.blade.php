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
    <script src="{{ asset('js/datetime.js') }}" defer></script>

</head>
<body>
    @section('content')
    <h3 class="card-header">กรอกข้อมูลโครงการใหม่</h3><br>
    <form action="{{ route('createProject', ['Strategic_Id' => $strategics->Id_Strategic, 'Employee_Id' => $employees->first()->Id_Employee]) }}" method="POST">
            @csrf 
                <div class="content-box"><b>ชื่อโครงการ</b>
                <hr>
                    @include('Project.App.ProjectName')
                </div><br>
                
                <!-- ผู้รับผิดชอบโครงการ  -->
                <div class="content-box"><b>ผู้รับผิดชอบโครงการ</b>
                    @include('Project.App.ProjectManager')
                </div><br>

                <!-- ลักษณะโครงการ  -->
                <div class="content-box"><b>ลักษณะโครงการ</b>
                    <!-- <div class="mb-3">
                        <div class="form-group-radio">
                            <label>
                                <input type="radio" name="projectType" value="1" onchange="toggleTextbox(this, 'textbox-projectType-')">
                                โครงการใหม่
                            </label>
                        </div>
                        <div class="form-group-radio">
                            <label>
                                <input type="radio" name="projectType" value="2" onchange="toggleTextbox(this, 'textbox-projectType-')">
                                โครงการต่อเนื่อง &nbsp;&nbsp;
                            </label>
                        </div>
                        <div class="form-group">
                            <input type="text" id="textbox-projectType-2" class="hidden" data-group="projectType" placeholder="กรอกชื่อโครงการเดิม">
                        </div>
                    </div> -->
                </div><br>

                <!-- ความสอดคล้องกับยุทธศาสตร์มหาวิทยาลัย  -->
                <div class="content-box"><b>ความสอดคล้องกับยุทธศาสตร์มหาวิทยาลัย</b>
                    <!-- include('Project.App.UniStrategic') -->
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
                    <textarea class="form-control" id="Objective_Project" name="Objective_Project" rows="5" placeholder="เพิ่มข้อความ" required></textarea>
                    @include('Project.App.ProjectObjective')
                </div><br>
                
                <!-- กลุ่มเป้าหมาย   -->
                <div class="content-box"><b>กลุ่มเป้าหมาย</b>
                    @include('Project.App.TargetGroup')
                </div><br>

                <!-- สถานที่   -->
                <!-- <div class="content-box"><b>สถานที่ดำเนินงาน</b>
                    <div class="form-group">
                        <textarea class="form-control" rows="2" id="comment" placeholder="เพิ่มข้อความ"></textarea>
                    </div>
                </div><br> -->
                
                <!-- ตัวชี้วัด   -->
                <div class="content-box"><b>ตัวชี้วัด</b>
                    @include('Project.App.Kpi')
                </div><br>
             
                 <!-- ระยะเวลาดำเนินโครงการ  -->
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

                <div class="content-box"><b>หลักการและเหตุผล</b>
                    <!-- <div class="form-group">
                        <textarea class="form-control" rows="15" id="comment" placeholder="เพิ่มข้อความ"></textarea>
                    </div> -->
                </div><br>

                <!-- บูรณาการ   -->
                <div class="content-box"><b>การบูรณาการงานโครงการ/กิจกรรม</b>
                    <!-- <div class="mb-3 col-md-6">
                        <div class="dropdown-container">
                            <div class="dropdown-button" onclick="toggleDropdown()">เลือกรายการโครงการ/กิจกรรม</div>
                            include('Project.App.ProjectIntegration')
                        </div>
                    </div> -->
                </div><br>


                <div class="content-box"><b>ขั้นตอนและแผนการดำเนินงาน</b>
                    <div class="mb-3">
                        <!-- include('Project.App.PlanProject') -->
                    </div>
                </div><br>

            <!-- Output   -->
                <div class="content-box"><b>เป้าหมายเชิงผลผลิต (Output)</b>
                        <!-- <div class="mb-3">
                            <form id="outputForm">
                                <div id="outputContainer">
                                    
                                </div>
                                <div>
                                    <button type="button" class="btn-addlist" onclick="addField('outputContainer', 'output[]')"><i class='bx bx-plus-circle'></i>เพิ่มรายการ</button>
                                </div>
                            </form>
                        </div> -->
                </div><br>

                <div class="content-box"><b>เป้าหมายเชิงผลลัพธ์ (Outcome)</b>
                        <!-- <div class="mb-3">
                            <form id="outcomeForm">
                                <div id="outcomeContainer">
                                    
                                </div>
                                <div>
                                    <button type="button" class="btn-addlist" onclick="addField('outcomeContainer', 'outcome[]')"><i class='bx bx-plus-circle'></i>เพิ่มรายการ</button>
                                </div>
                            </form>
                        </div> -->
                </div><br>

                <div class="content-box"><b>ผลที่คาดว่าจะได้รับ</b>
                <!-- <div class="mb-3">
                    <form id="resultForm">
                        <div id="resultContainer">
                            
                        </div>
                        <div>
                            <button type="button" class="btn-addlist" onclick="addField('resultContainer', 'result[]')"><i class='bx bx-plus-circle'></i>เพิ่มรายการ</button>
                        </div>
                    </form>
                </div> -->
                </div><br>

                <div class="content-box"><b>เอกสารเพิ่มเติม</b>
                    <!-- <div class="mb-3">
                        <form action="/action_page.php">
                            <input type="file" id="myFile" name="filename">
                        </form>
                    </div>     -->
                </div><br>

                <!-- save -->
                <div class="container">
                    <button type="submit" class="btn-submit">บันทึก</button>
                </div>
    
    </form> 
    @endsection

</body>
</html>