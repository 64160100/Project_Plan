<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeModel extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Employee';
    protected $primaryKey = 'Id_Employee';
    protected $fillable = [
        'Id_Employee',
        'Name_Employee',
        'Email',
        'Password',
    ];
    public $timestamps = false;

    public function permissions()
    {
        return $this->belongsToMany(PremissionModel::class, 'Users', 'Employee_Id_Employee', 'Premission_Id_Permission');
    }
}
