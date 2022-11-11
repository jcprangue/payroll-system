<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class casual_withoutpay extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'casual_employee_id',
        'month',
        'credit',
        'under',
        'ulwop',
        'quin',
        'travel',
        'is_modified'
    ];

    protected $with = [
        'employees'
    ];

    public function employees()
    {
        return $this->belongsTo(casual_employee::class, 'casual_employee_id', 'employee_id');
    }

    public function getMonthAttribute($value)
    {
        return date('F Y', strtotime($value));
    }
}
