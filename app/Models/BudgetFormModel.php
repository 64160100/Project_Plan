<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetFormModel extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Budget_Form';
    protected $primaryKey = 'Id_Budget_Form';
    protected $fillable = [
        'Budget_Source_Id',
        'Project_Id',
        'Big_Topic',
        'Amount_Big',
    ];

    public $timestamps = false;

    public function project()
    {
        return $this->belongsTo(ListProjectModel::class, 'Project_Id', 'Id_Project');
    }

    public function budgetSource()
    {
        return $this->belongsTo(BudgetSourceModel::class, 'Budget_Source_Id', 'Id_Budget_Source');
    }

    public function subtopicBudgetHasBudgetForm()
    {
        return $this->hasMany(SubtopicBudgetHasBudgetFormModel::class, 'Budget_Form_Id', 'Id_Budget_Form');
    }

}
