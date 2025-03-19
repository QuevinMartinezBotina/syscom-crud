<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\HolidayService;
use App\Services\WorkdayCalculator;

class UsuarioController extends Controller
{
    private $holidayService;
    private $workdayCalculator;

    public function __construct(HolidayService $holidayService, WorkdayCalculator $workdayCalculator)
    {
        $this->holidayService = $holidayService;
        $this->workdayCalculator = $workdayCalculator;
    }

    public function index()
    {
        $usuarios = Usuario::whereNull('fecha_eliminacion')->with('rol')->get();

        // Obtener feriados desde el servicio
        $feriados = $this->holidayService->obtenerFeriados(2025);

        // Calcular días trabajados con el WorkdayCalculator
        $usuarios->map(function ($usuario) use ($feriados) {
            $usuario->dias_trabajados = $this->workdayCalculator->calcularDiasTrabajados(
                $usuario->fecha_ingreso,
                now(), // o Carbon::now()
                $feriados
            );
            return $usuario;
        });

        return response()->json($usuarios);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'             => 'required|string|max:255',
            'correo_electronico' => 'required|email|unique:usuarios,correo_electronico',
            'id_rol'             => 'required|exists:roles,id',
            'fecha_ingreso'      => 'required|date',
            'firma'              => 'nullable|string'
        ]);

        $usuario = Usuario::create($request->only(
            'nombre',
            'correo_electronico',
            'id_rol',
            'fecha_ingreso',
            'firma'
        ));

        // Generar contrato PDF
        $pdf = Pdf::loadView('usuarios.contrato', ['usuario' => $usuario]);
        $nombreArchivo = 'contratos/contrato_' . $usuario->id . '.pdf';
        Storage::put('public/' . $nombreArchivo, $pdf->output());

        // Guardar ruta del contrato
        $usuario->update(['contrato' => Storage::url($nombreArchivo)]);

        return response()->json([
            'message' => 'Usuario creado exitosamente',
            'usuario' => $usuario
        ], 201);
    }

    public function show($id)
    {
        $usuario = Usuario::with('rol')->findOrFail($id);

        // Calcular días trabajados
        $feriados = $this->holidayService->obtenerFeriados(2025);
        $usuario->dias_trabajados = $this->workdayCalculator->calcularDiasTrabajados(
            $usuario->fecha_ingreso,
            now(),
            $feriados
        );

        return response()->json($usuario);
    }

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

        return response()->json([
            'message' => 'Usuario actualizado',
            'usuario' => $usuario
        ]);
    }

    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->update(['fecha_eliminacion' => Carbon::now()]);

        return response()->json(['message' => 'Usuario eliminado']);
    }
}
