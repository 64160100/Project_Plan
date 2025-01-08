<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Platform_Budget_Year extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Platform_Budget_Year';
    protected $primaryKey = 'Id_Platform_Budget_Year';
    protected $keyType = 'int';

    protected $fillable = [
        'Id_Platform_Budget_Year',
        'Platform_Id',
        'Budget_Year',
    ];
    public $timestamps = false;


    //ดึงข้อมูลด้วยตาราง Platform_Year 
    public function platformKpis()
    {
        return $this->belongsToMany(Platform_Kpi::class, 'Platform_Year', 'Platform_Budget_Year_Id', 'Platform_Kpi_Id')
                    ->withPivot('Value_Platform');
    }

    public function platform()
    {
        return $this->belongsTo(Platform::class, 'Platform_Id', 'Id_Platform');
    }

}
