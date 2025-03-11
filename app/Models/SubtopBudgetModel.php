<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubtopBudgetModel extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Subtopic_Budget';
    protected $primaryKey = 'Id_Subtopic_Budget'; 
    protected $fillable = [
        'Name_Subtopic_Budget',
    ];

    public $timestamps = false;


}