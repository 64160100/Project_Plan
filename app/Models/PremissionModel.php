<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PremissionModel extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Premission';
    protected $primaryKey = 'Id_Permission';
    protected $fillable = [
        'Id_Permission',
        'Name_Promission',
        'Dashborad',
        'List_Project',
        'Track_Status',
        'Documents_Project',
        'Report_results',
        'Check_Budget',
        'Approval_Project',
        'Manage_Users',
        'Data_Employee',
        'Setup_System',
    ];
    public $timestamps = false;

    public function employees()
    {
        return $this->belongsToMany(EmployeeModel::class, 'Users', 'Premission_Id_Permission', 'Employee_Id_Employee');
    }
}
