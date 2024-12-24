<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Platform extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Platform';
    protected $primaryKey = 'Id_Platform';

    protected $fillable = [
        'Id_Platform',
        'Name_Platform',
        'Name_Object',
        'Project_Id_Project',
    ];
    public $timestamps = false;

    public function platformKpis()
    {
        return $this->hasMany(Platform_Kpi::class, 'Id_Platform');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'Project_Id_Project', 'Id_Project');
    }

}
