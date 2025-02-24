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
        'Strategy_Id',
        'Name_Strategy',
        'Name_Project',
        'Description_Project',
        'Employee_Id',
        'Objective_Project',
        'Principles_Reasons',
        'Success_Indicators',
        'Value_Target',
        'Project_Type',
        'Status_Budget',
        'Name_Creator',
        'Role_Creator',
        'First_Time',
        'End_Time',
        'Count_Steps',
    ];
    public $timestamps = false;

    public function strategic()
    {
        return $this->belongsTo(StrategicModel::class, 'Strategic_Id', 'Id_Strategic');
    }

    public function strategy()
    {
        return $this->belongsTo(StrategyModel::class, 'Strategy_Id');
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

    public function storageFiles()
    {
        return $this->hasMany(StorageFileModel::class, 'Project_Id', 'Id_Project');
    }

    public function sdgs()
    {
        return $this->belongsToMany(SDGsModel::class, 'Project_has_SDGs', 'Project_Id', 'SDGs_Id');
    }

    public function indicators()
    {
        return $this->hasMany(IndicatorsModel::class, 'Project_Id', 'Id_Project');
    }

    public function platforms()
    {
        return $this->hasMany(PlatformModel::class, 'Project_Id', 'Id_Project');
    }

    public function projectHasSDGs()
    {
        return $this->hasMany(ProjectHasSDGModel::class, 'Project_Id', 'Id_Project');
    }

    public function projectHasIntegrationCategories()
    {
        return $this->hasMany(ProjectHasIntegrationCategoryModel::class, 'Project_Id', 'Id_Project');
    }

    public function targets()
    {
        return $this->hasMany(TargetModel::class, 'Project_Id', 'Id_Project');
    }

    public function locations()
    {
        return $this->hasMany(LocationModel::class, 'Project_Id', 'Id_Project');
    }

    public function projectHasIndicators()
    {
        return $this->hasMany(ProjectHasIndicatorsModel::class, 'Project_Id', 'Id_Project');
    }

    public function pdca()
    {
        return $this->hasMany(PdcaDetailsModel::class, 'Project_Id', 'Id_Project');
    }
    
    public function projectBudgetSources()
    {
        return $this->hasMany(ProjectHasBudgetSourceModel::class, 'Project_Id', 'Id_Project');
    }

    public function quarterProjects()
    {
        return $this->hasMany(ProjectHasQuarterProjectModel::class, 'Project_Id');
    }

}