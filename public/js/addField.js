let projectFieldCounter = 1;  
let objectiveFieldCounter = 1;
let methodFieldCounter = 1;
let outputFieldCounter = 1;
let outcomeFieldCounter = 1;
let resultFieldCounter = 1;

let projectKpiFieldCounter = 1;
let kpiFieldCounter = 1;

function addField(containerId, inputName, projectId) {
    const container = document.getElementById(containerId); 
    const newField = document.createElement('div');
    const placeholderText = getPlaceholder(inputName);
    newField.classList.add('form-group');

    newField.innerHTML = `
       <input type="text" 
            id="${placeholderText.fieldId}" 
            name="${inputName}[]"  
            placeholder="${placeholderText.placeholder}"
            required 
            oninput="handleInput(this)">
        <button type="button" class="remove-btn" onclick="removeField(this)"><i class='bx bx-x'></i></button>
    `;

    const addButton = container.lastElementChild;
    container.insertBefore(newField, addButton);

    // updateHiddenInput(inputName);
}

// Function to remove a specific field
function removeField(button, containerId) {
    const field = button.parentElement;
    field.remove();
}

function getPlaceholder(inputName) {
    let fieldId = "";
    // let Id_Sup_Project = "";

    let placeholderText = "";

    if (inputName === 'Name_Sup_Project[]') { //
        fieldId = `field-${projectFieldCounter}`;
        placeholderText = "กรอกชื่อโครงการย่อย";
        projectFieldCounter++;  

    } else if (inputName === 'Objective_Project[]') { //
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
        
    } else if (inputName === 'Indicators_Project[]') { //
        fieldId = `field-${projectKpiFieldCounter}`;
        placeholderText = "กรอกตัวชี้วัด";
        projectKpiFieldCounter++;  
    }
    else if (inputName === 'Target_Project[]') { //
        fieldId = `field-${kpiFieldCounter}`;
        placeholderText = "ระบุค่าเป้าหมาย ไม่น้อยกว่า...";
        kpiFieldCounter++;  
    }
    return { fieldId, Id_Sup_Project, placeholder: placeholderText };
}


// function updateHiddenInput(inputName) {
//     const inputs = document.getElementsByName(inputName);

//     // ลบ input ที่มีอยู่ก่อน เพื่ออัปเดตใหม่
//     const existingInputs = document.querySelectorAll(`[data-input-name="${inputName}"]`);
//     existingInputs.forEach(input => input.remove());

//     // สร้าง input ใหม่แยกแต่ละรายการ
//     inputs.forEach((input, index) => {
//         const hiddenInput = document.createElement('input');
//         hiddenInput.type = 'hidden';
//         hiddenInput.name = `${inputName}[${index}]`; // ชื่อแบบ array เพื่อส่งแยก
//         hiddenInput.value = input.value;
//         hiddenInput.setAttribute('data-input-name', inputName); // ใช้สำหรับค้นหาและลบ
//         document.querySelector('form').appendChild(hiddenInput);
//     });
// }

function handleInput(inputElement, inputName) {
    // updateHiddenInput(inputName);
    const fieldId = inputElement.id;
    const fieldName = inputElement.name;     
    const fieldValue = inputElement.value;   
    console.log(`Field ID: ${fieldId}, Name: ${fieldName}, Value: ${fieldValue}`);
}


// document.addEventListener('DOMContentLoaded', function() {
//     const addButtons = document.querySelectorAll('[id^="add-"]');
//     addButtons.forEach(button => {
//         const containerId = button.getAttribute('data-container');
//         const inputName = button.getAttribute('data-input');
//         button.addEventListener('click', () => addField(containerId, inputName));
//     });
// });















