
    @foreach ($sdgs as $Sdgs)
    <div class="form-group-sdgs">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="sdgs[]" value="{{ $Sdgs->id_SDGs }}" id="flexCheckDefault" />
            <label class="form-check-label" for="flexCheckDefault">{{ $Sdgs->Name_SDGs }}</label>
        </div>
    </div>    
    @endforeach
