<!-- Modal SQL Executor -->
<div id="ejesql_modalSQL" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">⚡ Ejecutor SQL</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> 
                    <strong>Precaución:</strong> La ejecución de scripts SQL puede afectar la integridad de la base de datos.
                    Asegúrese de saber lo que está haciendo.
                </div>

                <form id="ejesql_form">
                    <div class="mb-3">
                        <label for="ejesql_script" class="form-label">Script SQL:</label>
                        <textarea id="ejesql_script" class="form-control font-monospace" 
                                rows="10" style="resize: vertical;"
                                placeholder="Ingrese su script SQL aquí..."></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-play me-2"></i>Ejecutar
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="ejesql_limpiar()">
                            <i class="fas fa-eraser me-2"></i>Limpiar
                        </button>
                    </div>
                </form>

                <div id="ejesql_resultado" class="mt-4" style="display: none;">
                    <h6 class="border-bottom pb-2">Resultado de la ejecución:</h6>
                    <div id="ejesql_contenidoResultado" class="p-3 bg-light rounded">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('ejesql_form');
    
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        ejesql_ejecutar();
    });
});

function ejesql_abrirModal() {
    $("#ejesql_modalSQL").modal("show");
}

function ejesql_limpiar() {
    document.getElementById('ejesql_script').value = '';
    document.getElementById('ejesql_resultado').style.display = 'none';
}

function ejesql_ejecutar() {
    const script = document.getElementById('ejesql_script').value.trim();
    
    if (!script) {
        Swal.fire({
            icon: 'warning',
            title: '⚠️ Script vacío',
            text: 'Por favor, ingrese un script SQL para ejecutar.'
        });
        return;
    }

    Swal.fire({
        title: '¿Ejecutar SQL?',
        text: 'Esta acción podría modificar la base de datos. ¿Está seguro?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, ejecutar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'controllers/sqlController.php',
                method: 'POST',
                data: {
                    accion: 'ejecutar',
                    script: script
                },
                beforeSend: function() {
                    Swal.fire({
                        title: 'Ejecutando...',
                        text: 'Por favor espere',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    try {
                        const data = JSON.parse(response);
                        
                        // Mostrar resultado
                        const resultadoDiv = document.getElementById('ejesql_resultado');
                        const contenidoDiv = document.getElementById('ejesql_contenidoResultado');
                        
                        resultadoDiv.style.display = 'block';
                        contenidoDiv.innerHTML = `
                            <div class="alert alert-${data.status === 'ok' ? 'success' : 'danger'} mb-3">
                                ${data.message}
                            </div>
                            ${data.details ? `
                                <div class="mt-3">
                                    <strong>Detalles:</strong>
                                    <pre class="mt-2">${data.details}</pre>
                                </div>
                            ` : ''}
                        `;

                        Swal.fire({
                            icon: data.status === 'ok' ? 'success' : 'error',
                            title: data.status === 'ok' ? '✅ Éxito' : '❌ Error',
                            text: data.message
                        });
                    } catch (e) {
                        console.error('Error parsing response:', e);
                        Swal.fire({
                            icon: 'error',
                            title: '❌ Error',
                            text: 'Error al procesar la respuesta del servidor'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: '❌ Error',
                        text: 'Error al comunicarse con el servidor'
                    });
                }
            });
        }
    });
}
</script>