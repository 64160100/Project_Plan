<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@400;700&display=swap');
        
        body {
            margin: 0;
            padding: 0;
        }
        
        .page-container {
            padding: 40px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 20px;
        }
        
        th, td {
            border: 1px solid #000;
            padding: 10px;
            font-size: 14px;
            vertical-align: top;
            word-wrap: break-word;
            background-color: white;
        }
        
        th {
            background-color: #c8e6c9 !important;
            font-weight: bold;
            text-align: center;
        }
        
        .red-text {
            color: red;
        }
        
        .sub-item {
            padding-left: 20px;
        }

        .button-container {
            margin-bottom: 20px;
            padding: 20px;
            text-align: center;
        }

        button {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 4px;
        }
        
        #content {
            padding: 40px;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 0px;
            }
            
            #content {
                padding: 0;
            }
            
            .button-container {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="button-container">
        <button onclick="generatePDF()">Generate PDF</button>
    </div>
    <div id="content">
        <table>
            <thead>
                <tr>
                    <th style="width: 15%;">ยุทธศาสตร์สำนักทดสอบ</th>
                    <th style="width: 15%;">กลยุทธ์สำนักทดสอบ</th>
                    <th style="width: 20%;">โครงการ</th>
                    <th style="width: 15%;">วัตถุประสงค์โครงการ</th>
                    <th style="width: 15%;">ตัวชี้วัดความสำเร็จของโครงการ</th>
                    <th style="width: 10%;">ค่าเป้าหมาย</th>
                    <th style="width: 10%;">ระยะเวลา</th>
                    <th style="width: 10%;">งบประมาณ (บาท)</th>
                    <th style="width: 10%;">ผู้รับผิดชอบ</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>ยุทธศาสตร์ที่ 1 การพัฒนาการเป็นเลิศด้านการวิจัยมาตรฐานสากล</td>
                    <td>กลยุทธ์ที่ 1.1 พัฒนาการบริหารปฏิบัติการด้านคุณภาพ</td>
                    <td>
                        1. โครงการพัฒนาคุณภาพการปฏิบัติงานด้วยเกณฑ์คุณภาพ EdPEx
                        <div class="sub-item">โครงการย่อย:</div>
                        <div class="sub-item">1.1 การอบรมเชิงปฏิบัติการเพื่อยกระดับคุณภาพตามเกณฑ์ EdPEx</div>
                        <div class="sub-item">1.2 EdPEx for all</div>
                    </td>
                    <td>เพื่อยกระดับการปฏิบัติงานและการให้บริการที่เป็นเลิศตามเกณฑ์คุณภาพ EdPEx</td>
                    <td>
                        1. จำนวนครั้งของการจัดกิจกรรมที่เกี่ยวข้องกับ EdPEx<br>
                        2. การประเมินผลตามเกณฑ์ประกันคุณภาพการศึกษาเพื่อการดำเนินการที่เป็นเลิศ
                    </td>
                    <td>
                        Leading KPI :<br>
                        1. จำนวน 1 โครงการ/กิจกรรม ไตรมาส 6 เดือน<br>
                        2. คะแนนการประเมินตามเกณฑ์ EdPEx 200 คะแนนขึ้นไป<br>
                        <span class="red-text">(เริ่มนับหาหารเพิ่มเติม KPI C25)</span><br>
                        Lagging KPI :<br>
                        1. จำนวนไม่น้อยกว่า 2 โครงการ/กิจกรรม<br>
                        2. 220 คะแนน
                    </td>
                    <td>
                        1. ก.พ.-ส.ค. 67<br>
                        2. สิ้นการประเมิน ต.ค. 67
                    </td>
                    <td></td>
                    <td>ผช.ผอ.ฝ่ายพัฒนาคุณภาพและคณะอนุกรรมการประกันคุณภาพการปฏิบัติงาน</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>
                        2. โครงการบริหารภาครัฐสู่สากล
                        <div class="sub-item">โครงการย่อย:</div>
                        <div class="sub-item">2.1 การนำเสนอผลการดำเนินงานกิจกรรมระดับสากล</div>
                    </td>
                    <td>เพื่อนำเสนอความเป็นเลิศในทางวิทยาด้านกิจกรรมระดับสากล</td>
                    <td>จำนวนครั้งในการนำเสนอผลงานที่ยอมรับทางวิชาการในกิจกรรมระดับสากล<br>
                        <span class="red-text">(เริ่มนับหาหารเพิ่มเติม KPI C5)</span>
                    </td>
                    <td>
                        Leading KPI :<br>
                        1 ครั้ง<br>
                        Lagging KPI :<br>
                        จำนวน ≥ 2 ครั้ง
                    </td>
                    <td>ม.ค.-ส.ค. 67</td>
                    <td></td>
                    <td>ผช.ธย.<br>ฝ่ายยุทธศาสตร์ฯ<br>ผช.อย.<br>ฝ่ายภาพลักษณ์ฯ</td>
                </tr>
                <tr>
                    <td></td>
                    <td>กลยุทธ์ที่ 1.2<br>การพัฒนาห้องสมุดเพื่อการพัฒนาที่ยั่งยืน</td>
                    <td>
                        3. โครงการห้องสมุดสีเขียว (Green Library)
                        <div class="sub-item">โครงการย่อย:</div>
                        <div class="sub-item">3.1 โครงการ Big Cleaning Day</div>
                        <div class="sub-item">3.2 โครงการตั้งค่าพื้นฐานและซ่อมอุปกรณ์ไฟฟ้า</div>
                        <div class="sub-item">3.3 โครงการอบรมฯ ห้องสมุดสีเขียว ตามเกณฑ์มาตรฐานห้องสมุดสีเขียว ปี พ.ศ. 2566</div>
                        <div class="sub-item">3.4 กิจกรรม 7ส</div>
                        <div class="sub-item">3.5 กิจกรรมด้านห้องสมุดสีเขียว</div>
                    </td>
                    <td>1. เพื่อส่งเสริมการเป็นห้องสมุดสีเขียวอย่างต่อเนื่อง<br>2. เพื่อพัฒนาการบริหารจัดการห้องสมุดสีเขียวอย่างยั่งยืน</td>
                    <td>
                        1. จำนวนกิจกรรม/โครงการที่เกี่ยวข้อง<br>
                        2. จำนวนกิจกรรมที่สอดคล้องกับ SDGs<br>
                        <span class="red-text">(เริ่มนับมหาวิทยาลัย KPI C29, C30)</span>
                    </td>
                    <td>
                        Leading KPI :<br>
                        จำนวน ≥ 4 โครงการ/กิจกรรม<br>
                        Lagging KPI :<br>
                        จำนวน ≥ 4 โครงการ/กิจกรรม
                    </td>
                    <td>ต.ค.66 - ส.ค. 67</td>
                    <td>108,950 บาท</td>
                    <td>รอง ผอ.<br>ฝ่ายยุทธศาสตร์ฯ<br>และคณะกรรมการห้องสมุดสีเขียว/สำนักงานสีเขียว</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>
                        4. โครงการศึกษาและพัฒนาตามเกณฑ์สำนักงานสีเขียว (Green Office)
                        <div class="sub-item">โครงการย่อย:</div>
                        <div class="sub-item">4.1 การอบรมให้ความรู้ด้านเกณฑ์การประเมิน Green Office</div>
                        <div class="sub-item">4.2 ส่งเสริมการปฏิบัติงานให้สอดคล้องกับแนวทางการประเมินสำนักงานสีเขียว</div>
                    </td>
                    <td>1. เพื่อศึกษาความรู้การดำเนินงานสีเขียวในองค์กรสำนักงานเพื่อตอบสนองยุทธศาสตร์สีเขียว<br>2. เพื่อหาแนวทางการพัฒนางานสำนักงานให้เป็นมิตรต่อสิ่งแวดล้อม<br>3. เพื่อส่งเสริมให้บุคลากรมีการปรับปรุง และการรักษาสิ่งแวดล้อมที่ดีในพื้นที่</td>
                    <td>จำนวนกิจกรรม/โครงการที่เกี่ยวข้อง</td>
                    <td>
                        Leading KPI :<br>
                        จำนวน 1 โครงการ/กิจกรรม<br>
                        Lagging KPI :<br>
                        จำนวน ≥ 2 โครงการ/กิจกรรม
                    </td>
                    <td>ม.ค. - ส.ค.67</td>
                    <td>35,000 บาท</td>
                    <td>หัวหน้าสำนักงานและคณะกรรมการห้องสมุดสีเขียว/สำนักงานสีเขียว</td>
                </tr>
                <!-- Additional rows to ensure content spans multiple pages -->
                <tr class="page-break">
                    <td>ยุทธศาสตร์ที่ 2 การพัฒนาความสามารถในการแข่งขัน</td>
                    <td>กลยุทธ์ที่ 2.1 การพัฒนานวัตกรรมและเทคโนโลยี</td>
                    <td>
                        5. โครงการพัฒนานวัตกรรมการศึกษา
                        <div class="sub-item">โครงการย่อย:</div>
                        <div class="sub-item">5.1 การวิจัยและพัฒนานวัตกรรมการศึกษา</div>
                        <div class="sub-item">5.2 การจัดแสดงนวัตกรรมการศึกษา</div>
                    </td>
                    <td>เพื่อพัฒนานวัตกรรมการศึกษาและเทคโนโลยีที่ทันสมัย</td>
                    <td>
                        1. จำนวนงานวิจัยที่ตีพิมพ์<br>
                        2. จำนวนการจัดแสดงนวัตกรรม
                    </td>
                    <td>
                        Leading KPI :<br>
                        1. จำนวน 2 งานวิจัย/ปี<br>
                        2. จำนวน 1 การจัดแสดง/ปี
                    </td>
                    <td>ม.ค.-ธ.ค. 67</td>
                    <td>200,000 บาท</td>
                    <td>ผช.ผอ.ฝ่ายวิจัยและพัฒนา</td>
                </tr>
                <tr>
                    <td></td>
                    <td>กลยุทธ์ที่ 2.2 การพัฒนาทักษะและความสามารถ</td>
                    <td>
                        6. โครงการพัฒนาทักษะการเรียนรู้
                        <div class="sub-item">โครงการย่อย:</div>
                        <div class="sub-item">6.1 การจัดอบรมทักษะการเรียนรู้</div>
                        <div class="sub-item">6.2 การแข่งขันทักษะการเรียนรู้</div>
                    </td>
                    <td>เพื่อพัฒนาทักษะการเรียนรู้และความสามารถของนักเรียน</td>
                    <td>
                        1. จำนวนการอบรมที่จัดขึ้น<br>
                        2. จำนวนการแข่งขันที่จัดขึ้น
                    </td>
                    <td>
                        Leading KPI :<br>
                        1. จำนวน 3 การอบรม/ปี<br>
                        2. จำนวน 2 การแข่งขัน/ปี
                    </td>
                    <td>ม.ค.-ธ.ค. 67</td>
                    <td>150,000 บาท</td>
                    <td>ผช.ผอ.ฝ่ายพัฒนาทักษะ</td>
                </tr>
                <tr>
                    <td></td>
                    <td>กลยุทธ์ที่ 2.3 การพัฒนาความร่วมมือระหว่างประเทศ</td>
                    <td>
                        7. โครงการความร่วมมือระหว่างประเทศ
                        <div class="sub-item">โครงการย่อย:</div>
                        <div class="sub-item">7.1 การจัดประชุมความร่วมมือระหว่างประเทศ</div>
                        <div class="sub-item">7.2 การแลกเปลี่ยนบุคลากรและนักเรียน</div>
                    </td>
                    <td>เพื่อพัฒนาความร่วมมือระหว่างประเทศในด้านการศึกษา</td>
                    <td>
                        1. จำนวนการประชุมที่จัดขึ้น<br>
                        2. จำนวนการแลกเปลี่ยนบุคลากรและนักเรียน
                    </td>
                    <td>
                        Leading KPI :<br>
                        1. จำนวน 2 การประชุม/ปี<br>
                        2. จำนวน 1 การแลกเปลี่ยน/ปี
                    </td>
                    <td>ม.ค.-ธ.ค. 67</td>
                    <td>300,000 บาท</td>
                    <td>ผช.ผอ.ฝ่ายความร่วมมือระหว่างประเทศ</td>
                </tr>
                <tr>
                    <td></td>
                    <td>กลยุทธ์ที่ 2.4 การพัฒนาสิ่งแวดล้อมและความยั่งยืน</td>
                    <td>
                        8. โครงการพัฒนาสิ่งแวดล้อม
                        <div class="sub-item">โครงการย่อย:</div>
                        <div class="sub-item">8.1 การปลูกป่าและฟื้นฟูสิ่งแวดล้อม</div>
                        <div class="sub-item">8.2 การจัดการขยะและรีไซเคิล</div>
                    </td>
                    <td>เพื่อพัฒนาสิ่งแวดล้อมและความยั่งยืนในชุมชน</td>
                    <td>
                        1. จำนวนต้นไม้ที่ปลูก<br>
                        2. จำนวนขยะที่รีไซเคิล
                    </td>
                    <td>
                        Leading KPI :<br>
                        1. จำนวน 500 ต้นไม้/ปี<br>
                        2. จำนวน 100 ตันขยะ/ปี
                    </td>
                    <td>ม.ค.-ธ.ค. 67</td>
                    <td>250,000 บาท</td>
                    <td>ผช.ผอ.ฝ่ายสิ่งแวดล้อม</td>
                </tr>
            </tbody>
        </table>
    </div>

    <script>
        function generatePDF() {
            const element = document.getElementById('content');
            const opt = {
                margin: [10, 10, 10, 10], // top, right, bottom, left margins in millimeters
                filename: 'project-table.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { 
                    scale: 2,
                    useCORS: true,
                    logging: true,
                    letterRendering: true,
                    windowWidth: 1920,
                    width: element.scrollWidth
                },
                jsPDF: { 
                    unit: 'mm',
                    format: 'a4',
                    orientation: 'landscape',
                    compress: true
                },
                pagebreak: { mode: ['avoid-all', 'css', 'legacy'], before: '.page-break', after: '.page-break' }
            };

            html2pdf().set(opt).from(element).save();
        }
    </script>
</body>
</html>