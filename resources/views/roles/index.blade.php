<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Roles</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1>Gestión de Roles</h1>
        <button class="btn btn-primary mb-3" onclick="mostrarFormularioCreacion()">Crear Nuevo Rol</button>

        <!-- Formulario para crear rol (oculto por defecto) -->
        <div id="formularioCreacion" style="display:none;">
            <form id="formRole">
                <div class="form-group">
                    <label for="nombre_cargo">Nombre del Cargo</label>
                    <input type="text" id="nombre_cargo" name="nombre_cargo" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success">Crear Rol</button>
                <button type="button" class="btn btn-secondary" onclick="ocultarFormularioCreacion()">Cancelar</button>
            </form>
            <hr>
        </div>

        <!-- Tabla de Roles -->
        <table class="table" id="tablaRoles">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre del Cargo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Se llenará dinámicamente -->
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/roles.js') }}"></script>
</body>

</html>
