// Función para alternar la visualización del formulario de creación de rol
function toggleFormularioRol() {
    const formulario = document.getElementById("formularioRol");
    formulario.style.display =
        formulario.style.display === "none" || formulario.style.display === ""
            ? "block"
            : "none";
}

function ocultarFormularioRol() {
    document.getElementById("formularioRol").style.display = "none";
}

// Función para obtener roles y llenar la tabla, reinicializando DataTables
function obtenerRoles() {
    axios
        .get("/api/roles")
        .then((response) => {
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
            // Si ya se había inicializado DataTables, se destruye para reinicializar
            if ($.fn.DataTable.isDataTable("#tablaRoles")) {
                $("#tablaRoles").DataTable().destroy();
            }
            $("#tablaRoles").DataTable();
        })
        .catch((error) => {
            console.error("Error al obtener roles:", error);
        });
}

// Manejo del envío del formulario para crear rol
$("#formRol").submit(function (e) {
    e.preventDefault();
    const formData = {
        nombre_cargo: $("#nombre_cargo").val(),
    };
    axios
        .post("/api/roles", formData)
        .then((response) => {
            alert(response.data.message);
            $("#formRol")[0].reset();
            ocultarFormularioRol();
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

// Función para eliminar rol
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

// Función para redirigir a la página de edición para roles
function editarRol(id) {
    window.location.href = `/roles/${id}/edit`;
}

// Al cargar la página, se inicializa la DataTable y se obtienen los roles
$(document).ready(function () {
    obtenerRoles();
});
