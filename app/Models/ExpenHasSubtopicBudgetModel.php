<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenHasSubtopicBudgetModel extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Expense_has_Subtopic_Budget';
    protected $primaryKey = 'Id_Expense_has_Subtopic_Budget';
    
    protected $fillable = [
        'Subtopic_Budget_Id',
        'Expense_Id',
        'Details_Expense_Budget',
        'Amount_Expense_Budget'
    ];
    
    public $timestamps = false;
    
    public function subtopicBudget()
    {
        return $this->belongsTo(SubtopBudgetModel::class, 'Subtopic_Budget_Id', 'Id_Subtop_Budget');
    }
    
    public function subtopicBudgets()
    {
        return $this->hasMany(SubtopBudgetModel::class, 'Id_Subtopic_Budget', 'Subtopic_Budget_Id');
    } 

    public function expense()
    {
        return $this->belongsTo(ExpenseModel::class, 'Expense_Id', 'Id_Expense');
    }
}