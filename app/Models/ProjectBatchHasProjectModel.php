<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectBatchHasProjectModel extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Project_Batch_has_Project';
    protected $primaryKey = 'Project_Id';

    protected $fillable = [
        'Project_Batch_Id',
        'Project_Id',
        'Count_Steps_Batch'
    ];

    public $timestamps = false;

    public function batch()
    {
        return $this->belongsTo(BatchProjectModel::class, 'Project_Batch_Id', 'Id_Project_Batch');
    }
    
    public function project()
    {
        return $this->belongsTo(ListProjectModel::class, 'Project_Id', 'Id_Project');
    }
    

}