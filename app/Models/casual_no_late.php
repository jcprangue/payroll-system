<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class casual_no_late extends Model
{
    use HasFactory;
    protected $table = "casual_no_late";
    protected $fillable = [
        'employee_id',
    ];
}
