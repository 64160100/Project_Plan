<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Program extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Program';
    protected $primaryKey = 'Id_Program';

    protected $fillable = [
        'Id_Program',
        'Name_Program',
        'Name_Object',
        'Platform_Id_Platform',
    ];
    public $timestamps = false;
}