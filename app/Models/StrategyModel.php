<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\KpiModel;


class StrategyModel extends Model
{
    protected $connection = 'mydb';
    protected $table = 'Strategy';
    protected $primaryKey = 'Id_Strategy ';
    protected $keyType = 'int';
    protected $fillable = [
        'Id_Strategy ',
        'Strategic_Id',
        'Name_Strategy',
        'Strategy_Objectives',
    ];
    public $timestamps = false;

    public function kpis()
    {
        return $this->hasMany(KpiModel::class, 'Strategy_Id', 'Id_Strategy');
    }

    public function createWithKpis($strategyData, $kpiData)
    {
        DB::beginTransaction();
        try {
            // สร้าง Strategy
            $strategy = self::create($strategyData);
            
            // สร้าง KPI โดยการเชื่อมโยงกับ Strategy
            foreach ($kpiData as $kpi) {
                $strategy->kpis()->create($kpi);
            }

            DB::commit();
            return $strategy;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;  // หรือสามารถส่งข้อความผิดพลาดกลับมาได้
        }
    }

}
