<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Traits\HasCompositePrimaryKeyTrait;

class Platform_Year extends Model
{
    use HasCompositePrimaryKeyTrait;
    protected $connection = 'mydb';
    protected $table = 'Platform_Year';
    protected $primaryKey = ['Platform_Kpi_Id', 'Platform_Budget_Year_Id'];
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'Platform_Kpi_Id',
        'Platform_Budget_Year_Id',
        'Value_Platform',
    ];
    public $timestamps = false;

    public function platformKpi()
    {
        return $this->belongsTo(Platform_Kpi::class, 'Platform_Kpi_Id', 'Id_Platform_Kpi');
    }

    public function platformBudgetYear()
    {
        return $this->belongsTo(Platform_Budget_Year::class, 'Platform_Budget_Year_Id', 'Id_Platform_Budget_Year');
    }

}
