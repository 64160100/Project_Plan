<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrategicModel extends Model
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

    public function projects()
    {
        return $this->hasMany(ListProjectModel::class, 'Strategic_Id', 'Id_Strategic');
    }
}