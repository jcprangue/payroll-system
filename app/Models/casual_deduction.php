<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class casual_deduction extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = "casual_deductions";
    protected $fillable = [
        'deduction_id',
        'amount',
        'date_start',
    ];
    protected $with = [
        'deductions'
    ];

    public function getDateStartAttribute($value){
        return date('F Y',strtotime($value));
    }
    public function deductions(){
        return $this->belongsTo(payroll_code::class,'deduction_id','id');
    }
}
