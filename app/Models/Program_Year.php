<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Traits\HasCompositePrimaryKeyTrait;

class Program_Year extends Model
{
    use HasFactory, HasCompositePrimaryKeyTrait;
    protected $connection = 'mydb';
    protected $table = 'Program_Year';
    protected $primaryKey = ['Program_Kpi_Id', 'Program_Budget_Year_Id'];
    protected $keyType = 'int';
    public $incrementing = false;

    protected $fillable = [
        'Program_Kpi_Id',
        'Program_Budget_Year_Id',
        'Value_Program',
    ];
    public $timestamps = false;

    public function programKpi()
    {
        return $this->belongsTo(Program_Kpi::class, 'Program_Kpi_Id', 'Id_Program_Kpi');
    }

    public function programBudgetYear()
    {
        return $this->belongsTo(Program_Budget_Year::class, 'Program_Budget_Year_Id', 'Id_Program_Budget_Year');
    }

    
}

