<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Record2Model extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Record2';
    protected $primaryKey = 'Id_Record2';
    protected $fillable = [
        'Comment2',
        'Approve_Id_Approve',
    ];

    public $timestamps = false;

    public function approval()
    {
        return $this->belongsTo(ApproveModel::class, 'Approve_Id_Approve');
    }
}