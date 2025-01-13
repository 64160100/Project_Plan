<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Program_Kpi extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Program_Kpi';
    protected $primaryKey = 'Id_Program_Kpi';

    protected $fillable = [
        'Id_Program_Kpi',
        'Program_Id',
        'Name_Program_Kpi',
        'Description_Program_Kpi',
    ];
    public $timestamps = false;

    
    public function programs()
    {
        return $this->belongsTo(Program::class, 'Program_Id', 'Id_Program');
    }

    public function programYears()
    {
        return $this->hasMany(Program_Year::class, 'Program_Kpi_Id', 'Id_Program_Kpi');
    }

    public function programKpis()
    {
        return $this->belongsTo(Program::class, 'Program_Id', 'Id_Program');
    }

    public function budgetYears()
    {
        return $this->belongsToMany(Program_Budget_Year::class, 'Program_Year', 'Program_Kpi_Id', 'Program_Budget_Year_Id')
                    ->withPivot('Value_Program')
                    ->orderBy('Budget_Year');
    }
}

