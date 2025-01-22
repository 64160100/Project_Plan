<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PdcaModel extends Model
{
    protected $connection = 'mydb';
    protected $table = 'PDCA_Stages';
    protected $primaryKey = 'Id_PDCA_Stages';
    protected $fillable = [
        'Name_PDCA',
    ];

    public $timestamps = false; 
}
