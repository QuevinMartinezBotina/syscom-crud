// Inicializa DataTable y obtiene los roles
$(document).ready(function () {
    $("#tablaRoles").DataTable();
    obtenerRoles();
});

function obtenerRoles() {
    axios
        .get("/api/roles")
        .then((response) => {
            // Destruye la instancia actual de DataTable si existe
            if ($.fn.DataTable.isDataTable("#tablaRoles")) {
                $("#tablaRoles").DataTable().destroy();
            }

            // Construye el contenido del tbody con los datos recibidos
            let tbody = "";
            response.data.forEach((role) => {
                tbody += `<tr>
                    <td>${role.id}</td>
                    <td>${role.nombre_cargo}</td>
                    <td>
                        <button class="btn btn-warning" onclick="editarRol(${role.id})">Editar</button>
                        <button class="btn btn-danger" onclick="eliminarRol(${role.id})">Eliminar</button>
                    </td>
                </tr>`;
            });
            $("#tablaRoles tbody").html(tbody);

            // Reinicializa el DataTable
            $("#tablaRoles").DataTable();
        })
        .catch((error) => {
            console.error("Error al obtener roles:", error);
        });
}

function mostrarFormularioCreacion() {
    $("#formularioCreacion").show();
}

function ocultarFormularioCreacion() {
    $("#formularioCreacion").hide();
}

$("#formRole").submit(function (e) {
    e.preventDefault();
    const formData = {
        nombre_cargo: $("#nombre_cargo").val(),
    };

    axios
        .post("/api/roles", formData)
        .then((response) => {
            alert(response.data.message);
            $("#formRole")[0].reset();
            ocultarFormularioCreacion();
            obtenerRoles();
        })
        .catch((error) => {
            let mensaje = "Error al crear rol";
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

function eliminarRol(id) {
    if (confirm("¿Estás seguro de eliminar este rol?")) {
        axios
            .delete(`/api/roles/${id}`)
            .then((response) => {
                alert(response.data.message);
                obtenerRoles();
            })
            .catch((error) => {
                console.error("Error al eliminar rol:", error);
            });
    }
}

function editarRol(id) {
    // Redirige a la página de edición para roles
    window.location.href = `/roles/${id}/edit`;
}

$("#formEditarRol").submit(function (e) {
    e.preventDefault();
    const id = $("#formEditarRol").data("id");
    const formData = {
        nombre_cargo: $("#nombre_cargo").val(),
    };
    axios
        .put(`/api/roles/${id}`, formData)
        .then((response) => {
            alert(response.data.message);
            window.location.href = "/roles"; // Redirige a la lista de roles
        })
        .catch((error) => {
            console.error("Error al actualizar rol:", error);
            let mensaje = "Error al actualizar rol";
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
