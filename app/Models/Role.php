<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
class Role extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = ['title'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }


    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function allowTo($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::whereName($permission)->firstOrFail();
        }
        $this->permissions()->sync($permission);
    }
}
