@extends('navbar.app')
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Index</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('css/listproject.css') }}">
    
    <style>
        .accordion-btn {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
    
        .button-container {
            display: flex;
            align-items: flex-start;
            flex-wrap: wrap;
        }
    
        @media (max-width: 800px) {
            .accordion-btn {
                align-items: flex-start;
            }
    
            .button-container {
                width: 100%;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>
    @section('content')

    <div class="container-vp">
        <h3 class="head-project">
            <b>รายการโครงการ ปีงบประมาณ พ.ศ. 2567</b>
        </h3>
    </div>
    <br>

    @foreach ($strategics as $Strategic)
    <details class="accordion" id="{{ $Strategic->Id_Strategic }}">
        <summary class="accordion-btn">
            <b><a href="{{ route('viewProjectInStrategic', ['Id_Strategic' => $Strategic->Id_Strategic]) }}">
                    {{ $Strategic->Name_Strategic_Plan }}</a><br>จำนวนโครงการ : {{ $Strategic->project_count }}
                โครงการ</b>

            <div>
                <a href="{{ route('showCreateFirstForm', ['Strategic_Id' => $Strategic->Id_Strategic]) }}" class="btn-add">
                    <i class='bx bx-plus'></i>เพิ่มโครงการ
                </a>

                <a href="{{ route('showCreateProject', ['Strategic_Id' => $Strategic->Id_Strategic]) }}" class="btn-add">
                    <i class='bx bx-plus'></i>เพิ่มโครงการใหญ่
                </a>                
            </div>
        </summary>
        <div class="accordion-content">
            @if ($Strategic->projects->isEmpty())
            <p>ไม่มีโครงการที่เกี่ยวข้อง</p>
            @else
            @foreach ($Strategic->projects as $Project)
            <li class="d-flex justify-content-between align-items-center">
                <strong>
                    <a href="{{ route('viewProject', ['Id_Project' => $Project->Id_Project]) }}">
                        {{ $Project->Name_Project }}
                        <a href="{{ route('PDF.Project', $Project->Id_Project) }}" class='bx bx-folder-open'
                            style='color:#bd7ff9; font-size: 20px; padding-right: 5px;'></a>
                        <a href="{{ route('PDF.projectCtrlP', $Project->Id_Project) }}" class='bx bx-folder-open'
                            style='color:#000; font-size: 20px; padding-right: 5px;'></a>
                        <!-- Pdfยาว -->
                        <a href="{{ route('PDF.generate', $Project->Id_Project) }}" class='bx bx-folder-open'
                            style='color:#f00; font-size: 20px; padding-right: 5px;'></a>
                    </a>
                </strong>
                <a href="{{ route('editProject', ['Id_Project' => $Project->Id_Project]) }}" >
                    <i class='bx bx-edit'  style="font-size: 24px;"></i>
                </a>
            </li>
            @endforeach
            @endif
        </div>
    </details>
    @endforeach
    @endsection

</body>

</html>