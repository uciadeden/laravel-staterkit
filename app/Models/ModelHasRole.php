<?php

// app/Models/ModelHasRole.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ModelHasRole extends Model
{
    protected $table = 'model_has_roles'; // Nama tabel yang sesuai
    use HasFactory;

}
