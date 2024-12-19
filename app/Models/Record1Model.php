<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Record1Model extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Record1';
    protected $primaryKey = 'Id_Record1';
    protected $fillable = [
        'Comment1',
        'Approve_Id_Approve',
    ];

    public $timestamps = false;
    
    public function approval()
    {
        return $this->belongsTo(ApproveModel::class, 'Approve_Id_Approve');
    }
}