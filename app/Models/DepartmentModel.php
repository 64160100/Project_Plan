<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentModel extends Model
{
    protected $connection ='mydb';  // กำหนดการเชื่อต่อ��านข้อมูล
    protected $table = 'Department';
    protected $primaryKey = 'Id_Department';
    public $timestamps = false;

    protected $fillable = [
        'Name_Department',
    ];

    public function employees()
    {
        return $this->hasMany(EmployeeModel::class, 'Department_Id');
    }

    public function projects()
    {
        return $this->hasManyThrough(ListProjectModel::class, EmployeeModel::class, 'Department_Id', 'Employee_Id', 'Id_Department', 'Id_Employee');
    }

    
}