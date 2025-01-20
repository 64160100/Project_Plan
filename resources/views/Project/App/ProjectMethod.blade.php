<!-- <form id="methodForm"> -->
    <div for="formGroupExampleInput" class="form-label">วิธีการดำเนินงาน</div>
    <div id="methodContainer">
        <!-- <div class="form-group">
            <input type="text" id="field-1" name="method[]" placeholder="เพิ่มวิธีการดำเนินงาน" required oninput="handleInput(this)">
            <button type="button" class="remove-btn" onclick="removeField(this)">ลบ</button>
        </div> -->
    </div>
    <div>
        <button type="button" id="addMethodButton" class="btn-addlist"><i class='bx bx-plus-circle'></i>เพิ่มรายการ</button>
    </div>
<!-- </form> -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const methodContainer = document.getElementById('methodContainer');
        const addButton = document.getElementById('addMethodButton');
    
        function addField() {
            const newField = document.createElement('div');
            newField.className = 'form-group';
            const fieldId = 'field-' + (methodContainer.children.length + 1);
            newField.innerHTML = `
                <input type="text" id="${fieldId}" name="method[]" placeholder="เพิ่มวิธีการดำเนินงาน" required oninput="handleInput(this)">
                <button type="button" class="remove-btn" onclick="removeField(this)">ลบ</button>
            `;
            methodContainer.appendChild(newField);
        }

        window.removeField = function(button) {
            button.closest('.form-group').remove();
        }
        window.handleInput = function(input) {
            console.log('Input changed:', input.value);
        }

        if (addButton) {
            addButton.addEventListener('click', addField);
        } else {
            console.error('Add button not found');
        }
        window.removeField = removeField;
        window.handleInput = handleInput;
    });
</script>