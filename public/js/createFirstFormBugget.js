document.addEventListener('DOMContentLoaded', function() {
    // คำนวณยอดรวมเมื่อโหลดหน้า
    calculateTotal();

    // ตั้งค่าเริ่มต้นสำหรับฟอร์มวันที่
    toggleDateForm(document.getElementById('single_day'));
});

// สลับการแสดงฟอร์ม
function toggleIncomeForm(element) {
    const incomeForm = document.getElementById('incomeForm');
    if (element.value === 'Y') {
        incomeForm.style.display = 'block';
    } else {
        incomeForm.style.display = 'none';
    }
}

// จัดการฟอร์มตามประเภทวันที่ (วันเดียว/หลายวัน)
function toggleDateForm(element) {
    const addBudgetFormBtn = document.getElementById('addBudgetFormBtnContainer');
    const removeBtns = document.querySelectorAll('.remove-form-btn');

    if (element.value === 'M' || element.id === 'multiple_days') {
        // หลายวัน - แสดงปุ่มเพิ่มวัน และปุ่มลบ
        addBudgetFormBtn.style.display = 'block';

        // ถ้ามีหลายฟอร์ม ให้แสดงปุ่มลบ
        const forms = document.querySelectorAll('.budget-form');
        if (forms.length > 1) {
            removeBtns.forEach(btn => {
                btn.style.display = 'inline-block';
            });
        }
    } else {
        // วันเดียว - ซ่อนปุ่มเพิ่มวัน และปุ่มลบ
        addBudgetFormBtn.style.display = 'none';
        removeBtns.forEach(btn => {
            btn.style.display = 'none';
        });

        // ลบฟอร์มที่เกินมา (เหลือเพียงฟอร์มแรก)
        const forms = document.querySelectorAll('.budget-form');
        if (forms.length > 1) {
            for (let i = forms.length - 1; i > 0; i--) {
                forms[i].remove();
            }
        }
    }
}

// จัดการ checkbox แหล่งงบประมาณ
function handleSourceCheckbox(checkbox) {
    const amountInput = document.querySelector(`input[name="amount_${checkbox.value}"]`);
    if (checkbox.checked) {
        amountInput.disabled = false;
    } else {
        amountInput.disabled = true;
        amountInput.value = '';
    }
}

// เพิ่มรายการงบประมาณ
function addBudgetItem(containerId, category) {
    const container = document.getElementById(containerId);
    const itemDiv = document.createElement('div');
    itemDiv.className = 'budget-item mb-2';

    itemDiv.innerHTML = `
            <div class="d-flex align-items-center">
                <div style="flex: 3;">
                    <input type="text" name="item_desc[${category}][]" class="form-control mb-1" 
                        placeholder="รายละเอียดรายการ">
                </div>
                <div style="flex: 1; padding-left: 10px;">
                    <input type="text" name="item_amount[${category}][]" class="form-control calculation-input" 
                        data-category="${category}" value="0" onkeyup="calculateTotal()">
                </div>
                <div style="margin-left: 10px;">
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeItem(this)">
                        <i class="bx bx-trash"></i>
                    </button>
                </div>
            </div>
        `;

    container.appendChild(itemDiv);
}

// ลบรายการ
function removeItem(button) {
    const item = button.closest('.budget-item');
    item.remove();
    calculateTotal();
}

// Modified addBudgetForm function
function addBudgetForm() {
    const container = document.getElementById('budgetFormsContainer');

    // สร้าง ID ใหม่
    const formIndex = document.querySelectorAll('.budget-form').length;

    // สร้างฟอร์มใหม่
    const newForm = document.createElement('div');
    newForm.className = 'budget-form mb-4 pb-3 border-bottom';
    newForm.setAttribute('data-form-index', formIndex);

    // สร้าง HTML สำหรับฟอร์มใหม่ พร้อมตารางของตัวเอง - แก้ไขชื่อ input
    newForm.innerHTML = `
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div style="flex: 1;">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <label class="form-label">วันที่ดำเนินการ</label>
                        <input type="date" name="date[]" class="form-control" style="width: 200px;">
                    </div>
                    <div class="flex-grow-1">
                        <label class="form-label">รายละเอียดค่าใช้จ่ายสำหรับวันนี้</label>
                        <textarea name="budget_details[]" class="form-control"
                            placeholder="ระบุรายละเอียดค่าใช้จ่ายสำหรับวันที่นี้" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="ms-2">
                <button type="button" class="btn btn-warning btn-sm reset-form-btn" onclick="resetBudgetForm(this)">
                    <i class="bx bx-reset"></i> รีเซ็ตข้อมูล
                </button>
                <button type="button" class="btn btn-danger btn-sm remove-form-btn ms-2" onclick="removeBudgetForm(this)">
                    <i class="bx bx-trash"></i> ลบวันที่นี้
                </button>
            </div>
        </div>

        <!-- ตารางงบประมาณรวมสำหรับวันนี้ -->
        <div class="table-responsive mt-4">
            <table class="table table-bordered">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 5%;">ลำดับ</th>
                        <th style="width: 25%;">หมวดหมู่</th>
                        <th>รายการ</th>
                        <th style="width: 20%;">จำนวน (บาท)</th>
                        <th style="width: 5%;"></th>
                    </tr>
                </thead>
                <tbody id="budget_categories_container_${formIndex}" class="day-categories-container" data-day-index="${formIndex}">
                    <!-- แถวแรกสำหรับเลือกหมวดหมู่ -->
                    <tr class="category-row" data-row-id="0" data-day-id="${formIndex}">
                        <td class="align-middle text-center">1</td>
                        <td class="align-middle">
                            <select class="form-select category-select" name="budget_category[${formIndex}][]" 
                                onchange="handleDayCategorySelect(this, ${formIndex})">
                                ${getCategoryOptions()}
                            </select>
                        </td>
                        <td colspan="2" class="align-middle text-center">
                            <span class="text-muted">โปรดเลือกหมวดหมู่ก่อน</span>
                        </td>
                        <td class="align-middle text-center">
                            <!-- ปุ่มลบในแถวแรกจะถูกซ่อนไว้ -->
                            <button type="button" class="btn btn-sm btn-danger" 
                                onclick="removeDayCategoryRow(this)" style="display: none;">
                                <i class="bx bx-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5">
                            <button type="button" class="btn btn-outline-primary" 
                                onclick="addDayCategoryRow(${formIndex})">
                                <i class="bx bx-plus-circle"></i> เพิ่มหมวดหมู่
                            </button>
                        </td>
                    </tr>
                    <tr class="bg-light font-weight-bold">
                        <td colspan="3" class="text-end">รวมทั้งสิ้นสำหรับวันนี้</td>
                        <td class="text-center">
                            <span id="day_total_${formIndex}" class="day-total">0</span> บาท
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    `;

    container.appendChild(newForm);

    // อัปเดตการแสดงปุ่มลบ
    updateRemoveButtons();
}

// Helper function to get category options from the main form
function getCategoryOptions() {
    const mainSelect = document.querySelector('#budget_categories_container .category-select');
    return mainSelect ? mainSelect.innerHTML : '';
}

// อัปเดตการแสดงปุ่มลบฟอร์ม
function updateRemoveButtons() {
    const forms = document.querySelectorAll('.budget-form');
    const removeBtns = document.querySelectorAll('.remove-form-btn');

    if (forms.length > 1) {
        removeBtns.forEach(btn => {
            btn.style.display = 'inline-block';
        });
    } else {
        removeBtns.forEach(btn => {
            btn.style.display = 'none';
        });
    }
}

// Modified handleDayCategorySelect function for additional days
function handleDayCategorySelect(select, dayIndex) {
    const row = select.closest('.category-row');
    const rowId = row.getAttribute('data-row-id');
    const selectedCategoryId = select.value;
    const selectedCategoryText = select.options[select.selectedIndex].text;

    console.log("Selected category ID for day " + dayIndex + ":", selectedCategoryId);

    // Ensure the name attribute is correctly formatted for additional days
    select.name = `budget_category[${dayIndex}][]`;

    // Remove existing items container if any
    const existingContainer = row.querySelector('.items-container');
    if (existingContainer) {
        existingContainer.remove();
    }

    // Create a new container for items
    const itemsContainer = document.createElement('div');
    itemsContainer.className = 'items-container pl-3 mt-2';
    itemsContainer.id = `${selectedCategoryId}_items_day${dayIndex}_${rowId}`;

    // Update the cells to include the new container
    const cells = row.cells;
    if (cells.length === 4 && cells[2].colSpan === 2) {
        cells[2].colSpan = 1;
        cells[2].innerHTML = ''; // Clear previous content
        // Create a new cell for amount
        row.insertCell(3);
    }

    // Set up the new cells
    const descCell = row.cells[2];
    const amountCell = row.cells[3];

    // Clear previous content
    descCell.innerHTML = '';
    amountCell.innerHTML = '';

    // Add a strong tag to show the selected category
    const categoryTitle = document.createElement('strong');
    categoryTitle.textContent = selectedCategoryText;
    descCell.appendChild(categoryTitle);

    // Add hidden field for category_N array
    const hiddenField = document.createElement('input');
    hiddenField.type = 'hidden';
    hiddenField.name = `category_${dayIndex}[]`;
    hiddenField.value = selectedCategoryId;
    descCell.appendChild(hiddenField);

    // Add the items container
    descCell.appendChild(itemsContainer);

    // Add a button to add new items
    const addButton = document.createElement('button');
    addButton.type = 'button';
    addButton.className = 'btn btn-sm btn-outline-primary mt-2';
    addButton.innerHTML = '<i class="bx bx-plus"></i> เพิ่มรายการ';
    addButton.onclick = function() {
        addDayBudgetItem(itemsContainer.id, selectedCategoryId, dayIndex, rowId);
    };
    descCell.appendChild(addButton);

    // Add a span to show the total amount for the category
    amountCell.className = 'text-center align-middle';
    const totalSpan = document.createElement('span');
    totalSpan.id = `total_${selectedCategoryId}_day${dayIndex}_${rowId}`;
    totalSpan.className = 'total-category';
    totalSpan.textContent = '0';
    amountCell.appendChild(totalSpan);

    // Show delete buttons if there are more than one row
    const deleteButtons = document.querySelectorAll(`#budget_categories_container_${dayIndex} .btn-danger`);
    if (document.querySelectorAll(`#budget_categories_container_${dayIndex} .category-row`).length > 1) {
        deleteButtons.forEach(btn => {
            btn.style.display = 'inline-block';
        });
    }

    // Automatically add one item row to start with
    addDayBudgetItem(itemsContainer.id, selectedCategoryId, dayIndex, rowId);
}

// Fix the addDayCategoryRow function
function addDayCategoryRow(dayIndex) {
    const container = document.getElementById(`budget_categories_container_${dayIndex}`);
    const rows = container.querySelectorAll('.category-row');
    const newRowId = rows.length;

    const newRow = document.createElement('tr');
    newRow.className = 'category-row';
    newRow.setAttribute('data-row-id', newRowId);
    newRow.setAttribute('data-day-id', dayIndex);

    // คัดลอก select options จากแถวแรก
    const firstSelect = container.querySelector('select.category-select');
    const selectOptions = firstSelect ? firstSelect.innerHTML : getCategoryOptions();

    // สร้างเซลล์สำหรับแถวใหม่ - แก้ไขชื่อ select เป็น budget_category[dayIndex][]
    newRow.innerHTML = `
        <td class="align-middle text-center">${newRowId + 1}</td>
        <td class="align-middle">
            <select class="form-select category-select" name="budget_category[${dayIndex}][]" 
                onchange="handleDayCategorySelect(this, ${dayIndex})">
                ${selectOptions}
            </select>
        </td>
        <td colspan="2" class="align-middle text-center">
            <span class="text-muted">โปรดเลือกหมวดหมู่ก่อน</span>
        </td>
        <td class="align-middle text-center">
            <button type="button" class="btn btn-sm btn-danger" onclick="removeDayCategoryRow(this)">
                <i class="bx bx-trash"></i>
            </button>
        </td>
    `;

    container.appendChild(newRow);

    // แสดงปุ่มลบสำหรับทุกแถวเมื่อมีมากกว่า 1 แถว
    const deleteButtons = container.querySelectorAll('.btn-danger');
    if (rows.length + 1 > 1) {
        deleteButtons.forEach(btn => {
            btn.style.display = 'inline-block';
        });
    }
}

// Modified addDayBudgetItem function for additional days
function addDayBudgetItem(containerId, categoryId, dayIndex, rowId) {
    const container = document.getElementById(containerId);
    if (!container) return;

    const itemDiv = document.createElement('div');
    itemDiv.className = 'budget-item mb-2';

    // Format item_desc_N and item_amount_N for additional days
    itemDiv.innerHTML = `
        <div class="d-flex align-items-center">
            <div style="flex: 3;">
                <input type="text" name="item_desc_${dayIndex}[${categoryId}][${rowId}][]" class="form-control mb-1" 
                    placeholder="รายละเอียดรายการ">
            </div>
            <div style="flex: 1; padding-left: 10px;">
                <input type="number" name="item_amount_${dayIndex}[${categoryId}][${rowId}][]" 
                    class="form-control day-calculation-input" 
                    data-category="${categoryId}" data-day="${dayIndex}" data-row="${rowId}" 
                    value="0" onkeyup="calculateDayRowTotal('${categoryId}', ${dayIndex}, ${rowId})">
            </div>
            <div style="margin-left: 10px;">
                <button type="button" class="btn btn-sm btn-danger" 
                    onclick="removeDayItem(this, '${categoryId}', ${dayIndex}, ${rowId})">
                    <i class="bx bx-trash"></i>
                </button>
            </div>
        </div>
    `;

    container.appendChild(itemDiv);
    calculateDayRowTotal(categoryId, dayIndex, rowId);
}

// ลบรายการในวันที่เพิ่มเติม
function removeDayItem(button, category, dayIndex, rowId) {
    const item = button.closest('.budget-item');
    item.remove();
    calculateDayRowTotal(category, dayIndex, rowId);
}

// คำนวณยอดรวมแต่ละแถวในวันที่เพิ่มเติม
function calculateDayRowTotal(categoryId, dayIndex, rowId) {
    // ค้นหา inputs ที่มี data attributes ตรงกับวันที่ต้องการ
    const inputs = document.querySelectorAll(
        `input[data-category="${categoryId}"][data-day="${dayIndex}"][data-row="${rowId}"]`);
    let total = 0;

    // คำนวณยอดรวมจาก inputs ของแถวนี้
    inputs.forEach(input => {
        const value = parseFloat(input.value);
        total += value;
    });

    // อัปเดตยอดรวมของแถวนี้
    const totalSpan = document.getElementById(`total_${categoryId}_day${dayIndex}_${rowId}`);
    if (totalSpan) {
        totalSpan.textContent = formatNumber(total);
    }

    // คำนวณเฉพาะยอดรวมของวันนี้เท่านั้น โดยไม่ส่งผลกระทบต่อวันอื่น
    calculateDayTotal(dayIndex);
}


// คำนวณยอดรวมสำหรับวันแรก
function calculateDayZeroTotal() {
    let mainTotal = 0;
    const mainCategoryTotals = document.querySelectorAll('#budget_categories_container .total-category');
    mainCategoryTotals.forEach(total => {
        const value = parseFloat(total.textContent.replace(/,/g, '')) || 0;
        mainTotal += value;
    });
    
    // อัปเดตเฉพาะยอดรวมของวันแรก
    document.getElementById('grand_total').textContent = formatNumber(mainTotal);
}

// ปรับปรุง updateAllTotals function
function updateAllTotals() {
    // แยกการคำนวณเป็นของแต่ละวัน
    
    // 1. คำนวณรวมของวันแรก (ตารางหลัก)
    calculateDayZeroTotal();
    
    // 2. คำนวณรวมของแต่ละวันที่เพิ่มเติม (แยกกัน)
    const dayContainers = document.querySelectorAll('.day-categories-container');
    dayContainers.forEach(container => {
        const dayIndex = container.getAttribute('data-day-index');
        if (dayIndex && dayIndex !== '0') {
            calculateDayTotal(dayIndex);
        }
    });
    
}

// คำนวณยอดรวมทั้งหมดจากทุกวัน - แก้ไขใหม่
function calculateGrandTotal() {
    // แยกการคำนวณเป็นของแต่ละวัน

    // 1. คำนวณรวมของวันแรก (ตารางหลัก)
    let mainTotal = 0;
    const mainCategoryTotals = document.querySelectorAll('#budget_categories_container .total-category');
    mainCategoryTotals.forEach(total => {
        const value = parseFloat(total.textContent.replace(/,/g, '')) || 0;
        mainTotal += value;
    });

    // อัปเดตยอดรวมทั้งสิ้นของวันแรก
    document.getElementById('grand_total').textContent = formatNumber(mainTotal);

    // 2. คำนวณรวมของแต่ละวันที่เพิ่มเติม (แยกกัน)
    const dayForms = document.querySelectorAll('.budget-form');
    dayForms.forEach(form => {
        // ข้ามวันแรก (ฟอร์มหลัก ID=0)
        const dayIndex = form.getAttribute('data-form-index');
        if (dayIndex && dayIndex !== '0') {
            calculateDayTotal(dayIndex);
        }
    });
}

// คำนวณยอดรวมสำหรับแต่ละวัน
function calculateDayTotal(dayIndex) {
    let dayTotal = 0;
    const categoryTotals = document.querySelectorAll(`[id^="total_"][id*="_day${dayIndex}_"]`);

    categoryTotals.forEach(total => {
        const value = parseFloat(total.textContent.replace(/,/g, '')) || 0;
        dayTotal += value;
    });

    const dayTotalElement = document.getElementById(`day_total_${dayIndex}`);
    if (dayTotalElement) {
        dayTotalElement.textContent = formatNumber(dayTotal);
    }
}

// คำนวณยอดรวมแต่ละแถวในวันแรก
function calculateRowTotal(categoryId, rowId) {
    const inputs = document.querySelectorAll(`input[data-category="${categoryId}"][data-row-id="${rowId}"]`);
    let total = 0;

    inputs.forEach(input => {
        const value = parseFloat(input.value) || 0;
        total += value;
    });

    const totalSpan = document.getElementById(`total_${categoryId}_${rowId}`);
    if (totalSpan) {
        totalSpan.textContent = formatNumber(total);
    }

    // เรียกฟังก์ชันคำนวณยอดรวมของวันแรกโดยเฉพาะ
    let dayTotal = 0;
    // เลือกเฉพาะยอดรวมของแต่ละหมวดหมู่ในวันแรก (หลีกเลี่ยงการใช้ class total-category เพราะอาจจะมีในวันอื่นด้วย)
    const categoryTotals = document.querySelectorAll('#budget_categories_container [id^="total_"]:not([id*="day"])');

    categoryTotals.forEach(total => {
        const value = parseFloat(total.textContent.replace(/,/g, '')) || 0;
        dayTotal += value;
    });

    // อัปเดตยอดรวมของวันแรกโดยเฉพาะ
    document.getElementById('grand_total').textContent = formatNumber(dayTotal);
}



// ปรับปรุง event handler เพื่อให้คำนวณยอดรวมทั้งหมด
document.addEventListener('DOMContentLoaded', function() {
    // 1. คำนวณยอดรวมเมื่อโหลดหน้า
    updateAllTotals();

    // 2. ตั้งค่า event listeners สำหรับการเปลี่ยนแปลงข้อมูล
    document.body.addEventListener('input', function(e) {
        // ตรวจสอบว่าเป็น input ที่เกี่ยวข้องกับจำนวนเงินหรือไม่
        if (e.target.matches('.calculation-input') || e.target.matches('.day-calculation-input')) {
            updateAllTotals();
        }
    });

    // 3. ตั้งค่าเริ่มต้นสำหรับฟอร์มวันที่
    toggleDateForm(document.getElementById('single_day'));
});

// ลบแถวหมวดหมู่ในวันที่เพิ่มเติม
function removeDayCategoryRow(button) {
    const row = button.closest('.category-row');
    const dayId = row.getAttribute('data-day-id');
    const container = row.closest('.day-categories-container');

    // ถ้าเหลือเพียงแถวเดียว ไม่ให้ลบ
    const rows = container.querySelectorAll('.category-row');
    if (rows.length <= 1) return;

    row.remove();

    // ถ้าเหลือเพียงแถวเดียว ให้ซ่อนปุ่มลบ
    if (rows.length - 1 <= 1) {
        const lastDeleteButton = container.querySelector('.btn-danger');
        if (lastDeleteButton) lastDeleteButton.style.display = 'none';
    }

    // อัปเดตลำดับแถว
    const remainingRows = container.querySelectorAll('.category-row');
    remainingRows.forEach((row, index) => {
        row.cells[0].textContent = index + 1;
        row.setAttribute('data-row-id', index);
    });

    // อัพเดทการคำนวณรวม
    calculateDayTotal(dayId);
    calculateGrandTotal();
}

// รีเซ็ตแบบฟอร์ม - ปรับปรุงให้รีเซ็ตทุกข้อมูลในแต่ละวัน
function resetBudgetForm(button) {
    const form = button.closest('.budget-form');
    const formIndex = form.getAttribute('data-form-index');

    // 1. รีเซ็ตฟิลด์วันที่และรายละเอียด
    form.querySelector('input[name="date[]"]').value = '';
    form.querySelector('textarea[name="budget_details[]"]').value = '';

    // 2. รีเซ็ตตารางรายการงบประมาณ
    const container = formIndex === '0' ?
        document.getElementById('budget_categories_container') :
        document.getElementById(`budget_categories_container_${formIndex}`);

    if (container) {
        // 2.1 เก็บแถวแรกไว้
        const firstRow = container.querySelector('.category-row');

        // 2.2 ล้างทุกแถวในตาราง
        container.innerHTML = '';

        // 2.3 สร้างแถวแรกใหม่
        if (firstRow) {
            const newRow = document.createElement('tr');
            newRow.className = 'category-row';
            newRow.id = formIndex === '0' ? 'category-row-0' : '';
            newRow.setAttribute('data-row-id', '0');
            if (formIndex !== '0') {
                newRow.setAttribute('data-day-id', formIndex);
            }

            // กำหนดเนื้อหาสำหรับแถวแรก
            newRow.innerHTML = `
                <td class="align-middle text-center">1</td>
                <td class="align-middle">
                    <select class="form-select category-select" 
                        name="${formIndex === '0' ? 'budget_category[]' : `budget_category_${formIndex}[]`}" 
                        onchange="${formIndex === '0' ? 'handleCategorySelect(this)' : `handleDayCategorySelect(this, ${formIndex})`}">
                        <option value="" selected disabled>เลือกหมวดหมู่</option>
                        ${getCategoryOptions()}
                    </select>
                </td>
        <td colspan="2" class="align-middle text-center">
            <span class="text-muted">โปรดเลือกหมวดหมู่ก่อน</span>
        </td>
                <td class="align-middle text-center">
                    <!-- ปุ่มลบในแถวแรกจะถูกซ่อนไว้ -->
                    <button type="button" class="btn btn-sm btn-danger" 
                        onclick="${formIndex === '0' ? 'removeCategoryRow(this)' : 'removeDayCategoryRow(this)'}" 
                        style="display: none;">
                        <i class="bx bx-trash"></i>
                    </button>
                </td>
            `;

            container.appendChild(newRow);
        }
    }

    // 3. รีเซ็ตยอดรวม
    if (formIndex === '0') {
        document.getElementById('grand_total').textContent = '0';

        // รีเซ็ตตัวแปรนับจำนวนแถว (สำหรับตารางหลัก)
        categoryRowCount = 1;
    } else {
        const dayTotalElement = document.getElementById(`day_total_${formIndex}`);
        if (dayTotalElement) {
            dayTotalElement.textContent = '0';
        }
    }

    // 4. แสดงข้อความแจ้งเตือน
    alert('รีเซ็ตข้อมูลเรียบร้อยแล้ว');
}

// ลบแบบฟอร์ม
function removeBudgetForm(button) {
    const form = button.closest('.budget-form');
    form.remove();

    // ถ้าเหลือฟอร์มเดียว ให้ซ่อนปุ่มลบ
    const forms = document.querySelectorAll('.budget-form');
    if (forms.length === 1) {
        forms[0].querySelector('.remove-form-btn').style.display = 'none';
    }
}

// คำนวณยอดรวม
function calculateTotal() {
    // คำนวณยอดรวมแต่ละหมวดหมู่
    calculateCategoryTotal('compensation');
    calculateCategoryTotal('usage');
    calculateCategoryTotal('material');
    calculateCategoryTotal('other');

    // คำนวณยอดรวมทั้งหมด
    let grandTotal = 0;
    const categoryTotals = document.querySelectorAll('.total-category');

    categoryTotals.forEach(total => {
        const value = parseFloat(total.textContent.replace(/,/g, '')) || 0;
        grandTotal += value;
    });

    document.getElementById('grand_total').textContent = formatNumber(grandTotal);
}

// คำนวณยอดรวมแต่ละหมวดหมู่
function calculateCategoryTotal(category) {
    const inputs = document.querySelectorAll(`input[data-category="${category}"]`);
    let total = 0;

    inputs.forEach(input => {
        const value = parseFloat(input.value.replace(/,/g, '')) || 0;
        total += value;
    });

    document.getElementById(`total_${category}`).textContent = formatNumber(total);
}

// จัดรูปแบบตัวเลข
function formatNumber(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// ตัวแปรสำหรับเก็บจำนวนแถวหมวดหมู่
let categoryRowCount = 1;

// Modified handleCategorySelect function for the first day
function handleCategorySelect(select) {
    const row = select.closest('.category-row');
    const rowId = row.getAttribute('data-row-id');
    const selectedCategoryId = select.value;
    const selectedCategoryText = select.options[select.selectedIndex].text;

    console.log("Selected category ID:", selectedCategoryId);

    // Ensure the name attribute is correctly formatted for main form (day 0)
    select.name = `budget_category[0][]`;

    // Remove existing items container if any
    const existingContainer = row.querySelector('.items-container');
    if (existingContainer) {
        existingContainer.remove();
    }

    // Create a new container for items
    const itemsContainer = document.createElement('div');
    itemsContainer.className = 'items-container pl-3 mt-2';
    itemsContainer.id = `${selectedCategoryId}_items_${rowId}`;

    // Update the cells to include the new container
    const cells = row.cells;
    if (cells.length === 4 && cells[2].colSpan === 2) {
        cells[2].colSpan = 1;
        cells[2].innerHTML = ''; // Clear previous content
        // Create a new cell for amount
        row.insertCell(3);
    }

    // Set up the new cells
    const descCell = row.cells[2];
    const amountCell = row.cells[3];

    // Clear previous content
    descCell.innerHTML = '';
    amountCell.innerHTML = '';

    // Add a strong tag to show the selected category
    const categoryTitle = document.createElement('strong');
    categoryTitle.textContent = selectedCategoryText;
    descCell.appendChild(categoryTitle);

    // Add the items container
    descCell.appendChild(itemsContainer);

    // Add a button to add new items
    const addButton = document.createElement('button');
    addButton.type = 'button';
    addButton.className = 'btn btn-sm btn-outline-primary mt-2';
    addButton.innerHTML = '<i class="bx bx-plus"></i> เพิ่มรายการ';
    addButton.onclick = function() {
        addBudgetItem(itemsContainer.id, selectedCategoryId, rowId);
    };
    descCell.appendChild(addButton);

    // Add a span to show the total amount for the category
    amountCell.className = 'text-center align-middle';
    const totalSpan = document.createElement('span');
    totalSpan.id = `total_${selectedCategoryId}_${rowId}`;
    totalSpan.className = 'total-category';
    totalSpan.textContent = '0';
    amountCell.appendChild(totalSpan);

    // Show delete buttons if there are more than one row
    const deleteButtons = document.querySelectorAll('#budget_categories_container .btn-danger');
    if (document.querySelectorAll('#budget_categories_container .category-row').length > 1) {
        deleteButtons.forEach(btn => {
            btn.style.display = 'inline-block';
        });
    }

    // Automatically add one item row to start with
    addBudgetItem(itemsContainer.id, selectedCategoryId, rowId);
}

// เพิ่ม event listener เพื่อจัดการก่อนการส่งฟอร์ม
document.querySelector('form').addEventListener('submit', function(e) {
    // ป้องกันการส่งฟอร์มแบบปกติก่อน
    e.preventDefault();
    
    // ตรวจสอบว่าเลือก "ใช้งบประมาณ" หรือไม่
    if (!document.getElementById('income_seeking').checked) {
        // ถ้าไม่ใช้งบประมาณ ส่งฟอร์มได้เลย
        this.submit();
        return;
    }
    
    // ตรวจสอบว่าเลือกหมวดหมู่ครบหรือไม่
    const selects = document.querySelectorAll('select.category-select');
    let hasEmptyValue = false;
    
    selects.forEach(select => {
        if (select.value === "" || select.value === null) {
            hasEmptyValue = true;
        }
    });
    
    if (hasEmptyValue) {
        // ถ้าเลือก "ใช้งบประมาณ" แต่ไม่ได้เลือกหมวดหมู่
        alert('กรุณาเลือกหมวดหมู่งบประมาณก่อนส่งฟอร์ม');
        return;
    }
    
    // สร้างข้อมูลฟอร์มใหม่เพื่อปรับโครงสร้าง
    const formData = new FormData(this);
    const newForm = document.createElement('form');
    newForm.method = this.method;
    newForm.action = this.action;
    
    // เพิ่ม CSRF Token
    const csrfToken = document.querySelector('input[name="_token"]').value;
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = csrfToken;
    newForm.appendChild(csrfInput);
    
    // เพิ่มข้อมูลทั่วไปที่ไม่เกี่ยวกับงบประมาณ
    for (const [key, value] of formData.entries()) {
        // ข้ามข้อมูลงบประมาณที่จะถูกจัดการแยกต่างหาก
        if (!key.startsWith('budget_category') && 
            !key.startsWith('item_desc') && 
            !key.startsWith('item_amount') && 
            !key.startsWith('category_') && 
            !key.startsWith('item_desc_') && 
            !key.startsWith('item_amount_')) {
            
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = value;
            newForm.appendChild(input);
        }
    }
    
    // ดึงข้อมูลวันที่และรายละเอียด
    const dates = formData.getAll('date[]');
    const budgetDetails = formData.getAll('budget_details[]');
    
    // เพิ่มข้อมูลวันที่และรายละเอียด
    dates.forEach((date, dateIndex) => {
        const dateInput = document.createElement('input');
        dateInput.type = 'hidden';
        dateInput.name = `date[${dateIndex}]`;
        dateInput.value = date;
        newForm.appendChild(dateInput);
        
        const detailsInput = document.createElement('input');
        detailsInput.type = 'hidden';
        detailsInput.name = `budget_details[${dateIndex}]`;
        detailsInput.value = budgetDetails[dateIndex] || '';
        newForm.appendChild(detailsInput);
    });
    
    // จัดการเฉพาะข้อมูลวันที่ 0 สำหรับ budget_category
    const dayZeroBudgetForm = document.querySelector('.budget-form[data-form-index="0"]');
    if (dayZeroBudgetForm) {
        const categorySelects = dayZeroBudgetForm.querySelectorAll('select.category-select');
        
        categorySelects.forEach((select, selectIndex) => {
            if (select.value) {
                const categoryInput = document.createElement('input');
                categoryInput.type = 'hidden';
                categoryInput.name = `budget_category[0][${selectIndex}]`;
                categoryInput.value = select.value;
                newForm.appendChild(categoryInput);
            }
        });
    }
    
    // เพิ่มข้อมูล item_desc และ item_amount สำหรับวันแรก
    const mainCategoryRows = document.querySelectorAll('.budget-form[data-form-index="0"] .category-row');
    mainCategoryRows.forEach((row, rowIndex) => {
        const categorySelect = row.querySelector('select.category-select');
        if (categorySelect && categorySelect.value) {
            const categoryId = categorySelect.value;
            const itemsContainer = document.getElementById(`${categoryId}_items_${rowIndex}`);
            
            if (itemsContainer) {
                const items = itemsContainer.querySelectorAll('.budget-item');
                
                items.forEach((item, itemIndex) => {
                    const descInput = item.querySelector(`input[name^="item_desc[${categoryId}]"]`);
                    const amountInput = item.querySelector(`input[name^="item_amount[${categoryId}]"]`);
                    
                    if (descInput && descInput.value && amountInput) {
                        const newDescInput = document.createElement('input');
                        newDescInput.type = 'hidden';
                        newDescInput.name = `item_desc[${categoryId}][${rowIndex}][]`;
                        newDescInput.value = descInput.value;
                        newForm.appendChild(newDescInput);
                        
                        const newAmountInput = document.createElement('input');
                        newAmountInput.type = 'hidden';
                        newAmountInput.name = `item_amount[${categoryId}][${rowIndex}][]`;
                        newAmountInput.value = amountInput.value;
                        newForm.appendChild(newAmountInput);
                    }
                });
            }
        }
    });
    
    // เพิ่มข้อมูลสำหรับวันอื่นๆ
    const additionalDayForms = document.querySelectorAll('.budget-form[data-form-index]:not([data-form-index="0"])');
    additionalDayForms.forEach((dayForm) => {
        const dayIndex = dayForm.getAttribute('data-form-index');
        if (!dayIndex) return;
        
        // เพิ่มข้อมูล category_N
        const categorySelects = dayForm.querySelectorAll('select.category-select');
        categorySelects.forEach((select, selectIndex) => {
            if (select.value) {
                const categoryInput = document.createElement('input');
                categoryInput.type = 'hidden';
                categoryInput.name = `category_${dayIndex}[]`;
                categoryInput.value = select.value;
                newForm.appendChild(categoryInput);
            }
        });
        
        // เพิ่มข้อมูล item_desc_N และ item_amount_N
        const dayCategories = [...categorySelects].map(select => select.value).filter(Boolean);
        
        dayCategories.forEach((categoryId) => {
            const itemsContainerId = `${categoryId}_items_day${dayIndex}`;
            const itemsContainer = document.getElementById(itemsContainerId);
            
            if (!itemsContainer) {
                // ลองค้นหาด้วยรูปแบบอื่น (บางครั้ง rowId อาจถูกใช้)
                const allContainers = dayForm.querySelectorAll(`[id^="${categoryId}_items_day${dayIndex}"]`);
                if (allContainers.length > 0) {
                    allContainers.forEach(container => {
                        const items = container.querySelectorAll('.budget-item');
                        
                        items.forEach((item, itemIndex) => {
                            const descInput = item.querySelector(`input[name^="item_desc_${dayIndex}[${categoryId}]"]`);
                            const amountInput = item.querySelector(`input[name^="item_amount_${dayIndex}[${categoryId}]"]`);
                            
                            if (descInput && descInput.value && amountInput) {
                                const newDescInput = document.createElement('input');
                                newDescInput.type = 'hidden';
                                newDescInput.name = `item_desc_${dayIndex}[${categoryId}][][]`;
                                newDescInput.value = descInput.value;
                                newForm.appendChild(newDescInput);
                                
                                const newAmountInput = document.createElement('input');
                                newAmountInput.type = 'hidden';
                                newAmountInput.name = `item_amount_${dayIndex}[${categoryId}][][]`;
                                newAmountInput.value = amountInput.value;
                                newForm.appendChild(newAmountInput);
                            }
                        });
                    });
                }
            } else {
                const items = itemsContainer.querySelectorAll('.budget-item');
                
                items.forEach((item, itemIndex) => {
                    const descInput = item.querySelector(`input[name^="item_desc_${dayIndex}[${categoryId}]"]`);
                    const amountInput = item.querySelector(`input[name^="item_amount_${dayIndex}[${categoryId}]"]`);
                    
                    if (descInput && descInput.value && amountInput) {
                        const newDescInput = document.createElement('input');
                        newDescInput.type = 'hidden';
                        newDescInput.name = `item_desc_${dayIndex}[${categoryId}][][]`;
                        newDescInput.value = descInput.value;
                        newForm.appendChild(newDescInput);
                        
                        const newAmountInput = document.createElement('input');
                        newAmountInput.type = 'hidden';
                        newAmountInput.name = `item_amount_${dayIndex}[${categoryId}][][]`;
                        newAmountInput.value = amountInput.value;
                        newForm.appendChild(newAmountInput);
                    }
                });
            }
        });
    });

    // Log ข้อมูลที่จะส่งเพื่อตรวจสอบ
    console.log("ส่งข้อมูลในรูปแบบที่ปรับโครงสร้างแล้ว");
    
    // แทรกฟอร์มใหม่ลงใน DOM และส่ง
    document.body.appendChild(newForm);
    newForm.submit();
});

// เพิ่มแถวหมวดหมู่ใหม่
function addCategoryRow() {
    const container = document.getElementById('budget_categories_container');
    const firstRow = container.querySelector('.category-row');
    const newRow = document.createElement('tr');
    newRow.className = 'category-row';
    newRow.id = `category-row-${categoryRowCount}`;
    newRow.setAttribute('data-row-id', categoryRowCount);

    // คัดลอก select options จากแถวแรก
    const firstSelect = firstRow.querySelector('select.category-select');
    const selectOptions = firstSelect.innerHTML;

    // สร้างเซลล์สำหรับแถวใหม่ - แก้ไขชื่อ select เป็น budget_category[0][]
    newRow.innerHTML = `
        <td class="align-middle text-center">${categoryRowCount + 1}</td>
        <td class="align-middle">
            <select class="form-select category-select" name="budget_category[0][]" onchange="handleCategorySelect(this)">
                ${selectOptions}
            </select>
        </td>
        <td colspan="2" class="align-middle text-center">
            <span class="text-muted">โปรดเลือกหมวดหมู่ก่อน</span>
        </td>
        <td class="align-middle text-center">
            <button type="button" class="btn btn-sm btn-danger" onclick="removeCategoryRow(this)">
                <i class="bx bx-trash"></i>
            </button>
        </td>
    `;

    container.appendChild(newRow);
    categoryRowCount++;

    // แสดงปุ่มลบสำหรับทุกแถวเมื่อมีมากกว่า 1 แถว
    const deleteButtons = document.querySelectorAll('#budget_categories_container .btn-danger');
    if (categoryRowCount > 1) {
        deleteButtons.forEach(btn => {
            btn.style.display = 'inline-block';
        });
    }
}

// ลบแถวหมวดหมู่
function removeCategoryRow(button) {
    const row = button.closest('.category-row');

    // ถ้าเหลือเพียงแถวเดียว ไม่ให้ลบ
    const allRows = document.querySelectorAll('#budget_categories_container .category-row');
    if (allRows.length <= 1) return;

    row.remove();
    categoryRowCount--;

    // ถ้าเหลือเพียงแถวเดียว ให้ซ่อนปุ่มลบ
    if (categoryRowCount <= 1) {
        const lastDeleteButton = document.querySelector('#budget_categories_container .btn-danger');
        if (lastDeleteButton) lastDeleteButton.style.display = 'none';
    }

    // อัปเดตลำดับแถว
    const rows = document.querySelectorAll('#budget_categories_container .category-row');
    rows.forEach((row, index) => {
        row.cells[0].textContent = index + 1;
    });

    // คำนวณยอดรวมใหม่
    calculateTotal();
}

// Modified addBudgetItem function for the first day
function addBudgetItem(containerId, categoryId, rowId) {
    const container = document.getElementById(containerId);
    if (!container) return;

    const itemDiv = document.createElement('div');
    itemDiv.className = 'budget-item mb-2';

    // Format item_desc and item_amount for the main form (day 0)
    itemDiv.innerHTML = `
        <div class="d-flex align-items-center">
            <div style="flex: 3;">
                <input type="text" name="item_desc[${categoryId}][${rowId}][]" class="form-control mb-1" 
                    placeholder="รายละเอียดรายการ">
            </div>
            <div style="flex: 1; padding-left: 10px;">
                <input type="number" name="item_amount[${categoryId}][${rowId}][]" class="form-control calculation-input" 
                    data-category="${categoryId}" data-row-id="${rowId}" value="0" onkeyup="calculateRowTotal('${categoryId}', ${rowId})">
            </div>
            <div style="margin-left: 10px;">
                <button type="button" class="btn btn-sm btn-danger" onclick="removeItem(this, '${categoryId}', ${rowId})">
                    <i class="bx bx-trash"></i>
                </button>
            </div>
        </div>
    `;

    container.appendChild(itemDiv);
    calculateRowTotal(categoryId, rowId);
}

// ลบรายการ
function removeItem(button, categoryId, rowId) {
    const item = button.closest('.budget-item');
    item.remove();
    calculateRowTotal(categoryId, rowId);
}


// คำนวณยอดรวมทั้งหมด
function calculateTotal() {
    let grandTotal = 0;
    const categoryTotals = document.querySelectorAll('.total-category');

    categoryTotals.forEach(total => {
        const value = parseFloat(total.textContent.replace(/,/g, '')) || 0;
        grandTotal += value;
    });

    document.getElementById('grand_total').textContent = formatNumber(grandTotal);
}

// แปลงชื่อหมวดหมู่เป็น slug (ใช้ในการตั้งชื่อ id)
function slugify(text) {
    return text.toString().toLowerCase()
        .replace(/\s+/g, '_') // เปลี่ยนช่องว่างเป็น _
        .replace(/[^\w\-]+/g, '') // ลบอักขระที่ไม่ใช่ตัวอักษร ตัวเลข หรือ _
        .replace(/\-\-+/g, '_') // เปลี่ยน - หลายตัวเป็น _ ตัวเดียว
        .replace(/^-+/, '') // ตัด - ออกจากต้นสตริง
        .replace(/-+$/, ''); // ตัด - ออกจากท้ายสตริง
}

// จัดรูปแบบตัวเลข
function formatNumber(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}