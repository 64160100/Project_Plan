@extends('navbar.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/requestApproval.css') }}">


<div class="container">
    <h1>การอนุมัติทั้งหมด</h1>
    @foreach($approvals as $approval)
    @if($approval->Status !== 'Y' && $approval->Status !== 'N')
    <div class="outer-container">
        <div class="container">
            <div class="header">
                <div class="project-title">{{ $approval->project->Name_Project }}</div>
                <p>{{ $approval->project->employee->department->Name_Department ?? 'No Department' }}</p>
                <div class="project-info">
                    <div class="info-item">
                        <div class="info-top">
                            <i class='bx bx-calendar' style="width: 20px; height: 0px;"></i>
                            <span class="info-label">วันที่เริ่ม</span>
                        </div>
                        <span class="info-value">
                            {{ $approval->project->formattedFirstTime ?? 'N/A' }}
                        </span>
                    </div>
                    <div class="info-item">
                        <div class="info-top">
                            <i class='bx bx-user' style="width: 20px; height: 0px;"></i>
                            <span class="info-label">ผู้รับผิดชอบ</span>
                        </div>
                        <span class="info-value">
                            {{ $approval->project->employee->Firstname_Employee ?? 'No Name' }}
                            {{ $approval->project->employee->Lastname_Employee ?? '' }}
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
                                <form style="display:inline;">
                                    <button type="button" class="status-button not-approved" data-bs-toggle="modal"
                                        data-bs-target="#commentModal-{{ $approval->Id_Approve }}">
                                        <i class='bx bx-dislike'></i> ไม่อนุมัติ
                                    </button>
                                </form>
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
    @endforeach
</div>
@endsection