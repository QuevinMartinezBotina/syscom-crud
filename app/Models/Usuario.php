<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Usuario extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'correo_electronico',
        'id_rol',
        'fecha_ingreso',
        'firma',
        'contrato',
        'fecha_eliminacion',
    ];

    public function rol()
    {
        return $this->belongsTo(Role::class, 'id_rol');
    }
}
