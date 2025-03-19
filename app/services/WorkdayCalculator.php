<?php

namespace App\Services;

use Carbon\Carbon;

class WorkdayCalculator
{
    /**
     * Calcula los días hábiles (excluyendo fines de semana y feriados).
     *
     * @param string|Carbon $fechaInicio
     * @param string|Carbon $fechaFin
     * @param array $feriados
     * @return int
     */
    public function calcularDiasTrabajados($fechaInicio, $fechaFin, array $feriados): int
    {
        $inicio = Carbon::parse($fechaInicio);
        $fin = Carbon::parse($fechaFin);
        $dias = 0;

        // Itera desde la fecha de ingreso hasta la fecha final
        for ($date = $inicio->copy(); $date->lte($fin); $date->addDay()) {
            // Excluir sábados y domingos
            if ($date->isWeekend()) {
                continue;
            }
            // Excluir feriados
            if (in_array($date->format('Y-m-d'), $feriados)) {
                continue;
            }
            $dias++;
        }

        return $dias;
    }
}
