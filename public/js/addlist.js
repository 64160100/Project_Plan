let rows = []; // Array สำหรับเก็บข้อมูลที่จะส่งไปฐานข้อมูล

function addRow(projectId) {
    // รับค่า Input จาก UI
    const objective = document.querySelector('input[name="Objective_Project[]"]').value || null;
    const indicator = document.querySelector('input[name="Indicators_Project[]"]').value || null;
    const target = document.querySelector('input[name="Target_Project[]"]').value || null;

    // สร้างแถวใหม่
    const newRow = {
        Id_Project: projectId, // ใส่ค่า Project ID
        Objective_Project: objective,
        Indicators_Project: indicator,
        Target_Project: target,
    };

    // เพิ่มแถวเข้า Array
    rows.push(newRow);

    // // ล้างค่า Input หลังจากเพิ่มแล้ว
    // document.querySelector('input[name="Objective_Project[]"]').value = "";
    // document.querySelector('input[name="Indicators_Project[]"]').value = "";
    // document.querySelector('input[name="Target_Project[]"]').value = "";

    // console.log(rows); // ตรวจสอบข้อมูลใน console
}

function sendToDatabase() {
    // แปลงข้อมูล Array เป็น JSON
    const payload = JSON.stringify(rows);

    // ส่งข้อมูลไปยัง backend ผ่าน AJAX
    fetch('/your-backend-endpoint', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: payload,
    })
    .then(response => response.json())
    .then(data => {
        console.log('Data saved:', data);
        alert('บันทึกข้อมูลสำเร็จ!');
    })
    .catch(error => {
        console.error('Error saving data:', error);
        alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล');
    });
}
