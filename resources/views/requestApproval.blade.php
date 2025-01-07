@extends('navbar.app')

<style>
.outer-container {
    background: white;
    border: 1px solid #e0e0e0;
    max-width: 1300px;
    margin: auto;
    margin-bottom: 20px;
    padding: 15px;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.container {
    padding: 10px;
}

.project-title {
    font-size: 20px;
    font-weight: bold;
}

.project-subtitle {
    color: #666;
    font-size: 14px;
    margin: 5px 0 20px 0;
}

.project-info {
    display: flex;
    justify-content: center;
    gap: 200px;
    margin-bottom: 10px;
    padding-bottom: 10px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.info-top {
    display: flex;
    align-items: center;
    gap: 8px;
}

.info-label {
    color: #666;
    font-size: 14px;
}

.info-value {
    margin-left: 28px;
}

.project-actions {
    display: flex;
    justify-content: flex-start;
    margin-top: 20px;
    padding-bottom: 10px;
    border-bottom: 1px solid #e0e0e0;
}

.action-link {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #007bff;
    text-decoration: none;
    font-size: 14px;
    transition: color 0.3s;
    margin-right: 20px;
}

.action-link:hover {
    color: #0056b3;
}

.warning-icon {
    color: #f39c12;
}

.status-section {
    margin-top: 15px;
}

.status-header {
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 10px;
}

.status-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    border: 1px solid #e0e0e0;
    border-radius: 5px;
    background-color: #f9f9f9;
}

.status-button {
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 150px;
    height: 50px;
}

.action-buttons {
    display: flex;
    gap: 10px;
    justify-content: center;
    align-items: center;
}

.status-button.approved {
    background-color: #28a745;
    color: white;
}

.status-button.approved:hover {
    background-color: #218838;
}

.status-button.not-approved {
    background-color: #dc3545;
    color: white;
}

.status-button.not-approved:hover {
    background-color: #c82333;
}

.btn-action {
    padding: 8px 16px;
    border-radius: 16px;
    border: none;
    font-size: 14px;
    cursor: pointer;
}

.modal-content {
    border-radius: 8px;
}

.modal-header {
    border-bottom: 1px solid #e0e0e0;
    padding: 16px;
}

.modal-body {
    padding: 16px;
}

.form-control {
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    padding: 8px;
    width: 100%;
}

i {
    opacity: 0.7;
    font-size: 25px;
}
</style>

@section('content')
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
                            {{ $approval->recordHistory->first()->formattedDateTime ?? 'N/A' }}
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
                    <a href="{{ route('project.showPdf', ['id' => $approval->project->id]) }}" class="action-link">
                        <i class='bx bx-info-circle'></i>
                        ดูรายละเอียดโครงการ
                    </a>
                    <a href="#" class="action-link">
                        <i class='bx bx-message'></i>
                        ข้อเสนอแนะ(1)
                    </a>
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
                                รออนุมัติ :
                                {{ \Carbon\Carbon::parse($approval->recordHistory->first()->Time_Record)->diffInDays(\Carbon\Carbon::now()) < 1 ? 0 : \Carbon\Carbon::parse($approval->recordHistory->first()->Time_Record)->diffInDays(\Carbon\Carbon::now()) }}
                                วัน
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