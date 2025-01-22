<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndicatorsModel extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Indicators';
    protected $primaryKey = 'Id_Indicators';
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'Type_Indicators',
        'Details_Indicators',
        'Project_Id'
    ];

    public function project()
    {
        return $this->belongsTo(ListProjectModel::class, 'Project_Id', 'Id_Project');
    }
}
