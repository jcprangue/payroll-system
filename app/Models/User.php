<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'nickname',
        'email',
        'password',
        'department'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
        'permissions',
        'is_admin',
    ];

    public function getPermissionsAttribute(){
        $roles = $this->with('roles.permissions')->first();
        $permission_user = [];
        foreach ($roles->roles as $role) {
            foreach ($role->permissions as $permission) {
                $permission_user[] = $permission->title;
            }
        }
        return $permission_user;
    }
    
    public function getIsAdminAttribute()
    {
        return $this->roles()->where('id', 1)->exists();
    }

    
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function assignRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('title',$role)->firstOrFail();
        }
        $this->roles()->sync($role, false);
    }

    public function permission()
    {
        return $this->roles->map->permissions->flatten()->pluck('title')->unique()->toArray();
    }

    public function hasRoles($role)
    {
        return $this->roles()->pluck('title')->contains($role);
    }

    public function hasPermission($permission)
    {
        return in_array($permission,$this->permission());
    }


    
}