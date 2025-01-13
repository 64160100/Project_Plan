<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Program_Budget_Year extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Program_Budget_Year';
    protected $primaryKey = 'Id_Program_Budget_Year';

    protected $fillable = [
        'Id_Program_Budget_Year',
        'Program_Id',
        'Budget_Year',
    ];
    public $timestamps = false;

    public function program()
    {
        return $this->belongsTo(Program::class, 'Program_Id', 'Id_Program');
    }

    public function platformBudgetYears()
    {
        return $this->hasMany(Platform_Budget_Year::class, 'Budget_Year', 'Budget_Year');
    }
}



