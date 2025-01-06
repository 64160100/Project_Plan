<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupProjectModel extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Sup_Project';
    protected $primaryKey = 'Id_Sup_Project';
    // protected $keyType = 'int';

    protected $fillable = [
        'Name_Sup_Project',
        'Project_Id_Project',
    ];
    public $timestamps = false;

    public function project()
    {
        return $this->belongsTo(Project::class, 'Project_Id_Project', 'Id_Project');
    }
}
