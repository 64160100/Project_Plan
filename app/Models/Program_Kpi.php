<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Program_Kpi extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Program_Kpi';
    protected $primaryKey = 'Id_Program_Kpi';

    protected $fillable = [
        'Id_Program_Kpi',
        'Program_Id',
        'Name_Program_Kpi',
        'Description_Program_Kpi',
    ];
    public $timestamps = false;
}