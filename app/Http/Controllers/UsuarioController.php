<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class UsuarioController extends Controller
{
    /**
     * Muestra la lista de usuarios (excluyendo eliminados).
     */
    public function index()
    {
        // Obtener todos los usuarios que no han sido eliminados (fecha_eliminacion es null)
        $usuarios = Usuario::whereNull('fecha_eliminacion')->with('rol')->get();

        // Obtener la lista de feriados desde la API (se asume que retorna un arreglo de fechas en formato 'YYYY-MM-DD')
        $feriados = $this->obtenerFeriados();

        // Agregar campo calculado de días trabajados para cada usuario
        $usuarios->map(function ($usuario) use ($feriados) {
            $usuario->dias_trabajados = $this->calcularDiasTrabajados($usuario->fecha_ingreso, Carbon::now(), $feriados);
            return $usuario;
        });

        return response()->json($usuarios);
    }

    /**
     * Almacena un nuevo usuario, genera contrato PDF y guarda la ruta.
     */
    public function store(Request $request)
    {
        // Validación de datos
        $request->validate([
            'nombre'             => 'required|string|max:255',
            'correo_electronico' => 'required|email|unique:usuarios,correo_electronico',
            'id_rol'             => 'required|exists:roles,id',
            'fecha_ingreso'      => 'required|date',
            // Se espera que la firma se envíe como base64 opcional
            'firma'              => 'nullable|string'
        ]);

        // Crear el usuario
        $usuario = Usuario::create($request->only('nombre', 'correo_electronico', 'id_rol', 'fecha_ingreso', 'firma'));

        // Generar el contrato en PDF utilizando una vista (resources/views/usuarios/contrato.blade.php)
        $pdf = Pdf::loadView('usuarios.contrato', ['usuario' => $usuario]);
        $nombreArchivo = 'contratos/contrato_' . $usuario->id . '.pdf';
        Storage::put('public/' . $nombreArchivo, $pdf->output());

        // Actualizar el usuario con la ruta del contrato
        $usuario->update(['contrato' => Storage::url($nombreArchivo)]);

        return response()->json(['message' => 'Usuario creado exitosamente', 'usuario' => $usuario], 201);
    }

    /**
     * Muestra la información de un usuario específico.
     */
    public function show($id)
    {
        $usuario = Usuario::with('rol')->findOrFail($id);
        // Calcular los días trabajados
        $feriados = $this->obtenerFeriados();
        $usuario->dias_trabajados = $this->calcularDiasTrabajados($usuario->fecha_ingreso, Carbon::now(), $feriados);
        return response()->json($usuario);
    }

    /**
     * Actualiza la información de un usuario.
     */
    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'nombre'             => 'sometimes|required|string|max:255',
            'correo_electronico' => 'sometimes|required|email|unique:usuarios,correo_electronico,' . $id,
            'id_rol'             => 'sometimes|required|exists:roles,id',
            'fecha_ingreso'      => 'sometimes|required|date',
            'firma'              => 'nullable|string'
        ]);

        $usuario->update($request->all());

        return response()->json(['message' => 'Usuario actualizado', 'usuario' => $usuario]);
    }

    /**
     * "Elimina" un usuario marcándolo con fecha de eliminación (soft delete).
     */
    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->update(['fecha_eliminacion' => Carbon::now()]);
        return response()->json(['message' => 'Usuario eliminado']);
    }

    /**
     * Función privada para calcular los días hábiles (excluyendo fines de semana y feriados)
     *
     * @param string|Carbon $fechaInicio
     * @param Carbon $fechaFin
     * @param array $feriados Arreglo de fechas feriadas en formato 'Y-m-d'
     * @return int
     */
    private function calcularDiasTrabajados($fechaInicio, Carbon $fechaFin, array $feriados)
    {
        $inicio = Carbon::parse($fechaInicio);
        $dias = 0;

        // Itera desde la fecha de ingreso hasta la fecha actual
        for ($date = $inicio->copy(); $date->lte($fechaFin); $date->addDay()) {
            // Si es sábado o domingo, se salta
            if ($date->isWeekend()) {
                continue;
            }
            // Si la fecha está en el arreglo de feriados, se salta
            if (in_array($date->format('Y-m-d'), $feriados)) {
                continue;
            }
            $dias++;
        }
        return $dias;
    }

    /**
     * Función para obtener los feriados desde la API.
     * Se asume que la API retorna un JSON con un arreglo de fechas.
     *
     * @return array
     */
    private function obtenerFeriados()
    {
        // Realizar la petición a la API de feriados para el año 2025
        try {
            $response = Http::get('https://api-colombia.com/api/v1/holiday/year/2025');
            if ($response->successful()) {
                // Se asume que el JSON tiene una clave 'data' con las fechas (ajusta según la respuesta real)
                $data = $response->json()['data'] ?? [];
                // Extraer las fechas en formato 'Y-m-d'
                $feriados = array_map(function ($feriado) {
                    return Carbon::parse($feriado['date'])->format('Y-m-d');
                }, $data);
                return $feriados;
            }
        } catch (\Exception $e) {
            // En caso de error, se retorna un arreglo vacío para no interrumpir el flujo
        }
        return [];
    }
}
