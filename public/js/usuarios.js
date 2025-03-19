// public/js/usuarios.js

// Inicializo DataTable y obtengo los usuarios al cargar el documento
$(document).ready(function () {
    $("#tablaUsuarios").DataTable();
    obtenerUsuarios();
});

// Manejo el envío del formulario para crear un usuario
$("#formUsuario").submit(function (e) {
    e.preventDefault();

    const formData = {
        nombre: $("#nombre").val(),
        correo_electronico: $("#correo_electronico").val(),
        id_rol: $("#id_rol").val(),
        fecha_ingreso: $("#fecha_ingreso").val(),
        firma: $("#firma").val(),
    };

    axios
        .post("/api/usuarios", formData)
        .then((response) => {
            // Si el código de estado es 201, mostramos el mensaje de éxito.
            if (response.status === 201) {
                alert(response.data.message);
                $("#formUsuario")[0].reset();
                // Puedes recargar la tabla o incluso la página si lo prefieres:
                obtenerUsuarios();
                // Para recargar la página completa: window.location.reload();
            } else {
                // En caso de respuesta inesperada:
                alert("Operación completada, pero con respuesta inesperada.");
            }
        })
        .catch((error) => {
            let mensaje = "Error al crear usuario";
            // Si hay detalles de error en la respuesta del servidor, los mostramos.
            if (error.response && error.response.data) {
                if (error.response.data.message) {
                    mensaje = error.response.data.message;
                }
                if (error.response.data.errors) {
                    Object.keys(error.response.data.errors).forEach((campo) => {
                        mensaje +=
                            "\n" +
                            campo +
                            ": " +
                            error.response.data.errors[campo].join(", ");
                    });
                }
            }
            console.error("Error al crear usuario:", error);
            alert(mensaje);
        });
});

// Obtengo los usuarios y lleno la tabla
function obtenerUsuarios() {
    axios
        .get("/api/usuarios")
        .then((response) => {
            let tbody = "";
            response.data.forEach((usuario) => {
                tbody += `<tr>
                    <td>${usuario.id}</td>
                    <td>${usuario.nombre}</td>
                    <td>${usuario.correo_electronico}</td>
                    <td>${usuario.rol ? usuario.rol.nombre_cargo : ""}</td>
                    <td>${usuario.fecha_ingreso}</td>
                    <td>${usuario.dias_trabajados}</td>
                    <td>
                        <button class="btn btn-info" onclick="verContrato('${
                            usuario.contrato
                        }')">Ver Contrato</button>
                    </td>
                    <td>
                        <button class="btn btn-warning" onclick="editarUsuario(${
                            usuario.id
                        })">Editar</button>
                        <button class="btn btn-danger" onclick="eliminarUsuario(${
                            usuario.id
                        })">Eliminar</button>
                    </td>
                </tr>`;
            });
            $("#tablaUsuarios tbody").html(tbody);
        })
        .catch((error) => {
            console.error("Error al obtener usuarios:", error);
        });
}

// Abro el contrato en una nueva pestaña
function verContrato(rutaContrato) {
    if (rutaContrato) {
        window.open(rutaContrato, "_blank");
    } else {
        alert("No existe contrato registrado");
    }
}

// Elimino un usuario
function eliminarUsuario(id) {
    if (confirm("¿Estás seguro de eliminar este usuario?")) {
        axios
            .delete("/api/usuarios/" + id)
            .then((response) => {
                alert(response.data.message);
                obtenerUsuarios();
            })
            .catch((error) => {
                console.error("Error al eliminar usuario:", error);
            });
    }
}

// Función para editar usuario (pendiente de implementación)
function editarUsuario(id) {
    window.location.href = `/usuarios/${id}/edit`;
}

// Función para cargar los datos del usuario desde la API
function cargarDatosUsuario(id) {
    axios
        .get(`/api/usuarios/${id}`)
        .then((response) => {
            const usuario = response.data;
            $("#nombre").val(usuario.nombre);
            $("#correo_electronico").val(usuario.correo_electronico);
            $("#id_rol").val(usuario.id_rol);
            $("#fecha_ingreso").val(usuario.fecha_ingreso);
            $("#firma").val(usuario.firma);
        })
        .catch((error) => {
            console.error("Error al obtener datos del usuario:", error);
        });
}

// Al cargar la página, obtenemos el ID del formulario y llamamos a la función para cargar datos
$(document).ready(function () {
    const id = $("#formEditarUsuario").data("id");
    cargarDatosUsuario(id);
});

// Manejo del envío del formulario para actualizar el usuario
$("#formEditarUsuario").submit(function (e) {
    e.preventDefault();

    const id = $("#formEditarUsuario").data("id");
    const formData = {
        nombre: $("#nombre").val(),
        correo_electronico: $("#correo_electronico").val(),
        id_rol: $("#id_rol").val(),
        fecha_ingreso: $("#fecha_ingreso").val(),
        firma: $("#firma").val(),
    };

    axios
        .put(`/api/usuarios/${id}`, formData)
        .then((response) => {
            alert(response.data.message);
            // Redirigir o actualizar la vista según lo necesites
            window.location.href = "/"; // Ejemplo: redirige a la lista de usuarios
        })
        .catch((error) => {
            console.error("Error al actualizar usuario:", error);
            let mensaje = "Error al actualizar usuario";
            if (error.response && error.response.data) {
                if (error.response.data.message) {
                    mensaje = error.response.data.message;
                }
                if (error.response.data.errors) {
                    Object.keys(error.response.data.errors).forEach((campo) => {
                        mensaje += `\n${campo}: ${error.response.data.errors[
                            campo
                        ].join(", ")}`;
                    });
                }
            }
            alert(mensaje);
        });
});
