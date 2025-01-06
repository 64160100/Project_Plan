<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectModel extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Project';
    protected $primaryKey = 'Id_Project';
    protected $keyType = 'int';

    protected $fillable = [
        'Strategic_Id',
        'Name_Project',
        'Name_Strategy',
        'Objective_Project',
        'Indicators_Project',
        'Target_Project',
        'First_Time',
        'End_Time',
        'Count_Steps',
        'Employee_Id',
    ];

    public $timestamps = false;

    public function strategic()
    {
        return $this->belongsTo(Strategic::class, 'Strategic_Id', 'Id_Strategic');
    }

    public function sdgs()
    {
        return $this->belongsToMany(Sdg::class, 'Project_has_Sustainable_Development_Goals', 'Project_Id', 'SDGs_Id');
    }

    public function supProjects()
    {
        return $this->hasMany(Sup_Project::class, 'Project_Id_Project', 'Id_Project');
    }

    public function employee()
    {
        return $this->belongsTo(EmployeeModel::class, 'Employee_Id', 'Id_Employee');
    }
}