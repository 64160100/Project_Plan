@extends('navbar.app')
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('css/button.css') }}">

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <h1>แผนยุทธศาสตร์</h1>
            <a href="#" class='btn-add' data-bs-toggle="modal" data-bs-target="#ModalAddStrategic">เพิ่มข้อมูล</a>
        </div>   
        @if ( $strategic->isEmpty())     
            <div class='card p-3 m-3'>ไม่พบข้อมูลแผนยุทธศาสตร์</div>
        @else
            @foreach ( $strategic as $Strategic )
            <div class='card p-3 m-3' >
                <h3>
                    <a href="{{ route('strategy.index', $Strategic->Id_Strategic) }}">{{ $Strategic->Name_Strategic_Plan }}</a>
                </h3>
                {{ $Strategic->Goals_Strategic  }}

                <hr>
                <div class="d-flex ms-auto">
                    <a href="{{ route('pdf.strategicCtrlP', $Strategic->Id_Strategic) }}" class='bx bx-folder-open' style='color:#000; font-size: 20px; padding-right: 5px;'></a>
                    <a href="{{ route('PDF.strategic', $Strategic->Id_Strategic) }}" class='bx bx-folder-open' style='color:#bd7ff9; font-size: 20px; padding-right: 5px;'></a>
                    <a href="#" class='bx bx-edit-alt' style='color:#bd7ff9; font-size: 20px;' data-bs-toggle="modal" data-bs-target="#ModalEditStrategic{{ $Strategic->Id_Strategic }}"></a>
                    <form action="{{ route('strategic.destroy', $Strategic->Id_Strategic) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class='bx bx-trash' style='color:#bd7ff9; font-size: 20px; background:none; border:none; cursor:pointer;' onclick="return confirm('คุณยืนยันที่จะลบข้อมูลนี้หรือไม่');"></button>
                    </form>
                </div>
            </div>
            @endforeach            
        @endif
    </div>
    @include('strategic.addStrategic')
    @include('strategic.editStrategic')
@endsection