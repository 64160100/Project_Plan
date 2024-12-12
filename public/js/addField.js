let projectFieldCounter = 2;  
let objectiveFieldCounter = 2;
let methodFieldCounter = 2;
let outputFieldCounter = 2;
let outcomeFieldCounter = 2;
let resultFieldCounter = 2;

let projectKpiFieldCounter = 2;
let kpiFieldCounter = 2;


function addField(containerId, inputName) {
    const container = document.getElementById(containerId); 
    const newField = document.createElement('div');
    const placeholderText = getPlaceholder(inputName);
    newField.classList.add('form-group');

    newField.innerHTML = `
       <input type="text" 
            id="${placeholderText.fieldId}" 
            name="${inputName}" 
            placeholder="${placeholderText.placeholder}"
            required 
            oninput="handleInput(this)">
        <button type="button" class="remove-btn" onclick="removeField(this)">Remove</button>
    `;
    container.appendChild(newField);
}

// Function to remove a specific field
function removeField(button, containerId) {
    const field = button.parentElement;
    field.remove();

     // รีเซ็ต counter และเรียงลำดับใหม่
    //  reorderFields(containerId);
}

function getPlaceholder(inputName) {
    let fieldId = "";
    let placeholderText = "";

    if (inputName === 'projectName[]') {
        fieldId = `field-${projectFieldCounter}`;
        placeholderText = "กรอกชื่อโครงการย่อย";
        projectFieldCounter++;  

    } else if (inputName === 'projectObjective[]') {
        fieldId = `field-${objectiveFieldCounter}`;
        placeholderText = "เพิ่มวัตถุประสงค์";
        objectiveFieldCounter++;  

    } else if (inputName === 'method[]') {
            fieldId = `field-${methodFieldCounter}`;
            placeholderText = "เพิ่มวิธีการดำเนินงาน";
            methodFieldCounter++;  

    } else if (inputName === 'output[]') {
        fieldId = `field-${outputFieldCounter}`;
        placeholderText = "กรอกข้อมูล";
        outputFieldCounter++;  

    } else if (inputName === 'outcome[]') {
        fieldId = `field-${outcomeFieldCounter}`;
        placeholderText = "กรอกข้อมูล";
        outcomeFieldCounter++;  
        
    } else if (inputName === 'result[]') {
        fieldId = `field-${resultFieldCounter}`;
        placeholderText = "กรอกข้อมูล";
        resultFieldCounter++;  
    } else if (inputName === 'projectKpi[]') {
        fieldId = `field-${projectKpiFieldCounter}`;
        placeholderText = "กรอกตัวชี้วัด";
        projectKpiFieldCounter++;  
    }
    else if (inputName === 'Kpi[]') {
        fieldId = `field-${kpiFieldCounter}`;
        placeholderText = "ระบุค่าเป้าหมาย ไม่น้อยกว่า...";
        kpiFieldCounter++;  
    }
    return { fieldId, placeholder: placeholderText };
}


function handleInput(inputElement) {
    const fieldId = inputElement.id;         
    const fieldName = inputElement.name;     
    const fieldValue = inputElement.value;   
    console.log(`Field ID: ${fieldId}, Name: ${fieldName}, Value: ${fieldValue}`);
}

// function reorderFields(containerId) {
//     const container = document.getElementById(containerId);
//     const fields = container.querySelectorAll('.form-group');

//     // เรียงลำดับใหม่โดยการตั้งค่า ID และตัวนับใหม่
//     let counter = (containerId === 'projectContainer') ? 2 : 2;  // เริ่มที่ 2 สำหรับทั้งสอง container

//     fields.forEach((field, index) => {
//         const input = field.querySelector('input');
//         const removeButton = field.querySelector('.remove-btn');
        
//         // อัปเดต id ใหม่สำหรับฟิลด์ที่เหลือ
//         input.id = `field-${counter}`;
//         input.name = (containerId === 'projectContainer') ? 'projectName[]' : 'objectiveProject[]';
//         input.placeholder = (containerId === 'projectContainer') ? 'กรอกชื่อโครงการย่อย' : 'เพิ่มวัตถุประสงค์';

//         // เพิ่ม event listener ใหม่ให้กับปุ่ม Remove
//         removeButton.setAttribute('onclick', `removeField(this, '${containerId}')`);

//         counter++; // เพิ่ม counter
//     });
// }


// Form submission //
// const projectForm = document.getElementById('projectForm');
// projectForm.addEventListener('submit', function (event) {
//     event.preventDefault();
//     const formData = new FormData(projectForm);
//     const items = formData.getAll('projectName[]');
//     alert('Project Names:\n' + items.join('\n'));
// });













