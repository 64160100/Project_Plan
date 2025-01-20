    <!-- ชื่อตัวชี้วัด -->
    <textarea class="form-control" id="Indicators_Project" name="Indicators_Project" rows="5" placeholder="เพิ่มข้อความ" required></textarea>
    <br>

    <!-- ค่าเป้าหมาย -->
    <b>กลุ่มเป้าหมาย</b>
    <textarea class="form-control" id="Target_Project" name="Target_Project" rows="5" placeholder="เพิ่มข้อความ" required></textarea>
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


        <div id="quantitative-inputs" class="quantitative-inputs" style="display: none;">
            <div id="quantitative-items">
                
            </div>
            <button type="button" class="btn-addlist" onclick="addQuantitativeItem()"><i class='bx bx-plus-circle'></i>เพิ่มรายการ</button>
        </div>
        <div id="qualitative-input" class="qualitative-input" style="display: none;">
            <textarea class="form-control" id="textbox-goal-2" rows="3" placeholder="เพิ่มข้อความ"></textarea>
        </div>


<script>
    //
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

    //
    function addQuantitativeItem() {
        const container = document.getElementById('quantitative-items');
        const newItem = document.createElement('div');
        newItem.className = 'quantitative-item';
        newItem.innerHTML = `
            <input type="text" class="full-width" placeholder="ข้อความ">
            <div class="form-group">
                <input type="text" placeholder="จำนวน">&nbsp;&nbsp;
                <input type="text" placeholder="หน่วย">
            </div>
            <button type="button" class="btn-remove" onclick="removeQuantitativeItem(this)"><i class='bx bx-minus-circle'></i>ลบรายการ</button>
        `;
        container.appendChild(newItem);
    }

    function removeQuantitativeItem(button) {
        const item = button.closest('.quantitative-item');
        item.remove();
    }
</script>