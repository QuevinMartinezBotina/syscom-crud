<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\HolidayService;
use App\Services\WorkdayCalculator;
use App\Services\GeneratePdf;


class UsuarioController extends Controller
{
    private $holidayService;
    private $workdayCalculator;
    private $generatePdf;

    /**
     * UsuarioController constructor.
     *
     * @param HolidayService $holidayService Servicio para manejar días festivos.
     * @param WorkdayCalculator $workdayCalculator Calculadora para determinar días laborales.
     * @param GeneratePdf $generatePdf Servicio para generar PDFs.
     */
    public function __construct(HolidayService $holidayService, WorkdayCalculator $workdayCalculator, GeneratePdf $generatePdf)
    {
        $this->holidayService = $holidayService;
        $this->workdayCalculator = $workdayCalculator;
        $this->generatePdf = $generatePdf;
    }

    private function asignarDiasTrabajados($usuario)
    {
        $feriados = $this->holidayService->obtenerFeriados(2025);
        $usuario->dias_trabajados = $this->workdayCalculator->calcularDiasTrabajados(
            $usuario->fecha_ingreso,
            now(),
            $feriados
        );
        return $usuario;
    }

    /**
     * Muestra una lista de usuarios activos con sus roles y calcula los días trabajados.
     *
     * Este método obtiene todos los usuarios que no tienen una fecha de eliminación,
     * incluye la relación con el rol de cada usuario y calcula los días trabajados
     * desde la fecha de ingreso hasta la fecha actual, excluyendo los feriados.
     *
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con la lista de usuarios y sus días trabajados.
     */

    public function index()
    {
        $usuarios = Usuario::whereNull('fecha_eliminacion')->with('rol')->get();

        $usuarios->transform(function ($usuario) {
            return $this->asignarDiasTrabajados($usuario);
        });

        return response()->json($usuarios);
    }

    /**
     * Almacena un nuevo usuario en la base de datos.
     *
     * Este método valida los datos de entrada, crea un nuevo usuario en la base de datos,
     * genera un contrato en formato PDF utilizando Dompdf, guarda el PDF en el almacenamiento
     * público y actualiza el usuario con la ruta del contrato.
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP que contiene los datos del usuario.
     * @return \Illuminate\Http\JsonResponse La respuesta JSON que indica el resultado de la operación.
     */
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

            // Generar el contrato PDF
            $pdfUrl = $this->generatePdf->generarContrato('users.contrato', compact('usuario'), $usuario->id);

            // Actualizar el usuario con la ruta del contrato generado
            $usuario->update(['contrato' => $pdfUrl]);

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


    /**
     * Muestra la información de un usuario específico junto con su rol y calcula los días trabajados.
     *
     * @param int $id El ID del usuario a mostrar.
     * @return \Illuminate\Http\JsonResponse La respuesta JSON con la información del usuario.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si no se encuentra el usuario con el ID proporcionado.
     */
    public function show($id)
    {
        $usuario = Usuario::with('rol')->findOrFail($id);

        $this->asignarDiasTrabajados($usuario);

        return response()->json($usuario);
    }

    /**
     * Actualiza un usuario existente en la base de datos.
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP que contiene los datos del usuario.
     * @param int $id El ID del usuario a actualizar.
     * @return \Illuminate\Http\JsonResponse Una respuesta JSON que indica el éxito de la operación y los datos del usuario actualizado.
     *
     * @throws \Illuminate\Validation\ValidationException Si la validación de los datos falla.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si no se encuentra el usuario con el ID proporcionado.
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

        // Actualizar los datos del usuario
        $usuario->update($request->all());

        // Generar el contrato PDF
        $pdfUrl = $this->generatePdf->generarContrato('users.contrato', compact('usuario'), $usuario->id);

        // Actualizar el usuario con la ruta del contrato generado
        $usuario->update(['contrato' => $pdfUrl]);

        return response()->json([
            'message' => 'Usuario actualizado y contrato actualizado',
            'usuario' => $usuario
        ]);
    }



    /**
     * Elimina un usuario actualizando su fecha de eliminación.
     *
     * @param  int  $id  El ID del usuario a eliminar.
     * @return \Illuminate\Http\JsonResponse  Respuesta JSON con un mensaje de confirmación.
     */
    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->update(['fecha_eliminacion' => Carbon::now()]);

        return response()->json(['message' => 'Usuario eliminado']);
    }
}
