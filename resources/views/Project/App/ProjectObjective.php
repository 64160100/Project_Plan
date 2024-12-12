
<form id="objectiveForm">
    <div for="formGroupExampleInput" class="form-label">วัตถุประสงค์โครงการ</div>
        <div id="objectiveContainer">
            <div class="form-group">
                <input type="text" id="field-1" name="projectObjective[]" placeholder="เพิ่มวัตถุประสงค์" required oninput="handleInput(this)">
                <button type="button" class="remove-btn" onclick="removeField(this)">Remove</button>
            </div>
        </div>
        <div>
            <button type="button" class="btn-addlist" onclick="addField('objectiveContainer', 'projectObjective[]')"><i class='bx bx-plus-circle'></i>เพิ่มรายการ</button>
        </div>
</form>