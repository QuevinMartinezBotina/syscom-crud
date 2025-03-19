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
            alert(response.data.message);
            $("#formUsuario")[0].reset();
            obtenerUsuarios(); // Actualizo la lista de usuarios
        })
        .catch((error) => {
            console.error("Error al crear usuario:", error);
            alert("Error al crear usuario");
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
    alert("Funcionalidad de edición pendiente");
}
