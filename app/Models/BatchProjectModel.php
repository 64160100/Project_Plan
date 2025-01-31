<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BatchProjectModel extends Model
{
    protected $table = 'Project_Batch';
    protected $connection = 'mydb';
    protected $primaryKey = 'Id_Project_Batch';
    
    protected $fillable = [
        'Name_Batch'
    ];

    public $timestamps = false;

    public function projects() 
    {
        return $this->hasMany(ProjectBatchHasProjectModel::class, 'Project_Batch_Id', 'Id_Project_Batch');
    }

}