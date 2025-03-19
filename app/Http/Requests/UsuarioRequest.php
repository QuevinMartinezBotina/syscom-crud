<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioRequest extends FormRequest
{
    public function rules()
    {
        return [
            'nombre' => 'required|string|max:255',
            'correo_electronico' => 'required|email|unique:usuarios,correo_electronico',
            'id_rol' => 'required|exists:roles,id',
            'fecha_ingreso' => 'required|date',
            'contrato' => 'nullable|mimes:pdf|max:2048'
        ];
    }
}
