<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    public function rol()
    {
        return $this->belongsTo(Role::class, 'id_rol');
    }
}
