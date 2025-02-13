<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectHasQuarterProjectModel extends Model
{   

    protected $connection = 'mydb';
    protected $table = 'Project_has_Quarter_Project';
    protected $fillable = [
        'Project_Id',
        'Quarter_Project_Id',
    ];

    public $timestamps = false;

    public function project()
    {
        return $this->belongsTo(Project::class, 'Project_Id');
    }

    public function quarterProject()
    {
        return $this->belongsTo(FiscalYearQuarterModel::class, 'Quarter_Project_Id');
    }
}
