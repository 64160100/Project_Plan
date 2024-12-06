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

    protected $fillable = [
        'Id_Project',
        'Strategic_Id ',
        'Name_Project',
    ];
    public $timestamps = false;

    // ความสัมพันธ์ที่ Project belongs to Strategic
    public function strategic()
    {
        return $this->belongsTo(Strategic::class, 'Strategic_Id');
    }
    
}
