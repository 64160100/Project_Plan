<table class="table-PDCA">
    <tr>
        <th rowspan="2">กิจกรรมและแผนการเบิกจ่ายงบประมาณ</th>
        <th colspan="12">ปีงบประมาณ พ.ศ. 2567</th>
    </tr>
    <tr>
        <th>มกราคม</th>
        <th>กุมภาพันธ์</th>
        <th>มีนาคม</th>
        <th>เมษายน</th>
        <th>พฤษภาคม</th>
        <th>มิถุนายน</th>
        <th>กรกฎาคม</th>
        <th>สิงหาคม</th>
        <th>กันยายน</th>
        <th>ตุลาคม</th>
        <th>พฤศจิกายน</th>
        <th>ธันวาคม</th>
    </tr>

        <tr>
            <td class="PDCA">
                <div class="plan-text">ขั้นวางแผนงาน(Plan)</div>
                <textarea class="plan-textarea auto-expand" id="comment" placeholder="เพิ่มรายละเอียด"></textarea>
            </td>
            @for ($i = 1; $i <= 12; $i++)
                <td class="checkbox-container"><input type="checkbox"></td>
            @endfor
        </tr>

        <tr>
            <td class="PDCA">
                <div class="plan-text">ขั้นดำเนินการ(Do)</div>
                <textarea class="plan-textarea auto-expand" id="comment" placeholder="เพิ่มรายละเอียด"></textarea>
            </td>
            @for ($i = 1; $i <= 12; $i++)
                <td class="checkbox-container"><input type="checkbox"></td>
            @endfor
        </tr>

        <tr>
            <td class="PDCA">
                <div class="plan-text">ขั้นสรุปและประเมินผล(Check)</div>
                <textarea class="plan-textarea auto-expand" id="comment" placeholder="เพิ่มรายละเอียด"></textarea>
            </td>
            @for ($i = 1; $i <= 12; $i++)
                <td class="checkbox-container"><input type="checkbox"></td>
            @endfor
        </tr>

        <tr>
            <td class="PDCA">
                <div class="plan-text">ขั้นปรับปรุงตามผลการประเมิน(Act)</div>
                <textarea class="plan-textarea auto-expand" id="comment" placeholder="เพิ่มรายละเอียด"></textarea>
            </td>
            @for ($i = 1; $i <= 12; $i++)
                <td class="checkbox-container"><input type="checkbox"></td>
            @endfor
        </tr>
</table>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const textareas = document.querySelectorAll('.plan-textarea.auto-expand');
        
        textareas.forEach(textarea => {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
            textarea.dispatchEvent(new Event('input'));
        });
    });
</script>