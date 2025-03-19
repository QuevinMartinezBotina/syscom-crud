// Función para alternar el formulario de creación de usuario
function toggleFormularioUsuario() {
    const formulario = document.getElementById("formularioUsuario");
    formulario.style.display =
        formulario.style.display === "none" || formulario.style.display === ""
            ? "block"
            : "none";
}

function ocultarFormularioUsuario() {
    document.getElementById("formularioUsuario").style.display = "none";
}

// Función para obtener roles y llenar el select de "Cargo"
function obtenerRolesParaSelect() {
    axios
        .get("/api/roles")
        .then((response) => {
            let options = '<option value="">Seleccione un cargo</option>';
            response.data.forEach((role) => {
                options += `<option value="${role.id}">${role.nombre_cargo}</option>`;
            });
            $("#id_rol").html(options);
        })
        .catch((error) => {
            console.error("Error al obtener roles:", error);
        });
}

// Función para obtener usuarios y llenar la tabla
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

            // Si ya está inicializada, destruimos la instancia para reinicializar
            if ($.fn.DataTable.isDataTable("#tablaUsuarios")) {
                $("#tablaUsuarios").DataTable().destroy();
            }
            // Inicializamos DataTable nuevamente
            $("#tablaUsuarios").DataTable();
        })
        .catch((error) => {
            console.error("Error al obtener usuarios:", error);
        });
}

// Función para abrir el contrato en una nueva pestaña
function verContrato(rutaContrato) {
    if (rutaContrato) {
        window.open(rutaContrato, "_blank");
    } else {
        alert("No existe contrato registrado");
    }
}

// Función para eliminar usuario
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

// Función para redirigir a la vista de edición del usuario
function editarUsuario(id) {
    window.location.href = `/usuarios/${id}/edit`;
}

// Inicializamos la DataTable y cargamos los datos al cargar la página
$(document).ready(function () {
    obtenerRolesParaSelect(); // Cargar roles en el select
    obtenerUsuarios(); // Cargar usuarios en la tabla
});
