<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Platform_Kpi extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Platform_Kpi';
    protected $primaryKey = 'Id_Platform_Kpi';

    protected $fillable = [
        'Id_Platform_Kpi',
        'Name_Platfrom_Kpi',
        'Description_Platfrom_Kpi',
        'Platform_Id_Platform',
    ];
    public $timestamps = false;

    public function platform()
    {
        return $this->belongsTo(Platform::class, 'Platform_Id_Platform','Id_Platform_Kpi');
    }

    
    public function platformYears()
    {
        return $this->hasMany(Platform_Year::class, 'Platform_Kpi_Id', 'Id_Platform_Kpi');
    }

    //
    public function budgetYears()
    {
        return $this->belongsToMany(Platform_Budget_Year::class, 'Platform_Year', 'Platform_Kpi_Id', 'Platform_Budget_Year_Id')
                    ->withPivot('Value_Platform');
    }
}
