function saveData(element, projectId, fieldName) {
    const newValue = element.innerText;

    // สร้าง toast notification container ถ้ายังไม่มี
    if (!document.getElementById('toast-container')) {
        const toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.style.position = 'fixed';
        toastContainer.style.bottom = '20px';
        toastContainer.style.right = '20px';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }

    // แสดงสถานะกำลังโหลด
    element.classList.add('saving');

    // ส่งข้อมูลไปยัง server ด้วย AJAX - ระบุ projectId ใน URL
    fetch(`/projects/${projectId}/update-field`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                field: fieldName,
                value: newValue
            })
        })
        .then(response => response.json())
        .then(data => {
            // ลบคลาส saving
            element.classList.remove('saving');

            if (data.success) {
                // สร้าง toast notification
                const toast = document.createElement('div');
                toast.className = 'toast-notification show';
                toast.style.backgroundColor = '#28a745';
                toast.innerHTML =
                    `<i class='bx bx-check' style='margin-right:8px; font-size:20px;'></i> บันทึกข้อมูลเรียบร้อยแล้ว`;

                document.getElementById('toast-container').appendChild(toast);

                // ซ่อนตัวเองหลังจาก 3 วินาที
                setTimeout(() => {
                    toast.classList.add('hide');
                    setTimeout(() => {
                        toast.remove();
                    }, 300);
                }, 3000);

                element.classList.remove('editing');
                console.log('Data saved successfully');
            } else {
                // กรณีเกิดข้อผิดพลาด
                const toast = document.createElement('div');
                toast.className = 'toast-notification show error';
                toast.style.backgroundColor = '#dc3545';
                toast.innerHTML =
                    `<i class='bx bx-error-circle' style='margin-right:8px; font-size:20px;'></i> ${data.message || 'เกิดข้อผิดพลาดในการบันทึกข้อมูล'}`;

                document.getElementById('toast-container').appendChild(toast);

                setTimeout(() => {
                    toast.classList.add('hide');
                    setTimeout(() => {
                        toast.remove();
                    }, 300);
                }, 3000);

                console.error('Error saving data:', data.message);
            }
        })
        .catch(error => {
            element.classList.remove('saving');

            // แสดงข้อความแจ้งเตือนข้อผิดพลาด
            const toast = document.createElement('div');
            toast.className = 'toast-notification show error';
            toast.style.backgroundColor = '#dc3545';
            toast.innerHTML =
                `<i class='bx bx-error-circle' style='margin-right:8px; font-size:20px;'></i> เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์`;

            document.getElementById('toast-container').appendChild(toast);

            setTimeout(() => {
                toast.classList.add('hide');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000);

            console.error('Error:', error);
        });
}

function checkEnter(event, element) {
    if (event.key === 'Enter') {
        event.preventDefault();
        element.blur();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.editable').forEach(element => {
        element.addEventListener('focus', () => {
            element.classList.add('editing');
        });
        element.addEventListener('blur', () => {
            element.classList.remove('editing');
        });
    });
});