@extends('navbar.app')
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Index</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('css/listproject.css') }}">
</head>

<body>
    @section('content')

    <div class="container-vp">
        <h3 class="head-project">
            <b>รายการโครงการ</b>
        </h3>
    </div>
    <br>

    @foreach ($quarters as $quarter)
    <div
        class="border rounded-lg hover:bg-gray-50 transition-colors cursor-pointer border-l-4 border-l-transparent hover:border-l-blue-500 bg-white">
        <div class="flex justify-between items-center p-4">
            <div class="flex items-center space-x-4">
                <div class="text-lg">
                    ปีงบประมาณ {{ $quarter->Fiscal_Year }} ไตรมาส {{ $quarter->Quarter }}
                </div>
            </div>
            <a href="#collapse{{ $quarter->Id_Quarter_Project }}" class="inline-flex items-center"
                data-bs-toggle="collapse">
                <i class='bx bx-chevron-down mr-2'></i>
                ดูข้อมูล
            </a>
        </div>

        <div id="collapse{{ $quarter->Id_Quarter_Project }}" class="collapse">
            @php
            $hasStrategic = false;
            @endphp

            @foreach ($strategics as $Strategic)
            @if ($Strategic->quarterProjects->contains('Quarter_Project_Id', $quarter->Id_Quarter_Project))
            @php
            $hasStrategic = true;
            @endphp
            <details class="accordion" id="{{ $Strategic->Id_Strategic }}">
                <summary class="accordion-btn">
                    <b><a> {{ $Strategic->Name_Strategic_Plan }}</a><br>จำนวนโครงการ : {{ $Strategic->project_count }}
                        โครงการ</b>

                    <a href="{{ route('showCreateFirstForm', ['Strategic_Id' => $Strategic->Id_Strategic]) }}"
                        class="btn-add">
                        <i class='bx bx-plus'></i>เพิ่มโครงการ
                    </a>

                    <!-- <a href="{{ route('showCreateProject', ['Strategic_Id' => $Strategic->Id_Strategic]) }}"
                        class="btn-add">
                        <i class='bx bx-plus'></i>เพิ่มโครงการใหญ่
                    </a> -->
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
                                <a href="{{ route('PDF.projectCtrlP', $Project->Id_Project) }}"
                                    class='bx bx-folder-open'
                                    style='color:#000; font-size: 20px; padding-right: 5px;'></a>
                            </a>
                            <!-- pdf ยาว -->
                            <a href="{{ route('PDF.generate', $Project->Id_Project) }}" class='bx bx-folder-open'
                                style='color:#f00; font-size: 20px; padding-right: 5px;'></a>
                        </strong>
                        <a
                            href="{{ route('editProject', ['Id_Project' => $Project->Id_Project, 'sourcePage' => 'listProject']) }}">
                            <i class='bx bx-edit' style="font-size: 24px;"></i>
                        </a>
                    </li>
                    @endforeach
                    @endif
                </div>
            </details>
            @endif
            @endforeach

            @if (!$hasStrategic)
            <div class="card p-3 m-3">ไม่พบข้อมูลแผนยุทธศาสตร์</div>
            @endif
        </div>
    </div>
    @endforeach

    @endsection

</body>

</html>