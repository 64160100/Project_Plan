<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\StrategyModel;
use App\Models\ListProjectModel;

class StrategicModel extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Strategic';
    protected $primaryKey = 'Id_Strategic';
    protected $fillable = [
        'Id_Strategic',
        'Name_Strategic_Plan',
        'Goals_Strategic',
    ];
    public $timestamps = false;

    public function strategies()
    {
        return $this->hasMany(StrategyModel::class, 'Strategic_Id', 'Id_Strategic');
    }

    public function projects()
    {
        return $this->hasMany(ListProjectModel::class, 'Strategic_Id', 'Id_Strategic');
    }
}