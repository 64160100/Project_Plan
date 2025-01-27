<div class="modal fade" id="editBudget" tabindex="-1" aria-labelledby="editBudgetLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('PlanDLC.editBudget') }}" method="POST">
                <div class="modal-content">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="editBudgetLabel">แก้ไขงบประมาณ </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form>
                            <div class="mb-3">
                                <label for="Name_SDGs" class="col-form-label">กรอกงบประมาณ</label>
                                <input type="text" class="form-control" id="Name_SDGs" name="Name_SDGs" placeholder="10000" required>
                            </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                        </div>
                </div>
            </form>
        </div>
    </div>