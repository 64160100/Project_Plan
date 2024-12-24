<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Add Multiple KPIs</h1>

    <!-- แสดงฟอร์มเพิ่มหลายรายการ -->
    <form action="#" method="POST">
        @csrf
        <!-- ฟอร์มที่ยืดหยุ่น -->
        <div id="kpi-form-fields">
            <div class="kpi-field">
                <label for="kpi_name[]">KPI Name:</label>
                <input type="text" name="kpi_name[]" required>

                <label for="kpi_description[]">KPI Description:</label>
                <input type="text" name="kpi_description[]" required>

                <label for="fiscal_year[]">Fiscal Year:</label>
                <input type="text" name="fiscal_year[]" required>
            </div>
        </div>

        <!-- ปุ่มเพิ่มฟิลด์ -->
        <button type="button" onclick="addKpiField()">Add Another KPI</button>
        <br><br>
        <button type="submit">Submit</button>
    </form>

    <script>
        // ฟังก์ชั่นในการเพิ่มฟิลด์ใหม่
        function addKpiField() {
            // สร้างกลุ่มฟิลด์ใหม่
            var newField = document.createElement('div');
            newField.classList.add('kpi-field');

            newField.innerHTML = `
                <label for="kpi_name[]">KPI Name:</label>
                <input type="text" name="kpi_name[]" required>

                <label for="kpi_description[]">KPI Description:</label>
                <input type="text" name="kpi_description[]" required>

                <label for="fiscal_year[]">Fiscal Year:</label>
                <input type="text" name="fiscal_year[]" required>
            `;

            // เพิ่มกลุ่มฟิลด์ใหม่เข้าไปในฟอร์ม
            document.getElementById('kpi-form-fields').appendChild(newField);
        }
    </script>
</body>
</html>
