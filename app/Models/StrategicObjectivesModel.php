<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrategicObjectivesModel extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Strategic_Objectives';
    protected $primaryKey = 'Id_Strategic_Objectives';
    protected $fillable = [
        'Id_Strategic_Objectives',
        'Strategy_Id',
        'Details_Strategic_Objectives',
    ];
    public $timestamps = false;

}
