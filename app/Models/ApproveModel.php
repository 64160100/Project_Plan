<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApproveModel extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Approve';
    protected $primaryKey = 'Id_Approve';
    protected $fillable = [
        'Status',
        'Project_Id',
    ];

    public $timestamps = false; // Disable automatic timestamps

    public function project()
    {
        return $this->belongsTo(ListProjectModel::class, 'Project_Id', 'Id_Project');
    }
}