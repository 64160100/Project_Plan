<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectHasSDGModel extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Project_has_SDGs';
    protected $primaryKey = 'Project_Id';
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'Project_Id',
        'SDGs_Id'
    ];

    public function project()
    {
        return $this->belongsTo(ListProjectModel::class, 'Project_Id', 'Id_Project');
    }

    public function sdg()
    {
        return $this->belongsTo(SDGModel::class, 'SDG_Id', 'SDGs_Id');
    }
}
