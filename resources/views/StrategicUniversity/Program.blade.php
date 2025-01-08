@foreach ($Platform->programs as $Program)
    <!-- table header button -->
    <div class="bth-group">
        <button type="button" class="btn-add" data-bs-toggle="modal" data-bs-target="#addProgramKpi{{$Program->Id_Platform}}">
            <i class='bx bx-plus'></i>เพิ่ม KPI
        </button>

        <form action="{{ route('deleteProgram', $Program->Id_Program) }}" method="POST">
            @csrf
            @method('DELETE') 
            <button type="submit" class="btn-delete" onclick="return confirm('คุณต้องการลบ Platform นี้ใช่หรือไม่?')">
                <i class='bx bx-trash'></i>ลบตาราง
            </button>
        </form>
    </div><br>
    
    
    <div class="container-program">
        <table style="width:100%">
            <tr>
                <th colspan="4">{{ $Program->Name_Program }}</th>
                <th rowspan="4">
                    <div class="bth-group">
                        <button type="button" class="btn-edit" data-bs-toggle="modal" data-bs-target="#editPlatform{{$Platform->Id_Platform}}" id="{{$Platform->Id_Platform}}">
                            <i class='bx bx-edit'></i>แก้ไข
                        </button>
                    </div>
                </th>
            </tr>
            <tr>
                <th colspan="4">{{$Program->Name_Object}}</th>
            </tr>
            <tr>
                <th colspan="2" rowspan="2">ผลสัมฤทธิ</th>
                <th colspan="2" class="center-text">ปีงบประมาณ</th>
            </tr>

            <tr class="center-text">
                @if($Platform->budgetYears->isNotEmpty())
                    @foreach($Platform->budgetYears as $budgetYear)
                        <th>{{ $budgetYear->Budget_Year }}</th>
                    @endforeach
                @else
                    <th colspan="2">ไม่มีข้อมูลปีงบประมาณ</th>
                @endif
            </tr>

            @foreach ($Platform->platformKpis as $PlatformKpi)
            <tr>
                <td>{{ $PlatformKpi->Name_Platfrom_Kpi }}</td>
                <td>{{ $PlatformKpi->Description_Platfrom_Kpi }}</td>

                @foreach($Platform->budgetYears as $budgetYear)
                    @php
                        $platformYear = $PlatformKpi->platformYears()
                        ->where('Platform_Budget_Year_Id', $budgetYear->Id_Platform_Budget_Year)
                        ->where('Platform_Kpi_Id', $PlatformKpi->Id_Platform_Kpi)
                        ->first();
                    @endphp
                    <td>
                        @if($platformYear)
                            {{ $platformYear->Value_Platform }}
                        @else
                            -
                        @endif
                    </td>
                @endforeach

                <td>
                    <a href="{{ route('showPlatformKpi', ['Id_Platform' => $Platform->Id_Platform]) }}"></a>
                    <div class="bth-group">

                        <button type="button" class="btn-edit" data-bs-toggle="modal" data-bs-target="#editPlatformKpi{{$PlatformKpi->Id_Platform_Kpi}}" id="{{$PlatformKpi->Id_Platform_Kpi}}">
                            <i class='bx bx-edit'></i>แก้ไข
                        </button>
                        
                        <form action="{{ route('deletePlatformKpi', $PlatformKpi->Id_Platform_Kpi) }}" method="POST">
                            @csrf
                            @method('DELETE') 
                            <button type="submit" class="btn-delete" onclick="return confirm('คุณต้องการลบ KPI นี้ใช่หรือไม่?')">
                                <i class='bx bx-trash'></i>ลบ
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
 @endforeach