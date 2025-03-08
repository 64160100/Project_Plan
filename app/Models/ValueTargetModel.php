<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ValueTargetModel extends Model
{   
    protected $connection = 'mydb';
    protected $keyType = 'int';
    protected $table = 'Value_Target';
    protected $primaryKey = 'Id_Value_Target';
    public $timestamps = false;
    
    protected $fillable = [
        'Project_Id',
        'Value_Target',
        'Type_Value_Target'
    ];
    
    public function project()
    {
        return $this->belongsTo(ListProjectModel::class, 'Project_Id', 'Id_Project');
    }
    
    public function successIndicator()
    {
        return $this->belongsTo(SuccessIndicatorsModel::class, 'Project_Id', 'Project_Id');
    }
}