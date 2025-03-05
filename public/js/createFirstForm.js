document.addEventListener('DOMContentLoaded', function () {
    // Strategy, Objective, and KPI Selection
    const strategySelect = document.getElementById('Name_Strategy');
    const objectiveSelect = document.getElementById('Objective_Project');
    const kpiSelect = document.getElementById('Success_Indicators');
    const targetSelect = document.getElementById('Value_Target');

    strategySelect.addEventListener('change', function () {
        const selectedStrategyId = this.value;

        Array.from(objectiveSelect.options).forEach(option => {
            option.style.display = option.getAttribute('data-strategy-id') === selectedStrategyId ? 'block' : 'none';
        });

        Array.from(kpiSelect.options).forEach(option => {
            option.style.display = option.getAttribute('data-strategy-id') === selectedStrategyId ? 'block' : 'none';
        });

        targetSelect.innerHTML = '<option value="" disabled selected>กรอกค่าเป้าหมาย</option>';
    });

    kpiSelect.addEventListener('change', function () {
        const selectedOption = kpiSelect.options[kpiSelect.selectedIndex];
        const targetValue = selectedOption.getAttribute('data-target-value');

        targetSelect.innerHTML = '<option value="" disabled selected>กรอกค่าเป้าหมาย</option>';

        if (targetValue) {
            const option = document.createElement('option');
            option.value = targetValue;
            option.textContent = targetValue;
            targetSelect.appendChild(option);
        }
    });
});

// ====== ชื่อโครงการ =======
window.addField1 = function (containerId, fieldName) {
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

window.removeField = function (button) {
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

// ====== ลักษณะโครงการ =======
document.addEventListener('DOMContentLoaded', function() {
    const newProjectRadio = document.getElementById('newProject');
    const continuousProjectRadio = document.getElementById('continuousProject');
    const textbox = document.getElementById('textbox-projectType-2');
    const projectList = document.getElementById('project-list');
    
    // Replace this with your actual strategic ID - NOT using PHP syntax
    const strategicId = "your_strategic_id_here"; // You need to set this value correctly

    window.toggleTextbox = function(radio, prefix) {
        const textboxContainer = document.querySelector(`#${prefix}2`).closest('.form-group');
        const textbox = document.getElementById(`${prefix}2`);

        if (radio.value === 'C') {
            textboxContainer.style.display = 'block';
            textbox.classList.remove('hidden');
            textbox.required = true;
        } else {
            textboxContainer.style.display = 'none';
            textbox.classList.add('hidden');
            textbox.required = false;
            textbox.value = '';
            clearProjectDetails();
        }
    };

    if (textbox) {
        textbox.addEventListener('input', function() {
            const query = this.value;
            if (query.length > 2) {
                fetch(`/projects/search?query=${query}&strategic_id=${strategicId}`)
                    .then(response => response.json())
                    .then(data => {
                        projectList.innerHTML = '';
                        if (data.length > 0) {
                            projectList.style.display = 'block';
                            data.forEach(project => {
                                const listItem = document.createElement('li');
                                listItem.textContent = project.Name_Project;
                                listItem.addEventListener('click', function() {
                                    populateProjectDetails(project);
                                    projectList.style.display = 'none';
                                });
                                projectList.appendChild(listItem);
                            });
                        } else {
                            projectList.style.display = 'none';
                        }
                    })
                    .catch(error => console.error('Error fetching project data:', error));
            } else {
                projectList.style.display = 'none';
            }
        });
    }

    function populateProjectDetails(data) {
        document.getElementById('Name_Project').value = data.Name_Project;
        // Populate other fields as needed
    }

    function clearProjectDetails() {
        document.getElementById('Name_Project').value = '';
        // Clear other fields as needed
    }

    if (newProjectRadio && newProjectRadio.checked && textbox) {
        textbox.classList.add('hidden');
        textbox.closest('.form-group').style.display = 'none';
    }

    if (continuousProjectRadio && continuousProjectRadio.checked && textbox) {
        textbox.classList.remove('hidden');
        textbox.closest('.form-group').style.display = 'block';
        textbox.required = true;
    }
});

// ====== ตัวชี้วัดความสำเร็จของโครงการ =======
document.addEventListener('DOMContentLoaded', function() {
    const successIndicatorsCheckbox = document.getElementById('Success_Indicators_Other_Checkbox');
    const successIndicatorsSelect = document.getElementById('Success_Indicators');
    const successIndicatorsOther = document.getElementById('Success_Indicators_Other');

    if (successIndicatorsCheckbox) {
        successIndicatorsCheckbox.addEventListener('change', function() {
            if (this.checked) {
                successIndicatorsOther.style.display = 'block';
                successIndicatorsOther.required = true;
                successIndicatorsSelect.disabled = true;
            } else {
                successIndicatorsOther.style.display = 'none';
                successIndicatorsOther.required = false;
                successIndicatorsOther.value = '';
                successIndicatorsSelect.disabled = false;
            }
        });
    }

    const valueTargetCheckbox = document.getElementById('Value_Target_Other_Checkbox');
    const valueTargetSelect = document.getElementById('Value_Target');
    const valueTargetOther = document.getElementById('Value_Target_Other');

    if (valueTargetCheckbox) {
        valueTargetCheckbox.addEventListener('change', function() {
            if (this.checked) {
                valueTargetOther.style.display = 'block';
                valueTargetOther.required = true;
                valueTargetSelect.disabled = true;
            } else {
                valueTargetOther.style.display = 'none';
                valueTargetOther.required = false;
                valueTargetOther.value = '';
                valueTargetSelect.disabled = false;
            }
        });
    }

    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(event) {
            // ============ ตัวชี้วัดความสำเร็จของโครงการ ============
            if (successIndicatorsCheckbox && successIndicatorsCheckbox.checked) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'Success_Indicators';
                hiddenInput.value = successIndicatorsOther.value;
                form.appendChild(hiddenInput);
                successIndicatorsOther.disabled = false;
            } else if (successIndicatorsSelect) {
                const selectedOption = successIndicatorsSelect.options[successIndicatorsSelect.selectedIndex];
                if (selectedOption) {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'Success_Indicators';
                    hiddenInput.value = selectedOption.value;
                    form.appendChild(hiddenInput);
                    successIndicatorsSelect.disabled = true;
                }
            }

            // ============ ค่าเป้าหมาย ============
            if (valueTargetCheckbox && valueTargetCheckbox.checked) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'Value_Target';
                hiddenInput.value = valueTargetOther.value;
                form.appendChild(hiddenInput);
                valueTargetOther.disabled = false;
            } else if (valueTargetSelect) {
                const selectedOption = valueTargetSelect.options[valueTargetSelect.selectedIndex];
                if (selectedOption) {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'Value_Target';
                    hiddenInput.value = selectedOption.value;
                    form.appendChild(hiddenInput);
                    valueTargetSelect.disabled = true;
                }
            }
        });
    }
});

// Set up initial values when the page loads
document.addEventListener('DOMContentLoaded', function() {
    // Initial setup for date type selection
    const singleDayRadio = document.getElementById('single_day');
    if (singleDayRadio) {
        toggleDateForm(singleDayRadio);
    }

    document.querySelectorAll('.detailsContainer').forEach(container => updateRemoveButtons(container));
    updateFormTitles();
});

document.addEventListener('DOMContentLoaded', function() {
    // Get the search input and results list elements
    const searchInput = document.getElementById('textbox-projectType-2');
    const projectList = document.getElementById('project-list');

    // Exit if elements don't exist
    if (!searchInput || !projectList) return;

    // Setup the project list display
    projectList.style.position = 'absolute';
    projectList.style.width = '100%';
    projectList.style.maxHeight = '200px';
    projectList.style.overflowY = 'auto';
    projectList.style.backgroundColor = 'white';
    projectList.style.border = '1px solid #ddd';
    projectList.style.borderRadius = '4px';
    projectList.style.marginTop = '5px';
    projectList.style.zIndex = '1000';
    projectList.style.padding = '0';
    projectList.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';

    // Get Strategic_Id from the URL or form field
    function getStrategicId() {
        // Try to get from URL parameter
        const urlParams = new URLSearchParams(window.location.search);
        const urlStrategicId = urlParams.get('Strategic_Id');
        if (urlStrategicId) return urlStrategicId;

        // Try to get from URL path segment (Strategic_Id/123)
        const pathMatch = window.location.pathname.match(/Strategic_Id\/(\d+)/);
        if (pathMatch) return pathMatch[1];

        // Try to get from hidden input field
        const hiddenInput = document.querySelector('form input[name="Strategic_Id"]');
        if (hiddenInput) return hiddenInput.value;

        // Get from data attribute if available
        if (searchInput.dataset.strategicId) return searchInput.dataset.strategicId;

        // Fallback to the route parameter extraction
        const url = window.location.pathname;
        const segments = url.split('/');
        return segments[segments.length - 1];
    }

    // Debounce function to limit API calls
    let searchTimeout;

    // Listen for input in the search box
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout); // Clear any pending timeout

        const query = this.value.trim();
        const strategicId = getStrategicId();

        // Only search if query is at least 2 characters
        if (query.length >= 2) {
            projectList.style.display = 'block';
            projectList.innerHTML =
                '<li style="padding: 8px 12px; list-style: none; font-style: italic; color: #6c757d;">กำลังค้นหา...</li>';

            // Debounce the search request
            searchTimeout = setTimeout(() => {
                // Make API request to search projects
                fetch(
                        `/search-projects?query=${encodeURIComponent(query)}&strategic_id=${encodeURIComponent(strategicId)}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(projects => {
                        projectList.innerHTML = '';

                        if (projects.length === 0) {
                            projectList.innerHTML =
                                '<li style="padding: 8px 12px; list-style: none; font-style: italic; color: #6c757d;">ไม่พบโครงการที่ค้นหา</li>';
                            return;
                        }

                        // Add each project to the results list
                        projects.forEach(project => {
                            const li = document.createElement('li');
                            li.textContent = project.Name_Project;
                            li.style.padding = '8px 12px';
                            li.style.listStyle = 'none';
                            li.style.cursor = 'pointer';
                            li.style.borderBottom = '1px solid #f0f0f0';

                            // Hover effects
                            li.addEventListener('mouseenter', function() {
                                this.style.backgroundColor = '#f8f9fa';
                            });

                            li.addEventListener('mouseleave', function() {
                                this.style.backgroundColor = '';
                            });

                            // Handle click to select this project
                            li.addEventListener('click', function() {
                                selectProject(project);
                            });

                            projectList.appendChild(li);
                        });
                    })
                    .catch(error => {
                        console.error('Error searching projects:', error);
                        projectList.innerHTML =
                            '<li style="padding: 8px 12px; list-style: none; color: #dc3545;">เกิดข้อผิดพลาดในการค้นหา</li>';
                    });
            }, 300); // Wait 300ms before sending search request
        } else {
            projectList.style.display = 'none';
            projectList.innerHTML = '';
        }
    });

    // Function to handle project selection
    function selectProject(project) {
        // Fill the search input with the selected project name
        searchInput.value = project.Name_Project;

        // Hide the project list
        projectList.style.display = 'none';

        // Create or update hidden input with selected project ID
        let hiddenInput = document.getElementById('selected-project-id');
        if (!hiddenInput) {
            hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.id = 'selected-project-id';
            hiddenInput.name = 'selected_project_id';
            searchInput.parentNode.appendChild(hiddenInput);
        }
        hiddenInput.value = project.Id_Project;

        // Pre-fill other form fields if they exist
        if (document.getElementById('Name_Project')) {
            document.getElementById('Name_Project').value = project.Name_Project;
        }

        if (document.getElementById('Name_Strategy')) {
            document.getElementById('Name_Strategy').value = project.Strategy_Id;
        }

        if (document.getElementById('employee_id')) {
            document.getElementById('employee_id').value = project.Employee_Id;
        }

        if (document.getElementById('Objective_Project')) {
            document.getElementById('Objective_Project').value = project.Objective_Project;
        }

        if (document.getElementById('Principles_Reasons')) {
            document.getElementById('Principles_Reasons').value = project.Principles_Reasons;
        }

        // Optional: Display a success message
        const successMessage = document.createElement('div');
        successMessage.className = 'alert alert-success mt-2';
        successMessage.textContent = 'โครงการ "' + project.Name_Project + '" ถูกเลือกเรียบร้อยแล้ว';
        searchInput.parentNode.appendChild(successMessage);

        // Remove the success message after 3 seconds
        setTimeout(() => {
            if (successMessage.parentNode) {
                successMessage.parentNode.removeChild(successMessage);
            }
        }, 3000);

        // Trigger a custom event for other scripts to react to this selection
        document.dispatchEvent(new CustomEvent('projectSelected', {
            detail: project
        }));
    }

    // Close the dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (!searchInput.contains(event.target) && !projectList.contains(event.target)) {
            projectList.style.display = 'none';
        }
    });

    // Handle project type radio buttons if they exist
    const continuousProjectRadio = document.getElementById('continuousProject');
    const newProjectRadio = document.getElementById('newProject');

    if (continuousProjectRadio) {
        continuousProjectRadio.addEventListener('change', function() {
            if (this.checked) {
                // Show the project search field when "continuous project" is selected
                searchInput.parentElement.style.display = 'block';
            }
        });

        // Show search box initially if continuous project is already selected
        if (continuousProjectRadio.checked) {
            searchInput.parentElement.style.display = 'block';
        }
    }

    if (newProjectRadio) {
        newProjectRadio.addEventListener('change', function() {
            if (this.checked) {
                // Hide the project search field when "new project" is selected
                searchInput.parentElement.style.display = 'none';
                projectList.style.display = 'none';
                searchInput.value = '';

                // Remove any hidden project ID field
                const hiddenInput = document.getElementById('selected-project-id');
                if (hiddenInput) {
                    hiddenInput.parentNode.removeChild(hiddenInput);
                }
            }
        });
    }
});