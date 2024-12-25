<div> 
    <label for="formGroupExampleInput" class="form-label">สร้างชื่อโครงการ</label>
    <input type="text" class="form-control" id="Name_Project" name="Name_Project" placeholder="กรอกชื่อโครงการ" required>
</div>

    <div id="projectContainer">
    @csrf
        <!-- <div class="form-group">
            <input type="text" id="Id_Sup_Project-1" name="Name_Sup_Project[]" placeholder="กรอกชื่อโครงการย่อย" required oninput="handleInput(this)">
            <button type="button" class="remove-btn" onclick="removeField(this)"><i class='bx bx-x'></i></button> 
        </div> -->
    </div>
    <div>
        <button type="button" class="btn-addlist" onclick="addField('projectContainer', 'Name_Sup_Project[]')"><i class='bx bx-plus-circle'></i>เพิ่มรายการ</button>
    </div>

