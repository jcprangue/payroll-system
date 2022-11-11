<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class casual_payroll_group extends Model
{
    use HasFactory,SoftDeletes;
    protected $with = [
        'department_',
        'employees',
        'departmentCharging'
    ];
    protected $fillable = [
        'department',
        'group_name',
        'status',
        'payroll_type',
        'with_hazard',
        'department_charging_id',
        'is_recompute'
        
    ];

    public function employees(){
        return $this->hasMany(casual_employee::class,'group_id','id');
    }

    public function department_(){
        return $this->belongsTo(department::class,'department','id');
    }

    public function departmentCharging(){
        return $this->hasOne(department::class,'id',"department_charging_id");
    }
}
