<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuccessIndicatorsModel extends Model
{   
    protected $connection = 'mydb';
    protected $keyType = 'int';
    protected $table = 'Success_Indicators';
    protected $primaryKey = 'Id_Success_Indicators';
    public $timestamps = false;
    
    protected $fillable = [
        'Project_Id',
        'Description_Indicators',
        'Type_Indicators'
    ];
    
    public function project()
    {
        return $this->belongsTo(ListProjectModel::class, 'Project_Id', 'Id_Project');
    }
    
    public function valueTargets()
    {
        return $this->hasMany(ValueTargetModel::class, 'Id_Success_Indicators', 'Id_Success_Indicators');
    }
}