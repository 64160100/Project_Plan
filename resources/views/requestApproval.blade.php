@extends('navbar.app')

@section('content')
<div class="container">
    <h1>การอนุมัติทั้งหมด</h1>

    <table class="table mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>สถานะ</th>
                <th>Project ID</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($approvals as $approval)
            @if($approval->Status !== 'Y' && $approval->Status !== 'N')
            <tr>
                <td>{{ $approval->Id_Approve }}</td>
                <td>
                    @if($approval->Status === 'I')
                    รอการอนุมัติ
                    @else
                    {{ $approval->Status ?? 'รอการอนุมัติ' }}
                    @endif
                </td>
                <td>{{ $approval->Project_Id }}</td>
                <td>
                    <form
                        action="{{ route('approvals.updateStatus', ['id' => $approval->Id_Approve, 'status' => 'Y']) }}"
                        method="POST" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-success">อนุมัติ</button>
                    </form>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                        data-bs-target="#commentModal-{{ $approval->Id_Approve }}">
                        ไม่อนุมัติ
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="commentModal-{{ $approval->Id_Approve }}" tabindex="-1"
                        aria-labelledby="commentModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="commentModalLabel">เพิ่มความคิดเห็น</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form
                                        action="{{ route('approvals.updateStatus', ['id' => $approval->Id_Approve, 'status' => 'N']) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label for="comment" class="form-label">ความคิดเห็น:</label>
                                            <textarea class="form-control" id="comment2" name="comment2" rows="3"
                                                required></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-danger">ยืนยันการไม่อนุมัติ</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
</div>
@endsection