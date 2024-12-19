<div class="mb-3">
    <label for="employee_id" class="form-label">เลือกผู้รับผิดชอบ</label>
    <select class="form-control" id="employee_id" name="employee_id" required>
        <option value="" selected disabled>เลือกผู้รับผิดชอบ</option>
        @foreach($employees as $employee)
        <option value="{{ $employee->Id_Employee }}">{{ $employee->Firstname_Employee }}
            {{ $employee->Lastname_Employee }}</option>
        @endforeach
    </select>
</div>