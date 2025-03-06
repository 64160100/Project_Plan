<!DOCTYPE html>
<html lang="th">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <!-- <link rel="stylesheet" href="<?php echo e(public_path('css/pdf.css')); ?>"> -->
    <title>ตัวอย่าง PDF ภาษาไทย</title>
    <style>
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: normal;
            src: url('<?php echo e(public_path('fonts/THSarabunNew.ttf')); ?>') format('truetype');
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: bold;
            src: url('<?php echo e(public_path('fonts/THSarabunNew Bold.ttf')); ?>') format('truetype');
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: italic;
            font-weight: normal;
            src: url('<?php echo e(public_path('fonts/THSarabunNew Italic.ttf')); ?>') format('truetype');
        }
        @font-face {
            font-family: 'THSarabunNew';
            font-style: italic;
            font-weight: bold;
            src: url('<?php echo e(public_path('fonts/THSarabunNew BoldItalic.ttf')); ?>') format('truetype');
        }

        table {
            width: 111%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-left: -0.5in;
            margin-right: 3in;
            line-height: 0.4;

        }

        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: center;
            font-size: 18px;
            word-wrap: break-word; 
        }

        th {
            background-color:rgba(197,224,179,255);
        }

        td {
            /* text-align: left; */
            vertical-align: top;
        }

    </style>
</head>
<body>
<table>
        <tr>
            <th style="width: 30%" rowspan="2">กิจกรรมและการเบิกจ่ายงบประมาณ</th>
            <th colspan="13">ปีงบประมาณ พ.ศ.๒๕๖๗</th>
        </tr>

        <tr style="text-align: center">
            <td>ต.ค</td>
            <td>พ.ย</td>
            <td>ธ.ค</td>
            <td>ม.ค</td>
            <td>ก.พ</td>
            <td>มี.ค</td>
            <td>เม.ย</td>
            <td>พ.ค</td>
            <td>มิ.ย</td>
            <td>ก.ค</td>
            <td>ต.ค</td>
            <td>ส.ค</td>
            <td>ก.ย</td>
        </tr>
    </table>
    
</body>
</html><?php /**PATH /var/www/resources/views/PDF/LongTerm.blade.php ENDPATH**/ ?>