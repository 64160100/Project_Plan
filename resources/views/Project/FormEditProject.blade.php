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

</head>
<body>
    @section('content')
    <h3 class="card-header">แก้ไขโครงการ : {{ $projects->Name_Project }}</h3><br>
    <form action="{{ route('editProject', ['Id_Project' => $projects->Id_Project]) }}" method="POST">
            @csrf 
            @method('PUT')
            <!-- ชื่อโครงการ -->
                <div class="content-box"><b>ชื่อโครงการ</b>
                    <div> 
                        <label for="formGroupExampleInput" class="form-label">สร้างชื่อโครงการ</label>
                        <input type="text" class="form-control" id="Name_Project" name="Name_Project" value="{{ $projects->Name_Project }}" placeholder="กรอกชื่อโครงการ" required>
                    </div>

                    <div id="projectContainer">
                        @foreach($projects->supProjects as $index => $supProject)
                            <div class="form-group">
                                <input type="text" id="Id_Sup_Project-{{ $index + 1 }}" name="Name_Sup_Project[]" placeholder="กรอกชื่อโครงการย่อย" value="{{ $supProject->Name_Sup_Project }}" required oninput="handleInput(this)">
                                <button type="button" class="remove-btn" onclick="removeField(this)"><i class='bx bx-x'></i></button> 
                            </div>
                        @endforeach
                    </div>

                    <div>
                        <button type="button" class="btn-addlist" onclick="addField('projectContainer', 'Name_Sup_Project[]')"><i class='bx bx-plus-circle'></i>เพิ่มรายการ</button>
                    </div>

                </div><br>
            <!-- endชื่อโครงการ -->

            <!-- ผู้รับผิดชอบโครงการ  -->
            <div class="content-box"><b>ผู้รับผิดชอบโครงการ</b>
                <div class="mb-3">
                    <!-- <label for="employee_id" class="form-label">เลือกผู้รับผิดชอบ</label> -->
                    <select class="form-control" id="employee_id" name="employee_id" required>
                        <option value="">เลือกผู้รับผิดชอบ</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->Id_Employee }}" 
                                @if($projects->Employee_Id == $employee->Id_Employee) selected @endif>
                                {{ $employee->Firstname_Employee }} {{ $employee->Lastname_Employee }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div><br>
            <!-- end ผู้รับผิดชอบโครงการ  -->

            
            <!-- ความสอดคล้องส่วนงาน -->
            <div class="content-box"><b>ความสอดคล้องกับยุทธศาสตร์ส่วนงาน</b>
                <select class="form-select" name="Strategic_Id" id="Strategic_Id" required>
                    <option value="" selected disabled>เลือกกลยุทธ์</option>
                    @forelse($strategies as $Strategies)
                        <option value="{{ $Strategies->Strategic_Id }}" 
                            {{ isset($projects) && $projects->Strategic_Id == $Strategies->Strategic_Id ? 'selected' : '' }}>
                            {{ $Strategies->Name_Strategy }}
                        </option>
                    @empty
                        <option value="" disabled>ไม่มีกลยุทธ์ที่เกี่ยวข้อง</option>
                    @endforelse
                </select>     
            </div><br>
            <!-- end ความสอดคล้องยุทศาสตร์ -->

            <!-- SDGs -->
            <div class="content-box"><b>ความสอดคล้องกับ (SDGs)</b>
                @foreach ($sdgs as $Sdgs)
                    <div class="form-group-sdgs">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="sdgs[]" value="{{ $Sdgs->id_SDGs }}" id="flexCheck{{ $Sdgs->id_SDGs }}"
                            @if(in_array($Sdgs->id_SDGs, $projects->sdgs->pluck('id_SDGs')->toArray())) checked @endif/>
                            <label class="form-check-label" for="flexCheck{{ $Sdgs->id_SDGs }}">{{ $Sdgs->Name_SDGs }}</label>
                        </div>
                    </div>    
                @endforeach
            </div><br>  

             <!-- วัตถุประสงค์โครงการ   -->
             <div class="content-box"><b>วัตถุประสงค์โครงการ</b>
                <textarea class="form-control" id="Objective_Project" name="Objective_Project" rows="5" placeholder="เพิ่มข้อความ" required>{{ $projects->Objective_Project }}</textarea>
            </div><br>

            <!-- ตัวชี้วัด   -->
            <div class="content-box"><b>ตัวชี้วัด</b>
                <textarea class="form-control" id="Indicators_Project" name="Indicators_Project" rows="5" placeholder="เพิ่มข้อความ" required>{{ $projects->Indicators_Project }}</textarea>
                <br>
                <b>กลุ่มเป้าหมาย</b>
                <textarea class="form-control" id="Target_Project" name="Target_Project" rows="5" placeholder="เพิ่มข้อความ" required>{{ $projects->Target_Project }}</textarea>
            </div><br>
                
            <!-- ระยะเวลาดำเนินโครงการ   -->
            <div class="content-box"><b>ระยะเวลาดำเนินโครงการ</b>
                <div>
                    <label for="First_Time">วันที่เริ่มต้น:</label><br>
                    <input type="date" id="First_Time" name="First_Time" value="{{ $projects->First_Time }}" required>
                </div>
                <br>
                <div>
                    <label for="End_Time">วันที่สิ้นสุด:</label><br>
                    <input type="date" id="End_Time" name="End_Time" value="{{ $projects->End_Time }}" required>
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