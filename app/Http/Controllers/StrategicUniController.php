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

class StrategicUniController extends Controller
{
    public function showPlatform()
    {
        $platforms = Platform::all();
        // $platforms = Platform::with('budgetYears')->get();
        return view('StrategicUniversity.Platform',compact('platforms'));
    }

    public function createPlatform(Request $request)
    {
        $validatedData = $request->validate([
            'Name_Platform' => 'required|string|max:255',
            'Name_Object' => 'required|string',
            'Budget_Year' => 'required|array|size:2',
            'Budget_Year.*' => 'required|integer|min:2500|max:3500|distinct',
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
                'Budget_Year.*' => 'required|integer|min:2500|max:3500|distinct',
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
    
        try {
            // ลบข้อมูลใน Platform_Year ก่อน
            Platform_Year::where('Platform_Kpi_Id', function($query) use ($Id_Platform) {
                $query->select('Id_Platform_Kpi')
                      ->from('Platform_Kpi')
                      ->where('Platform_Id_Platform', $Id_Platform);
            })->delete();
    
            $platforms->platformKpis()->delete();
            $platforms->budgetYears()->delete();
            $platforms->delete();
    
            DB::commit();
            return redirect()->back()->with('success', 'ลบเป้าหมายการพัฒนาเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการลบข้อมูล: ' . $e->getMessage());
        }
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
                'Value_Platform.*' => 'required|numeric|min:0',
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

    //editPlatformKpi
    public function editPlatformKpi(Request $request, $Id_Platform, $Id_Platform_Kpi)
    {
        $platforms = Platform::findOrFail($Id_Platform);
        $platformKpi = $platforms->platformKpis()->findOrFail($Id_Platform_Kpi);
    
        if ($request->isMethod('put')) {
            $request->validate([
                'Name_Platfrom_Kpi' => 'required|string|max:255',
                'Description_Platfrom_Kpi' => 'required|string',
                'Value_Platform' => 'required|array|min:2',
                'Value_Platform.*' => 'required|numeric|min:0',
            ]);
    
            DB::beginTransaction();
    
            try {
                $platformKpi->Name_Platfrom_Kpi = $request->Name_Platfrom_Kpi;
                $platformKpi->Description_Platfrom_Kpi = $request->Description_Platfrom_Kpi;
                $platformKpi->save();
    
                // $budgetYears = $platforms->budgetYears()->orderBy('Budget_Year')->get();
                // foreach ($budgetYears as $index => $budgetYear) {
                //     $budgetYearId = $budgetYear->Id_Platform_Budget_Year;
                //     if (isset($request->Value_Platform[$index])) {
                //         Platform_Year::updateOrCreate(
                //             [
                //                 'Platform_Kpi_Id' => $Id_Platform_Kpi,
                //                 'Platform_Budget_Year_Id' => $budgetYearId,
                //             ],
                //             ['Value_Platform' => $request->Value_Platform[$index]]
                //         );
                //     }
                // }

                $budgetYears = $platforms->budgetYears()->orderBy('Budget_Year')->get();
                foreach ($budgetYears as $index => $budgetYear) {
                    $kpiValue = $request->input("Value_Platform.{$index}");

                        if ($kpiValue !== null) {
                            Platform_Year::updateOrCreate(
                                [
                                    'Platform_Kpi_Id' => $Id_Platform_Kpi,
                                    'Platform_Budget_Year_Id' => $budgetYear->Id_Platform_Budget_Year,
                                ],
                                ['Value_Platform' => $kpiValue]
                            );
                        }
                }
                
                DB::commit();
                return redirect()->route('showPlatform', ['Id_Platform' => $Id_Platform])
                            ->with('success', 'อัพเดต Platform KPI เรียบร้อยแล้ว');
            } catch (\Exception $e) {
                DB::rollback();
                return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            }
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
        // $platforms = Platform::findOrFail($Id_Platform);
    }

}
