<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class HolidayService
{
    /**
     * Obtiene los feriados desde la API de Colombia para un año específico.
     *
     * @param int $year
     * @return array
     */
    public function obtenerFeriados(int $year = 2025): array
    {
        try {
            $response = Http::get("https://api-colombia.com/api/v1/holiday/year/{$year}");
            if ($response->successful()) {
                // Ajustar según la estructura real que devuelva la API
                $data = $response->json()['data'] ?? [];
                // Extraer las fechas en formato 'Y-m-d'
                $feriados = array_map(function ($feriado) {
                    return Carbon::parse($feriado['date'])->format('Y-m-d');
                }, $data);
                return $feriados;
            }
        } catch (\Exception $e) {
            // Si algo falla, retorna un arreglo vacío
            return [];
        }

        // Si algo falla, retorna un arreglo vacío
        return [];
    }
}
