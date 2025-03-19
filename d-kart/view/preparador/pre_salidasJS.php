<?php
header('Content-Type: application/javascript');
?>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#tablaSalidas').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
        },
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "api/preparador/salidasApi.php",
            "type": "POST",
            "data": function(d) {
                return $.extend({}, d, {
                    "action": "getSalidas"
                });
            }
        },
        "columns": [
            { data: "session_datetime" },
            { data: "trazado" },
            { data: "vel_max" },
            { data: "rpm_max" },
            { data: "temp_motor" },
            { data: "mejor_tiempo" },
            { 
                data: "id_sesion",
                render: function(data) {
                    return '<button class="btn btn-primary btn-sm" onclick="verDetalle(' + data + ')">Detalles</button>';
                }
            }
        ],
        "order": [[0, "desc"]]
    });

    // Function to view details
    function verDetalle(idSesion) {
        $.ajax({
            url: "api/preparador/salidasApi.php",
            type: "POST",
            data: {
                "action": "getSalidaDetalle",
                "id_sesion": idSesion
            },
            success: function(response) {
                if (response.success) {
                    let detalle = response.detalle;
                    $("#detalleFecha").text(detalle.session_datetime);
                    $("#detalleTrazado").text(detalle.trazado);
                    $("#detalleVelMax").text(detalle.vel_max);
                    $("#detalleRpmMax").text(detalle.rpm_max);
                    $("#detalleTempMotor").text(detalle.temp_motor);
                    $("#detalleMejorTiempo").text(detalle.mejor_tiempo);
                    $("#modalDetalle").modal("show");
                } else {
                    alert("Error al cargar los detalles: " + response.error);
                }
            },
            error: function(xhr, status, error) {
                alert("Error: " + error);
            }
        });
    }

    // Functionality for the add form
    $('#formAgregar').submit(function(e) {
        e.preventDefault();
        let formData = $(this).serialize();
        
        $.ajax({
            url: "api/preparador/salidasApi.php",
            type: "POST",
            data: {
                "action": "agregarSalida",
                "formData": formData
            },
            success: function(response) {
                if (response.success) {
                    $('#tablaSalidas').DataTable().ajax.reload();
                    $('#modalAgregar').modal('hide');
                } else {
                    alert("Error al guardar: " + response.error);
                }
            },
            error: function(xhr, status, error) {
                alert("Error: " + error);
            }
        });
    });
});
</script>