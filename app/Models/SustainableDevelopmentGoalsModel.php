<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SustainableDevelopmentGoalsModel extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Sustainable_Development_Goals';
    protected $primaryKey = 'id_SDGs';
    // protected $keyType = 'int';

    protected $fillable = [
        'Name_SDGs',
    ];
    public $timestamps = false;

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'Project_has_Sustainable_Development_Goals', 'SDGs_Id', 'Project_Id');
    }
}
