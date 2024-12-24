<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Platform;
use App\Models\Platform_Kpi;

class StrategicUniController extends Controller
{
    public function showPlatform()
    {
        $platforms = Platform::all();
        return view('StrategicUniversity.Platform',compact('platforms'));
    }

    public function createPlatform(Request $request)
    {
        $currentIdPlatform = Platform::max('Id_Platform') ?? 0;
        $currentIdPlatform++;

        $platforms = new Platform;
        $platforms->Id_Platform = $currentIdPlatform;
        $platforms->Name_Platform = $request->Name_Platform;
        $platforms->Name_Object = $request->Name_Object;
        $platforms->save();

        return redirect()->route('showPlatform')->with('success', 'save');

    }


    public function editPlatform(Request $request, $Id_Platform)
    {
        $platforms = Platform::find($Id_Platform);

        if (!$platforms) {
            return redirect()->back()->with('error', 'ไม่พบข้อมูลใน Platform ที่ต้องการแก้ไข');
        }

        if ($request->isMethod('put')) {
            $platforms->Name_Platform = $request->Name_Platform;
            $platforms->Name_Object = $request->Name_Object;
            $platforms->save();
        }
        return redirect()->back()->with('success', 'อัพเดต platform เรียบร้อยแล้ว');
    }


    public function deletePlatform(Request $request, $Id_Platform)
    {
        $platforms = Platform::find($Id_Platform);
        if (!$platforms) {
            return redirect()->back()->with('error', 'ไม่พบเป้าหมายการพัฒนานี้');
        }
        $platforms->delete();
        return redirect()->back()->with('success', 'อัปเดตเป้าหมายการพัฒนาเรียบร้อยแล้ว');
    }

    // showPlatformObj------------------------------------------------------------------------------------------------
    public function showPlatformObj($Id_Platform){
        $platforms = Platform::find($Id_Platform);
        // $platforms = Platform::with('platformKpis')->findOrFail($Id_Platform_Kpi);
        return view('StrategicUniversity.showPlatformObj',compact('platforms'));
    }

    public function createPlatformKpi(Request $request, $Id_Platform){
        $platforms = Platform::findOrFail($Id_Platform); //หาแพลตฟอร์มตาม id

        $currentIdPlatformKpi = Platform_Kpi::max('Id_Platform_Kpi') ?? 0;
        $currentIdPlatformKpi++;

        $platformsKpi = new Platform_Kpi;
        $platformsKpi->Id_Platform_Kpi = $currentIdPlatformKpi;
        $platformsKpi->Name_Platfrom_Kpi = $request->Name_Platfrom_Kpi;
        $platformsKpi->Description_Platfrom_Kpi = $request->Description_Platfrom_Kpi;
        $platformsKpi->Platform_Id_Platform = $platforms->Id_Platform;
        
        $platformsKpi->save();

        return redirect()->route('showPlatformObj', ['Id_Platform' => $platforms->Id_Platform])->with('success', 'save');
        // return view('StrategicUniversity.showPlatformObj');
    }

    public function editPlatformKpi(){
        // return view('StrategicUniversity.PlatformKpi');
    }

    public function deletePlatformKpi(){
        // return view('StrategicUniversity.PlatformKpi');
    }
}
