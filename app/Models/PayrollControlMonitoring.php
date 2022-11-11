<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PayrollControlMonitoring extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = "payroll_control_monitoring";
    protected $guarded = [];
    protected $appends = ['name'];

    public function getNameAttribute(){
        $name = $this->payroll_group->group_name ?? null;
        return $this->control_number  . ' - ' . $name;
    }
    public function payroll_group()
    {
        return $this->belongsTo(casual_payroll_group::class);
    }
}
