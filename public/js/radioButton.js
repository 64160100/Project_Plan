function toggleTextbox(element, textboxIdPrefix) { 
    const groupName = element.name; // ได้ชื่อกลุ่มจาก name attribute ของ element ที่ถูกคลิก
    
    // ซ่อน Textbox ทั้งหมดในกลุ่มเดียวกัน
    const allTextboxes = document.querySelectorAll(`div[data-group="${groupName}"], div[id="textbox-goal-"${groupName}"], input[type="text"][data-group="${groupName}"]`);
    allTextboxes.forEach(tb => {
        tb.classList.add('hidden');
        tb.value = ''; // ล้างค่า Textbox ที่ไม่ได้ใช้งาน
    });

    // ตรวจสอบว่ามี textbox ที่ตรงกับ element ที่เลือกหรือไม่
    const textbox = document.getElementById(`${textboxIdPrefix}${element.value}`);
    if (textbox) {
        textbox.classList.remove('hidden'); // แสดง textbox ที่ตรงกับค่าของ radio หรือ element
    }

    const allRadioGroup = document.querySelectorAll(`input[name="${groupName}"]`);
    allRadioGroup.forEach(radio => {
        radio.disabled = false; // เปิดการใช้งาน radio ทุกตัว
    });
}

