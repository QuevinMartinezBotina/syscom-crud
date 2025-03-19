<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Contrato de Usuario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .contrato {
            margin: 20px;
        }
    </style>
</head>

<body>
    <div class="contrato">
        <h2>Contrato de Trabajo</h2>
        <p><strong>Nombre:</strong> {{ $usuario->nombre }}</p>
        <p><strong>Correo Electrónico:</strong> {{ $usuario->correo_electronico }}</p>
        <p><strong>Cargo:</strong> {{ $usuario->rol->nombre_cargo ?? '' }}</p>
        <p><strong>Fecha de Ingreso:</strong> {{ $usuario->fecha_ingreso }}</p>
        <hr>
        <p>Este contrato se firma en el momento de la creación del usuario y confirma el acuerdo entre Syscom Colombia y
            el empleado.</p>
        <p><strong>Firma:</strong></p>
        @if ($usuario->firma)
            <img src="{{ $usuario->firma }}" alt="Firma del usuario" style="max-width:300px;">
        @else
            <p>No se proporcionó firma.</p>
        @endif
    </div>
</body>

</html>
