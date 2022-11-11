<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class payroll_signatory extends Model
{
    use HasFactory, SoftDeletes;
    protected $with = [
        'department'
    ];
    protected $fillable = [
        'department_id',
        'department_head',
        'department_head_position',
        'signatory_role',
        'company',
        'status'
    ];


    public function department()
    {
        return $this->belongsTo(department::class, 'department_id');
    }
}
