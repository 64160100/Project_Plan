<!-- <div id="targetGroupContainer">
    <div class="target-group-item">
        <div class="form-group">
            <input type="text" name="target_group[]" placeholder="กรอกกลุ่มเป้าหมาย" required>&nbsp;
            <input type="number" name="target_count[]" placeholder="จำนวน" required>&nbsp;&nbsp;คน
            &nbsp;&nbsp;
            <button type="button" class="btn btn-danger btn-sm remove-target-group" style="display: none;">ลบ</button>
        </div>
    </div>
</div>

<button type="button" id="addTargetGroupBtn" class="btn-addlist"><i class='bx bx-plus-circle'></i>กลุ่มเป้าหมาย</button> -->

<div>
    <div>รายละเอียดกลุ่มเป้าหมาย</div>
    <textarea class="form-control" rows="5" id="Target_Project" name="Target_Project" placeholder="เพิ่มข้อความ"></textarea>
</div>
        
    <!-- <div>รายละเอียดกลุ่มเป้าหมาย</div>
    <textarea class="form-control" rows="5" id="comment" placeholder="เพิ่มข้อความ"></textarea> -->
    


<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('targetGroupContainer');
    const addButton = document.getElementById('addTargetGroupBtn');

    addButton.addEventListener('click', function() {
        const newItem = document.createElement('div');
        newItem.className = 'target-group-item';
        newItem.innerHTML = `
            <div class="form-group">
                <input type="text" name="target_group[]" placeholder="กรอกกลุ่มเป้าหมาย" required>&nbsp;&nbsp;
                <input type="number" name="target_count[]" placeholder="จำนวน" required>
                &nbsp;&nbsp;คน
                &nbsp;&nbsp;
                <button type="button" class="btn btn-danger btn-sm remove-target-group">ลบ</button>
            </div>
        `;
        container.appendChild(newItem);
        
        // แสดงปุ่มลบสำหรับทุกรายการหลังจากเพิ่มรายการใหม่
        document.querySelectorAll('.remove-target-group').forEach(button => {
            button.style.display = 'inline-block';
        });
    });

    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-target-group')) {
            e.target.closest('.target-group-item').remove();
            
            // ซ่อนปุ่มลบถ้าเหลือเพียงรายการเดียว
            if (container.children.length === 1) {
                container.querySelector('.remove-target-group').style.display = 'none';
            }
        }
    });
});
</script>


