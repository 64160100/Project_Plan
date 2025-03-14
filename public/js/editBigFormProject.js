document.addEventListener('DOMContentLoaded', function() {
    // ====== ชื่อโครงการ =======
    window.addField1 = function(containerId, fieldName) {
        const container = document.getElementById(containerId);
        if (!container) return;

        const index = container.children.length; // Start from 1
        const div = document.createElement('div');
        div.classList.add('form-group', 'mb-2', 'dynamic-field');
        div.innerHTML = `
        <div class="input-group">
            <span class="input-group-text">1.${index}</span>
            <input type="text" class="form-control" name="${fieldName}" placeholder="กรอกชื่อโครงการย่อย" required>
            <button type="button" class="btn btn-danger" onclick="removeField(this)">
                <i class='bx bx-trash'></i>
            </button>
        </div>
    `;
        container.appendChild(div);
        updateRemoveButtons(container);
    };

    window.removeField = function(button) {
        const field = button.closest('.dynamic-field');
        const container = field.parentElement;
        field.remove();
        updateRemoveButtons(container);
        updateFieldNumbers(container);
    };

    function updateRemoveButtons(container) {
        const buttons = container.querySelectorAll('.btn-danger');
        buttons.forEach(btn => {
            btn.style.display = buttons.length > 0 ? 'block' : 'none';
        });
    }

    function updateFieldNumbers(container) {
        const fields = container.querySelectorAll('.dynamic-field .input-group-text');
        fields.forEach((field, index) => {
            field.textContent = `1.${index + 1}`;
        });
    }

    // ======= ลักษณะโครงการ ==========
    window.toggleTextbox = function(radio, prefix) {
        const textboxContainer = document.querySelector(`#${prefix}2`).closest('.form-group');
        const textbox = document.getElementById(`${prefix}2`);

        if (radio.value === '2') {
            textboxContainer.style.display = 'block';
            textbox.classList.remove('hidden');
            textbox.required = true;
        } else {
            textboxContainer.style.display = 'none';
            textbox.classList.add('hidden');
            textbox.required = false;
            textbox.value = '';
        }
    };

    document.addEventListener('DOMContentLoaded', function() {
        const newProjectRadio = document.getElementById('newProject');
        const textbox = document.getElementById('textbox-projectType-2');
        if (newProjectRadio.checked) {
            textbox.classList.add('hidden');
            textbox.closest('.form-group').style.display = 'none';
        }

        const projectTypeDetails = document.querySelector('.form-group-radio');
        const toggleIcon = document.getElementById('toggleIconProjectType');
        projectTypeDetails.style.display = 'none';
        toggleIcon.classList.remove('bx-chevron-down');
        toggleIcon.classList.add('bx-chevron-up');
    });

    // ============ ผู้รับผิดชอบโครงการ============

    // ============ จัดการความสอดคล้องกับยุทธศาสตร์มหาวิทยาลัย ============
    let platformCount = 1;

    window.addPlatform = function() {
    const container = document.getElementById('platform-container');
    const platformTemplate = document.querySelector('.platform-card').cloneNode(true);

    platformTemplate.querySelector('.card-title').textContent = `แพลตฟอร์มที่ ${platformCount + 1}`;

    const inputs = platformTemplate.querySelectorAll('input');
    inputs.forEach(input => {
        const name = input.name.replace('[0]', `[${platformCount}]`);
        input.name = name;
        input.value = '';
    });

    const kpiContainer = platformTemplate.querySelector('.kpi-container');
    const kpiGroups = kpiContainer.querySelectorAll('.kpi-group');
    for (let i = 1; i < kpiGroups.length; i++) {
        kpiGroups[i].remove();
    }

    const removeBtn = platformTemplate.querySelector('.btn-danger');
    removeBtn.style.display = 'block';

    container.appendChild(platformTemplate);
    platformCount++;
    updatePlatformNumbers();
    toggleRemovePlatformButtons();
}

window.removePlatform = function(button) {
    const platformCard = button.closest('.platform-card');
    platformCard.remove();
    updatePlatformNumbers();
    toggleRemovePlatformButtons();
}

function updatePlatformNumbers() {
    const platformContainer = document.getElementById('platform-container');
    const platformCards = platformContainer.querySelectorAll('.platform-card');
    platformCards.forEach((card, index) => {
        card.querySelector('.card-title').textContent = `แพลตฟอร์มที่ ${index + 1}`;
        const inputs = card.querySelectorAll('input');
        inputs.forEach(input => {
            const name = input.name.replace(/\[\d+\]/, `[${index}]`);
            input.name = name;
        });
    });
    platformCount = platformCards.length;
}

    window.addKpi = function(btn) {
        const kpiContainer = btn.closest('.kpi-container');
        const kpiGroup = kpiContainer.querySelector('.kpi-group').cloneNode(true);

        kpiGroup.querySelector('input').value = '';

        const removeBtn = kpiGroup.querySelector('.btn-danger');
        if (!removeBtn) {
            const newRemoveBtn = document.createElement('button');
            newRemoveBtn.type = 'button';
            newRemoveBtn.className = 'btn btn-danger btn-sm remove-field';
            newRemoveBtn.innerHTML = "<i class='bx bx-trash'></i>";
            newRemoveBtn.onclick = function() {
                removeKpi(this);
            };
            kpiGroup.querySelector('.input-group').appendChild(newRemoveBtn);
        } else {
            removeBtn.style.display = 'block';
        }

        kpiContainer.appendChild(kpiGroup);
        updateKpiNumbers(kpiContainer);
        updateRemoveButtons(kpiContainer);
    }

    window.removeKpi = function(btn) {
        const kpiGroup = btn.closest('.kpi-group');
        const kpiContainer = kpiGroup.closest('.kpi-container');

        if (kpiContainer.querySelectorAll('.kpi-group').length > 1) {
            kpiGroup.remove();
            updateKpiNumbers(kpiContainer);
            updateRemoveButtons(kpiContainer);
        }
    }

    function updateKpiNumbers(kpiContainer) {
        const kpiGroups = kpiContainer.querySelectorAll('.kpi-group');
        kpiGroups.forEach((group, index) => {
            group.querySelector('.location-number').textContent = `${index + 1}`;
        });
    }

    function updateRemoveButtons(kpiContainer) {
        const kpiGroups = kpiContainer.querySelectorAll('.kpi-group');
        const removeButtons = kpiContainer.querySelectorAll('.remove-field');
        removeButtons.forEach(btn => {
            btn.style.display = kpiGroups.length > 1 ? 'block' : 'none';
        });
    }

    function toggleRemovePlatformButtons() {
        const platformContainer = document.getElementById('platform-container');
        const platformCards = platformContainer.querySelectorAll('.platform-card');
        const removeButtons = platformContainer.querySelectorAll('.remove-platform-btn');

        if (platformCards.length > 1) {
            removeButtons.forEach(button => button.style.display = 'block');
        } else {
            removeButtons.forEach(button => button.style.display = 'none');
        }
    }

    // Initial call to set the correct visibility of remove buttons
    document.addEventListener('DOMContentLoaded', toggleRemovePlatformButtons);

    // ============ ความสอดคล้องกับยุทธศาสตร์ส่วนงาน ============

    // ============ ความสอดคล้องกับ (SDGs) ============

    // ============ การบูรณาการงานโครงการ ============

    window.toggleSelectTextbox = function(checkbox) {
        const textbox = checkbox.closest('.option-item').querySelector('.additional-info');
        if (textbox) {
            textbox.disabled = !checkbox.checked;
            if (!textbox.disabled) {
                textbox.focus();
            } else {
                textbox.value = '';
            }
        }
    }

    // ============ หลักการและเหตุผล ============

    // ============ วัตถุประสงค์โครงการ ============

    // ============ จัดการกลุ่มเป้าหมาย ============
    const targetGroupContainer = document.getElementById('targetGroupContainer');
    const addTargetGroupBtn = document.getElementById('addTargetGroupBtn');

    if (addTargetGroupBtn && targetGroupContainer) {
        addTargetGroupBtn.addEventListener('click', function() {
            const targetGroupCount = targetGroupContainer.querySelectorAll('.target-group-item').length + 1;
            const newTargetGroup = document.createElement('div');
            newTargetGroup.className = 'target-group-item';
            newTargetGroup.innerHTML = `
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-text target-group-number">10.${targetGroupCount}</span>
                        <input type="text" name="target_group[]" class="form-control" placeholder="กรอกกลุ่มเป้าหมาย" required>
                        <input type="number" name="target_count[]" class="form-control" placeholder="จำนวน" required>
                        <input type="text" name="target_unit[]" class="form-control" placeholder="หน่วย" required>
                        <button type="button" class="btn btn-danger btn-sm remove-target-group">
                            <i class='bx bx-trash'></i>
                        </button>
                    </div>
                </div>
            `;
            targetGroupContainer.appendChild(newTargetGroup);
            updateTargetGroupNumbers();
            updateTargetGroupButtons();
        });

        targetGroupContainer.addEventListener('click', function(e) {
            if (e.target.closest('.remove-target-group')) {
                e.target.closest('.target-group-item').remove();
                updateTargetGroupNumbers();
                updateTargetGroupButtons();
            }
        });

        // Initialize target group numbers and buttons on page load
        updateTargetGroupNumbers();
        updateTargetGroupButtons();
    }

    function updateTargetGroupNumbers() {
        const targetGroups = targetGroupContainer.querySelectorAll('.target-group-item');
        targetGroups.forEach((item, index) => {
            const numberSpan = item.querySelector('.target-group-number');
            if (numberSpan) {
                numberSpan.textContent = `10.${index + 1}`;
            }
        });
    }

    function updateTargetGroupButtons() {
        const buttons = targetGroupContainer.querySelectorAll('.remove-target-group');
        buttons.forEach(btn => {
            btn.style.display = buttons.length > 1 ? 'block' : 'none';
        });
    }

    window.toggleTargetAreaDetails = function() {
        const targetAreaDetails = document.getElementById('targetAreaDetails');
        const checkbox = document.getElementById('targetAreaCheckbox');

        if (checkbox.checked) {
            targetAreaDetails.style.display = 'block';
        } else {
            targetAreaDetails.style.display = 'none';
        }
    }

    // ============ สถานที่ ============
    window.toggleLocationDetails = function() {
        const locationDetails = document.getElementById('locationDetails');
        const toggleIcon = document.getElementById('toggleIconLocation');

        if (locationDetails.style.display === 'none' || locationDetails.style.display === '') {
            locationDetails.style.display = 'block';
            toggleIcon.classList.remove('bx-chevron-up');
            toggleIcon.classList.add('bx-chevron-down');
        } else {
            locationDetails.style.display = 'none';
            toggleIcon.classList.remove('bx-chevron-down');
            toggleIcon.classList.add('bx-chevron-up');
        }
    }

    const locationContainer = document.getElementById('locationContainer');
    const addLocationBtn = document.getElementById('addLocationBtn');

    if (addLocationBtn && locationContainer) {
        addLocationBtn.addEventListener('click', function() {
            const locationCount = locationContainer.querySelectorAll('.location-item').length + 1;
            const newLocation = document.createElement('div');
            newLocation.className = 'form-group location-item';
            newLocation.innerHTML = `
                <div class="d-flex align-items-center">
                    <div class="input-group">
                        <span class="input-group-text location-number">11.${locationCount}</span>
                        <input type="text" class="form-control" name="location[]" 
                            placeholder="กรอกสถานที่" style="min-width: 800px;">
                        <button type="button" class="btn btn-danger btn-sm remove-location">
                            <i class='bx bx-trash'></i>
                        </button>
                    </div>
                </div>
            `;
            locationContainer.appendChild(newLocation);
            updateLocationButtons();
        });

        locationContainer.addEventListener('click', function(e) {
            if (e.target.closest('.remove-location')) {
                e.target.closest('.location-item').remove();
                updateLocationNumbers();
                updateLocationButtons();
            }
        });
        
        // Initialize location numbers on page load
        updateLocationNumbers();
    }

    function updateLocationButtons() {
        const buttons = locationContainer.querySelectorAll('.remove-location');
        buttons.forEach(btn => {
            btn.style.display = buttons.length > 1 ? 'block' : 'none';
        });
    }

    // New function to update location numbers
    function updateLocationNumbers() {
        const locations = locationContainer.querySelectorAll('.location-item');
        locations.forEach((item, index) => {
            const numberSpan = item.querySelector('.location-number');
            if (numberSpan) {
                numberSpan.textContent = `11.${index + 1}`;
            } else {
                // If the span doesn't exist, we need to add it (for existing items)
                const input = item.querySelector('input');
                if (input) {
                    const parentDiv = input.closest('.d-flex');
                    if (parentDiv) {
                        // Create the input group structure
                        const inputGroup = document.createElement('div');
                        inputGroup.className = 'input-group';
                        
                        // Create the number span
                        const numberSpan = document.createElement('span');
                        numberSpan.className = 'input-group-text location-number';
                        numberSpan.textContent = `11.${index + 1}`;
                        
                        // Get the button if it exists
                        const button = parentDiv.querySelector('.remove-location');
                        
                        // Clear and rebuild the structure
                        parentDiv.innerHTML = '';
                        inputGroup.appendChild(numberSpan);
                        inputGroup.appendChild(input);
                        if (button) inputGroup.appendChild(button);
                        parentDiv.appendChild(inputGroup);
                    }
                }
            }
        });
    }

    // ============ ระยะเวลาดำเนินโครงการ ============
    window.toggleProjectDurationDetails = function() {
        const projectDurationDetails = document.getElementById('projectDurationDetails');
        const toggleIcon = document.getElementById('toggleIconProjectDuration');

        if (projectDurationDetails.style.display === 'none' || projectDurationDetails.style.display ===
            '') {
            projectDurationDetails.style.display = 'block';
            toggleIcon.classList.remove('bx-chevron-up');
            toggleIcon.classList.add('bx-chevron-down');
        } else {
            projectDurationDetails.style.display = 'none';
            toggleIcon.classList.remove('bx-chevron-down');
            toggleIcon.classList.add('bx-chevron-up');
        }
    };

    const today = new Date().toISOString().split('T')[0];
    const startDateInput = document.getElementById('First_Time');
    const endDateInput = document.getElementById('End_Time');

    startDateInput.setAttribute('min', today);

    startDateInput.addEventListener('change', function() {
        endDateInput.setAttribute('min', this.value);
    });

    if (startDateInput.value) {
        endDateInput.setAttribute('min', startDateInput.value);
    }

    // ============ ขั้นตอนและแผนการดำเนินงาน ============

    // ============ แหล่งงบประมาณ ============
    window.toggleIncomeForm = function(radio) {
        const incomeForm = document.getElementById('incomeForm');
        incomeForm.style.display = radio.value === 'Y' ? 'block' : 'none';
    }

    window.toggleBudgetDetails = function() {
        const budgetDetails = document.getElementById('budgetDetails');
        const toggleIcon = document.getElementById('toggleIconBudget');

        const isHidden = budgetDetails.style.display === 'none' || budgetDetails.style.display === '';
        budgetDetails.style.display = isHidden ? 'block' : 'none';
        toggleIcon.classList.toggle('bx-chevron-up', !isHidden);
        toggleIcon.classList.toggle('bx-chevron-down', isHidden);
    }

    window.handleSourceSelect = function(radio) {
        const selectedId = radio.getAttribute('data-id');
        const budgetSources = document.querySelectorAll('input[name="budget_source"]');
        budgetSources.forEach(source => {
            const amountInput = document.querySelector(`input[name="amount_${source.value}"]`);
            const isSelected = source.value === selectedId;
            amountInput.disabled = !isSelected;
            if (!isSelected) amountInput.value = '';
        });
    }

    window.toggleBudgetForm = function(radio) {
        const budgetFormsContainer = document.getElementById('budgetFormsContainer');
        budgetFormsContainer.style.display = radio.value === 'yes' ? 'block' : 'none';
    }

    window.addBudgetForm = function() {
        const budgetFormsContainer = document.getElementById('budgetFormsContainer');
        const budgetFormTemplate = document.getElementById('budgetFormTemplate');
        const newBudgetForm = budgetFormTemplate.cloneNode(true);
        const formCount = budgetFormsContainer.getElementsByClassName('budget-form').length;

        newBudgetForm.style.display = 'block';
        newBudgetForm.id = '';
        newBudgetForm.querySelector('h5').innerText = `แบบฟอร์มที่ ${formCount + 1}`;
        newBudgetForm.querySelector('.remove-form-btn').style.display = 'inline-block';

        // Clear input fields in the cloned form
        newBudgetForm.querySelectorAll('textarea').forEach(textarea => textarea.value = '');
        newBudgetForm.querySelectorAll('input[type="number"]').forEach(input => input.value = '');
        newBudgetForm.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

        // Update name attributes for subActivity, description, and amount
        newBudgetForm.querySelectorAll('select[name="subActivity[]"]').forEach((select, index) => {
            select.name = `subActivity[${formCount}][]`;
        });
        newBudgetForm.querySelectorAll('textarea[name="description[]"]').forEach((textarea, index) => {
            textarea.name = `description[${formCount}][]`;
        });
        newBudgetForm.querySelectorAll('input[name="amount[]"]').forEach((input, index) => {
            input.name = `amount[${formCount}][]`;
        });

        budgetFormsContainer.appendChild(newBudgetForm);
    }

    window.addDetail = function(button) {
        const detailsContainer = button.closest('.sub-activity').querySelector('.detailsContainer');
        const formCount = button.closest('.budget-form').querySelector('h5').innerText.split(' ')[1] - 1;
        const newDetail = document.createElement('div');
        newDetail.className = 'mb-3 d-flex align-items-center detail-item';
        newDetail.innerHTML = `
        <div style="flex: 3;">
            <label class="form-label">รายละเอียด</label>
            <textarea name="description[${formCount}][]" class="form-control" placeholder="เช่น ค่าอาหารว่างสำหรับการจัดประชุมคณะกรรมการจัดการความรู้"></textarea>
        </div>
        <div style="flex: 1; margin-left: 1rem;">
            <label class="form-label">จำนวนเงิน</label>
            <div class="input-group">
                <input type="number" name="amount[${formCount}][]" class="form-control" placeholder="880">
                <span class="input-group-text">บาท</span>
            </div>
        </div>
        <button type="button" class="btn btn-danger btn-sm ml-2 remove-btn" onclick="removeDetail(this)">ลบ</button>
    `;
        detailsContainer.appendChild(newDetail);
        updateRemoveButtons(detailsContainer);
    }

    window.addSubActivity = function(button) {
        const subActivityContainer = button.closest('.budget-form').querySelector('#subActivityContainer');
        const formCount = button.closest('.budget-form').querySelector('h5').innerText.split(' ')[1] - 1;
        const newSubActivity = document.createElement('div');
        newSubActivity.className = 'sub-activity mb-3';
        newSubActivity.innerHTML = `
        <label class="form-label">หัวข้อย่อย</label>
        <select name="subActivity[${formCount}][]" class="form-control">
            <option value="" disabled selected>เลือกหัวข้อย่อย</option>
            @foreach($subtopBudgets as $subtop)
            <option value="{{ $subtop->Id_Subtopic_Budget }}">{{ $subtop->Name_Subtopic_Budget }}</option>
            @endforeach
        </select>
        <div class="detailsContainer">
            <div class="mb-3 d-flex align-items-center detail-item">
                <div style="flex: 3;">
                    <label class="form-label">รายละเอียด</label>
                    <textarea name="description[${formCount}][]" class="form-control" placeholder="เช่น ค่าอาหารว่างสำหรับการจัดประชุมคณะกรรมการจัดการความรู้"></textarea>
                </div>
                <div style="flex: 1; margin-left: 1rem;">
                    <label class="form-label">จำนวนเงิน</label>
                    <div class="input-group">
                        <input type="number" name="amount[${formCount}][]" class="form-control" placeholder="880">
                        <span class="input-group-text">บาท</span>
                    </div>
                </div>
                <button type="button" class="btn btn-danger btn-sm ml-2 remove-btn" onclick="removeDetail(this)">ลบ</button>
            </div>
        </div>
        <button type="button" class="btn btn-success btn-sm" onclick="addDetail(this)">เพิ่มรายละเอียด</button>
    `;
        subActivityContainer.appendChild(newSubActivity);
    }

    window.removeDetail = function(button) {
        const detail = button.parentElement;
        const detailsContainer = detail.parentElement;
        detail.remove();
        updateRemoveButtons(detailsContainer);
    }

    window.updateRemoveButtons = function(detailsContainer) {
        const detailItems = detailsContainer.querySelectorAll('.detail-item');
        const removeButtons = detailsContainer.querySelectorAll('.remove-btn');
        removeButtons.forEach(button => button.style.display = detailItems.length > 1 ? 'inline-block' :
            'none');
    }

    window.removeBudgetForm = function(button) {
        const budgetForm = button.closest('.budget-form');
        budgetForm.remove();
        updateFormTitles();
    }

    window.updateFormTitles = function() {
        const budgetForms = document.querySelectorAll('.budget-form');
        budgetForms.forEach((form, index) => {
            form.querySelector('h5').innerText = `แบบฟอร์มที่ ${index + 1}`;
        });
    }

    document.querySelectorAll('.detailsContainer').forEach(container => updateRemoveButtons(container));

    // ============ เป้าหมายเชิงผลผลิต (Output) ============


    // ============ ตัวชี้วัดความสำเร็จของโครงการ ============
    document.addEventListener('DOMContentLoaded', function() {
        // ปุ่มสลับการกรอกตัวชี้วัดด้วยตนเอง
        document.getElementById('toggleIndicatorInput').addEventListener('click', function() {
            const selectElement = document.getElementById('Success_Indicators');
            const textareaElement = document.getElementById('Success_Indicators_Other');

            if (textareaElement.style.display === 'none') {
                // แสดง textarea และซ่อน select
                textareaElement.style.display = 'block';
                selectElement.disabled = true;
                this.innerHTML = '<i class="bx bx-x"></i> ยกเลิกการกรอกด้วยตนเอง';
            } else {
                // ซ่อน textarea และแสดง select
                textareaElement.style.display = 'none';
                selectElement.disabled = false;
                this.innerHTML = '<i class="bx bx-edit"></i> กรอกตัวชี้วัดด้วยตนเอง';
            }
        });

        // ปุ่มสลับการกรอกค่าเป้าหมายด้วยตนเอง
        document.getElementById('toggleTargetInput').addEventListener('click', function() {
            const selectElement = document.getElementById('Value_Target');
            const textareaElement = document.getElementById('Value_Target_Other');

            if (textareaElement.style.display === 'none') {
                // แสดง textarea และซ่อน select
                textareaElement.style.display = 'block';
                selectElement.disabled = true;
                this.innerHTML = '<i class="bx bx-x"></i> ยกเลิกการกรอกด้วยตนเอง';
            } else {
                // ซ่อน textarea และแสดง select
                textareaElement.style.display = 'none';
                selectElement.disabled = false;
                this.innerHTML = '<i class="bx bx-edit"></i> กรอกค่าเป้าหมายด้วยตนเอง';
            }
        });

        // เมื่อเลือกตัวชี้วัด ให้เติมค่าเป้าหมายอัตโนมัติ
        document.getElementById('Success_Indicators').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const targetValue = selectedOption.getAttribute('data-target-value');

            if (targetValue) {
                // เคลียร์ตัวเลือกค่าเป้าหมายเดิม
                const targetSelect = document.getElementById('Value_Target');
                targetSelect.innerHTML = '<option value="" disabled>กรอกค่าเป้าหมาย</option>';

                // เพิ่มค่าเป้าหมายใหม่
                const newOption = document.createElement('option');
                newOption.value = targetValue;
                newOption.text = targetValue;
                newOption.selected = true;
                targetSelect.appendChild(newOption);
            }
        });

        // เพิ่มตัวชี้วัดและค่าเป้าหมายใหม่
        document.getElementById('addIndicatorBtn').addEventListener('click', function() {
            let indicatorValue, targetValue, targetType;

            // รับค่าตัวชี้วัด
            const indicatorTextarea = document.getElementById('Success_Indicators_Other');
            const indicatorSelect = document.getElementById('Success_Indicators');

            if (indicatorTextarea.style.display !== 'none' && indicatorTextarea.value.trim() !== '') {
                indicatorValue = indicatorTextarea.value;
            } else if (!indicatorSelect.disabled && indicatorSelect.selectedIndex > 0) {
                indicatorValue = indicatorSelect.options[indicatorSelect.selectedIndex].text;
            } else {
                alert('กรุณาเลือกหรือกรอกตัวชี้วัดความสำเร็จ');
                return;
            }

            // รับค่าเป้าหมาย
            const targetTextarea = document.getElementById('Value_Target_Other');
            const targetSelect = document.getElementById('Value_Target');

            if (targetTextarea.style.display !== 'none' && targetTextarea.value.trim() !== '') {
                targetValue = targetTextarea.value;
                targetType = 'manual';
            } else if (!targetSelect.disabled && targetSelect.selectedIndex > 0) {
                targetValue = targetSelect.options[targetSelect.selectedIndex].text;
                targetType = 'selected';
            } else {
                alert('กรุณาเลือกหรือกรอกค่าเป้าหมาย');
                return;
            }

            // สร้างรายการตัวชี้วัดใหม่จาก template
            const template = document.getElementById('indicatorItemTemplate').innerHTML;
            const newId = Date.now(); // ใช้เวลาปัจจุบันเป็น ID ชั่วคราว

            let newIndicatorHtml = template
                .replace('__ID__', newId)
                .replace('__INDICATOR__', indicatorValue)
                .replace('__INDICATOR__', indicatorValue)
                .replace('__TARGET__', targetValue)
                .replace('__TARGET__', targetValue)
                .replace('__TARGET_TYPE__', targetType);

            // เพิ่มรายการใหม่เข้าไปใน container
            document.getElementById('indicatorsContainer').insertAdjacentHTML('beforeend', newIndicatorHtml);

            // เพิ่ม event listener สำหรับปุ่มลบ
            document.querySelectorAll('.delete-indicator[data-id="' + newId + '"]').forEach(function(button) {
                button.addEventListener('click', function() {
                    this.closest('.indicator-item').remove();
                });
            });

            // รีเซ็ตฟอร์ม
            resetIndicatorForm();
        });

        // ติดตั้ง event listener สำหรับปุ่มลบที่มีอยู่แล้ว
        document.querySelectorAll('.delete-indicator').forEach(function(button) {
            button.addEventListener('click', function() {
                this.closest('.indicator-item').remove();
            });
        });

        // ฟังก์ชันรีเซ็ตฟอร์ม
        function resetIndicatorForm() {
            // รีเซ็ตตัวชี้วัด
            document.getElementById('Success_Indicators').selectedIndex = 0;
            document.getElementById('Success_Indicators').disabled = false;
            document.getElementById('Success_Indicators_Other').value = '';
            document.getElementById('Success_Indicators_Other').style.display = 'none';
            document.getElementById('toggleIndicatorInput').innerHTML = '<i class="bx bx-edit"></i> กรอกตัวชี้วัดด้วยตนเอง';

            // รีเซ็ตค่าเป้าหมาย
            document.getElementById('Value_Target').innerHTML = '<option value="" disabled selected>กรอกค่าเป้าหมาย</option>';
            document.getElementById('Value_Target').disabled = false;
            document.getElementById('Value_Target_Other').value = '';
            document.getElementById('Value_Target_Other').style.display = 'none';
            document.getElementById('toggleTargetInput').innerHTML = '<i class="bx bx-edit"></i> กรอกค่าเป้าหมายด้วยตนเอง';
        }
        
        // เพิ่มการตรวจสอบก่อนส่งฟอร์ม
        document.querySelector('form').addEventListener('submit', function(event) {
            // ตรวจสอบว่ามีการกรอกข้อมูลในฟอร์มตัวชี้วัดแต่ยังไม่ได้กดปุ่มเพิ่ม
            const indicatorSelect = document.getElementById('Success_Indicators');
            const indicatorTextarea = document.getElementById('Success_Indicators_Other');
            const targetSelect = document.getElementById('Value_Target');
            const targetTextarea = document.getElementById('Value_Target_Other');
            
            // ตรวจสอบว่ามีการกรอกข้อมูลตัวชี้วัด (ทั้งจาก select หรือ textarea)
            let hasIndicatorValue = false;
            let indicatorValue = '';
            
            if ((indicatorSelect.selectedIndex > 0 && !indicatorSelect.disabled)) {
                hasIndicatorValue = true;
                indicatorValue = indicatorSelect.options[indicatorSelect.selectedIndex].text;
            } else if (indicatorTextarea.style.display !== 'none' && indicatorTextarea.value.trim() !== '') {
                hasIndicatorValue = true;
                indicatorValue = indicatorTextarea.value.trim();
            }
            
            // ตรวจสอบว่ามีการกรอกข้อมูลค่าเป้าหมาย (ทั้งจาก select หรือ textarea)
            let hasTargetValue = false;
            let targetValue = '';
            
            if ((targetSelect.selectedIndex > 0 && !targetSelect.disabled)) {
                hasTargetValue = true;
                targetValue = targetSelect.options[targetSelect.selectedIndex].text;
            } else if (targetTextarea.style.display !== 'none' && targetTextarea.value.trim() !== '') {
                hasTargetValue = true;
                targetValue = targetTextarea.value.trim();
            }
            
            // ถ้ามีการกรอกทั้งตัวชี้วัดและค่าเป้าหมาย แต่ยังไม่ได้กดปุ่มเพิ่ม
            if (hasIndicatorValue && hasTargetValue) {
                event.preventDefault(); // หยุดการส่งฟอร์ม
                
                if (confirm('คุณได้กรอกข้อมูลตัวชี้วัดและค่าเป้าหมายแล้ว แต่ยังไม่ได้กดปุ่ม "เพิ่มตัวชี้วัดและค่าเป้าหมาย" ต้องการเพิ่มข้อมูลนี้ก่อนบันทึกหรือไม่?')) {
                    // กดปุ่มเพิ่มตัวชี้วัดให้อัตโนมัติ
                    document.getElementById('addIndicatorBtn').click();
                    
                    // ส่งฟอร์มหลังจากเพิ่มตัวชี้วัด
                    setTimeout(() => {
                        // ล้างค่าฟอร์มเพื่อไม่ให้ส่งไปพร้อมกับ indicators[] และ targets[]
                        document.getElementById('Success_Indicators_Other').value = '';
                        document.getElementById('Value_Target_Other').value = '';
                        
                        this.submit();
                    }, 300);
                } else {
                    // ล้างค่าฟอร์มเพื่อไม่ให้ส่งไปพร้อมกับ indicators[] และ targets[]
                    document.getElementById('Success_Indicators_Other').value = '';
                    document.getElementById('Value_Target_Other').value = '';
                    
                    this.submit();
                }
            } else if (hasIndicatorValue || hasTargetValue) {
                // ถ้ากรอกเพียงบางส่วน
                event.preventDefault();
                alert('กรุณากรอกทั้งตัวชี้วัดและค่าเป้าหมายให้ครบถ้วน');
            } else {
                // ถ้าทั้งคู่ว่าง ก็ล้างค่าเพื่อไม่ให้ส่งไปที่ server
                document.getElementById('Success_Indicators_Other').value = '';
                document.getElementById('Value_Target_Other').value = '';
            }
        });
    });


    // ============ ค่าเป้าหมาย ============
});