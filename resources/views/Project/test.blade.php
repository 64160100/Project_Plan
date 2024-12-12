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


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

</head>
<body>
    @section('content')
    <h3 class="card-header">กรอกข้อมูลโครงการใหม่</h3><br>
    <form id="formAuthentication" class="mb-3" action="{{ route('addProject') }}" medthod="POST">
     @csrf   
        <!-- ชื่อโครงการ -->
        <details class="accordion">
            <summary class="accordion-btn"><b>ชื่อโครงการ</b></summary>
            <div class="accordion-content">
                <div class="mb-3">
                    @include('Project.ProjectName')
                </div>
            </div>
        </details>
        <!-- endชื่อโครงการ -->
        
        <!-- ลักษณะโครงการ -->
        <details class="accordion">
            <summary class="accordion-btn"><b>ลักษณะโครงการ</b></summary>
            <div class="accordion-content">
                <div class="mb-3">
                    <form id="dynamicForm">
                        <div id="itemContainer">
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
                        </div> 
                    </form>
                </div>
            </div>
        </details>

        <!-- ผู้รับผิดชอบโครงการ    -->
        <details class="accordion">
            <summary class="accordion-btn"><b>ผู้รับผิดชอบโครงการ</b></summary>
            <div class="accordion-content">
                <div class="mb-3 col-md-6">
                    <select id="advanced-multiselect" class="form-control choices-multiple" multiple>
                        <option value="ค้นหารายชื่อ" disabled>ค้นหารายชื่อ</option>
                        <option value="AZ">Arizona</option>
                        <option value="CO">Colorado</option>
                        
                    </select>
                </div>
            </div>
        </details>

        <!-- ความสอดคล้องกับยุทธศาสตร์มหาวิทยาลัย  -->
        <details class="accordion">
            <summary class="accordion-btn"><b>ความสอดคล้องกับยุทธศาสตร์มหาวิทยาลัย</b></summary>
            <div class="accordion-content">
                <div class="mb-3">
                @include('Project.UniStrategic')
                </div>
            </div>
        </details>
        <!-- end ความสอดคล้องกับยุทธศาสตร์มหาวิทยาลัย  -->
        
        <!-- ความสอดคล้องส่วนงาน -->
        <details class="accordion">
            <summary class="accordion-btn"><b>ความสอดคล้องกับยุทธศาสตร์ส่วนงาน</b></summary>
            <div class="accordion-content">
                @include('Project.Strategic_Strategy')
            </div>
        </details>
        <!-- end ความสอดคล้องยุทศาสตร์ -->


        <!-- SDG    -->
        <details class="accordion">
            <summary class="accordion-btn"><b>ความสอดคล้องกับ (SDGs)</b></summary>
            <div class="accordion-content">
                <div class="mb-3 col-md-6">
                    <!-- <label for="Id_Strategic" class="form-label">เลือกยุทธศาสตร์</label> -->
                    <select 
                        class="form-select @error('Id_Strategic') is-invalid @enderror" 
                        id="Id_Strategic"
                        name="Id_Strategic"
                        required>
                        
                        <!-- Option แรกสำหรับเลือก -->
                        <option select>เลือกรายการ SDGs</option>     
                            
                    </select>
                </div>
            </div>
        </details>

        <!-- บูรณาการ   -->
        <details class="accordion">
            <summary class="accordion-btn"><b>การบูรณาการงานโครงการ/กิจกรรม</b></summary>
            <div class="accordion-content">
                <div class="mb-3 col-md-6">
                    <div class="dropdown-container">
                        <div class="dropdown-button" onclick="toggleDropdown()">เลือกรายการโครงการ/กิจกรรม</div>
                            @include('Project.ProjectIntegration')
                    </div>
                </div>
            </div>
        </details>

        <!-- บูรณาการ   -->
        <details class="accordion">
            <summary class="accordion-btn"><b>หลักการและเหตุผล</b></summary>
            <div class="accordion-content">
                <div class="container">
                    <form>
                        <div class="form-group">
                            <textarea class="form-control" rows="5" id="comment" placeholder="เพิ่มข้อความ"></textarea>
                        </div>
                    </form>
                </div>
            </div>
        </details>

        
        <!-- วัตถุประสงค์โครงการ   -->
        <details class="accordion">
            <summary class="accordion-btn"><b>วัตถุประสงค์โครงการ</b></summary>
            <div class="accordion-content">
                <div class="mb-3">
                @include('Project.ProjectObjective')
                </div>
            </div>
        </details>
        <!-- end วัตถุประสงค์โครงการ   -->

        <!-- กลุ่มเป้าหมาย   -->
        <details class="accordion">
            <summary class="accordion-btn"><b>กลุ่มเป้าหมาย</b></summary>
            <div class="accordion-content">
                <div class="mb-3">
                    <form id="dynamicForm">
                        <div class="form-group">
                            <input type="text" name="place" placeholder="เพิ่มกลุ่มเป้าหมาย" required>
                        </div>
                    </form>
                </div>
            </div>
        </details>

        <!-- ตัวชี้วัด   -->
        <details class="accordion">
            <summary class="accordion-btn"><b>ตัวชี้วัด</b></summary>
            <div class="accordion-content">
                <div class="mb-3">
                @include('Project.Kpi')
                </div>
            </div>
        </details>

        <!-- สถานที่ดำเนินงาน   -->
        <details class="accordion">
            <summary class="accordion-btn"><b>สถานที่ดำเนินงาน</b></summary>
            <div class="accordion-content">
                <div class="mb-3">
                    <form id="dynamicForm">
                        <div class="form-group">
                            <input type="text" name="place" placeholder="กรอกสถานที่" required>
                        </div>
                    </form>
                </div>
            </div>
        </details>

        <!-- ระยะเวลาดำเนินโครงการ   -->
        <details class="accordion">
            <summary class="accordion-btn"><b>ระยะเวลาดำเนินโครงการ</b></summary>
            <div class="accordion-content">
                <div class="mb-3">
                    <form id="dynamicForm">
                        <div>
                            <label for="birthdaytime">วันที่เริ่มต้น:</label><br>
                            <input type="datetime-local" name="birthdaytime">
                        </div>
                        <br>
                        <div>
                            <label for="birthdaytime">วันที่สิ้นสุด:</label><br>
                            <input type="datetime-local" name="birthdaytime">
                        </div>
                    </form>
                </div>
            </div>
        </details>

        
        <!-- ขั้นตอนและแผนการดำเนินงาน   -->
        <details class="accordion">
            <summary class="accordion-btn"><b>ขั้นตอนและแผนการดำเนินงาน</b></summary>
            <div class="accordion-content">
                <div class="mb-3">
                @include('Project.PlanProject')
                </div>
            </div>
        </details>


        <!-- Output   -->
        <details class="accordion">
            <summary class="accordion-btn"><b>เป้าหมายเชิงผลผลิต (Output)</b></summary>
            <div class="accordion-content">
                <div class="mb-3">
                    <form id="outputForm">
                        <div id="outputContainer">
                            <div class="form-group">
                                <input type="text" id="field-1" name="output[]" placeholder="กรอกข้อมูล" required oninput="handleInput(this)">
                                <button type="button" class="remove-btn" onclick="removeField(this)">Remove</button>
                            </div>
                        </div>
                        <div>
                            <button type="button" class="btn-addlist" onclick="addField('outputContainer', 'output[]')"><i class='bx bx-plus-circle'></i>เพิ่มรายการ</button>
                        </div>
                    </form>
                </div>
            </div>
        </details>

        <!-- Outcome   -->
        <details class="accordion">
            <summary class="accordion-btn"><b>เป้าหมายเชิงผลลัพธ์ (Outcome)</b></summary>
            <div class="accordion-content">
                <div class="mb-3">
                    <form id="outcomeForm">
                        <div id="outcomeContainer">
                            <div class="form-group">
                                <input type="text" id="field-1" name="outcome[]" placeholder="กรอกข้อมูล" required oninput="handleInput(this)">
                                <button type="button" class="remove-btn" onclick="removeField(this)">Remove</button>
                            </div>
                        </div>
                        <div>
                            <button type="button" class="btn-addlist" onclick="addField('outcomeContainer', 'outcome[]')"><i class='bx bx-plus-circle'></i>เพิ่มรายการ</button>
                        </div>
                    </form>
                </div>
            </div>
        </details>

        <!-- ผลที่คาดว่าจะได้รับ   -->
        <details class="accordion">
            <summary class="accordion-btn"><b>ผลที่คาดว่าจะได้รับ</b></summary>
            <div class="accordion-content">
                <div class="mb-3">
                    <form id="resultForm">
                        <div id="resultContainer">
                            <div class="form-group">
                                <input type="text" id="field-1" name="result[]" placeholder="กรอกข้อมูล" required oninput="handleInput(this)">
                                <button type="button" class="remove-btn" onclick="removeField(this)">Remove</button>
                            </div>
                        </div>
                        <div>
                            <button type="button" class="btn-addlist" onclick="addField('resultContainer', 'result[]')"><i class='bx bx-plus-circle'></i>เพิ่มรายการ</button>
                        </div>
                    </form>
                </div>
            </div>
        </details>

        <!-- เอกสารเพิ่มเติม   -->
        <details class="accordion">
            <summary class="accordion-btn"><b>เอกสารเพิ่มเติม</b></summary>
            <div class="accordion-content">
                <div class="mb-3">
                    <form id="resultForm">
                        <p>ไฟล์เอกสารเพิ่มเติม</p>
                    </form>
                </div>
            </div>
        </details>



        <div>
            <button type="submit" class="btn btn-primary me-2">บันทึก</button> 
        </div>
    </form>

    

       
    @endsection
</body>
</html>