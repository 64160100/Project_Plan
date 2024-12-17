<div class="form-group">
    <div class="dropdown-options">
        @foreach ($sdgs as $Sdgs)
            <label>
                <input type="checkbox" name="sdgs[]" value="{{ $Sdgs->id_SDGs }}" onchange="toggleSelectTextbox(this)">
                {{ $Sdgs->Name_SDGs }}
            </label><br>
        @endforeach
    </div>
</div>