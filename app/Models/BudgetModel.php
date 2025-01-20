<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetModel extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Budget';
    protected $primaryKey = 'Id_Budget';
    protected $keyType = 'int';
    protected $fillable = [
        'Status_Budget',
        'Project_Id'
    ];
    public $timestamps = false;

    public function project()
    {
        return $this->belongsTo(ListProjectModel::class, 'Project_Id', 'Id_Project');
    }
}