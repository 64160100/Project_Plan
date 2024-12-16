@extends('navbar.app')

@section('content')
<div class="container">
    <h1>เพิ่ม KPI สำหรับกลยุทธ์</h1>

    <form action="{{ route('kpi.addKpi', $Id_Strategy) }}" method="POST">
        @csrf
        <div id="kpi-container">
            <div class="kpi-entry mb-3">
                <label for="Name_Kpi" class="form-label">ตัวชี้วัดกลยุทธ์</label>
                <textarea class="form-control" name="Name_Kpi[]" placeholder="กรอกตัวชี้วัดกลยุทธ์" required></textarea>
                <label for="Target_Value" class="form-label">เป้าหมาย</label>
                <input type="text" class="form-control" name="Target_Value[]" placeholder="กรอกค่าเป้าหมาย" required>
            </div>
        </div>
        <button type="button" class="btn btn-secondary mb-3" id="add-kpi">เพิ่มตัวชี้วัด</button>
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">บันทึก</button>
        </div>
    </form>
</div>

<script>
    document.getElementById('add-kpi').addEventListener('click', function() {
        const kpiContainer = document.getElementById('kpi-container');
        const newKpiEntry = document.createElement('div');
        newKpiEntry.classList.add('kpi-entry', 'mb-3');
        newKpiEntry.innerHTML = `
            <label for="Name_Kpi" class="form-label">ตัวชี้วัดกลยุทธ์</label>
            <textarea class="form-control" name="Name_Kpi[]" placeholder="กรอกตัวชี้วัดกลยุทธ์" required></textarea>
            <label for="Target_Value" class="form-label">เป้าหมาย</label>
            <input type="text" class="form-control" name="Target_Value[]" placeholder="กรอกค่าเป้าหมาย" required>
            <button type="button" class="btn btn-danger remove-kpi">ลบ</button>
        `;
        kpiContainer.appendChild(newKpiEntry);
    });

    document.getElementById('kpi-container').addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-kpi')) {
            event.target.parentElement.remove();
        }
    });
</script>
@endsection