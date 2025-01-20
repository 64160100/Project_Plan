<!-- <div id="kpi-form-fields">
    <div class="kpi-field">
        <label for="Name_Platform">ชื่อ Platform</label>
        <input type="text" class="form-control" name="platforms[]" placeholder="กรอกชื่อ Platform" required><br>

        <label for="Name_Object">ชื่อ obj</label>
        <input type="text" class="form-control" name="Name_Object" placeholder="กรอกชื่อ obj" required><br>

        <label for="Name_Platfrom_Kpi">ชื่อ Kpi</label>
        <input type="text" class="form-control" name="Name_Platfrom_Kpi" placeholder="กรอกชื่อ Kpi" required><br>

        <label for="Description_Platfrom_Kpi">รายละเอียด Kpi</label>
        <input type="text" class="form-control" name="Description_Platfrom_Kpi" placeholder="กรอกชื่อ Kpi" required><br>

        <label for="Budget_Year">ปีงบประมาณ</label>
        <div style="display: flex; gap: 10px;">
            <input type="text" class="form-control" name="Budget_Year" placeholder="ปีงบประมาณ..." required>
            <input type="text" class="form-control" name="Value_Platform" placeholder="จำนวน" required>
            <input type="text" class="form-control" name="Value_Platform" placeholder="หน่วย" required>
        </div>
        <br>
        <button type="button" class="remove-field" onclick="removeKpiField(this)">Remove</button>
    </div>
</div>

<div>
    <button type="button" class="btn-addlist" onclick="addKpiField()">เพิ่ม Platform</button>
</div> -->

<div id="strategic-container">
    <div class="mb-3 col-md-6 strategic-item">
        <div class="mb-3">
            <select class="form-select mb-2 mt-2" name="platform[]" required>
                <option value="" selected disabled>เลือกแพลตฟอร์ม</option>
                <option value="1">platform1</option>
                <option value="2">platform2</option>
            </select>

            <select class="form-select mb-2" name="program[]" required>
                <option value="" selected disabled>เลือกโปรแกรม</option>
                <option value="1">program1</option>
            </select>

            <select class="form-select mb-2" name="kpi[]" required>
                <option value="" selected disabled>ตัวชี้วัดมหาวิทยาลัย(KPI)</option>
                <option value="1">KPI 01</option>
            </select>
        </div>
    </div>
</div>
<button type="button" id="add-strategic-item" class="btn-addlist mt-2">เพิ่มรายการ</button>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('strategic-container');
    const addButton = document.getElementById('add-strategic-item');

    addButton.addEventListener('click', function() {
        const newItem = document.querySelector('.strategic-item').cloneNode(true);
        
        // Reset all select elements to their default value
        newItem.querySelectorAll('select').forEach(select => {
            select.selectedIndex = 0;
        });

        // Add a remove button to the new item
        const removeButton = document.createElement('button');
        removeButton.textContent = 'ลบรายการ';
        removeButton.className = 'btn btn-danger mt-2';
        removeButton.addEventListener('click', function() {
            container.removeChild(newItem);
        });

        newItem.appendChild(removeButton);
        container.appendChild(newItem);
    });
});
</script>

