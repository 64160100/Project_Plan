<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SDGsModel;

class SustainableDevelopmentGoalsController extends Controller
{
    public function showSdg()
    {
        $sdg = SDGsModel::all();
        return view('SDG.Sdg', compact('sdg'));
    }

    public function createSDG(Request $request)
    {
            $sdg = new SDGsModel;
            $sdg->id_SDGs = $request->id_SDGs;
            $sdg->Name_SDGs = $request->Name_SDGs;
            $sdg->save();

        return redirect()->route('showSdg')->with('success', 'save');
    }

   
    public function editSDG(Request $request, $id_SDGs)
    {
        $sdg = SDGsModel::find($id_SDGs);

        if (!$sdg) {
            return redirect()->back()->with('error', 'ไม่พบข้อมูลเป้าหมายการพัฒนานี้');
        }
    

        if($request->isMethod('put')){
            $sdg->id_SDGs = $request->id_SDGs;
            $sdg->Name_SDGs = $request->Name_SDGs;
            $sdg->save();
        }
            return redirect()->back()->with('success', 'อัปเดตเป้าหมายการพัฒนาเรียบร้อยแล้ว');
        // }
    }

    public function deleteSDG(Request $request, $id_SDGs)
    {
        $sdg = SDGsModel::find($id_SDGs);
        if (!$sdg) {
            return redirect()->back()->with('error', 'ไม่พบเป้าหมายการพัฒนานี้');
        }
        $sdg->delete();
        return redirect()->back()->with('success', 'อัปเดตเป้าหมายการพัฒนาเรียบร้อยแล้ว');
    }

}
