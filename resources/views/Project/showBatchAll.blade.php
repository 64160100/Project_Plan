@extends('navbar.app')

@section('content')
<div class="container">
    <h3>รายละเอียดชุดโครงการ</h3>
    <div class="batch-projects">
        @foreach($batchRelations as $relation)
        <div class="batch-project-item d-flex justify-content-between align-items-center">
            <div class="project-name">
                {{ $relation->project->Name_Project }}
            </div>
            <div class="project-actions">
                <a href="{{ route('projects.showBatchesProject', ['id' => $relation->Project_Id]) }}" class="btn btn-info btn-sm">
                    <i class='bx bx-show'></i> ดูข้อมูล
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection