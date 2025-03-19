<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contrato de Usuario</title>
</head>
<body>
    <h1>Contrato de Trabajo</h1>
    <p><strong>Nombre:</strong> {{ $usuario->nombre }}</p>
    <p><strong>Correo Electrónico:</strong> {{ $usuario->correo_electronico }}</p>
    <p><strong>Cargo (ID Rol):</strong> {{ $usuario->id_rol }}</p>
    <p><strong>Fecha de Ingreso:</strong> {{ $usuario->fecha_ingreso }}</p>
    @if($usuario->firma)
        <p><strong>Firma:</strong></p>
        <img src="{{ $usuario->firma }}" alt="Firma del usuario" style="max-width:300px;">
    @endif
</body>
</html>
