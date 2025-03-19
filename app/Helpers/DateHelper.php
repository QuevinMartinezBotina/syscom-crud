<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

function calcularDiasHabiles($fechaIngreso)
{
    $inicio = Carbon::parse($fechaIngreso);
    $hoy = Carbon::today();
    $diasHabiles = 0;

    // Obtener feriados de la API
    $response = Http::get('https://api-colombia.com/api/v1/holiday/year/2025');
    $feriados = [];
    if ($response->successful()) {
        $feriados = collect($response->json()['data'])->pluck('date')->toArray();
    }

    while ($inicio->lte($hoy)) {
        // Verifica si el día no es sábado (6) ni domingo (0) y no está en la lista de feriados
        if (!$inicio->isWeekend() && !in_array($inicio->toDateString(), $feriados)) {
            $diasHabiles++;
        }
        $inicio->addDay();
    }
    return $diasHabiles;
}
