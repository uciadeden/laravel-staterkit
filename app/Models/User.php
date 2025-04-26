<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles; // Import trait HasRoles

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    // public function role()
    // {
    //     return $this->belongsTo(Role::class);
    // }
    //   public function menus()
    // {
    //     return $this->belongsToMany(Menu::class, 'role_menu', 'role_id', 'menu_id')->distinct();
    // }

    // app/Models/User.php
public function roles()
{
    return $this->belongsToMany(Role::class, 'model_has_roles', 'model_id', 'role_id');
}

public function menus()
{
    return $this->belongsToMany(Menu::class, 'role_menu', 'role_id', 'menu_id');
}

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
