<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class payroll_casual_records extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'casual_payroll_group_id',
        'month',
        'data',
    ];
}
