<?php
//  │   ├── usuarios.php
?>

<!-- Modal Circuitos -->
<div id="cir_modalCircuitos" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">🛣 Gestión de Circuitos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <button class="btn btn-success mb-3" onclick="cir_nuevoCircuito()">➕ Agregar Circuito</button>
                <div id="cir_tablaCircuitos"></div>

                <!-- Formulario oculto -->
                <div id="cir_formCircuito" style="display: none;">
                    <input type="hidden" id="cir_id_circuito">
                    <label>Nombre:</label>
                    <input type="text" id="cir_nombre_circuito" class="form-control mb-2">
                    <label>Ubicación:</label>
                    <input type="text" id="cir_ubicacion" class="form-control mb-2">
                    <label>Longitud (km):</label>
                    <input type="number" step="0.01" id="cir_longitud" class="form-control mb-2">
                    <button class="btn btn-primary w-100" onclick="cir_guardarCircuito()">💾 Guardar</button>
                    <button class="btn btn-secondary w-100 mt-2" onclick="cir_cancelarEdicionCircuito()">❌ Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function cir_abrirModalCircuitos() {
        $("#cir_modalCircuitos").modal("show");
        cir_cargarListaCircuitos();
    }

    function cir_cargarListaCircuitos() {
        $.post("controllers/circuitosController.php", { accion: "listar" }, function(data) {
            $("#cir_tablaCircuitos").html(data);
        });
    }

    function cir_nuevoCircuito() {
        $("#cir_id_circuito").val("");
        $("#cir_nombre_circuito").val("");
        $("#cir_ubicacion").val("");
        $("#cir_longitud").val("");
        $("#cir_formCircuito").show();
    }

    function cir_editarCircuito(id, nombre, ubicacion, longitud) {
        $("#cir_id_circuito").val(id);
        $("#cir_nombre_circuito").val(nombre);
        $("#cir_ubicacion").val(ubicacion);
        $("#cir_longitud").val(longitud);
        $("#cir_formCircuito").show();
    }

    function cir_cancelarEdicionCircuito() {
        $("#cir_formCircuito").hide();
    }

    function cir_guardarCircuito() {
        let id = $("#cir_id_circuito").val();
        let nombre = $("#cir_nombre_circuito").val();
        let ubicacion = $("#cir_ubicacion").val();
        let longitud = $("#cir_longitud").val();
        let accion = id ? "editar" : "agregar";

        if (!nombre || !ubicacion || !longitud) {
            Swal.fire("⚠️ Error", "Todos los campos son obligatorios.", "error");
            return;
        }

        $.post("controllers/circuitosController.php", { accion, id_circuito: id, nombre, ubicacion, longitud }, function(response) {
            let data = JSON.parse(response);
            Swal.fire({
                title: data.status === "ok" ? "✅ Éxito" : "❌ Error",
                text: data.message,
                icon: data.status === "ok" ? "success" : "error"
            }).then(() => {
                cir_cargarListaCircuitos();
                $("#cir_formCircuito").hide();
            });
        });
    }

    function cir_eliminarCircuito(id) {
        Swal.fire({
            title: "¿Eliminar Circuito?",
            text: "Esta acción no se puede deshacer.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("controllers/circuitosController.php", { accion: "eliminar", id_circuito: id }, function(response) {
                    let data = JSON.parse(response);
                    Swal.fire(data.status === "ok" ? "✅ Eliminado" : "❌ Error", data.message, data.status === "ok" ? "success" : "error")
                    .then(() => {
                        cir_cargarListaCircuitos();
                    });
                });
            }
        });
    }
</script>