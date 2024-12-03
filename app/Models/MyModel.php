<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MyModel extends Model
{
    protected $connection = 'mydb'; // Ensure this matches your database connection name in .env
    protected $table = 'Employee';
    protected $primaryKey = 'Id_Employee';
    protected $fillable = [
        'Id_Employee',
        'Name_Employee',
        'Email',
        'Password',
    ];
    public $timestamps = false;
}