<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class casual_employee extends Model
{
    use HasFactory;

    protected $appends = ['name'];
    protected $with = ['charging'];
    protected $guarded = [];
    public function getNameAttribute(){
        return $this->last_name . " " . $this->first_name . " ";
    }

    public function charging(){
        return $this->belongsTo(casual_charging::class,'charging_id');
    }

    public function position(){
        return $this->belongsTo(casual_position::class,'position_id');
    }

}
