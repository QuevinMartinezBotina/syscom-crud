<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::with('rol')->get();
        $usuarios->map(function ($usuario) {
            $usuario->dias_trabajados = calcularDiasHabiles($usuario->fecha_ingreso);
            return $usuario;
        });

        return response()->json($usuarios);
    }

    public function store(Request $request)
    {
        // Validar la solicitud
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'correo_electronico' => 'required|email|unique:usuarios',
            'id_rol' => 'required|exists:roles,id',
            'fecha_ingreso' => 'required|date',
            // Valida también el archivo del contrato si viene
        ]);

        // Manejar la subida del contrato
        if ($request->hasFile('contrato')) {
            $rutaContrato = $request->file('contrato')->store('contratos');
            $validated['contrato'] = $rutaContrato;
        }

        // Guardar el usuario
        $usuario = Usuario::create($validated);

        return response()->json(['mensaje' => 'Usuario creado correctamente', 'usuario' => $usuario], 201);
    }
}
