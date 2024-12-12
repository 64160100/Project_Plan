<div class="mb-3 col-md-6">
    <select 
        class="form-select @error('Id_Strategic') is-invalid @enderror" 
        id="Id_Strategic"
        name="Id_Strategic"
        required>
        
        <!-- Option แรกสำหรับเลือก -->
        <option select>เลือกยุทธศาสตร์</option>

        <!-- แสดงข้อมูลจากฐานข้อมูล -->
        @foreach($strategics['Strategic'] as $Strategic)
            <option value="{{ $Strategic->Id_Strategic }}"
                {{ old('Id_Strategic') == $Strategic->Id_Strategic ? 'selected' : '' }}>
                {{ $Strategic->Name_Strategic_Plan }}
            </option>
        @endforeach
    </select>
    @error('Id_Strategic')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    <!-- กลยุท -->
    <!-- <select 
        class="form-select @error('Id_Strategic') is-invalid @enderror" 
        id="Id_Strategic"
        name="Id_Strategic"
        required>

        <option select>เลือกกลยุทธ์</option>

        
    </select> -->
</div>