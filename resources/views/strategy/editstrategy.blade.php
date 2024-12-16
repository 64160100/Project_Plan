@extends('navbar.app')

@section('content')
<div class="container">
    <h1>แก้ไขกลยุทธ์และตัวชี้วัด</h1>
    <form action="{{ route('strategy.update', $strategy->Id_Strategy) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="Name_Strategy">ชื่อกลยุทธ์</label>
            <input type="text" name="Name_Strategy" id="Name_Strategy" value="{{ $strategy->Name_Strategy }}" class="form-control" required>
        </div>

        <h3>วัตถุประสงค์เชิงกลยุทธ์</h3>
        <div id="strategicObjectivesContainer">
            @foreach ($strategy->strategicObjectives as $so)
                <div class="form-group">
                    <label for="Details_Strategic_Objectives_{{ $so->Id_Strategic_Objectives }}">รายละเอียดวัตถุประสงค์เชิงกลยุทธ์</label>
                    <input type="text" name="strategicObjectives[{{ $so->Id_Strategic_Objectives }}][Details_Strategic_Objectives]" id="Details_Strategic_Objectives_{{ $so->Id_Strategic_Objectives }}" value="{{ $so->Details_Strategic_Objectives }}" class="form-control" required>
                </div>
            @endforeach
        </div>
        <button type="button" class="btn btn-success" id="addObjectiveButton">เพิ่มวัตถุประสงค์ใหม่</button>

        <h3>ตัวชี้วัด</h3>
        <div id="kpisContainer">
            @foreach ($strategy->kpis as $kpi)
                <div class="form-group">
                    <label for="Name_Kpi_{{ $kpi->Id_Kpi }}">ชื่อ KPI</label>
                    <input type="text" name="kpis[{{ $kpi->Id_Kpi }}][Name_Kpi]" id="Name_Kpi_{{ $kpi->Id_Kpi }}" value="{{ $kpi->Name_Kpi }}" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="Target_Value_{{ $kpi->Id_Kpi }}">ค่าเป้าหมาย</label>
                    <input type="text" name="kpis[{{ $kpi->Id_Kpi }}][Target_Value]" id="Target_Value_{{ $kpi->Id_Kpi }}" value="{{ $kpi->Target_Value }}" class="form-control" required>
                </div>
            @endforeach
        </div>
        <button type="button" class="btn btn-success" id="addKpiButton">เพิ่ม KPI ใหม่</button>

        <a href="{{ route('strategy.index', $Id_Strategic) }}" class="btn btn-danger">ยกเลิก</a>
        <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
    </form>
</div>

<script>
    document.getElementById('addObjectiveButton').addEventListener('click', function() {
        const container = document.getElementById('strategicObjectivesContainer');
        const index = container.children.length;
        const newObjective = document.createElement('div');
        newObjective.classList.add('form-group');
        newObjective.innerHTML = `
            <label for="newStrategicObjectives_${index}">รายละเอียดวัตถุประสงค์เชิงกลยุทธ์ใหม่</label>
            <input type="text" name="newStrategicObjectives[]" id="newStrategicObjectives_${index}" class="form-control" >
        `;
        container.appendChild(newObjective);
    });

    document.getElementById('addKpiButton').addEventListener('click', function() {
        const container = document.getElementById('kpisContainer');
        const index = container.children.length / 2; // Each KPI has two inputs
        const newKpiName = document.createElement('div');
        newKpiName.classList.add('form-group');
        newKpiName.innerHTML = `
            <label for="newKpis_${index}_Name_Kpi">ชื่อ KPI ใหม่</label>
            <input type="text" name="newKpis[${index}][Name_Kpi]" id="newKpis_${index}_Name_Kpi" class="form-control" >
        `;
        const newKpiTarget = document.createElement('div');
        newKpiTarget.classList.add('form-group');
        newKpiTarget.innerHTML = `
            <label for="newKpis_${index}_Target_Value">ค่าเป้าหมายใหม่</label>
            <input type="text" name="newKpis[${index}][Target_Value]" id="newKpis_${index}_Target_Value" class="form-control" required>
        `;
        container.appendChild(newKpiName);
        container.appendChild(newKpiTarget);
    });
    
</script>
@endsection