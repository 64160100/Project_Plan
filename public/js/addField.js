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
            id="${placeholderText.Id_Sup_Project}" 
            name="${inputName}[]" 
            placeholder="${placeholderText.placeholder}"
            required 
            oninput="handleInput(this)">
        <button type="button" class="remove-btn" onclick="removeField(this)"><i class='bx bx-x'></i></button>
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
    let Id_Sup_Project = "";

    let placeholderText = "";

    if (inputName === 'Name_Sup_Project[]') {
        Id_Sup_Project = `Id_Sup_Project-${projectFieldCounter}`;
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
    return { fieldId, Id_Sup_Project, placeholder: placeholderText };
}


// function handleInput(inputElement) {
//     const fieldId = inputElement.id;         
//     const fieldName = inputElement.name;     
//     const fieldValue = inputElement.value;   
//     console.log(`Field ID: ${fieldId}, Name: ${fieldName}, Value: ${fieldValue}`);
// }

function handleInput(inputElement) {
    const Id_Sup_Project = inputElement.id;         
    const fieldName = inputElement.name;     
    const fieldValue = inputElement.value;   
    console.log(`Field ID: ${Id_Sup_Project}, Name: ${fieldName}, Value: ${fieldValue}`);
}















