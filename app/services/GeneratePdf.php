<?php

namespace App\Services;

use Dompdf\Dompdf;
use Illuminate\Support\Facades\Storage;

class GeneratePdf
{
    /**
     * Genera un PDF a partir de una vista y sus datos.
     *
     * @param string $view La vista a renderizar.
     * @param array $data Datos a pasar a la vista.
     * @param int|string $usuarioId Identificador del usuario para crear un nombre único para el PDF.
     * @return string URL del PDF generado.
     */
    public function generarContrato(string $view, array $data, $usuarioId): string
    {
        // Renderizar la vista a HTML
        $html = view($view, $data)->render();

        // Instanciar Dompdf y configurar la generación del PDF
        $dompdf = new Dompdf();
        $dompdf->set_option('isHtml5ParserEnabled', true);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Obtener el contenido generado
        $pdfContent = $dompdf->output();

        // Definir el nombre y la ruta donde se almacenará el PDF
        $nombreArchivo = 'contratos/contrato_' . $usuarioId . '.pdf';
        Storage::disk('public')->put($nombreArchivo, $pdfContent);

        // Retornar la URL pública del PDF generado
        return Storage::url($nombreArchivo);
    }
}
