@extends('navbar.app')

<!DOCTYPE html>
<html lang="en">

<head>
    <title>FormInsertProject</title>
    <link rel="stylesheet" href="{{ asset('css/listproject.css') }}">
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
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="addProjectModalLabel">เพิ่มโปรเจคใหม่</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{ route('projects.createProject') }}" method="POST">
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

                <!-- ความสอดคล้องส่วนงาน -->
                <details class="accordion">
                    <summary class="accordion-btn"><b>ความสอดคล้องกับยุทธศาสตร์ส่วนงาน</b></summary>
                    <div class="accordion-content">
                    @include('Project.App.Strategic')
                    </div>
                </details>
                <!-- end ความสอดคล้องยุทศาสตร์ -->

                <button type="submit" class="btn btn-primary">บันทึก</button>
            </form>
        </div>
    </div>
    @endsection
</body>

</html>