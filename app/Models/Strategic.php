<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Strategic extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Strategic';
    protected $primaryKey = 'Id_Strategic';

    protected $fillable = [
        'Id_Strategic',
        'Name_Strategic_Plan',
        'Goals_Strategic',
    ];
    public $timestamps = false;

    // Strategic มีหลายโปรเจค
    public function projects()
    {
        // return $this->hasMany(Project::class, 'Strategic_Id');
        return $this->hasMany(Project::class, 'Strategic_Id', 'Id_Strategic');
    }

    
    //เพิ่มโครงการ
    public static function SelectFormStrategic(){
        $strategic=Strategic::where('Status', 0)->get();
        foreach ($strategic as $strategicItem) {
            // ดึงข้อมูลโครงการที่เกี่ยวข้องกับยุทธศาสตร์นั้น
            $strategicItem->projects = $strategicItem->projects()
                ->select('Id_Project', 'Strategic_Id', 'Name_Project')  // เลือกคอลัมน์ที่ต้องการ
                ->get();
        }

        return $strategic;
    }

    

    
    
}
