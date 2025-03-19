<?php
// Este archivo es incluido desde preparador.php, por lo que no necesita las etiquetas HTML básicas
?>

<!-- Estilos específicos para la lista de salidas -->
<style>
    .dataTables_wrapper .dt-buttons {
        margin-bottom: 15px;
    }
    
    .btn-action {
        margin-right: 5px;
    }
    
    .detail-section {
        padding: 15px 0;
        border-bottom: 1px solid #e9ecef;
    }
    
    .detail-section:last-child {
        border-bottom: none;
    }
    
    .detail-title {
        font-weight: 600;
        color: #495057;
        margin-bottom: 15px;
    }
    
    .detail-item {
        margin-bottom: 8px;
    }
    
    .detail-label {
        font-weight: 500;
        color: #6c757d;
    }
</style>

<!-- Contenedor principal -->
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="mb-3">Lista de Salidas</h3>
            <div class="card">
                <div class="card-body">
                    <table id="tablaSalidas" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fecha/Hora</th>
                                <th>Piloto</th>
                                <th>RPM Máx</th>
                                <th>Vel. Máx</th>
                                <th>Mejor Tiempo</th>
                                <th>Vuelta</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Los datos se cargarán mediante AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Detalles -->
<div class="modal fade" id="modalDetalles" tabindex="-1" role="dialog" aria-labelledby="modalDetallesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetallesLabel">Detalles de la Sesión</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="detallesSesion">
                    <!-- Información General -->
                    <div class="detail-section">
                        <h4 class="detail-title">Información General</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <span class="detail-label">ID Sesión:</span>
                                    <span id="detalle-id"></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Fecha y Hora:</span>
                                    <span id="detalle-datetime"></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Piloto:</span>
                                    <span id="detalle-piloto"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <span class="detail-label">Circuito:</span>
                                    <span id="detalle-circuito"></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Trazado:</span>
                                    <span id="detalle-trazado"></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Condiciones:</span>
                                    <span id="detalle-condiciones"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Rendimiento -->
                    <div class="detail-section">
                        <h4 class="detail-title">Rendimiento</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <span class="detail-label">RPM Máximo:</span>
                                    <span id="detalle-rpm-max"></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Velocidad Máxima:</span>
                                    <span id="detalle-vel-max"></span> km/h
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Mejor Tiempo:</span>
                                    <span id="detalle-mejor-tiempo"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <span class="detail-label">Vuelta del Mejor Tiempo:</span>
                                    <span id="detalle-vuelta-mejor"></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Tiempo Promedio:</span>
                                    <span id="detalle-tiempo-promedio"></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Total de Vueltas:</span>
                                    <span id="detalle-total-vueltas"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Configuración -->
                    <div class="detail-section">
                        <h4 class="detail-title">Configuración</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <span class="detail-label">Presión Neumáticos:</span>
                                    <span id="detalle-presion"></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Convergencia:</span>
                                    <span id="detalle-convergencia"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <span class="detail-label">Altura:</span>
                                    <span id="detalle-altura"></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Otros Ajustes:</span>
                                    <span id="detalle-otros-ajustes"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notas -->
                    <div class="detail-section">
                        <h4 class="detail-title">Notas</h4>
                        <div class="row">
                            <div class="col-12">
                                <div class="detail-item">
                                    <p id="detalle-notas"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Edición -->
<div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarLabel">Editar Sesión</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEditar">
                    <input type="hidden" id="edit-id" name="id_sesion">
                    
                    <!-- Información General -->
                    <div class="form-group">
                        <h4 class="mb-3">Información General</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit-datetime">Fecha y Hora</label>
                                    <input type="datetime-local" class="form-control" id="edit-datetime" name="session_datetime" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit-piloto">Piloto</label>
                                    <input type="text" class="form-control" id="edit-piloto" name="piloto" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit-circuito">Circuito</label>
                                    <input type="text" class="form-control" id="edit-circuito" name="circuito">
                                </div>
                                <div class="form-group">
                                    <label for="edit-trazado">Trazado</label>
                                    <input type="text" class="form-control" id="edit-trazado" name="trazado">
                                </div>
                                <div class="form-group">
                                    <label for="edit-condiciones">Condiciones</label>
                                    <input type="text" class="form-control" id="edit-condiciones" name="condiciones">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Rendimiento -->
                    <div class="form-group mt-4">
                        <h4 class="mb-3">Rendimiento</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit-rpm-max">RPM Máximo</label>
                                    <input type="number" class="form-control" id="edit-rpm-max" name="rpm_max">
                                </div>
                                <div class="form-group">
                                    <label for="edit-vel-max">Velocidad Máxima (km/h)</label>
                                    <input type="number" class="form-control" id="edit-vel-max" name="vel_max">
                                </div>
                                <div class="form-group">
                                    <label for="edit-mejor-tiempo">Mejor Tiempo (mm:ss.ms)</label>
                                    <input type="text" class="form-control" id="edit-mejor-tiempo" name="mejor_tiempo" placeholder="1:23.456">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit-vuelta-mejor">Vuelta del Mejor Tiempo</label>
                                    <input type="number" class="form-control" id="edit-vuelta-mejor" name="vuelta_mejor_tiempo">
                                </div>
                                <div class="form-group">
                                    <label for="edit-tiempo-promedio">Tiempo Promedio (mm:ss.ms)</label>
                                    <input type="text" class="form-control" id="edit-tiempo-promedio" name="tiempo_promedio" placeholder="1:25.789">
                                </div>
                                <div class="form-group">
                                    <label for="edit-total-vueltas">Total de Vueltas</label>
                                    <input type="number" class="form-control" id="edit-total-vueltas" name="total_vueltas">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Configuración -->
                    <div class="form-group mt-4">
                        <h4 class="mb-3">Configuración</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit-presion">Presión Neumáticos</label>
                                    <input type="text" class="form-control" id="edit-presion" name="presion_neumaticos">
                                </div>
                                <div class="form-group">
                                    <label for="edit-convergencia">Convergencia</label>
                                    <input type="text" class="form-control" id="edit-convergencia" name="convergencia">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit-altura">Altura</label>
                                    <input type="text" class="form-control" id="edit-altura" name="altura">
                                </div>
                                <div class="form-group">
                                    <label for="edit-otros-ajustes">Otros Ajustes</label>
                                    <input type="text" class="form-control" id="edit-otros-ajustes" name="otros_ajustes">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notas -->
                    <div class="form-group mt-4">
                        <h4 class="mb-3">Notas</h4>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <textarea class="form-control" id="edit-notas" name="notas" rows="4"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnGuardarEdicion">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación de Eliminación -->
<div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog" aria-labelledby="modalEliminarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEliminarLabel">Confirmar Eliminación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar esta sesión? Esta acción no se puede deshacer.</p>
                <input type="hidden" id="eliminar-id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts específicos para la lista de salidas -->
<script>
// Variables globales
let tablaSalidas;

// Inicializar DataTable
$(document).ready(function() {
    initDataTable();
    
    // Event listeners para los botones de acción
    $('#tablaSalidas').on('click', '.btn-ver', function() {
        const id = $(this).data('id');
        cargarDetallesSesion(id);
    });
    
    $('#tablaSalidas').on('click', '.btn-editar', function() {
        const id = $(this).data('id');
        cargarDatosEdicion(id);
    });
    
    $('#tablaSalidas').on('click', '.btn-eliminar', function() {
        const id = $(this).data('id');
        $('#eliminar-id').val(id);
        $('#modalEliminar').modal('show');
    });
    
    // Event listener para guardar cambios en la edición
    $('#btnGuardarEdicion').click(function() {
        guardarEdicion();
    });
    
    // Event listener para confirmar eliminación
    $('#btnConfirmarEliminar').click(function() {
        eliminarSesion();
    });
});

// Función para inicializar DataTable
function initDataTable() {
    // Comprobar si DataTable está disponible
    if (typeof $.fn.DataTable !== 'function') {
        console.error('DataTables no está cargado correctamente');
        return;
    }
    
    // Destruir la tabla existente si ya está inicializada
    if ($.fn.DataTable.isDataTable('#tablaSalidas')) {
        $('#tablaSalidas').DataTable().destroy();
    }
    
    // Inicializar la nueva tabla
    tablaSalidas = $('#tablaSalidas').DataTable({
        ajax: {
            url: 'api/salidasApi.php?action=list',
            dataSrc: function(json) {
                // Verificar si hay datos
                if (json.data) {
                    return json.data;
                } else if (json.message) {
                    // Mostrar mensaje de error si existe
                    console.error('Error en la API:', json.message);
                    return [];
                } else {
                    return [];
                }
            }
        },
        columns: [
            { data: 'id_sesion' },
            { data: 'session_datetime' },
            { data: 'piloto' },
            { data: 'rpm_max' },
            { data: 'vel_max' },
            { data: 'mejor_tiempo' },
            { data: 'vuelta_mejor_tiempo' },
            { 
                data: null,
                render: function(data, type, row) {
                    return `
                        <button class="btn btn-sm btn-info btn-action btn-ver" data-id="${row.id_sesion}" title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-warning btn-action btn-editar" data-id="${row.id_sesion}" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger btn-action btn-eliminar" data-id="${row.id_sesion}" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    `;
                }
            }
        ],
        order: [[1, 'desc']],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-success',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                },
                title: 'Lista de Salidas'
            }
        ]
    });
}

// Función para cargar los detalles de una sesión
function cargarDetallesSesion(id) {
    $.ajax({
        url: 'api/salidasApi.php?action=get',
        type: 'GET',
        data: { id: id },
        dataType: 'json',
        success: function(response) {
            if (response.success && response.data) {
                const sesion = response.data;
                
                // Llenar los campos del modal de detalles
                $('#detalle-id').text(sesion.id_sesion);
                $('#detalle-datetime').text(sesion.session_datetime);
                $('#detalle-piloto').text(sesion.piloto);
                $('#detalle-circuito').text(sesion.circuito || 'No especificado');
                $('#detalle-trazado').text(sesion.trazado || 'No especificado');
                $('#detalle-condiciones').text(sesion.condiciones || 'No especificado');
                
                $('#detalle-rpm-max').text(sesion.rpm_max || 'No registrado');
                $('#detalle-vel-max').text(sesion.vel_max || 'No registrado');
                $('#detalle-mejor-tiempo').text(sesion.mejor_tiempo || 'No registrado');
                $('#detalle-vuelta-mejor').text(sesion.vuelta_mejor_tiempo || 'No registrado');
                $('#detalle-tiempo-promedio').text(sesion.tiempo_promedio || 'No registrado');
                $('#detalle-total-vueltas').text(sesion.total_vueltas || 'No registrado');
                
                $('#detalle-presion').text(sesion.presion_neumaticos || 'No especificado');
                $('#detalle-convergencia').text(sesion.convergencia || 'No especificado');
                $('#detalle-altura').text(sesion.altura || 'No especificado');
                $('#detalle-otros-ajustes').text(sesion.otros_ajustes || 'No especificado');
                
                $('#detalle-notas').text(sesion.notas || 'Sin notas');
                
                // Mostrar el modal
                $('#modalDetalles').modal('show');
            } else {
                alert('No se pudo cargar la información de la sesión');
            }
        },
        error: function() {
            alert('Error al comunicarse con el servidor');
        }
    });
}

// Función para cargar los datos para edición
function cargarDatosEdicion(id) {
    $.ajax({
        url: 'api/salidasApi.php?action=get',
        type: 'GET',
        data: { id: id },
        dataType: 'json',
        success: function(response) {
            if (response.success && response.data) {
                const sesion = response.data;
                
                // Llenar los campos del formulario de edición
                $('#edit-id').val(sesion.id_sesion);
                
                // Formatear la fecha y hora para el input datetime-local
                if (sesion.session_datetime) {
                    const datetime = new Date(sesion.session_datetime);
                    const formattedDatetime = datetime.toISOString().slice(0, 16);
                    $('#edit-datetime').val(formattedDatetime);
                }
                
                $('#edit-piloto').val(sesion.piloto);
                $('#edit-circuito').val(sesion.circuito);
                $('#edit-trazado').val(sesion.trazado);
                $('#edit-condiciones').val(sesion.condiciones);
                
                $('#edit-rpm-max').val(sesion.rpm_max);
                $('#edit-vel-max').val(sesion.vel_max);
                $('#edit-mejor-tiempo').val(sesion.mejor_tiempo);
                $('#edit-vuelta-mejor').val(sesion.vuelta_mejor_tiempo);
                $('#edit-tiempo-promedio').val(sesion.tiempo_promedio);
                $('#edit-total-vueltas').val(sesion.total_vueltas);
                
                $('#edit-presion').val(sesion.presion_neumaticos);
                $('#edit-convergencia').val(sesion.convergencia);
                $('#edit-altura').val(sesion.altura);
                $('#edit-otros-ajustes').val(sesion.otros_ajustes);
                
                $('#edit-notas').val(sesion.notas);
                
                // Mostrar el modal
                $('#modalEditar').modal('show');
            } else {
                alert('No se pudo cargar la información de la sesión');
            }
        },
        error: function() {
            alert('Error al comunicarse con el servidor');
        }
    });
}

// Función para guardar los cambios de la edición
function guardarEdicion() {
    const formData = $('#formEditar').serialize();
    
    $.ajax({
        url: 'api/salidasApi.php?action=update',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // Cerrar el modal
                $('#modalEditar').modal('hide');
                
                // Actualizar la tabla
                tablaSalidas.ajax.reload();
                
                // Mostrar mensaje de éxito
                alert('Sesión actualizada correctamente');
            } else {
                alert('Error al actualizar la sesión: ' + response.message);
            }
        },
        error: function() {
            alert('Error al comunicarse con el servidor');
        }
    });
}

// Función para eliminar una sesión
function eliminarSesion() {
    const id = $('#eliminar-id').val();
    
    $.ajax({
        url: 'api/salidasApi.php?action=delete',
        type: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // Cerrar el modal
                $('#modalEliminar').modal('hide');
                
                // Actualizar la tabla
                tablaSalidas.ajax.reload();
                
                // Mostrar mensaje de éxito
                alert('Sesión eliminada correctamente');
            } else {
                alert('Error al eliminar la sesión: ' + response.message);
            }
        },
        error: function() {
            alert('Error al comunicarse con el servidor');
        }
    });
}
</script>