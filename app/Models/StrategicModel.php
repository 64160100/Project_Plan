<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrategicModel extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Strategic';
    protected $primaryKey = 'Id_Strategic';
    protected $fillable = [
        'Id_Strategic',
        'Name_Strategic_Plan',
        'Goals_Strategic',
    ];
    public $timestamps = false;
}
