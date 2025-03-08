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

    // ============ จัดการความสอดคล้องกับยุทธศาสตร์มหาวิทยาลัย============
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
    }

    window.removePlatform = function(button) {
        const platformCard = button.closest('.platform-card');
        platformCard.remove();
        updatePlatformNumbers();
    }

    window.addKpi = function(btn) {
        const kpiContainer = btn.closest('.kpi-container');
        const kpiGroup = kpiContainer.querySelector('.kpi-group').cloneNode(true);

        kpiGroup.querySelector('input').value = '';

        const removeBtn = kpiGroup.querySelector('.btn-danger');
        if (!removeBtn) {
            const newRemoveBtn = document.createElement('button');
            newRemoveBtn.type = 'button';
            newRemoveBtn.className = 'btn btn-danger';
            newRemoveBtn.innerHTML = "<i class='bx bx-trash'></i>";
            newRemoveBtn.onclick = function() {
                removeKpi(this);
            };
            kpiGroup.querySelector('.input-group').appendChild(newRemoveBtn);
        } else {
            removeBtn.style.display = 'block';
        }

        kpiContainer.appendChild(kpiGroup);
    }

    window.removeKpi = function(btn) {
        const kpiGroup = btn.closest('.kpi-group');
        const kpiContainer = kpiGroup.closest('.kpi-container');

        if (kpiContainer.querySelectorAll('.kpi-group').length > 1) {
            kpiGroup.remove();
        }
    }

    function updatePlatformNumbers() {
        const platformCards = document.querySelectorAll('.platform-card');
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
                            placeholder="กรอกสถานที่" style="min-width: 1000px;">
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


    // ============ ตัวชี้วัด ============
    window.toggleIndicatorsDetails = function() {
        const indicatorsDetails = document.getElementById('indicatorsDetails');
        const toggleIcon = document.getElementById('toggleIconIndicators');

        if (indicatorsDetails.style.display === 'none' || indicatorsDetails.style.display === '') {
            indicatorsDetails.style.display = 'block';
            toggleIcon.classList.remove('bx-chevron-up');
            toggleIcon.classList.add('bx-chevron-down');
        } else {
            indicatorsDetails.style.display = 'none';
            toggleIcon.classList.remove('bx-chevron-down');
            toggleIcon.classList.add('bx-chevron-up');
        }
    }

    window.toggleGoalInputs = function(checkbox) {
        const quantitativeInputs = document.getElementById('quantitative-inputs');
        const qualitativeInputs = document.getElementById('qualitative-inputs');

        if (checkbox.id === 'quantitative') {
            quantitativeInputs.style.display = checkbox.checked ? 'block' : 'none';
        } else if (checkbox.id === 'qualitative') {
            qualitativeInputs.style.display = checkbox.checked ? 'block' : 'none';
        }
    }

    window.addQuantitativeItem = function() {
        const quantitativeItems = document.getElementById('quantitative-items');
        const newItem = document.createElement('div');
        newItem.className = 'form-group location-item mt-2';
        const itemNumber = quantitativeItems.children.length + 1;
        newItem.innerHTML = `
            <div class="d-flex align-items-center">
                <div class="input-group">
                    <span class="input-group-text location-number">${itemNumber}</span>
                    <input type="text" class="form-control" name="quantitative[]" placeholder="เพิ่มรายการ">
                    <button type="button" class="btn btn-danger btn-sm remove-quantitative-item mt-2">
                        <i class='bx bx-trash'></i>
                    </button>
                </div>
            </div>
        `;
        quantitativeItems.appendChild(newItem);
        updateQuantitativeButtons();
    }

    window.addQualitativeItem = function() {
        const qualitativeItems = document.getElementById('qualitative-items');
        const newItem = document.createElement('div');
        newItem.className = 'form-group location-item mt-2';
        const itemNumber = qualitativeItems.children.length + 1;
        newItem.innerHTML = `
            <div class="d-flex align-items-center">
                <div class="input-group">
                    <span class="input-group-text location-number">${itemNumber}</span>
                    <input type="text" class="form-control" name="qualitative[]" placeholder="เพิ่มข้อความ">
                    <button type="button" class="btn btn-danger btn-sm remove-qualitative-item mt-2">
                        <i class='bx bx-trash'></i>
                    </button>
                </div>
            </div>
        `;
        qualitativeItems.appendChild(newItem);
        updateQualitativeButtons();
    }

    document.getElementById('quantitative-items').addEventListener('click', function(e) {
        if (e.target.closest('.remove-quantitative-item')) {
            e.target.closest('.form-group').remove();
            updateQuantitativeButtons();
        }
    });

    document.getElementById('qualitative-items').addEventListener('click', function(e) {
        if (e.target.closest('.remove-qualitative-item')) {
            e.target.closest('.form-group').remove();
            updateQualitativeButtons();
        }
    });

    function updateQuantitativeButtons() {
        const items = document.querySelectorAll('#quantitative-items .form-group');
        items.forEach((item, index) => {
            item.querySelector('.location-number').textContent = `${index + 1}`;
            const btn = item.querySelector('.remove-quantitative-item');
            btn.style.display = items.length > 1 ? 'block' : 'none';
        });
    }

    function updateQualitativeButtons() {
        const items = document.querySelectorAll('#qualitative-items .form-group');
        items.forEach((item, index) => {
            item.querySelector('.location-number').textContent = `${index + 1}`;
            const btn = item.querySelector('.remove-qualitative-item');
            btn.style.display = items.length > 1 ? 'block' : 'none';
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
    const shortTermProject = document.getElementById('shortTermProject');
    const longTermProject = document.getElementById('longTermProject');
    const textboxPlanType1 = document.getElementById('textbox-planType-1');
    const textboxPlanType2 = document.getElementById('textbox-planType-2');

    shortTermProject.addEventListener('change', function() {
        if (shortTermProject.checked) {
            textboxPlanType1.style.display = 'block';
            textboxPlanType2.style.display = 'none';
        }
    });

    longTermProject.addEventListener('change', function() {
        if (longTermProject.checked) {
            textboxPlanType2.style.display = 'block';
            textboxPlanType1.style.display = 'none';
        }
    });

    const methodContainer = document.getElementById('methodContainer');
    const addMethodBtn = document.querySelector('.btn-addlist');

    window.addMethodItem = function() {
        const newMethod = document.createElement('div');
        newMethod.className = 'form-group mt-2';
        newMethod.innerHTML = `
        <input type="text" class="form-control" name="Details_Short_Project[]" placeholder="เพิ่มรายการ">
        <button type="button" class="btn btn-danger btn-sm remove-method mt-2">
            <i class='bx bx-trash'></i>
        </button>
    `;
        methodContainer.appendChild(newMethod);
        updateMethodButtons();
    }

    methodContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-method')) {
            e.target.closest('.form-group').remove();
            updateMethodButtons();
        }
    });

    function updateMethodButtons() {
        const buttons = methodContainer.querySelectorAll('.remove-method');
        buttons.forEach(btn => {
            btn.style.display = buttons.length > 1 ? 'block' : 'none';
        });
    }

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
    window.addField = function(containerId, fieldName) {
        const container = document.getElementById(containerId);
        const newField = document.createElement('div');
        newField.className = 'form-group mt-2';
        newField.innerHTML = `
        <input type="text" class="form-control" name="${fieldName}" placeholder="เพิ่มรายการ">
        <button type="button" class="btn btn-danger btn-sm remove-field mt-2">
            <i class='bx bx-trash'></i>
        </button>
    `;
        container.appendChild(newField);
        updateFieldButtons(containerId);
    }

    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-field')) {
            e.target.closest('.form-group').remove();
            updateFieldButtons(e.target.closest('.dynamic-container').id);
        }
    });

    function updateFieldButtons(containerId) {
        const container = document.getElementById(containerId);
        const buttons = container.querySelectorAll('.remove-field');
        buttons.forEach(btn => {
            btn.style.display = buttons.length > 1 ? 'block' : 'none';
        });
    }


    // ============ ตัวชี้วัดความสำเร็จของโครงการ ============


    // ============ ค่าเป้าหมาย ============
});