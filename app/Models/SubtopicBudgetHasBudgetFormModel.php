<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubtopicBudgetHasBudgetFormModel extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Subtopic_Budget_has_Budget_Form';
    protected $primaryKey = 'Id_Subtopic_Budget_has_Budget_Form';
    protected $fillable = [
        'Subtopic_Budget_Id',
        'Budget_Form_Id',
        'Details_Subtopic_Form',
        'Amount_Sub',
    ];

    public $timestamps = false;

    public function subtopicBudget()
    {
        return $this->belongsTo(SubtopBudgetModel::class, 'Subtopic_Budget_Id', 'Id_Subtopic_Budget');
    }

    public function budgetForm()
    {
        return $this->belongsTo(BudgetFormModel::class, 'Budget_Form_Id', 'Id_Budget_Form');
    }
}
