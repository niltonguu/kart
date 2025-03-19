<!-- Modal Trazados -->
<div id="tra_modalTrazados" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">🛣 Gestión de Trazados</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <button class="btn btn-success mb-3" onclick="tra_nuevoTrazado()">➕ Agregar Trazado</button>
                <div id="tra_tablaTrazados"></div>

                <!-- Formulario oculto -->
                <div id="tra_formTrazado" style="display: none;">
                    <form id="tra_trazadoForm" class="needs-validation" novalidate>
                        <input type="hidden" id="tra_id">
                        
                        <div class="mb-3">
                            <label for="tra_id_circuito" class="form-label">Circuito</label>
                            <select id="tra_id_circuito" class="form-select" required>
                                <option value="">Seleccione un circuito...</option>
                            </select>
                            <div class="invalid-feedback">Por favor, seleccione un circuito.</div>
                        </div>

                        <div class="mb-3">
                            <label for="tra_nombre" class="form-label">Nombre del Trazado</label>
                            <input type="text" id="tra_nombre" class="form-control" required>
                            <div class="invalid-feedback">Por favor, ingrese un nombre.</div>
                        </div>

                        <div class="mb-3">
                            <label for="tra_longitud" class="form-label">Longitud (metros)</label>
                            <input type="number" step="0.01" id="tra_longitud" class="form-control" required>
                            <div class="invalid-feedback">Por favor, ingrese la longitud.</div>
                        </div>

                        <div class="mb-3">
                            <label for="tra_tipo" class="form-label">Tipo</label>
                            <input type="text" id="tra_tipo" class="form-control" required>
                            <div class="invalid-feedback">Por favor, ingrese el tipo.</div>
                        </div>

                        <div class="mb-3">
                            <label for="tra_sentido" class="form-label">Sentido</label>
                            <select id="tra_sentido" class="form-select" required>
                                <option value="Horario">Horario</option>
                                <option value="Antihorario">Antihorario</option>
                            </select>
                            <div class="invalid-feedback">Por favor, seleccione el sentido.</div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Guardar
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="tra_cancelarEdicion()">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('tra_trazadoForm');
    
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        event.stopPropagation();
        
        if (form.checkValidity()) {
            tra_guardarTrazado();
        }
        
        form.classList.add('was-validated');
    });
});

function tra_abrirModalTrazados() {
    $("#tra_modalTrazados").modal("show");
    tra_cargarListaTrazados();
    tra_cargarCircuitos();
}

function tra_cargarCircuitos() {
    $.post("controllers/circuitosController.php", { accion: "listar_circuitos" }, function(data) {
        $("#tra_id_circuito").html('<option value="">Seleccione un circuito...</option>' + data);
    });
}

function tra_cargarListaTrazados() {
    $.post("controllers/trazadosController.php", { accion: "listar" }, function(data) {
        $("#tra_tablaTrazados").html(data);
    });
}

function tra_nuevoTrazado() {
    document.getElementById('tra_trazadoForm').reset();
    document.getElementById('tra_id').value = '';
    $("#tra_formTrazado").show();
    $("#tra_tablaTrazados").hide();
}

function tra_editarTrazado(id, id_circuito, nombre, longitud, tipo, sentido) {
    $("#tra_id").val(id);
    $("#tra_id_circuito").val(id_circuito);
    $("#tra_nombre").val(nombre);
    $("#tra_longitud").val(longitud);
    $("#tra_tipo").val(tipo);
    $("#tra_sentido").val(sentido);
    $("#tra_formTrazado").show();
    $("#tra_tablaTrazados").hide();
}

function tra_cancelarEdicion() {
    document.getElementById('tra_trazadoForm').reset();
    document.getElementById('tra_trazadoForm').classList.remove('was-validated');
    $("#tra_formTrazado").hide();
    $("#tra_tablaTrazados").show();
}

function tra_guardarTrazado() {
    const formData = {
        accion: $("#tra_id").val() ? "editar" : "agregar",
        id: $("#tra_id").val(),
        id_circuito: $("#tra_id_circuito").val(),
        nombre: $("#tra_nombre").val().trim(),
        longitud: $("#tra_longitud").val(),
        tipo: $("#tra_tipo").val().trim(),
        sentido: $("#tra_sentido").val()
    };

    $.post("controllers/trazadosController.php", formData, function(response) {
        let data = JSON.parse(response);
        Swal.fire({
            title: data.status === "ok" ? "✅ Éxito" : "❌ Error",
            text: data.message,
            icon: data.status === "ok" ? "success" : "error"
        }).then(() => {
            if (data.status === "ok") {
                tra_cancelarEdicion();
                tra_cargarListaTrazados();
            }
        });
    });
}

function tra_eliminarTrazado(id) {
    Swal.fire({
        title: "¿Eliminar Trazado?",
        text: "Esta acción no se puede deshacer.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("controllers/trazadosController.php", {
                accion: "eliminar",
                id: id
            }, function(response) {
                let data = JSON.parse(response);
                Swal.fire(
                    data.status === "ok" ? "✅ Eliminado" : "❌ Error",
                    data.message,
                    data.status === "ok" ? "success" : "error"
                ).then(() => {
                    if (data.status === "ok") {
                        tra_cargarListaTrazados();
                    }
                });
            });
        }
    });
}
</script>