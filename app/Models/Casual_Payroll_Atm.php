<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Casual_Payroll_Atm extends Model
{
    use HasFactory;
    protected $table = "casual_payroll_atms";
    protected $fillable = [
        'employee_id',
        'amount',
        'month',
        'batch',
    ];
    protected $with = [
        'casualEmployee',
    ];
    public function casualEmployee()
    {
        return $this->belongsTo(casual_employee::class, 'employee_id', 'employee_id');
    }

    public function getMonthAttribute($value)
    {
        return date('F Y', strtotime($value));
    }
}
