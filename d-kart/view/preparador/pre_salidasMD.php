<?php
// /view/preparador/salidas/pre_salidasMD.php

?>
<!-- Modal para lista de salidas -->
<div class="modal fade" id="modalListaSalidas" tabindex="-1" aria-labelledby="modalListaSalidasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalListaSalidasLabel">Lista de Salidas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table id="tablaSalidas" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Fecha y Hora</th>
                            <th>Trazado</th>
                            <th>Vel. Máxima</th>
                            <th>RPM Máxima</th>
                            <th>Temp. Motor</th>
                            <th>Mejor Tiempo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Los datos se cargarán aquí via AJAX -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar salida -->
<div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAgregarLabel">Nueva Salida</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAgregar">
                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha y Hora</label>
                        <input type="datetime-local" class="form-control" id="fecha" name="fecha" required>
                    </div>
                    <div class="mb-3">
                        <label for="trazado" class="form-label">Trazado</label>
                        <select class="form-select" id="trazado" name="trazado" required>
                            <option value="">Seleccione un trazado</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="vel_max" class="form-label">Velocidad Máxima</label>
                        <input type="number" class="form-control" id="vel_max" name="vel_max" step="0.1" required>
                    </div>
                    <div class="mb-3">
                        <label for="rpm_max" class="form-label">RPM Máxima</label>
                        <input type="number" class="form-control" id="rpm_max" name="rpm_max" required>
                    </div>
                    <div class="mb-3">
                        <label for="temp_motor" class="form-label">Temperatura del Motor</label>
                        <input type="number" class="form-control" id="temp_motor" name="temp_motor" required>
                    </div>
                    <div class="mb-3">
                        <label for="mejor_tiempo" class="form-label">Mejor Tiempo</label>
                        <input type="time" class="form-control" id="mejor_tiempo" name="mejor_tiempo" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver detalles -->
<div class="modal fade" id="modalDetalle" tabindex="-1" aria-labelledby="modalDetalleLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetalleLabel">Detalles de la Salida</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Fecha y Hora:</strong> <span id="detalleFecha"></span></p>
                <p><strong>Trazado:</strong> <span id="detalleTrazado"></span></p>
                <p><strong>Velocidad Máxima:</strong> <span id="detalleVelMax"></span></p>
                <p><strong>RPM Máxima:</strong> <span id="detalleRpmMax"></span></p>
                <p><strong>Temperatura del Motor:</strong> <span id="detalleTempMotor"></span></p>
                <p><strong>Mejor Tiempo:</strong> <span id="detalleMejorTiempo"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>