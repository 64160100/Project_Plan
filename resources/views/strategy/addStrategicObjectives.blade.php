@extends('navbar.app')

@section('content')
<div class="container">
    <h1>เพิ่มวัตถุประสงค์เชิงกลยุทธ์(Strategic Objectives : SO)</h1>

    <form action="{{ route('StrategicObjectives.add', $Id_Strategy) }}" method="POST">
        @csrf
        <div id="objective-container">
            <div class="objective-entry mb-3">
                <label for="Details_Strategic_Objectives" class="form-label">รายละเอียดวัตถุประสงค์เชิงกลยุทธ์</label>
                <textarea class="form-control" name="Details_Strategic_Objectives[]" placeholder="กรอกรายละเอียดวัตถุประสงค์เชิงกลยุทธ์" required></textarea>
            </div>
        </div>
        <button type="button" class="btn btn-secondary mb-3" id="add-objective">เพิ่มวัตถุประสงค์</button>
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">ถัดไป</button>
        </div>
    </form>
</div>

<script>
    document.getElementById('add-objective').addEventListener('click', function() {
        const objectiveContainer = document.getElementById('objective-container');
        const newObjectiveEntry = document.createElement('div');
        newObjectiveEntry.classList.add('objective-entry', 'mb-3');
        newObjectiveEntry.innerHTML = `
            <label for="Details_Strategic_Objectives" class="form-label">รายละเอียดวัตถุประสงค์เชิงกลยุทธ์</label>
            <textarea class="form-control" name="Details_Strategic_Objectives[]" placeholder="กรอกรายละเอียดวัตถุประสงค์เชิงกลยุทธ์" required></textarea>
            <button type="button" class="btn btn-danger remove-objective">ลบ</button>
        `;
        objectiveContainer.appendChild(newObjectiveEntry);
    });

    document.getElementById('objective-container').addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-objective')) {
            event.target.parentElement.remove();
        }
    });
</script>

@endsection