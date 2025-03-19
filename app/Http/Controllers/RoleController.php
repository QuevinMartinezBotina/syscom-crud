<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    /**
     * Muestra todos los roles.
     */
    public function index()
    {
        $roles = Role::all();
        return response()->json($roles);
    }

    /**
     * Almacena un nuevo rol en la base de datos.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre_cargo' => 'required|string|max:255|unique:roles,nombre_cargo',
        ]);

        $role = Role::create($validatedData);

        return response()->json([
            'message' => 'Rol creado exitosamente',
            'role' => $role,
        ], 201);
    }

    /**
     * Muestra la información de un rol específico.
     */
    public function show($id)
    {
        $role = Role::findOrFail($id);
        return response()->json($role);
    }

    /**
     * Actualiza un rol existente.
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validatedData = $request->validate([
            'nombre_cargo' => 'required|string|max:255|unique:roles,nombre_cargo,' . $id,
        ]);

        $role->update($validatedData);

        return response()->json([
            'message' => 'Rol actualizado exitosamente',
            'role' => $role,
        ]);
    }

    /**
     * Elimina un rol.
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json([
            'message' => 'Rol eliminado exitosamente',
        ]);
    }
}
