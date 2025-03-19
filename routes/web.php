<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('users.index');
});

Route::get('/usuarios/{id}/edit', function ($id) {
    return view('users.edit', ['id' => $id]);
});
