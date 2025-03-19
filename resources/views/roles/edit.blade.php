<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Rol</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1>Editar Rol</h1>
        <form id="formEditarRol" data-id="{{ $role->id }}" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nombre_cargo">Nombre del Cargo</label>
                <input type="text" id="nombre_cargo" name="nombre_cargo" class="form-control"
                    value="{{ $role->nombre_cargo }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Rol</button>
            <a href="/roles" class="btn btn-info ">Gestionar Roles</a>

        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="{{ asset('js/roles.js') }}"></script>
</body>

</html>
