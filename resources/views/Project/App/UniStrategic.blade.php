@csrf
<div id="kpi-form-fields">
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
</div>
