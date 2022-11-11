<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class casual_charging extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'charging_name',
        'parent_id',
        'department_id',
        'code',
        'accounts',
        'kra_charging',
        'is_visible'
    ];
    protected $parentColumn = 'parent_id';
    protected $appends = ['name','amount'];

    public function getNameAttribute(){
        return $this->charging_name;
    }
    public function getAmountAttribute(){
        return (int) 0;
    }

    public function parent()
    {
        return $this->belongsTo(casual_charging::class,$this->parentColumn);
    }

    public function kraCharging()
    {
        return $this->hasOne(chart_of_accounts::class,'account_number',"kra_charging");
    }

    public function children()
    {
        return $this->hasMany(casual_charging::class, $this->parentColumn)->with('children');
    }

    public function getParentsAttribute()
    {
        $parents = collect([]);

        $parent = $this->parent;
        while (!is_null($parent)) {
            
            $parents->push($parent->charging_name);
            $parent = $parent->parent;
        }
       
        return $parents->toArray();
    }

    public function getParentsIdAttribute()
    {
        $parents = collect([]);

        $parent = $this->parent;
        while (!is_null($parent)) {
            $parents->push($parent->id);
            $parent = $parent->parent;
        }
        $parents->push($this->id);
        return $parents->toArray();
    }

}
