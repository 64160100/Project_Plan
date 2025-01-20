<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntegrationModel extends Model
{   
    protected $connection = 'mydb';
    protected $table = 'Integration_Category';
    protected $primaryKey = 'Id_Integration_Category';
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'Name_Integration_Category'
    ];
}