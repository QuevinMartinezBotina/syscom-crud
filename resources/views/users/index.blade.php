<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Syscom</title>
    <!-- Incluye Bootstrap (puedes usar CDN) -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- DataTables CSS (opcional) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Registro de Usuario</h2>
        <form id="formUsuario" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="correo_electronico">Correo Electrónico:</label>
                <input type="email" class="form-control" id="correo_electronico" name="correo_electronico" required>
            </div>
            <div class="form-group">
                <label for="id_rol">Cargo:</label>
                <select class="form-control" id="id_rol" name="id_rol" required>
                    <!-- Rellenar dinámicamente o estáticamente: 1 => Empleado, 2 => Jefe -->
                    <option value="1">Empleado</option>
                    <option value="2">Jefe</option>
                </select>
            </div>
            <div class="form-group">
                <label for="fecha_ingreso">Fecha de Ingreso:</label>
                <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" required>
            </div>
            <div class="form-group">
                <label for="contrato">Contrato (PDF):</label>
                <input type="file" class="form-control-file" id="contrato" name="contrato" accept="application/pdf">
            </div>
            <button type="submit" class="btn btn-primary">Registrar Usuario</button>
        </form>

        <hr>

        <h2>Listado de Usuarios</h2>
        <table id="tablaUsuarios" class="table table-bordered">
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
                <!-- Se rellenará dinámicamente con AJAX -->
            </tbody>
        </table>
    </div>

    <!-- Incluir jQuery y Bootstrap JS (puedes usar CDN) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- DataTables JS (opcional) -->
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
    <!-- Incluir Axios (si prefieres) o usar jQuery AJAX -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        $(document).ready(function() {
            // Función para cargar usuarios en la tabla
            function cargarUsuarios() {
                axios.get('/api/usuarios')
                .then(function(response) {
                    const usuarios = response.data;
                    let filas = '';
                    usuarios.forEach(function(usuario) {
                        // Aquí deberás calcular los días hábiles, si no lo envías desde el backend, puedes calcularlo en JS o mostrar el valor calculado previamente
                        filas += `<tr>
                                    <td>${usuario.id}</td>
                                    <td>${usuario.nombre}</td>
                                    <td>${usuario.correo_electronico}</td>
                                    <td>${usuario.rol ? usuario.rol.nombre_cargo : ''}</td>
                                    <td>${usuario.fecha_ingreso}</td>
                                    <td>${usuario.dias_trabajados || 'N/A'}</td>
                                    <td>
                                        ${usuario.contrato ? `<a href="/storage/${usuario.contrato}" target="_blank" class="btn btn-sm btn-info">Ver Contrato</a>` : 'No hay contrato'}
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-warning editar" data-id="${usuario.id}">Editar</button>
                                        <button class="btn btn-sm btn-danger eliminar" data-id="${usuario.id}">Eliminar</button>
                                    </td>
                                  </tr>`;
                    });
                    $('#tablaUsuarios tbody').html(filas);
                    // Inicializa DataTables si lo deseas
                    $('#tablaUsuarios').DataTable();
                })
                .catch(function(error) {
                    console.error('Error al cargar los usuarios', error);
                });
            }

            cargarUsuarios();

            // Manejo del formulario para crear usuario
            $('#formUsuario').submit(function(e) {
                e.preventDefault();

                // Crear objeto FormData para enviar datos y archivo
                const formData = new FormData(this);

                axios.post('/api/usuarios', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(function(response) {
                    alert(response.data.mensaje);
                    $('#formUsuario')[0].reset();
                    cargarUsuarios();
                })
                .catch(function(error) {
                    console.error('Error al registrar usuario', error);
                    alert('Ocurrió un error al registrar el usuario.');
                });
            });

            // Implementar las funciones de edición y eliminación (ejemplo para eliminar)
            $(document).on('click', '.eliminar', function() {
                const id = $(this).data('id');
                if (confirm('¿Estás seguro de eliminar este usuario?')) {
                    axios.delete(`/api/usuarios/${id}`)
                    .then(function(response) {
                        alert('Usuario eliminado');
                        cargarUsuarios();
                    })
                    .catch(function(error) {
                        console.error('Error al eliminar usuario', error);
                        alert('Error al eliminar el usuario.');
                    });
                }
            });
        });
    </script>
</body>
</html>
