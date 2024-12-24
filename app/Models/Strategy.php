<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Strategy extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Strategy'; 
    protected $primaryKey = 'Id_Strategy';
    protected $keyType = 'int';

    protected $fillable = [
        'Strategic_Id',
        'Name_Strategy',
    ];
    public $timestamps = false;

    // Strategic มีหลายโปรเจค
    // public function projects()
    // {
    //     return $this->hasMany(Project::class, 'Strategic_Id', 'Id_Strategy');
    // }

     // แต่ละ Strategy เชื่อมกับหนึ่ง Strategic
     public function strategic()
     {
         return $this->belongsTo(Strategic::class, 'Strategic_Id', 'Id_Strategic');
     }
    
    
}
