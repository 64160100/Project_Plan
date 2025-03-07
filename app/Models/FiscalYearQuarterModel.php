<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FiscalYearQuarterModel extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Quarter_Project';
    protected $primaryKey = 'Id_Quarter_Project';
    protected $fillable = [
        'Fiscal_Year',
        'Quarter',
    ];

    public $timestamps = false;

    public function projects()
    {
        return $this->hasMany(ProjectHasQuarterProject::class, 'Quarter_Project_Id');
    }

    // public function strategic()
    // {
    //     return $this->hasMany(StrategicHasQuarterProjectModel::class, 'Quarter_Project_Id');
    // }

    public function strategic()
    {
        return $this->belongsToMany(StrategicModel::class,'Strategic_has_Quarter_Project','Quarter_Project_Id','Strategic_Id');
    }
}