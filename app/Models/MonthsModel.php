<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthsModel extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Months';
    protected $primaryKey = 'Id_Months';
    protected $fillable = [
        'Name_Month',
    ];

    public $timestamps = false;

}
