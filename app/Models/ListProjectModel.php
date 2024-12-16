<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListProjectModel extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Project';
    protected $primaryKey = 'Id_Project';
    protected $keyType = 'int';
    protected $fillable = [
        'Strategic_Id',
        'Name_Project',
    ];
    public $timestamps = false;

    public function strategic()
    {
        return $this->belongsTo(StrategicModel::class, 'Strategic_Id', 'Id_Strategic');
    }
}