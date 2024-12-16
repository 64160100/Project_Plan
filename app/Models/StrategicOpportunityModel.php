<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrategicOpportunityModel extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Strategic_Opportunity';
    protected $primaryKey = 'Id_Strategic_Opportunity';
    protected $keyType = 'int';
    protected $fillable = [
        'Name_Strategic_Opportunity',
        'Strategic_Id_Strategic',
    ];
    public $timestamps = false;

    public function strategic()
    {
        return $this->belongsTo(StrategicModel::class, 'Strategic_Id_Strategic', 'Id_Strategic');
    }

    public function details()
    {
        return $this->hasMany(StrategicOpportunityDetailsModel::class, 'Strategic_Opportunity_Id', 'Id_Strategic_Opportunity');
    }

    public function challenges()
    {
        return $this->hasMany(StrategicChallengesModel::class, 'Strategic_Opportunity_Id', 'Id_Strategic_Opportunity');
    }

    public function advantages()
    {
        return $this->hasMany(StrategicAdvantagesModel::class, 'Strategic_Opportunity_Id', 'Id_Strategic_Opportunity');
    }
}