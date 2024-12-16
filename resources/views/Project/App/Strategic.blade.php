<div class="mb-3">
    <label for="strategicSelect" class="form-label">เลือกยุทธศาสตร์</label>
    <select class="form-select" id="strategicSelect" name="strategic_id" required>
        <option value="" selected disabled>เลือกยุทธศาสตร์</option>
        @foreach($strategics as $strategic)
            <option value="{{ $strategic->Id_Strategic }}">{{ $strategic->Name_Strategic_Plan }}</option>
        @endforeach
    </select>
</div>