<div class="mb-3 col-md-6">
    <div class="mb-3">
        <label for="strategicSelect" class="form-label">กลยุทธ์</label>
        <select class="form-select" name="Name_Strategy" id="Name_Strategy" required>
            <option value="" selected disabled>เลือกกลยุทธ์</option>
            @if($strategies->isNotEmpty())    
                @foreach($strategies as $Strategies)
                    <option value="{{ $Strategies->Name_Strategy }}">{{ $Strategies->Name_Strategy }}</option>
                @endforeach
            @else
                <option value="" disable>ไม่มีกลยุทที่เกี่ยวข้อง</option>
            @endif
        </select>
    </div>
</div>