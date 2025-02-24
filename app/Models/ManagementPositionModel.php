<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManagementPositionModel extends Model
{   
    protected $connection = 'mydb';
    protected $table = 'management_positions';
    protected $primaryKey = 'Id_ManagementPosition';
    protected $fillable = [
        'ManagementPositionName',
        'Employee_Id'
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeModel::class, 'Employee_Id', 'Id_Employee');
    }
}
