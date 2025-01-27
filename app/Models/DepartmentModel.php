<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentModel extends Model
{   
    protected $connection = 'mydb';
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

}