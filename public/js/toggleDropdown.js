function toggleDropdown() {
    const options = document.querySelector('.dropdown-options');
    options.style.display = options.style.display === 'block' ? 'none' : 'block';
}


function toggleSelectTextbox(checkbox) {
  const label = checkbox.parentElement; // เข้าถึง <label> ที่ครอบ Checkbox และ TextBox
  const textbox = label.querySelector('input[type="text"]'); // หา TextBox ที่อยู่ใน <label>
  textbox.disabled = !checkbox.checked; // เปิดหรือปิด TextBox ขึ้นอยู่กับสถานะ Checkbox
}

// ปิด dropdown เมื่อคลิกนอก dropdown
document.addEventListener('click', function (event) {
    const dropdown = document.querySelector('.dropdown-container');
    if (!dropdown.contains(event.target)) {
        document.querySelector('.dropdown-options').style.display = 'none';
    }
});