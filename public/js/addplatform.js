// document.addEventListener('DOMContentLoaded', function () {
//     const addNameObjectButton = document.getElementById('addNameObject');
//     const nameObjectContainer = document.getElementById('nameObjectContainer');

//     addNameObjectButton.addEventListener('click', function () {
//         const newInputGroup = document.createElement('div');
//         newInputGroup.classList.add('mb-3');

//         newInputGroup.innerHTML = `
//             <input type="text" class="form-control" name="Name_Object[]" placeholder="กรอกชื่อวัตถุประสงค์" required>
//         `;

//         nameObjectContainer.appendChild(newInputGroup);
//     });
// });

document.addEventListener('DOMContentLoaded', function () {
    const addNameObjectButton = document.getElementById('addNameObject');
    const nameObjectContainer = document.getElementById('nameObjectContainer');

    addNameObjectButton.addEventListener('click', function () {
        // สร้าง container ใหม่สำหรับ input และปุ่มลบ
        const newInputGroup = document.createElement('div');
        newInputGroup.classList.add('mb-3', 'd-flex', 'align-items-center');

        newInputGroup.innerHTML = `
            <input type="text" class="form-control me-2" name="Name_Object[]" placeholder="กรอกชื่อวัตถุประสงค์" required>
            <button type="button" class="btn btn-danger btn-sm remove-field">ลบ</button>
        `;

        // เพิ่มฟิลด์ใหม่ไปยัง container หลัก
        nameObjectContainer.appendChild(newInputGroup);

        // เพิ่ม event สำหรับปุ่มลบภายใน container
        const removeButton = newInputGroup.querySelector('.remove-field');
        removeButton.addEventListener('click', function () {
            newInputGroup.remove(); // ลบ container ปัจจุบัน
        });
    });
});

