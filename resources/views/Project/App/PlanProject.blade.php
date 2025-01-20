<form id="dynamicForm">
    <div id="itemContainer">
        <!-- <div class="form-group-radio">
            <input type="radio" name="planType" value="1" onchange="toggleTextbox(this, 'textbox-planType-')">
            โครงการระยะสั้น &nbsp;&nbsp;
            <input type="radio" name="planType" value="2" onchange="toggleTextbox(this, 'textbox-planType-')">
            โครงการระยะยาว 
        </div> -->

        <div class="form-group-radio">
            <input type="radio" name="planType" value="1" id="shortTermProject">
            <label for="shortTermProject">โครงการระยะสั้น</label>
            &nbsp;&nbsp;
            <input type="radio" name="planType" value="2" id="longTermProject">
            <label for="longTermProject">โครงการระยะยาว</label>
        </div>

        <!-- วิธีการดำเนินงาน -->
        <div class="form-group">
        <div id="textbox-planType-1" class="hidden" data-group="planType">
            @include('Project.App.ProjectMethod')
        </div>
 
            <div id="textbox-planType-2" class="hidden" data-group="planType">
                @include('Project.App.PlanTable')
            </div>
            <!-- <input type="text" id="textbox-planType-2" class="hidden" data-group="planType" placeholder="ตาราง PDCA"> -->
        </div>

        <!-- แหล่งงบประมาณ -->
        <div id="secondRadioGroup" class="form-group-radio">
            <label>ประเภทโครงการ<br>
                <input type="radio" name="budgetType" value="1" onchange="toggleIncomeForm(this)"> 
                แสวงหารายได้ &nbsp;&nbsp;
                <input type="radio" name="budgetType" value="2" onchange="toggleIncomeForm(this)"> 
                ไม่แสวงหารายได้
            </label>
        </div>

        <div id="incomeForm" class="form-group hidden">
            <label>แหล่งงบประมาณ<br>
            <input type="text" data-group="budgetType" placeholder="เงินรายได้"> 
            <input type="text" data-group="budgetType" placeholder="จำนวน"> 
            <input type="text" data-group="budgetType" placeholder="หน่วย เช่น บาท"> 
            
            <div>
                <label>แผนงาน<br>
                <div id="funcAreaContainer">
                    <button type="button" id="add-funcArea" class="btn-addlist" onclick="addField('funcAreaContainer', 'functionalArea[]')"><i class='bx bx-plus-circle'></i>เพิ่มรายการ</button>
                </div>
            </div>

            <label>หน่วยงาน<br>
            <input type="text" data-group="budgetType" placeholder="หน่วยงาน"><br>
            <label>ศูนย์ต้นทุน<br>
            <input type="text" data-group="budgetType" placeholder="ศูนย์ต้นทุน"> 

            <!-- หมวดรายจ่าย -->
            <div>
                <div class="mb-3 col-md-6">
                    <div class="mb-3">
                        <label for="expenseCategory" class="form-label">หมวดรายจ่าย</label>
                        <select class="form-select" name="expenseCategory" id="expenseCategory" required>
                            <option value="" selected disabled>เลือกหมวดรายจ่าย</option>
                            <option value="1">ค่าตอบแทน</option>
                            <option value="2">งบบุคลากร</option>
                        </select>
                    </div>
                </div>

                <div id="expenseContentBox" class="content-box-expense" style="display: none;">
                    <div id="selectedExpenses" class="mb-3">
                        <!-- ตัวเลือกที่ถูกเลือกจะถูกแสดงที่นี่ -->
                    </div>
                    <button type="button" id="addExpenseBtn" class="btn-addlist"><i class='bx bx-plus-circle'></i>เพิ่มรายการ</button>
                </div>
            </div>
            <!-- end หมวดรายจ่าย -->

            <div>
                <label>รวมค่าใช้จ่าย<br>
                <input type="text" data-group="budgetType" placeholder="จำนวน"> 
                <input type="text" data-group="budgetType" placeholder="หน่วย"> 
            </div>
            
        </div>
    </div>
</form>
<div></div>

<!-- ระยะโครงการ -->
 <script>
    document.addEventListener('DOMContentLoaded', function() {
        const planTypeRadios = document.querySelectorAll('input[name="planType"]');
        const shortTermContent = document.getElementById('textbox-planType-1');
        const longTermContent = document.getElementById('textbox-planType-2');
    
        function togglePlanTypeContent() {
            planTypeRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === '1') {
                        shortTermContent.classList.remove('hidden');
                        longTermContent.classList.add('hidden');
                    } else if (this.value === '2') {
                        shortTermContent.classList.add('hidden');
                        longTermContent.classList.remove('hidden');
                    }
                });
            });
        }
    
        togglePlanTypeContent();
    });
 </script>

<!-- แหล่งงบประมาณ -->
<script>
    function toggleIncomeForm(radio) {
        const incomeForm = document.getElementById('incomeForm');
        
        if (radio.value === '1') { // แสวงหารายได้
            incomeForm.classList.remove('hidden');
        } else { // ไม่แสวงหารายได้
            incomeForm.classList.add('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const incomeForm = document.getElementById('incomeForm');
        if (incomeForm) {
            incomeForm.classList.add('hidden');
        }
    });
</script>

<!-- ระยะโครงการ -->


<!-- หมวดรายจ่าย -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const expenseCategorySelect = document.getElementById('expenseCategory');
    const selectedExpensesDiv = document.getElementById('selectedExpenses');
    const addExpenseBtn = document.getElementById('addExpenseBtn');
    const expenseContentBox = document.getElementById('expenseContentBox');

    expenseCategorySelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const expenseId = this.value;
        const expenseName = selectedOption.text;

        if (expenseId) {
            expenseContentBox.style.display = 'block';
            expenseContentBox.style.width = '100%'
            expenseContentBox.style.maxWidth = '100%';
            addExpenseItem(expenseId, expenseName);
            this.selectedIndex = 0; // รีเซ็ตการเลือกs
            addExpenseBtn.style.display = 'inline-block';
        }
    });

    function adjustExpenseContentBoxWidth() {
        const parentWidth = expenseContentBox.parentElement.offsetWidth;
        expenseContentBox.style.width = parentWidth + 'px';
        expenseContentBox.style.maxWidth = parentWidth + 'px';
    }

    window.addEventListener('load', adjustExpenseContentBoxWidth);
    window.addEventListener('resize', adjustExpenseContentBoxWidth);

    addExpenseBtn.addEventListener('click', function() {
        const selectedOption = expenseCategorySelect.options[expenseCategorySelect.selectedIndex];
        if (selectedOption.value) {
            addExpenseItem(selectedOption.value, selectedOption.text);
        } else {
            // ถ้าไม่มีตัวเลือกที่เลือกไว้ ให้เพิ่มรายการใหม่โดยใช้ข้อมูลจากรายการล่าสุด
            const lastExpenseItem = selectedExpensesDiv.lastElementChild;
            if (lastExpenseItem) {
                const lastExpenseId = lastExpenseItem.querySelector('input[name^="expense["]').name.match(/\d+/)[0];
                const lastExpenseName = lastExpenseItem.querySelector('div').textContent.replace('✓', '').trim();
                addExpenseItem(lastExpenseId, lastExpenseName);
            }
        }
        expenseCategorySelect.selectedIndex = 0;
    });

    function addExpenseItem(id, name) {
        const expenseItem = document.createElement('div');
        // const cleanName = name.replace(/ลบรายการนี้/g, '').trim();

        expenseItem.classList.add('expense-item', 'mb-2');
        expenseItem.innerHTML = `
        <div style="width: 100%">
            <div><i class='bx bx-check-circle'></i>${name}</div>
            <input type="text" name="expense[${id}][desc]" placeholder="กรอกรายละเอียด" required>
            <input type="text" name="expense[${id}][amount]" placeholder="จำนวน" required>
            <input type="text" name="expense[${id}][unit]" placeholder="หน่วย" required>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeExpenseItem(this)">
                <i class='bx bx-trash'></i>
            </button>
        </div>
        `;
        selectedExpensesDiv.appendChild(expenseItem);
    }

    window.removeExpenseItem = function(button) {
        const itemToRemove = button.closest('.expense-item');
        selectedExpensesDiv.removeChild(itemToRemove);

        if (selectedExpensesDiv.children.length === 0) {
            addExpenseBtn.style.display = 'none';
            expenseContentBox.style.display = 'none';
        }
    };
});
</script>
