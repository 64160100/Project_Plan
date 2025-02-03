@extends('navbar.app')

<hade>
    <link rel="stylesheet" href="{{ asset('css/requestApproval.css') }}">
</hade>

@section('content')

<div class="container">
    <h1>การอนุมัติทั้งหมด</h1>
    @foreach($approvals as $approval)
    @if($approval->Status !== 'Y' && $approval->Status !== 'N')
    @if($approval->project->Count_Steps != 1)
    <div class="outer-container">
        <div class="container">
            <div class="header">
                <div class="project-title">{{ $approval->project->Name_Project }}</div>
                <p>{{ $approval->project->employee->department->Name_Department ?? 'ยังไม่มีผู้รับผิดชอบโครงการ' }}</p>
                <div class="project-info">
                    <div class="info-item">
                        <div class="info-top">
                            <i class='bx bx-calendar' style="width: 20px; height: 0px;"></i>
                            <span class="info-label">วันที่เริ่ม</span>
                        </div>
                        <span class="info-value">
                            {{ $approval->project->formattedFirstTime ?? '-' }}
                        </span>
                    </div>
                    <div class="info-item">
                        <div class="info-top">
                            <i class='bx bx-user' style="width: 20px; height: 0px;"></i>
                            <span class="info-label">ผู้รับผิดชอบ</span>
                        </div>
                        <span class="info-value">
                            @if($approval->project->employee && ($approval->project->employee->Firstname_Employee ||
                            $approval->project->employee->Lastname_Employee))
                            {{ $approval->project->employee->Firstname_Employee ?? '' }}
                            {{ $approval->project->employee->Lastname_Employee ?? '' }}
                            @else
                            -
                            @endif
                        </span>

                    </div>
                    <div class="info-item">
                        <div class="info-top">
                            <i class='bx bx-wallet-alt' style="width: 20px; height: 0px;"></i>
                            <span class="info-label">งบประมาณ</span>
                        </div>
                        <span class="info-value">-</span>
                    </div>
                </div>

                <div class="project-actions">
                    <a href="{{ route('StorageFiles.index') }}" class="action-link">
                        <i class='bx bx-info-circle'></i>
                        ดูรายละเอียดโครงการ
                    </a>

                    <div class="dropdown">
                        <a href="#" class="action-link dropdown-toggle"
                            id="commentsDropdown-{{ $approval->Id_Approve }}" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class='bx bx-message'></i>
                            ข้อเสนอแนะ({{ $approval->recordHistory->where('Status_Record', 'N')->count() }})
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="commentsDropdown-{{ $approval->Id_Approve }}"
                            style="max-height: 200px; overflow-y: auto; width: 300px;">
                            @php
                            $filteredRecords = $approval->recordHistory->where('Status_Record', 'N');
                            @endphp
                            @if($filteredRecords->count() > 0)
                            @foreach($filteredRecords as $record)
                            <li class="p-2 border-bottom">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="font-weight-bold">{{ $record->Name_Record ?? 'Unknown' }}</span>
                                    <span class="text-muted small">{{ $record->formattedDateTime ?? 'N/A' }}</span>
                                </div>
                                <p class="mb-0">{{ $record->comment ?? 'No Comment' }}</p>
                            </li>
                            @endforeach
                            @else
                            <li class="p-2 text-center text-muted">ไม่มีข้อเสนอแนะ</li>
                            @endif
                        </ul>
                    </div>

                    <a href="#" class="action-link">
                        <i class='bx bx-error warning-icon'></i>
                        แจ้งเตือน
                    </a>
                </div>

                <div class="status-section">
                    <div class="status-header">การดำเนินการ</div>
                    <div class="status-card">
                        <div class="status-left">
                            <div class="status-text">
                                รออนุมัติ: {{ floor($approval->recordHistory->last()->daysSinceTimeRecord ?? 0) }} วัน
                            </div>
                        </div>
                        <div class="status-right">
                            <div class="action-buttons">
                                <form
                                    action="{{ route('approvals.updateStatus', ['id' => $approval->Id_Approve, 'status' => 'Y']) }}"
                                    method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="status-button approved">
                                        <i class='bx bx-like'></i> อนุมัติ
                                    </button>
                                </form>
                                <button type="button" class="status-button not-approved" data-bs-toggle="modal"
                                    data-bs-target="#commentModal-{{ $approval->Id_Approve }}">
                                    <i class='bx bx-dislike'></i> ไม่อนุมัติ
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="commentModal-{{ $approval->Id_Approve }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">เพิ่มความคิดเห็น</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form
                            action="{{ route('approvals.updateStatus', ['id' => $approval->Id_Approve, 'status' => 'N']) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="comment" class="form-label">ความคิดเห็น:</label>
                                <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="status-button not-approved">ยืนยันการไม่อนุมัติ</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endif
    @endforeach


    @if($employee->IsDirector === 'Y')
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">ชุดโครงการที่สร้างแล้ว</h5>
        </div>
        <div class="card-body">
            <div class="batches-list">
                @foreach($projectBatchRelations->groupBy('batch.Name_Batch') as $batchName => $relations)
                @php
                // Filter out relations with Count_Steps_Batch set to 1
                $filteredRelations = $relations->filter(function ($relation) {
                return $relation->Count_Steps_Batch == 1;
                });
                @endphp
                @if($filteredRelations->isNotEmpty())
                <div class="batch-item">
                    <div class="batch-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            {{ $batchName }}
                            <small>({{ $filteredRelations->count() }} โครงการ)</small>
                        </h5>
                        <a href="{{ route('projects.showBatchAll', ['batch_id' => $filteredRelations->first()->Project_Batch_Id]) }}"
                            class="btn btn-primary btn-sm">
                            <i class='bx bx-show'></i> ดูข้อมูลทั้งหมด
                        </a>
                    </div>
                    <div class="batch-projects">
                        @foreach($filteredRelations as $index => $relation)
                        <div class="batch-project-item d-flex justify-content-between align-items-center">
                            <div class="project-name">
                                {{ $index + 1 }}. {{ $relation->project->Name_Project }}
                            </div>
                            <div class="project-actions">
                                <a href="{{ route('projects.showBatchesProject', ['id' => $relation->Project_Id]) }}"
                                    class="btn btn-info btn-sm">
                                    <i class='bx bx-show'></i> ดูข้อมูล
                                </a>
                                <form
                                    action="{{ route('project-batches.removeProject', ['batch_id' => $relation->Project_Batch_Id, 'project_id' => $relation->Project_Id]) }}"
                                    method="POST"
                                    onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบโครงการนี้ออกจากชุดโครงการ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class='bx bx-trash'></i> ลบ
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="button-container mt-3">
                        <div class="status-section">
                            <div class="status-header">การดำเนินการ</div>
                            <div class="status-card">
                                <div class="status-left">
                                    <div class="status-text">
                                        รออนุมัติ:
                                        {{ floor($approval->recordHistory->last()->daysSinceTimeRecord ?? 0) }} วัน
                                    </div>
                                </div>
                                <div class="status-right">
                                    <div class="action-buttons">
                                        <form
                                            action="{{ route('approvals.updateBatchStatus', ['id' => $filteredRelations->first()->Project_Batch_Id, 'status' => 'Y']) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('PUT')
                                            @foreach($filteredRelations as $relation)
                                            <input type="hidden" name="project_ids[]"
                                                value="{{ $relation->project->Id_Project }}">
                                            @endforeach
                                            <button type="submit" class="status-button approved">
                                                <i class='bx bx-like'></i> อนุมัติทั้งหมด
                                            </button>
                                        </form>
                                        <button type="button" class="status-button not-approved" data-bs-toggle="modal"
                                            data-bs-target="#commentModal-{{ $filteredRelations->first()->Project_Batch_Id }}">
                                            <i class='bx bx-dislike'></i> ไม่อนุมัติทั้งหมด
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </div>

    <!-- Modal for not approving -->
    @foreach($approvals->groupBy('project.Project_Batch_Id') as $batchId => $projects)
    @php
    $filteredProjects = $projects->filter(function ($approval) {
    return $approval->project->Count_Steps == 1;
    });
    @endphp
    @if($filteredProjects->isNotEmpty())
    <div class="modal fade" id="commentModal-{{ $batchId }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">เพิ่มความคิดเห็น</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>
    @endif
    @endforeach
    @endif

</div>

@endsection