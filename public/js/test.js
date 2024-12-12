document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("myModal");
    const openModalBtn = document.getElementById("openModalBtn");
    const closeModalBtn = modal.querySelector(".close-btn");
    const modalForm = document.getElementById("modalForm");

    // เปิด Modal
    openModalBtn.addEventListener("click", () => {
        modal.classList.remove("hidden");
    });

    // ปิด Modal
    closeModalBtn.addEventListener("click", () => {
        modal.classList.add("hidden");
    });

    // ปิด Modal เมื่อคลิกข้างนอก
    window.addEventListener("click", (event) => {
        if (event.target === modal) {
            modal.classList.add("hidden");
        }
    });

    // ส่งข้อมูลจาก Modal
    modalForm.addEventListener("submit", (event) => {
        event.preventDefault();
        const inputData = document.getElementById("dataInput").value;

        // เพิ่มข้อมูลใหม่
        console.log("ข้อมูลที่เพิ่ม:", inputData);

        // ปิด Modal หลังจากบันทึกข้อมูล
        modal.classList.add("hidden");
        modalForm.reset(); // รีเซ็ตฟอร์ม
    });
});
