<div class="mb-3 col-md-6">
    <div class="mb-3">
        <label for="strategicSelect" class="form-label">เลือกยุทธศาสตร์</label>
        <select class="form-select" id="strategicSelect" name="Strategic_Id" required>
            <option value="" selected disabled>เลือกยุทธศาสตร์</option>
            @if($strategics->isNotEmpty())
                @foreach($strategics as $Strategic)
                    <option value="{{ $Strategic->Id_Strategic }}">{{ $Strategic->Name_Strategic_Plan }}</option>
                @endforeach
            @else
                <option value="" disabled></option>
            @endif
        </select>
    </div>
</div>