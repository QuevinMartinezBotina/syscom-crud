<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios Syscom Colombia</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- DataTables CSS (versión actualizada) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <!-- Para firma digital -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h1>Gestión de Usuarios</h1>
        <!-- Botón para redirigir a la sección de roles -->
        <a href="/roles" class="btn btn-info mb-3">Gestionar Roles</a>

        <!-- Botón para mostrar/ocultar el formulario de creación de usuario -->
        <button class="btn btn-primary mb-3" onclick="toggleFormularioUsuario()">Crear Nuevo Usuario</button>

        <!-- Formulario para crear usuario (oculto por defecto) -->
        <div id="formularioUsuario" style="display: none;">
            <form id="formUsuario" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="correo_electronico">Correo Electrónico</label>
                    <input type="email" id="correo_electronico" name="correo_electronico" class="form-control"
                        required>
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
                <!-- Campo de firma digital con canvas -->
                <div class="form-group">
                    <label for="signatureCanvas">Firma Digital</label>
                    <canvas id="signatureCanvas" width="400" height="200" style="border:1px solid #000;"></canvas>
                    <button type="button" class="btn btn-secondary" id="clearSignature">Borrar Firma</button>
                </div>

                <!-- Campo oculto para almacenar la firma en Base64 antes de enviar -->
                <input type="hidden" id="firma" name="firma">


                <button type="submit" class="btn btn-primary">Crear Usuario</button>
                <button type="button" class="btn btn-secondary" onclick="ocultarFormularioUsuario()">Cancelar</button>
            </form>
        </div>

        <hr>

        <!-- Tabla para mostrar los usuarios -->
        <table class="table" id="tablaUsuarios">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo Electrónico</th>
                    <th>Cargo</th>
                    <th>Fecha de Ingreso</th>
                    <th>Días Trabajados</th>
                    <th>Contrato</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Los datos se llenarán dinámicamente -->
            </tbody>
        </table>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- DataTables (versión actualizada) -->
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Archivo JS para usuarios -->
    <script src="{{ asset('js/usuarios.js') }}"></script>


</body>

</html>
