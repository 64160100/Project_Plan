@extends('navbar.app')

@section('content')
<div class="container">
    <h1>เสนอโครงการเพื่อพิจารณา</h1>

    <table class="table mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>ชื่อโครงการ</th>
                <th>สถานะ</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($projects as $project)
            <tr>
                <td>{{ $project->Id_Project }}</td>
                <td>{{ $project->Name_Project }}</td>
                <td>
                    @if($project->Count_Steps === 0)
                    ส่ง Email
                    @elseif($project->Count_Steps === 1)
                    รอหัวหน้าฝ่ายพิจารณา
                    @elseif($project->Count_Steps === 2)
                    รอ ผู้บริหาร พิจารณา
                    @else
                    {{ $project->approvals->first()->Status ?? 'รอการอนุมัติ' }}
                    @endif
                </td>
                <td>
                    @if($project->Count_Steps === 0)
                    <form action="{{ route('projects.submitForApproval', ['id' => $project->Id_Project]) }}"
                        method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary">เสนอเพื่อพิจารณา</button>
                    </form>
                    @else
                    <button type="button" class="btn btn-secondary" disabled>เสนอเพื่อพิจารณา</button>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection