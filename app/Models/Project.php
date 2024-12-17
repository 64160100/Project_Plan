<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Project extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Project';
    protected $primaryKey = 'Id_Project';
    // protected $keyType = 'int';

    protected $fillable = [
        'Strategic_Id',
        'Name_Project',
    ];
    public $timestamps = false;

    // ความสัมพันธ์ที่ Project belongs to Strategic
    public function strategic()
    {
        return $this->belongsTo(Strategic::class, 'Strategic_Id', 'Id_Strategic');
    }

    public function sdgs()
    {
        return $this->belongsToMany(Sdg::class, 'Project_has_Sustainable_Development_Goals', 'Project_Id', 'SDGs_Id');
    }


    
}
