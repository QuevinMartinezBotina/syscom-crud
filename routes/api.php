<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\RoleController;

Route::apiResource('usuarios', UsuarioController::class);
Route::apiResource('roles', RoleController::class);
