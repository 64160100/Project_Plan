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
    <h3 class="card-header">แก้ไขโครงการ</h3><br>
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
                            <label for="strategicSelect" class="form-label">เลือกยุทธศาสตร์</label>
                            <select class="form-select" id="strategicSelect" name="Strategic_Id" required>
                                <option value="" selected disabled>เลือกยุทธศาสตร์</option>
                                @if($strategics->isNotEmpty())
                                    @foreach($strategics as $Strategic)
                                        <option value="{{ $Strategic->Id_Strategic }}" {{ $projects->Strategic_Id == $Strategic->Id_Strategic ? 'selected' : '' }}> {{ $Strategic->Name_Strategic_Plan }}</option>
                                    @endforeach
                                @else
                                    <option value="" disabled></option>
                                @endif
                            </select>
                        </div>
                    </div>

                </div>
            </details>
            <!-- end ความสอดคล้องยุทศาสตร์ -->
        <button type="submit" class="btn btn-primary">บันทึก</button>
    </form> 
        

    @endsection
</body>
</html>