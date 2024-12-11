@extends('navbar.app')
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('css/button.css') }}">

    <style>
        .header-bar {
            background-color: #7C3AED; 
            color: white;
            padding: 10px 15px 3;
            font-weight: bold; 
            border-radius: 15px;
            display: flex;
            justify-content: space-between; 
            align-items: center; 
            width: fit-content;
            margin: 0 auto;
        }
        .text-box {
            border: 1px solid #7C3AED;
            border-radius: 15px;
            background-color: white;
            color: #7C3AED;
            padding: 10px 15px;
            font-size: 14px;
            line-height: 1.5;
            width: fit-content;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
        }

        table {
            width: 100%;
            margin: 0 auto;
            margin-top: 15px;
            border-radius: 15px; 
            overflow: hidden;
        }

        th, td {
            border: 1px solid #7C3AED;
            background: #fff;
            padding: 15px;
            color: #7C3AED;
        }

    </style>

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div class="header-bar">
                <h3>{{ $strategic->Name_Strategic_Plan }}</h3>
                <a  class='bx bxs-down-arrow ms-3' style='color:#ffffff' data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample"></a>
            </div>
            <div>
                <a href="#" class='btn-add' data-bs-toggle="modal" data-bs-target="#ModalAddStrategy">เพิ่มข้อมูล</a>
            </div>
        </div>
        <div class="collapse" id="collapseExample">
            <div class="text-box mt-2">
                {{ $strategic->Goals_Strategic  }}
            </div>
        </div>

        <div>
            <table>
                <tr style="text-align: center;">
                    <th style="width: 15%">กลยุทธ์</th>
                    <th style="width: 25%">วัตถุประสงค์เชิงกลยุทธ์ <br>(Strategic Objectives : SO)</th>
                    <th style="width: 25%">ตัวชี้วัดกลยุทธ์</th>
                    <th style="20">ค่าเป้าหมาย</th>
                    <th style="15">จัดการ</th>
                </tr>
                @foreach ( $strategy as $strategy )
                <tr style="vertical-align: top;">
                    @if ( $strategic->Id_Strategic == $strategy->Strategic_Id )
                        <td>{{ $strategy->Name_Strategy }}</td>
                        <td>{{ $strategy->Strategy_Objectives }}</td>
                        <td>
                            @foreach ($strategy->kpis as $kpi)
                                {{ $kpi->Name_Kpi }} <br><br>
                            @endforeach
                        </td>
                        <td>
                            @foreach ($strategy->kpis as $kpi)
                               {{ $kpi->Target_Value }} <br><br>
                            @endforeach
                        </td>
                        <td>การจัดการ</td>
                    @endif
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    @include('strategy.addStrategy')

@endsection