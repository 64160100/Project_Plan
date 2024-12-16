<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\KpiModel;


class StrategyModel extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Strategy';
    protected $primaryKey = 'Id_Strategy';
    protected $keyType = 'int';
    protected $fillable = [
        'Id_Strategy',
        'Strategic_Id',
        'Name_Strategy',
        'Strategy_Objectives',
    ];
    public $timestamps = false;

    public function kpis()
    {
        return $this->hasMany(KpiModel::class, 'Strategy_Id', 'Id_Strategy');
    }

    public function strategicObjectives()
    {
        return $this->hasMany(StrategicObjectivesModel::class, 'Strategy_Id_Strategy', 'Id_Strategy');
    }

}
