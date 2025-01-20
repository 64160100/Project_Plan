<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupProjectModel extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Sup_Project';
    protected $primaryKey = 'Id_Sup_Project';
    protected $keyType = 'int';
    protected $fillable = [
        'Project_Id',
        'Name_Sup_Project'
    ];
    public $timestamps = false;

    public function project()
    {
        return $this->belongsTo(ListProjectModel::class, 'Project_Id', 'Id_Project');
    }
}