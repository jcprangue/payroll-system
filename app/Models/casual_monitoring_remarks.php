<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class casual_monitoring_remarks extends Model
{
    use HasFactory;
    protected $fillable = [
        'casual_monitoring_id',
        'status',
        'user_id',
        'remarks',
        'method',
    ];
    protected $with = [
        'casualMonitoring','user'
    ];
    public function casualMonitoring(){
        return $this->belongsTo(casual_monitoring::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function getCreatedAtAttribute($value){
        return date("F d, Y h:i:s",strtotime($value));
    }
}
