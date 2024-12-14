@extends('navbar.app')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ViewProject</title>
</head>
<body>
@section('content')
    <h2>
        <b>{{ $projects->Name_Project }}</b>
        <form action="" method="get" style="display: inline;">
            <a href="" class="btn-add">
                <i class='bx bx-edit'></i>แก้ไข
            </a>
        </form>
    </h2>

    <b><p>หน้าฟอร์มโครงการ</p></b>

    <table>
        <thead>
            <tr>
                <td>ชื่อโครงการ</td><br>
                <td>ยุทศาสตร์</td>
            </tr>
        </thead>
        <tbody>
        
        </tbody>
    </table>
    
    
        
      

@endsection
</body>
</html>