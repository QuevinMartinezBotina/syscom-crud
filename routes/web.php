<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('users.index');
});

Route::get('/usuarios/{id}/edit', function ($id) {
    return view('users.edit', ['id' => $id]);
});


// Ruta para listar y gestionar roles
Route::get('/roles', function () {
    return view('roles.index');
});

// Ruta para editar un rol
Route::get('/roles/{id}/edit', function ($id) {
    $role = App\Models\Role::findOrFail($id);
    return view('roles.edit', compact('role'));
});
