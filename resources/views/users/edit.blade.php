<!-- resources/views/usuarios/edit.blade.php -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1>Editar Usuario</h1>
        <!-- Agrega un campo oculto para guardar el ID -->
        <form id="formEditarUsuario" data-id="{{ $id }}" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="correo_electronico">Correo Electrónico</label>
                <input type="email" id="correo_electronico" name="correo_electronico" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="id_rol">Cargo</label>
                <select id="id_rol" name="id_rol" class="form-control" required>
                    <option value="">Seleccione un cargo</option>
                </select>
            </div>
            <div class="form-group">
                <label for="fecha_ingreso">Fecha de Ingreso</label>
                <input type="date" id="fecha_ingreso" name="fecha_ingreso" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="firma">Firma </label>
                <input type="text" id="firma" name="firma" class="form-control"
                    placeholder="Código de la firma">
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="{{ asset('js/usuarios.js') }}"></script>

</body>

</html>
