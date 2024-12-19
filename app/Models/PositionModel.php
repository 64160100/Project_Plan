<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PositionModel extends Model
{
    protected $table = 'Position';
    protected $primaryKey = 'Id_Position';
    public $timestamps = false;

    protected $fillable = [
        'Name_Position',
    ];

    public function employees()
    {
        return $this->hasMany(EmployeeModel::class, 'Position_Id');
    }
}