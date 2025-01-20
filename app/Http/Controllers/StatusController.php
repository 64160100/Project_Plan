<?php
namespace App\Http\Controllers;

use App\Models\ProjectModel;
use App\Models\ListProjectModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StatusController extends Controller
{
    public function statusTracking(Request $request)
    {
        $employee = $request->session()->get('employee');
        $permissions = $request->session()->get('permissions');
    
        if ($employee) {
            $projectsQuery = ListProjectModel::query();

            if ($employee->IsAdmin !== 'Y') {
                $projectsQuery->where('Employee_Id', $employee->Id_Employee);
            }

            $projects = $projectsQuery->whereHas('approvals', function($query) {
                    $query->whereIn('Status', ['I', 'N']);
                })
                ->with(['approvals.recordHistory', 'employee.department', 'employee'])
                ->get();
    
            foreach ($projects as $project) {
                $employeeData = $project->employee;
                $project->employeeName = $employeeData ? $employeeData->Firstname_Employee . ' ' . $employeeData->Lastname_Employee : '-';
                $project->departmentName = $employeeData->department->Name_Department ?? 'No Department';
    
                if ($project->First_Time) {
                    $date = new \DateTime($project->First_Time);
                    $thaiMonths = [
                        1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน',
                        5 => 'พฤษภาคม', 6 => 'มิถุนายน', 7 => 'กรกฎาคม', 8 => 'สิงหาคม',
                        9 => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม'
                    ];
                    $day = $date->format('d');
                    $month = $thaiMonths[(int)$date->format('m')];
                    $year = (int)$date->format('Y') + 543; 
                    $project->formattedFirstTime = "วันที่ $day $month พ.ศ $year";
                } else {
                    $project->formattedFirstTime = '-';
                }
    
                foreach ($project->approvals as $approval) {
                    foreach ($approval->recordHistory as $history) {
                        $timeRecord = $history->Time_Record;
                        $date = \Carbon\Carbon::parse($timeRecord);
                        $thaiMonths = [
                            1 => 'ม.ค', 2 => 'ก.พ', 3 => 'มี.ค', 4 => 'เม.ย',
                            5 => 'พ.ค', 6 => 'มิ.ย', 7 => 'ก.ค', 8 => 'ส.ค',
                            9 => 'ก.ย', 10 => 'ต.ค', 11 => 'พ.ย', 12 => 'ธ.ค'
                        ];
                        $history->formattedTimeRecord = $date->format('j') . ' ' . $thaiMonths[(int)$date->format('m')] . ' ' . ($date->year + 543);
                    }
                }
    
                foreach ($project->approvals as $approval) {
                    foreach ($approval->recordHistory as $history) {
                        $timeRecord = $history->Time_Record;
                        $date = \Carbon\Carbon::parse($timeRecord);
                        $thaiMonths = [
                            1 => 'ม.ค', 2 => 'ก.พ', 3 => 'มี.ค', 4 => 'เม.ย',
                            5 => 'พ.ค', 6 => 'มิ.ย', 7 => 'ก.ค', 8 => 'ส.ค',
                            9 => 'ก.ย', 10 => 'ต.ค', 11 => 'พ.ย', 12 => 'ธ.ค'
                        ];
                        $day = $date->format('j');
                        $month = $thaiMonths[(int)$date->format('m')];
                        $year = $date->year + 543;
                        $time = $date->format('H:i น.');
                        $history->formattedDateTime = "$day $month $year $time";
                    }
                }
            }

            Log::info('User ' . $projects);
            
            return view('statusTracking', compact('projects'));
        } else {
            return redirect()->back()->with('error', 'You are not authorized to view these projects.');
        }
    }

    public function showDetails($Id_Project)
    {
        $project = ListProjectModel::with(['approvals.recordHistory', 'employee.department', 'employee'])
            ->findOrFail($Id_Project);
    
        $stepTexts = [
            'เสนอโครงการ',
            'ผู้บริหารอนุมัติ',
            'กรอกข้อมูลเพิ่มเติม',
            'ตรวจสอบงบประมาณ',
            'หัวหน้าฝ่าย',
            'ผู้บริหารพิจารณา',
            'ดำเนินโครงการ',
            'ตรวจสอบผล',
            'รับรองผล',
            'ปิดโครงการ',
            'สถานะพิเศษ'
        ];
    
        $statusMessages = [
            0 => ['title' => 'ขั้นตอนที่ 1: เริ่มต้นการเสนอโครงการ', 'detail' => 'ถึง: ผู้บริหารพิจารณาเบื้องต้น'],
            1 => ['title' => 'ขั้นตอนที่ 2: อยู่ระหว่างการพิจารณาเบื้องต้น', 'detail' => 'สถานะ: รอการพิจารณาจากผู้บริหาร'],
            2 => ['title' => 'ขั้นตอนที่ 3: การพิจารณาด้านงบประมาณ', 'detail' => 'ถึง: ฝ่ายการเงินตรวจสอบงบประมาณ'],
            3 => ['title' => 'ขั้นตอนที่ 4: การตรวจสอบความเหมาะสมด้านงบประมาณ', 'detail' => 'สถานะ: อยู่ระหว่างการตรวจสอบโดยฝ่ายการเงิน'],
            4 => ['title' => 'ขั้นตอนที่ 5: การพิจารณาโดยหัวหน้าฝ่าย', 'detail' => 'สถานะ: อยู่ระหว่างการตรวจสอบโดยหัวหน้าฝ่าย'],
            5 => ['title' => 'ขั้นตอนที่ 6: การพิจารณาขั้นสุดท้าย', 'detail' => 'สถานะ: อยู่ระหว่างการพิจารณาโดยผู้บริหาร'],
            6 => ['title' => 'ขั้นตอนที่ 7: การดำเนินโครงการ', 'detail' => 'สถานะ: อยู่ระหว่างการดำเนินงาน'],
            7 => ['title' => 'ขั้นตอนที่ 8: การตรวจสอบผลการดำเนินงาน', 'detail' => 'สถานะ: รอการตรวจสอบจากหัวหน้าฝ่าย'],
            8 => ['title' => 'ขั้นตอนที่ 9: การรับรองผลการดำเนินงาน', 'detail' => 'สถานะ: รอการรับรองจากผู้บริหาร'],
            9 => ['title' => 'ขั้นตอนที่ 10: การปิดโครงการ', 'detail' => 'สถานะ: ดำเนินการเสร็จสิ้นสมบูรณ์'],
            10 => ['title' => 'สถานะพิเศษ: การดำเนินการล่าช้า', 'detail' => 'สถานะ: รอการพิจารณาจากผู้บริหาร']
        ];
    
        return view('statusDetails', compact('project', 'stepTexts', 'statusMessages'));
    }
}