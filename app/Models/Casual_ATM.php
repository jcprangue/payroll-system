<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Casual_ATM extends Model
{
    use HasFactory;
    protected $table = "casual_atm";
    protected $casts = ['isLock' => 'boolean'];

    protected $with = [
        'employees'
    ];

    protected $fillable = [
        'employee_id',
        'employee_atm',
        'isLock',
    ];

    public function employees()
    {
        return $this->belongsTo(casual_employee::class, 'employee_id', 'employee_id');
    }

    public function getEmployeeAtmAttribute($value)
    {
        return str_pad(substr($value, -4), strlen($value), '*', STR_PAD_LEFT);
    }
}
