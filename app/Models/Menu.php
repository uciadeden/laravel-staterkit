<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['name', 'url', 'icon'];

public function roles()
{
    return $this->belongsToMany(Role::class, 'role_menu', 'menu_id', 'role_id');
}
}