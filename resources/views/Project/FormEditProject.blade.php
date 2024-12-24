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
    <h3 class="card-header">แก้ไขโครงการ : {{ $projects->Name_Project }}</h3><br>
    <form action="{{ route('editProject', ['Id_Project' => $projects->Id_Project]) }}" method="POST">
            @csrf 
            @method('PUT')
            <!-- ชื่อโครงการ -->
            <details class="accordion">
                <summary class="accordion-btn"><b>ชื่อโครงการ</b></summary>
                <div class="accordion-content">
                    <div class="mb-3">
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

                    </div>

                </div>
            </details>
            <!-- endชื่อโครงการ -->
            
            
            <!-- ความสอดคล้องส่วนงาน -->
            <details class="accordion">
                <summary class="accordion-btn"><b>ความสอดคล้องกับยุทธศาสตร์ส่วนงาน</b></summary>
                <div class="accordion-content">
                    <div class="mb-3 col-md-6">
                        <div class="mb-3">
                            <label for="strategicSelect" class="form-label">กลยุทธ์</label>
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

                        </div>
                    </div>
                </div>
            </details>
            <!-- end ความสอดคล้องยุทศาสตร์ -->

            <!-- SDGs -->
            <details class="accordion">
                <summary class="accordion-btn"><b>ความสอดคล้องกับ (SDGs)</b></summary>
                <div class="accordion-content">
                    <div class="mb-3 col-md-6">
                        @foreach ($sdgs as $Sdgs)
                            <div class="form-group-sdgs">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="sdgs[]" value="{{ $Sdgs->id_SDGs }}" id="flexCheck{{ $Sdgs->id_SDGs }}"
                                    @if(in_array($Sdgs->id_SDGs, $projects->sdgs->pluck('id_SDGs')->toArray())) checked @endif/>
                                    <label class="form-check-label" for="flexCheck{{ $Sdgs->id_SDGs }}">{{ $Sdgs->Name_SDGs }}</label>
                                </div>
                            </div>    
                        @endforeach
                    </div>
                </div>
            </details>

             <!-- วัตถุประสงค์โครงการ   -->
             <details class="accordion">
                <summary class="accordion-btn"><b>วัตถุประสงค์โครงการ</b></summary>
                <div class="accordion-content">
                    <div class="mb-3">
                        <div for="formGroupExampleInput" class="form-label">วัตถุประสงค์โครงการ</div>
                        <div class="form-group">
                            <input type="text" id="Objective_Project" name="Objective_Project" value="{{ $projects->Objective_Project }}" placeholder="เพิ่มวัตถุประสงค์" required>
                        </div>
                    </div>
                </div>
            </details>
            <!-- end วัตถุประสงค์โครงการ   -->

            <!-- ตัวชี้วัด   -->
            <details class="accordion">
                <summary class="accordion-btn"><b>ตัวชี้วัด</b></summary>
                <div class="accordion-content">
                    <div class="mb-3">
                        <label for="formGroupExampleInput" class="form-label"><b>ตัวชี้วัด</b></label>
                        <div class="form-group">
                            <input type="text" id="Indicators_Project" name="Indicators_Project" value="{{ $projects->Indicators_Project }}" placeholder="กรอกตัวชี้วัด">
                        </div>
                    </div>
                </div>
            </details>

            <!-- กลุ่มเป้าหมาย   -->
            <details class="accordion">
                <summary class="accordion-btn"><b>กลุ่มเป้าหมาย</b></summary>
                <div class="accordion-content">
                    <div class="mb-3">
                        <div class="form-group">
                            <input type="text" name="Target_Project" id="Target_Project" value="{{ $projects->Target_Project }}" placeholder="เพิ่มกลุ่มเป้าหมาย" required>
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
                            <input type="date" id="First_Time" name="First_Time" value="{{ $projects->First_Time }}" required>
                        </div>
                        <br>
                        <div>
                            <label for="End_Time">วันที่สิ้นสุด:</label><br>
                            <input type="date" id="End_Time" name="End_Time" value="{{ $projects->End_Time }}" required>
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