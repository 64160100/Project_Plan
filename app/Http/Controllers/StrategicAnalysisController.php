<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StrategicOpportunityModel;
use App\Models\StrategicOpportunityDetailsModel;
use App\Models\StrategicChallengesModel;
use App\Models\StrategicAdvantagesModel;
use Illuminate\Support\Facades\Log;

class StrategicAnalysisController extends Controller
{
    public function index()
    {
        $strategicOpportunities = StrategicOpportunityModel::with(['details', 'challenges', 'advantages'])->get();
        return view('strategy.modelStrategy', compact('strategicOpportunities'));
    }

    public function updateDetail($type, $id, Request $request)
    {
        try {
            switch ($type) {
                case 'opportunity':
                    $opportunity = StrategicOpportunityModel::findOrFail($id);
                    $opportunity->Name_Strategic_Opportunity = $request->Name_Strategic_Opportunity;
                    $opportunity->save();

                    if ($request->has('details')) {
                        $opportunity->details()->delete();
                        $details = is_array($request->details) ? $request->details : explode("\n", $request->details);
                        foreach ($details as $detail) {
                            if (!empty(trim($detail))) {
                                $opportunity->details()->create(['Details_Strategic_Opportunity' => trim($detail)]);
                            }
                        }
                    }

                    if ($request->has('challenges')) {
                        $opportunity->challenges()->delete();
                        $challenges = is_array($request->challenges) ? $request->challenges : explode("\n", $request->challenges);
                        foreach ($challenges as $challenge) {
                            if (!empty(trim($challenge))) {
                                $opportunity->challenges()->create(['Details_Strategic_Challenges' => trim($challenge)]);
                            }
                        }
                    }

                    if ($request->has('advantages')) {
                        $opportunity->advantages()->delete();
                        $advantages = is_array($request->advantages) ? $request->advantages : explode("\n", $request->advantages);
                        foreach ($advantages as $advantage) {
                            if (!empty(trim($advantage))) {
                                $opportunity->advantages()->create(['Details_Strategic_Advantages' => trim($advantage)]);
                            }
                        }
                    }
                    break;

                case 'details':
                    $detail = StrategicOpportunityDetailsModel::findOrFail($id);
                    $detail->Details_Strategic_Opportunity = $request->Details_Strategic_Opportunity;
                    $detail->save();
                    break;

                case 'challenges':
                    $challenge = StrategicChallengesModel::findOrFail($id);
                    $challenge->Details_Strategic_Challenges = $request->Details_Strategic_Challenges;
                    $challenge->save();
                    break;

                case 'advantages':
                    $advantage = StrategicAdvantagesModel::findOrFail($id);
                    $advantage->Details_Strategic_Advantages = $request->Details_Strategic_Advantages;
                    $advantage->save();
                    break;

                default:
                    return redirect()->back()->with('error', 'ไม่พบประเภทข้อมูลที่ต้องการแก้ไข');
            }

            return redirect()->back()->with('success', 'อัปเดตข้อมูลสำเร็จ');
        } catch (\Exception $e) {
            \Log::error('Error updating ' . $type . ' with ID ' . $id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล: ' . $e->getMessage());
        }
    }

    public function destroyDetail($type, $id)
    {
        try {
            switch ($type) {
                case 'details':
                    $detail = StrategicOpportunityDetailsModel::findOrFail($id);
                    break;
                case 'challenges':
                    $detail = StrategicChallengesModel::findOrFail($id);
                    break;
                case 'advantages':
                    $detail = StrategicAdvantagesModel::findOrFail($id);
                    break;
                default:
                    return redirect()->back()->with('error', 'ไม่พบประเภทข้อมูลที่ต้องการลบ');
            }

            $detail->delete();
            return redirect()->back()->with('success', 'ลบข้อมูลสำเร็จ');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการลบข้อมูล: ' . $e->getMessage());
        }
    }


    public function addDetail(Request $request)
    {
        try {
            if (empty($request->Name_Strategic_Opportunity) || empty($request->Strategic_Id_Strategic)) {
                return redirect()->back()->with('error', 'Name and Strategic ID are required.');
            }

            $opportunity = StrategicOpportunityModel::where('Name_Strategic_Opportunity', $request->Name_Strategic_Opportunity)
                ->where('Strategic_Id_Strategic', $request->Strategic_Id_Strategic)
                ->first();

            if (!$opportunity) {
                return redirect()->back()->with('error', 'Strategic Opportunity not found.');
            }

            if ($request->has('details')) {
                $details = is_array($request->details) ? $request->details : explode("\n", $request->details);
                foreach ($details as $detail) {
                    if (!empty(trim($detail))) {
                        $opportunity->details()->create([
                            'Details_Strategic_Opportunity' => trim($detail),
                            'Strategic_Opportunity_Id' => $opportunity->Id_Strategic_Opportunity
                        ]);
                    }
                }
            }

            if ($request->has('challenges')) {
                $challenges = is_array($request->challenges) ? $request->challenges : explode("\n", $request->challenges);
                foreach ($challenges as $challenge) {
                    if (!empty(trim($challenge))) {
                        $opportunity->challenges()->create([
                            'Details_Strategic_Challenges' => trim($challenge),
                            'Strategic_Opportunity_Id' => $opportunity->Id_Strategic_Opportunity
                        ]);
                    }
                }
            }

            if ($request->has('advantages')) {
                $advantages = is_array($request->advantages) ? $request->advantages : explode("\n", $request->advantages);
                foreach ($advantages as $advantage) {
                    if (!empty(trim($advantage))) {
                        $opportunity->advantages()->create([
                            'Details_Strategic_Advantages' => trim($advantage),
                            'Strategic_Opportunity_Id' => $opportunity->Id_Strategic_Opportunity
                        ]);
                    }
                }
            }

            \Log::info('Details added to existing strategic analysis: ' . $opportunity->Id_Strategic_Opportunity);

            return redirect()->back()->with('success', 'เพิ่มรายละเอียดการวิเคราะห์เชิงกลยุทธ์สำเร็จ');
        } catch (\Exception $e) {
            \Log::error('Error adding details to strategic analysis: ' . $e->getMessage());
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล: ' . $e->getMessage());
        }
    }

}