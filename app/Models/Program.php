<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Program extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Program';
    protected $primaryKey = 'Id_Program';

    protected $fillable = [
        'Id_Program',
        'Name_Program',
        'Name_Object',
        'Platform_Id_Platform',
    ];
    public $timestamps = false;

    public function platform()
    {
        return $this->belongsTo(Platform::class, 'Platform_Id_Platform', 'Id_Platform');
    }

    public function programKpis()
    {
        return $this->hasMany(Program_Kpi::class, 'Program_Id', 'Id_Program');
    }

    public function budgetYears()
    {
        return $this->hasMany(Program_Budget_Year::class, 'Program_Id', 'Id_Program');
    }


    
}

