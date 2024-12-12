<div> 
    <label for="formGroupExampleInput" class="form-label" name="Name_Project">สร้างชื่อโครงการ</label>
    <input type="text" class="form-control" id="Name_Project" value="{{ old('Name_Project') }}" placeholder="กรอกชื่อโครงการ" fdprocessedid="2hlulq">
</div>


<form id="projectForm">
    <div id="projectContainer">
        <div class="form-group">
            <input type="text" id="field-1" name="projectName[]" placeholder="กรอกชื่อโครงการย่อย" required oninput="handleInput(this)">
            <button type="button" class="remove-btn" onclick="removeField(this)">Remove</button>
        </div>
    </div>
    <div>
        <button type="button" class="btn-addlist" onclick="addField('projectContainer', 'projectName[]')"><i class='bx bx-plus-circle'></i>เพิ่มรายการ</button>
    </div>
</form>