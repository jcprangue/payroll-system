<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class casual_deduction_exempt extends Model
{
    use HasFactory;

    protected $with = [
        'casualEmployee',
        'deduction'
    ];
    protected $fillable = [
        'casual_employee_id',
        'deduction_id',
        'month',
    ];
    public function casualEmployee(){
        return $this->hasOne(casual_employee::class,'employee_id','casual_employee_id');
    }
    public function getMonthAttribute($value){
        return date('F Y',strtotime($value));
    }
    public function deduction(){
        return $this->belongsTo(casual_deduction::class);
    }
}
