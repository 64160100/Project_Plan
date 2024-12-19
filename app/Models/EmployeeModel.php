<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeModel extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Employee';
    protected $primaryKey = 'Id_Employee';
    protected $fillable = [
        'Firstname_Employee',
        'Lastname_Employee',
        'Email',
        'Password',
        'IsManager',
        'IsDirector',
        'Department_Id',
        'Position_Id',
    ];

    public $timestamps = false; 

    public function permissions()
    {
        return $this->belongsToMany(PermissionModel::class, 'Users', 'Employee_Id_Employee', 'Premission_Id_Permission');
    }

    public function department()
    {
        return $this->belongsTo(DepartmentModel::class, 'Department_Id');
    }

    public function position()
    {
        return $this->belongsTo(PositionModel::class, 'Position_Id');
    }
}