const First_TimeInput = document.getElementById("First_Time");
    const End_TimeInput = document.getElementById("End_Time");

    // ตั้งค่าให้วันที่เริ่มต้นต้องมากกว่าหรือเท่ากับเวลาปัจจุบัน
    const today = new Date().toISOString().split("T")[0]; // รูปแบบ YYYY-MM-DD
    First_TimeInput.min = today;

    // ตรวจสอบและอัปเดตค่า min ของวันที่สิ้นสุดเมื่อเลือกวันที่เริ่มต้น
    First_TimeInput.addEventListener("change", function () {
        const First_TimeValue = First_TimeInput.value;
        if (First_TimeValue) {
            End_TimeInput.min = First_TimeValue; // ตั้งค่า min ของวันที่สิ้นสุดให้ตรงกับวันที่เริ่มต้น
        }
    });

    // ตรวจสอบเมื่อผู้ใช้กดส่งฟอร์ม
    document.getElementById("dynamicForm").addEventListener("submit", function (event) {
        const First_TimeValue = First_TimeInput.value;
        const End_TimeValue = End_TimeInput.value;

        if (End_TimeValue && new Date(End_TimeValue) < new Date(First_TimeValue)) {
            alert("วันที่สิ้นสุดต้องอยู่หลังวันที่เริ่มต้น!");
            event.preventDefault(); // ยกเลิกการส่งฟอร์ม
        }
    });