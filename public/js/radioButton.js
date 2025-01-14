function toggleTextbox(element, textboxIdPrefix) {
    const groupName = element.name;
    
    // ซ่อน Textbox ทั้งหมดในกลุ่มเดียวกัน
    const allTextboxes = document.querySelectorAll(`input[type="text"][data-group="${groupName}"]`);
    allTextboxes.forEach(tb => {
        tb.classList.add('hidden');
        tb.value = ''; // ล้างค่า Textbox ที่ไม่ได้ใช้งาน
    });

    // ตรวจสอบว่ามี textbox ที่ตรงกับ element ที่เลือกหรือไม่
    const textbox = document.getElementById(`${textboxIdPrefix}${element.value}`);
    if (textbox) {
        textbox.classList.remove('hidden'); // แสดง textbox ที่ตรงกับค่าของ radio
    }

    console.log('Toggle function called for:', groupName, 'Value:', element.value);
}

    document.addEventListener('DOMContentLoaded', function() {
        const radioButtons = document.querySelectorAll('input[type="radio"][name="projectType"]');
        radioButtons.forEach(radio => {
            radio.addEventListener('change', function() {
                toggleTextbox(this, 'textbox-projectType-');
            });
        });

    });

