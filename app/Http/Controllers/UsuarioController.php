<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Dompdf\Dompdf;
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
        try {
            // Validación de datos
            $validatedData = $request->validate([
                'nombre'             => 'required|string|max:255',
                'correo_electronico' => 'required|email|unique:usuarios,correo_electronico',
                'id_rol'             => 'required|exists:roles,id',
                'fecha_ingreso'      => 'required|date',
                'firma'              => 'nullable|string'
            ]);

            // Crear el usuario
            $usuario = Usuario::create($validatedData);

            // Renderizar la vista Blade a HTML para el contrato
            $html = view('users.contrato', compact('usuario'))->render();

            // Instanciar Dompdf
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            // Opcional: configurar tamaño de papel y orientación
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Obtener el contenido PDF generado
            $pdfContent = $dompdf->output();

            // Definir el nombre y ruta para guardar el PDF
            $nombreArchivo = 'contratos/contrato_' . $usuario->id . '.pdf';
            Storage::disk('public')->put($nombreArchivo, $pdfContent);

            // Actualizar el usuario con la ruta del contrato
            $usuario->update(['contrato' => Storage::url($nombreArchivo)]);

            return response()->json([
                'message' => 'Usuario creado exitosamente',
                'usuario' => $usuario
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear usuario',
                'error' => $e->getMessage()
            ], 500);
        }
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
