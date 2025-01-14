    <!-- ตัวชี้วัด -->
    <div id="projectKpiContainer">
        <div>
            <button type="button" id="add-indicators" class="btn-addlist" onclick="addField('projectKpiContainer', 'Indicators_Project[]')"><i class='bx bx-plus-circle'></i>เพิ่มรายการ</button>
        </div>
    </div>


    <br>

    <!-- ค่าเป้าหมาย -->
    <div id="targetProjectContainer">
        <div for="formGroupExampleInput" class="form-label"><b>ค่าเป้าหมาย</b></div>
        <div>
            <button type="button" id="add-targetProject" class="btn-addlist" onclick="addField('targetProjectContainer', 'Target_Project[]')"><i class='bx bx-plus-circle'></i>เพิ่มรายการ</button>
        </div>
    </div>

    <br>

    <div id="itemContainer">
        <div for="formGroupExampleInput" class="form-label"><b>ตัวชี้วัดตามเป้าหมายการให้บริการหน่วยงานและเป้าหมายผลผลิตของมหาวิทยาลัย</b></div>
        <div class="form-group-radio">
            <input type="radio" name="goal" value="1" onchange="toggleGoalInputs(this.value)">
            เชิงปริมาณ &nbsp;&nbsp;
            <input type="radio" name="goal" value="2" onchange="toggleGoalInputs(this.value)">
            เชิงคุณภาพ
        </div>
    </div>
    
    <div class="form-group">
        <div id="quantitative-inputs" style="display: none;">
            <input type="text" id="textbox-goal-1" placeholder="ข้อความ"><br>
            <input type="text" id="textbox-goal-amount" placeholder="จำนวน">
            <input type="text" id="textbox-goal-unit" placeholder="หน่วย">
        </div>
        <div id="qualitative-input" style="display: none;">
            <input type="text" id="textbox-goal-2" placeholder="ข้อความ">
        </div>
    </div>
    


<!-- </form> -->

<script>
    function toggleGoalInputs(value) {
        const quantitativeInputs = document.getElementById('quantitative-inputs');
        const qualitativeInput = document.getElementById('qualitative-input');
    
        if (value === '1') {
            quantitativeInputs.style.display = 'block';
            qualitativeInput.style.display = 'none';
        } else if (value === '2') {
            quantitativeInputs.style.display = 'none';
            qualitativeInput.style.display = 'block';
        }
    }
</script>