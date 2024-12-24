<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Strategic extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Strategic'; 
    protected $primaryKey = 'Id_Strategic';
    protected $keyType = 'int';

    protected $fillable = [
        'Name_Strategic_Plan',
        'Goals_Strategic',
    ];
    public $timestamps = false;

    // Strategic มีหลายโปรเจค
    public function projects()
    {
        return $this->hasMany(Project::class, 'Strategic_Id', 'Id_Strategic');
    }

    //หนึ่ง Strategic มีหลาย Strategy
    public function strategies()
    {
        return $this->hasMany(Strategy::class, 'Strategic_Id', 'Id_Strategic');
    }

   
    
    
}
