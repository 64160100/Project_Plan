let projectFieldCounter = 1;  
let objectiveFieldCounter = 1;
let methodFieldCounter = 1;
let outputFieldCounter = 1;
let outcomeFieldCounter = 1;
let resultFieldCounter = 1;

let projectKpiFieldCounter = 1;
let kpiFieldCounter = 1;

function addField(containerId, inputName) {
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

}

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
    return { fieldId, placeholder: placeholderText };
}


function handleInput(inputElement, inputName) {
    // updateHiddenInput(inputName);
    const fieldId = inputElement.id;
    const fieldName = inputElement.name;     
    const fieldValue = inputElement.value;   
    console.log(`Field ID: ${fieldId}, Name: ${fieldName}, Value: ${fieldValue}`);
}















