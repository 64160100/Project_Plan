<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sdg extends Model
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
        return $this->belongsToMany(Project::class, 'project_sdg', 'SDGs_Id', 'Project_Id');
    }

}
