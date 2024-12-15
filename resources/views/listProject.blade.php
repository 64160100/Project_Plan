@extends('navbar.app')

@section('content')
<div class="container">
    <h1>รายการโปรเจค</h1>
    <!-- Button to trigger modal -->
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addProjectModal">
        เพิ่มโปรเจค
    </button>

    <!-- Modal -->
    <div class="modal fade" id="addProjectModal" tabindex="-1" aria-labelledby="addProjectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProjectModalLabel">เพิ่มโปรเจคใหม่</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('projects.createProject') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="projectName" class="form-label">ชื่อโปรเจค</label>
                            <input type="text" class="form-control" id="projectName" name="project_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="strategicSelect" class="form-label">เลือกยุทธศาสตร์</label>
                            <select class="form-select" id="strategicSelect" name="strategic_id" required>
                                <option value="" selected disabled>เลือกยุทธศาสตร์</option>
                                @foreach($strategics as $strategic)
                                    <option value="{{ $strategic->Id_Strategic }}">{{ $strategic->Name_Strategic_Plan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">บันทึก</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="accordion" id="strategicAccordion">
        @foreach($strategics as $strategic)
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading{{ $strategic->Id_Strategic }}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $strategic->Id_Strategic }}" aria-expanded="false" aria-controls="collapse{{ $strategic->Id_Strategic }}">
                        {{ $strategic->Name_Strategic_Plan }}
                    </button>
                </h2>
                <div id="collapse{{ $strategic->Id_Strategic }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $strategic->Id_Strategic }}" data-bs-parent="#strategicAccordion">
                    <div class="accordion-body">
                        <ul>
                            @foreach($strategic->projects as $project)
                                <li>{{ $project->Name_Project }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection