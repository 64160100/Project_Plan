<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KpiModel extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Kpi';
    protected $primaryKey = 'Id_Kpi';
    protected $fillable = [
        'Id_Kpi',
        'Strategy_Id',
        'Name_Kpi',
        'Target_Value',
    ];
    public $timestamps = false;

    public function strategy()
    {
        return $this->belongsTo(Strategy::class, 'Strategy_Id', 'Id_Strategy');
    }

}
