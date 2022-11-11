<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class casual_monitoring extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'control_number',
        'casual_payroll_group',
        'status',
        'department_id',
        'month',
        'quincena',
        'amount',
        'user_id',
        'remarks',

    ];

    protected $with = [
        'payrollGroups'
    ];

    public function payrollGroups()
    {
        return $this->belongsTo(casual_payroll_group::class, 'casual_payroll_group');
    }

    public function department()
    {
        return $this->belongsTo(department::class, 'department_id');
    }

    public function remarks()
    {
        return $this->hasMany(casual_monitoring_remarks::class,);
    }

    public function getCreatedAtAttribute($value)
    {
        return date("F d, Y h:i:s", strtotime($value));
    }
    public function getAmountAttribute($value)
    {
        return number_format($value, 2);
    }
}
