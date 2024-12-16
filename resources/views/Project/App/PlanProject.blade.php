<form id="dynamicForm">
    <div id="itemContainer">
        <div class="form-group-radio">
            <input type="radio" name="planType" value="1" onchange="toggleTextbox(this, 'textbox-planType-')">
            โครงการระยะสั้น &nbsp;&nbsp;
            <input type="radio" name="planType" value="2" onchange="toggleTextbox(this, 'textbox-planType-')">
            โครงการระยะยาว 
            
        </div>

        <div class="form-group">
            <div id="textbox-planType-1" class="hidden" data-group="planType">
            <form id="methodForm">
                <div for="formGroupExampleInput" class="form-label">วิธีการดำเนินงาน</div>
                    <div id="methodContainer">
                        <div class="form-group">
                            <input type="text" id="field-1" name="method[]" placeholder="เพิ่มวิธีการดำเนินงาน" required oninput="handleInput(this)">
                            <button type="button" class="remove-btn" onclick="removeField(this)">Remove</button>
                        </div>
                    </div>
                    <div>
                        <button type="button" class="btn-addlist" onclick="addField('methodContainer', 'method[]')"><i class='bx bx-plus-circle'></i>เพิ่มรายการ</button>
                    </div>
            </form>

            </div>
 
            <div id="textbox-planType-2" class="hidden" data-group="planType">
                @include('Project.PlanTable')
            </div>
            <!-- <input type="text" id="textbox-planType-2" class="hidden" data-group="planType" placeholder="ตาราง PDCA"> -->
        </div>

        <div id="secondRadioGroup" class="form-group-radio">
            <label>แหล่งงบประมาณ<br>
                <input type="radio" name="budgetType" value="1" onclick="toggleTextbox(this, 'textbox-budgetType-')"> 
                แสวงหารายได้ &nbsp;&nbsp;
                <input type="radio" name="budgetType" value="2" onclick="toggleTextbox(this, 'textbox-budgetType-')"> 
                ไม่แสวงหารายได้
            </label>
        </div>

        <div class="form-group">
            <input type="text" id="textbox-budgetType-1" class="hidden" data-group="budgetType" placeholder="กรอกงบประมาณ">
            @include('Project.budget')
        </div>
    </div>
</form>

