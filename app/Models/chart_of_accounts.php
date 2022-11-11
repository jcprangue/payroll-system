<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class chart_of_accounts extends Model
{
    use HasFactory;
    protected $appends = ['name'];

    public function getNameAttribute()
    {
        return $this->account_number . ' | ' . $this->account_title;
    }
}
