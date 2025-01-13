<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Platform;
use App\Models\Platform_Kpi;
use App\Models\Platform_Budget_Year;
use App\Models\Platform_Year;
use App\Models\Program;
use App\Models\Program_Kpi;
use App\Models\Program_Budget_Year;
use App\Models\Program_Year;

class StrategicUniController extends Controller
{
    public function showPlatform()
    {
        $platforms = Platform::all();
        return view('StrategicUniversity.Platform',compact('platforms'));
    }

    public function createPlatform(Request $request)
    {
        $validatedData = $request->validate([
            'Name_Platform' => 'required|string|max:255',
            'Name_Object' => 'required|string',
            'Budget_Year' => 'required|array|size:2',
            'Budget_Year.*' => 'required|integer|distinct',
        ]);
    
        DB::beginTransaction();
    
        $currentIdPlatform = Platform::max('Id_Platform') ?? 0;
        $currentIdPlatform++;

        // สร้าง Platform
        $platform = Platform::create([
            'Id_Platform' => $currentIdPlatform,
            'Name_Platform' => $request->Name_Platform,
            'Name_Object' => $request->Name_Object,
        ]);

        $this->createPlatformBudgetYears($currentIdPlatform, $validatedData['Budget_Year']);

        DB::commit();

        return redirect()->route('showPlatform')->with('success', 'Platform created successfully');
    }

    private function createPlatformBudgetYears($currentIdPlatform, $budgetYears)
    {
        foreach ($budgetYears as $budgetYear) {
            $currentIdPlatformBudgetYear = Platform_Budget_Year::max('Id_Platform_Budget_Year') ?? 0;
            $currentIdPlatformBudgetYear++;

            Platform_Budget_Year::create([
                'Id_Platform_Budget_Year' => $currentIdPlatformBudgetYear,
                'Budget_Year' => $budgetYear,
                'Platform_Id' => $currentIdPlatform,
            ]);
        }
    }

    public function editPlatform(Request $request, $Id_Platform)
    {
        $platforms = Platform::findOrFail($Id_Platform);
    
        if ($request->isMethod('put')) {
            $validatedData = $request->validate([
                'Name_Platform' => 'required|string|max:255',
                'Name_Object' => 'required|string',
                'Budget_Year' => 'required|array|size:2',
                'Budget_Year.*' => 'required|integer|distinct',
            ]);
    
            DB::beginTransaction();
    
            try {
                $platforms->Name_Platform = $validatedData['Name_Platform'];
                $platforms->Name_Object = $validatedData['Name_Object'];
                $platforms->save();
    
                // ลบปีงบประมาณเก่า
                $platforms->budgetYears()->delete();
    
                // สร้างปีงบประมาณใหม่
                $this->createPlatformBudgetYears($Id_Platform, $validatedData['Budget_Year']);
    
                DB::commit();
                return redirect()->route('showPlatform')->with('success', 'Platform updated successfully');
            } catch (\Exception $e) {
                DB::rollback();
                return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            }
        }
    
        $budgetYears = $platform->budgetYears()->orderBy('Budget_Year')->pluck('Budget_Year')->toArray();
        return view('StrategicUniversity.editPlatform', compact('platform', 'budgetYears'));
    }

    public function deletePlatform(Request $request, $Id_Platform)
    {
        $platforms = Platform::find($Id_Platform);
        if (!$platforms) {
            return redirect()->back()->with('error', 'not found');
        }
    
        DB::beginTransaction();
    
        // ลบข้อมูลใน Platform_Year ก่อน
        Platform_Year::whereIn('Platform_Kpi_Id', function($query) use ($Id_Platform) {
            $query->select('Id_Platform_Kpi')
                    ->from('Platform_Kpi')
                    ->where('Platform_Id_Platform', $Id_Platform);
        })->delete();

        $platforms->platformKpis()->delete();
        $platforms->budgetYears()->delete();
        $platforms->delete();

        DB::commit();
        return redirect()->back()->with('success', 'ลบเป้าหมายการพัฒนาเรียบร้อยแล้ว');
    }

    // PlatformKpi------------------------------------------------------------------------------------------------
    public function showPlatformKpi($Id_Platform){
        $platforms = Platform::find($Id_Platform);

        return view('StrategicUniversity.showPlatformKpi',compact('platforms','budgetYears'));
    }

    public function createPlatformKpi(Request $request, $Id_Platform)
    {
        $platforms = Platform::findOrFail($Id_Platform);
    
        if ($request->isMethod('post')) {
            $request->validate([
                'Name_Platfrom_Kpi' => 'required|string|max:255',
                'Description_Platfrom_Kpi' => 'required|string',
                'Value_Platform' => 'required|array|min:2',
                'Value_Platform.*' => 'required|numeric|min:1',
            ]);
    
            DB::beginTransaction();
    
            $currentIdPlatformKpi = Platform_Kpi::max('Id_Platform_Kpi') ?? 0;
            $currentIdPlatformKpi++;

            $platformsKpi = new Platform_Kpi;
            $platformsKpi->Id_Platform_Kpi = $currentIdPlatformKpi;
            $platformsKpi->Name_Platfrom_Kpi = $request->Name_Platfrom_Kpi;
            $platformsKpi->Description_Platfrom_Kpi = $request->Description_Platfrom_Kpi;
            $platformsKpi->Platform_Id_Platform = $platforms->Id_Platform;
            $platforms->platformKpis()->save($platformsKpi);

            // สร้าง Platform_Year สำหรับแต่ละปีงบประมาณ(Budget_Year)
            $budgetYears = $platforms->budgetYears()->orderBy('Budget_Year')->get();
            foreach ($budgetYears as $index => $budgetYear) {
                if (isset($request->Value_Platform[$index])) {
                    $platformYear = new Platform_Year([
                        'Platform_Kpi_Id' => $currentIdPlatformKpi,
                        'Platform_Budget_Year_Id' => $budgetYear->Id_Platform_Budget_Year,
                        'Value_Platform' => $request->Value_Platform[$index],
                    ]);
                    $platformYear->save();
                }
            }

            DB::commit();
            return redirect()->route('showPlatform', ['Id_Platform' => $platforms->Id_Platform])
                        ->with('success', 'บันทึก Platform KPI เรียบร้อยแล้ว');
        }
        $budgetYears = $platforms->budgetYears()->orderBy('Budget_Year')->get();
        return view('StrategicUniversity.createPlatformKpi', compact('platforms', 'budgetYears'));
    }

    public function editPlatformKpi(Request $request, $Id_Platform, $Id_Platform_Kpi)
    {
        $platforms = Platform::findOrFail($Id_Platform);
        $platformKpi = Platform_Kpi::with('platformYears')->findOrFail($Id_Platform_Kpi);

        if ($request->isMethod('put')) {
            $request->validate([
                'Name_Platfrom_Kpi' => 'required|string|max:255',
                'Description_Platfrom_Kpi' => 'required|string',
                'Value_Platform' => 'required|array|min:2',
                'Value_Platform.*' => 'required|numeric|min:1',
            ]);

            DB::beginTransaction();

            // อัปเดตข้อมูล Platform_Kpi
            $platformKpi->update([
                'Name_Platfrom_Kpi' => $request->Name_Platfrom_Kpi,
                'Description_Platfrom_Kpi' => $request->Description_Platfrom_Kpi,
            ]);

            // อัปเดตข้อมูล Platform_Year
            $budgetYears = $platforms->budgetYears()->orderBy('Budget_Year')->get();

            foreach ($budgetYears as $index => $budgetYear) {
                if (isset($request->Value_Platform[$index])) {
                    Platform_Year::updateOrCreate(
                        [
                            'Platform_Kpi_Id' => $platformKpi->Id_Platform_Kpi,
                            'Platform_Budget_Year_Id' => $budgetYear->Id_Platform_Budget_Year,
                        ],
                        [
                            'Value_Platform' => $request->Value_Platform[$index],
                        ]
                    );
                }
            }

            DB::commit();

            return redirect()->route('showPlatform', ['Id_Platform' => $platforms->Id_Platform])
                ->with('success', 'แก้ไข Platform KPI เรียบร้อยแล้ว');
        }

        $budgetYears = $platforms->budgetYears()->orderBy('Budget_Year')->get();
        $platformYears = $platformKpi->platformYears()->get()->keyBy('Platform_Budget_Year_Id');
    
        return view('StrategicUniversity.editPlatformKpi', compact('platforms', 'platformKpi', 'budgetYears', 'platformYears'));

    }


    public function deletePlatformKpi($Id_Platform_Kpi)
    {
        $platformKpi = Platform_Kpi::find($Id_Platform_Kpi);
        if (!$platformKpi) {
            return redirect()->back()->with('error', 'ไม่พบ KPI นี้');
        }

        $platformKpi->platformYears()->delete();
        $platformKpi->delete();
        return redirect()->back()->with('success', 'ลบ KPI เรียบร้อยแล้ว');
    }

    // Program------------------------------------------------------------------------------------------------
    public function showProgram($Id_Platform, $Id_Program)
    {
        $platforms = Platform::find($Id_Platform);
        $program = $platforms->programs()->findOrFail($Id_Program);
        return view('StrategicUniversity.showProgram', compact('platforms', 'program'));
    }
    
    public function createProgram(Request $request, $Id_Platform)
    {
        $validatedData = $request->validate([
            'Name_Program' => 'required|string|max:255',
            'Name_Object' => 'required|string',
            'Budget_Year' => 'required|array|size:2',
            'Budget_Year.*' => 'required|integer|distinct',
        ]);
    
        DB::beginTransaction();
    
        $platform = Platform::findOrFail($Id_Platform);

        $currentIdProgram = Program::max('Id_Program') ?? 0;
        $currentIdProgram++;

        // สร้าง Program
        $program = Program::create([
            'Id_Program' => $currentIdProgram,
            'Name_Program' => $validatedData['Name_Program'],
            'Name_Object' => $validatedData['Name_Object'],
            'Platform_Id_Platform' => $Id_Platform,
        ]);

        $this->createProgramBudgetYears($currentIdProgram, $validatedData['Budget_Year']);

        DB::commit();

        return redirect()->route('showPlatform', ['Id_Platform' => $Id_Platform])
                            ->with('success', 'Program created successfully');
        
    }

    private function createProgramBudgetYears($currentIdProgram, $budgetYears)
    {
        foreach ($budgetYears as $budgetYear) {
            $currentIdProgramBudgetYear = Program_Budget_Year::max('Id_Program_Budget_Year') ?? 0;
            $currentIdProgramBudgetYear++;

            Program_Budget_Year::create([
                'Id_Program_Budget_Year' => $currentIdProgramBudgetYear,
                'Budget_Year' => $budgetYear,
                'Program_Id' => $currentIdProgram,
            ]);
        }
    }

    public function editProgram(Request $request, $Id_Platform, $Id_Program)
    {
        $platforms = Platform::findOrFail($Id_Platform);
        $program = $platforms->programs()->findOrFail($Id_Program);
    
        if ($request->isMethod('put')) {
            $validatedData = $request->validate([
                'Name_Program' => 'required|string|max:255',
                'Name_Object' => 'required|string|max:255',
                'Budget_Year' => 'required|array|size:2',
                'Budget_Year.*' => 'required|integer|distinct',
            ]);
    
            DB::beginTransaction();
    
            try {
                $program->Name_Program = $validatedData['Name_Program'];
                $program->Name_Object = $validatedData['Name_Object'];
                $program->save();
    
                // ลบปีงบประมาณเก่า
                $program->budgetYears()->delete();
    
                // สร้างปีงบประมาณใหม่
                $this->createProgramBudgetYears($Id_Program, $validatedData['Budget_Year']);
    
                DB::commit();
                return redirect()->route('showPlatform')->with('success', 'Platform updated successfully');
            } catch (\Exception $e) {
                DB::rollback();
                return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            }
        }
    
        $budgetYears = $program->budgetYears()->orderBy('Budget_Year')->pluck('Budget_Year')->toArray();
        return view('StrategicUniversity.editPlatform', compact('platform', 'budgetYears'));
    }


    public function deleteProgram(Request $request, $Id_Program)
    {
        $program = Program::find($Id_Program);
        if (!$program) {
            return redirect()->back()->with('error', 'Program not found');
        }
    
        DB::beginTransaction();
    
        Program_Year::whereIn('Program_Kpi_Id', function($query) use ($Id_Program) {
            $query->select('Id_Program_Kpi')
                  ->from('Program_Kpi')
                  ->where('Program_Id', $Id_Program);
        })->delete();

        $program->programKpis()->delete();
        $program->budgetYears()->delete();
        $program->delete();

        DB::commit();
        return redirect()->back()->with('success', 'ลบเป้าหมายการพัฒนาเรียบร้อยแล้ว');
    }


    // PlatformKpi---------------------------------------------------------------------------------------------------
    public function showProgramKpi($Id_Program)
    {
        $program = Program::findOrFail($Id_Program);
        // ดึงข้อมูล Program_Kpi ที่เกี่ยวข้องกับ Program นี้
        $programKpis = Program_Kpi::where('Program_Id', $Id_Program)->with('program')->get();
        $program = Program::with(['programKpis.programYears', 'budgetYears'])->findOrFail($Id_Program);
        $budgetYears = $program->budgetYears;

        return view('StrategicUniversity.showProgramKpi', compact('program', 'programKpis', 'budgetYears'));
    }

    public function createProgramKpi(Request $request, $Id_Program)
    {
        $program = Program::findOrFail($Id_Program);
    
        if ($request->isMethod('post')) {
            $request->validate([
                'Name_Program_Kpi' => 'required|string|max:255',
                'Description_Program_Kpi' => 'required|string',
                'Value_Program' => 'required|array|min:2',
                'Value_Program.*' => 'required|numeric|min:1',
            ]);
    
            DB::beginTransaction();
    
            $currentIdProgramKpi = Program_Kpi::max('Id_Program_Kpi') ?? 0;
            $currentIdProgramKpi++;

            $programKpi = new Program_Kpi;
            $programKpi->Id_Program_Kpi = $currentIdProgramKpi;
            $programKpi->Name_Program_Kpi = $request->Name_Program_Kpi;
            $programKpi->Description_Program_Kpi = $request->Description_Program_Kpi;
            $programKpi->Program_Id = $program->Id_Program;
            $program->programKpis()->save($programKpi);

            $budgetYears = $program->budgetYears()->orderBy('Budget_Year')->get();
            foreach ($budgetYears as $index => $budgetYear) {
                if (isset($request->Value_Program[$index])) {
                    $programYear = new Program_Year([
                        'Program_Kpi_Id' => $currentIdProgramKpi,
                        'Program_Budget_Year_Id' => $budgetYear->Id_Program_Budget_Year,
                        'Value_Program' => $request->Value_Program[$index],
                    ]);
                    $programYear->save();
                }
            }

            DB::commit();
            return redirect()->route('showPlatform', ['Id_Program' => $program->Id_Program])
                        ->with('success', 'บันทึก Platform KPI เรียบร้อยแล้ว');
           
        }
        $budgetYears = $program->budgetYears()->orderBy('Budget_Year')->get();
        return view('StrategicUniversity.createProgramKpi', compact('program', 'budgetYears'));
    }


    public function editProgramKpi(Request $request, $Id_Program, $Id_Program_Kpi)
    {
        $program = Program::with(['budgetYears', 'programKpis.programYears'])->findOrFail($Id_Program);
        $programKpi = Program_Kpi::with('programYears')->findOrFail($Id_Program_Kpi);
        $budgetYears = $program->budgetYears()->orderBy('Budget_Year')->get();

        if ($request->isMethod('put')) {
            $request->validate([
                'Name_Program_Kpi' => 'required|string|max:255',
                'Description_Program_Kpi' => 'required|string',
                'Value_Program' => 'required|array|min:2',
                'Value_Program.*' => 'required|numeric|min:1',
            ]);
    
            DB::beginTransaction();
    
            try {
                // อัปเดตข้อมูล Program_Kpi
                $programKpi->update([
                    'Name_Program_Kpi' => $request->Name_Program_Kpi,
                    'Description_Program_Kpi' => $request->Description_Program_Kpi,
                ]);
    
                // อัปเดตข้อมูล Program_Year
                foreach ($budgetYears as $budgetYear) {
                    $value = $request->input('Value_Program.' . $budgetYear->Id_Program_Budget_Year, null);
                    if ($value !== null) {
                        Program_Year::updateOrCreate(
                            [
                                'Program_Kpi_Id' => $Id_Program_Kpi,
                                'Program_Budget_Year_Id' => $budgetYear->Id_Program_Budget_Year,
                            ],
                            ['Value_Program' => $value]
                        );
                    }
                }
    
                DB::commit();
    
                return redirect()->route('showProgram', ['Id_Program' => $program->Id_Program])
                    ->with('success', 'แก้ไข Program KPI เรียบร้อยแล้ว');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->withErrors('เกิดข้อผิดพลาด: ' . $e->getMessage());
            }
        }
    
        $budgetYears = $program->budgetYears()->orderBy('Budget_Year')->get();
        $programYears = $programKpi->programYears->keyBy('Program_Budget_Year_Id');
    
        return view('StrategicUniversity.editProgramKpi', compact('program', 'programKpi', 'budgetYears', 'programYears'));
    }

    public function deleteProgramKpi(Request $request, $Id_Program_Kpi)
    {
        $programKpi = Program_Kpi::find($Id_Program_Kpi);
        if (!$programKpi) {
            return redirect()->back()->with('error', 'ไม่พบ Program KPI นี้');
        }
    
        DB::beginTransaction();
    
        try {
            $programKpi->programYears()->delete();
            $programKpi->delete();
    
            DB::commit();
            return redirect()->back()->with('success', 'ลบ Program KPI เรียบร้อยแล้ว');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการลบ Program KPI: ' . $e->getMessage());
        }
    }
}
