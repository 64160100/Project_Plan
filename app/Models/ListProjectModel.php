<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListProjectModel extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Project';
    protected $primaryKey = 'Id_Project';
    protected $keyType = 'int';
    protected $fillable = [
        'Strategic_Id',
        'Name_Project',
        'Count_Steps',
        'Employee_Id'
    ];
    public $timestamps = false;

    public function strategic()
    {
        return $this->belongsTo(StrategicModel::class, 'Strategic_Id', 'Id_Strategic');
    }

    public function employee()
    {
        return $this->belongsTo(EmployeeModel::class, 'Employee_Id', 'Id_Employee');
    }
    
    public function approvals()
    {
        return $this->hasMany(ApproveModel::class, 'Project_Id', 'Id_Project');
    }

    public function supProjects()
    {
        return $this->hasMany(SupProjectModel::class, 'Project_Id', 'Id_Project');
    }

    public function budgets()
    {
        return $this->hasMany(BudgetModel::class, 'Project_Id', 'Id_Project');
    }

    public function storageFiles()
    {
        return $this->hasMany(StorageFileModel::class, 'Project_Id', 'Id_Project');
    }
}