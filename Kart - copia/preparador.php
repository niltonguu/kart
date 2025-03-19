<?php
session_start();
if (!isset($_SESSION["id_usuario"]) || $_SESSION["rol"] !== "preparador") {
    header("Location: login.php");
    exit();
}

// Verificar si existe selección de contexto
$tieneContexto = isset($_SESSION["trazado_id"]) && isset($_SESSION["piloto_nombre"]);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Preparador</title>
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

    <!-- jQuery primero para evitar conflictos -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    
    <!-- DataTables JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>

    <!-- JSZip para exportación Excel -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    
    <!-- Bootstrap & AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>



    <style>
        :root {
            --transition-speed: 0.3s;
        }

        /* Mini-header styles */
        .context-header {
            background: rgba(255, 255, 255, 0.95);
            border-bottom: 1px solid rgba(0,0,0,0.1);
            font-size: 0.85rem;
            position: sticky;
            top: 0;
            z-index: 1030;
            backdrop-filter: blur(5px);
            height: 28px;
        }

        .context-header .container {
            height: 100%;
            padding-top: 0;
            padding-bottom: 0;
        }

        .context-info {
            color: #666;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: flex;
            align-items: center;
            gap: 8px;
            height: 100%;
        }

        .context-switch {
            color: #007bff;
            cursor: pointer;
            border: none;
            background: none;
            transition: color 0.3s ease;
            height: 100%;
            display: flex;
            align-items: center;
        }

        .context-switch:hover {
            color: #0056b3;
        }

        /* Estilos para el botón hamburguesa */
        .context-menu-toggle {
            border: none;
            background: none;
            color: #007bff;
            height: 100%;
            display: flex;
            align-items: center;
            font-size: 0.9rem;
        }

        /* Estilos para el menú móvil */
        .context-menu-mobile {
            background: white;
            padding: 8px 15px;
            border-radius: 0 0 4px 4px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-top: 1px;
            font-size: 0.85rem;
            min-width: 200px;
            z-index: 1040;
        }

        .context-menu-mobile > div {
            border-bottom: 1px solid #eee;
        }

        .context-menu-mobile > div:last-child {
            border-bottom: none;
        }

        /* Panel styles */
        .prep-button {
            transition: transform var(--transition-speed), box-shadow var(--transition-speed);
            margin-bottom: 1rem;
            padding: 1.5rem;
            border-radius: 15px;
            font-weight: 500;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .prep-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .prep-panel {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .welcome-text {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 2rem;
            padding: 1rem;
            border-left: 4px solid #2c3e50;
            background: #f8f9fa;
            border-radius: 0 10px 10px 0;
        }

        .footer {
            /*position: fixed;*/
            bottom: 0;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            padding: 0.5rem 0;
        }

        body {
            padding-bottom: 50px;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .context-header {
                height: 28px;
            }
            
            .container {
                padding: 1rem;
            }
            
            .prep-panel {
                padding: 1rem;
            }

            .prep-button {
                padding: 1rem;
            }

            h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body class="bg-light">
    <!-- Mini-header con información de contexto -->
    <?php if ($tieneContexto): ?>
    <div class="context-header">
        <div class="container d-flex justify-content-between align-items-center h-100">
            <div class="d-flex align-items-center position-relative">
                <!-- Botón hamburguesa slim -->
                <button class="context-menu-toggle d-md-none p-0" type="button" data-bs-toggle="collapse" data-bs-target="#contextMenu" aria-expanded="false">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- Contenido principal (visible en desktop) -->
                <div class="context-info d-none d-md-flex">
                    <button class="context-switch p-0" id="btnCambiarContexto" title="Cambiar selección">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                    <span class="d-flex align-items-center">
                        <i class="fas fa-road"></i>&nbsp;<?= htmlspecialchars($_SESSION["trazado_nombre"]) ?>
                    </span>
                    <span class="d-flex align-items-center">
                        <i class="fas fa-user"></i>&nbsp;<?= htmlspecialchars($_SESSION["piloto_nombre"]) ?>
                    </span>
                </div>

                <!-- Menú colapsable para móvil (oculto por defecto) -->
                <div class="collapse position-absolute top-100 start-0" id="contextMenu">
                    <div class="context-menu-mobile">
                        <div class="py-1">
                            <button class="context-switch p-0" id="btnCambiarContextoMobile" title="Cambiar selección">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                        <div class="py-1">
                            <i class="fas fa-road"></i>&nbsp;<?= htmlspecialchars($_SESSION["trazado_nombre"]) ?>
                        </div>
                        <div class="py-1">
                            <i class="fas fa-user"></i>&nbsp;<?= htmlspecialchars($_SESSION["piloto_nombre"]) ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botón de logout slim -->
            <a href="logout.php" class="text-danger text-decoration-none p-0" 
               onclick="return confirm('¿Seguro que deseas cerrar sesión?')">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </div>
    <?php endif; ?>

    <!-- Panel Principal -->
    <div class="container mt-4" id="panelPrincipal" style="display: <?= $tieneContexto ? 'block' : 'none' ?>">
        <div class="prep-panel">
            <h2 class="mb-4">🏁 Panel de Preparador</h2>
            <p class="welcome-text">
                Bienvenido, <strong><?= htmlspecialchars($_SESSION["nombre"]) ?></strong>
                <br>
                <small class="text-muted">Gestiona y analiza el rendimiento de las sesiones de tus pilotos</small>
            </p>

            <div class="row g-4">
                <!-- Lista de Salidas -->
                <div class="col-12 col-md-6 col-lg-3">
                    <button type="button" id="btnListaSalidas" class="btn btn-primary prep-button w-100">
                        <i class="fas fa-clipboard-list fa-2x mb-2"></i>
                        <br>
                        Lista de Salidas
                        <br>
                        <small class="text-light">Historial completo</small>
                    </button>
                </div>

                <!-- 5 Últimas Salidas -->
                <div class="col-12 col-md-6 col-lg-3">
                    <button type="button" class="btn btn-success prep-button w-100" id="btn5UltimasSalidas">
                        <i class="fas fa-history fa-2x mb-2"></i>
                        <br>
                        5 Últimas Salidas
                        <br>
                        <small class="text-light">Sesiones recientes</small>
                    </button>
                </div>

                <!-- 5 Mejores Tiempos -->
                <div class="col-12 col-md-6 col-lg-3">
                    <button type="button" class="btn btn-warning prep-button w-100" id="btn5MejoresTiempos">
                        <i class="fas fa-trophy fa-2x mb-2"></i>
                        <br>
                        5 Mejores Tiempos
                        <br>
                        <small class="text-dark">Récords personales</small>
                    </button>
                </div>

                <!-- Tips y Consejos -->
                <div class="col-12 col-md-6 col-lg-3">
                    <button type="button" class="btn btn-info prep-button w-100" id="btnTips">
                        <i class="fas fa-lightbulb fa-2x mb-2"></i>
                        <br>
                        Tips
                        <br>
                        <small class="text-light">Recomendaciones</small>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Selección de Contexto -->
    <div class="modal fade" id="modalSeleccionContexto" tabindex="-1" aria-labelledby="modalSeleccionContextoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalSeleccionContextoLabel">
                        <i class="fas fa-tasks"></i> Selección de Trabajo
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formSeleccionContexto">
                        <div class="mb-3">
                            <label for="selectTrazado" class="form-label">Trazado</label>
                            <select class="form-select" id="selectTrazado" required>
                                <option value="">Seleccione un trazado...</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="selectPiloto" class="form-label">Piloto</label>
                            <select class="form-select" id="selectPiloto" required>
                                <option value="">Seleccione un piloto...</option>
                            </select>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check"></i> Confirmar Selección
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container text-center">
            <span class="text-muted">
                © <?= date('Y') ?> Panel Preparador | NGU
            </span>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Función para manejar el cambio de contexto
            function handleContextSwitch() {
                abrirSeleccionContexto();
            }

            // Obtener referencias a los botones
            const btnDesktop = document.getElementById('btnCambiarContexto');
            const btnMobile = document.getElementById('btnCambiarContextoMobile');

            // Añadir event listeners solo si los elementos existen
            if (btnDesktop) {
                btnDesktop.addEventListener('click', handleContextSwitch);
            }
            
            if (btnMobile) {
                btnMobile.addEventListener('click', handleContextSwitch);
            }
        });

        $(document).ready(function() {
            // Para los otros botones, muestra un mensaje de "próximamente"
            // Nuevo código para manejar los clicks
$('#btnListaSalidas').click(function() {
    $('#modalListaSalidas').modal('show');
});

$('#btn5UltimasSalidas').click(function() {
    $('#modal5UltimasSalidas').modal('show');
});

$('#btn5MejoresTiempos').click(function() {
    $('#modal5MejoresTiempos').modal('show');
});

$('#btnTips').click(function() {
    $('#modalTips').modal('show');
});
            
            // Verificar conexión con el controlador al inicio
            $.ajax({
                url: 'controllers/preparadorController.php',
                type: 'POST',
                data: { action: 'verificarContexto' },
                success: function(response) {
                    try {
                        const data = JSON.parse(response);
                        if (!data.tieneContexto) {
                            abrirSeleccionContexto();
                        }
                    } catch (e) {
                        console.error('Error al verificar contexto:', e);
                        Swal.fire('Error', 'Error de comunicación con el servidor', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al verificar controlador:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de conexión',
                        text: 'No se puede conectar con el servidor',
                        confirmButtonText: 'Reintentar',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                }
            });
        });

        // Función para abrir modal de selección
        function abrirSeleccionContexto() {
            cargarOpcionesContexto();
            $('#modalSeleccionContexto').modal('show');
        }

        // Cargar opciones de trazados y pilotos
        function cargarOpcionesContexto() {
            $.ajax({
                url: 'controllers/preparadorController.php',
                type: 'POST',
                data: { action: 'getOpciones' },
                success: function(response) {
                    try {
                        const data = JSON.parse(response);
                        if (data.error) {
                            Swal.fire('Error', data.error, 'error');
                            return;
                        }
                        
                        // Llenar trazados
                        const selectTrazado = $('#selectTrazado');
                        selectTrazado.empty().append('<option value="">Seleccione un trazado...</option>');
                        data.trazados.forEach(t => {
                            selectTrazado.append(`<option value="${t.id_trazado}">${t.nombre}</option>`);
                        });
                        
                        // Llenar pilotos
                        const selectPiloto = $('#selectPiloto');
                        selectPiloto.empty().append('<option value="">Seleccione un piloto...</option>');
                        data.pilotos.forEach(p => {
                            selectPiloto.append(`<option value="${p.piloto}">${p.piloto}</option>`);
                        });
                    } catch (e) {
                        console.error('Error al procesar la respuesta:', e);
                        Swal.fire('Error', 'Error al procesar los datos', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error AJAX:', error);
                    Swal.fire('Error', 'Error al cargar las opciones', 'error');
                }
            });
        }

        // Manejar envío del formulario de selección
        $('#formSeleccionContexto').on('submit', function(e) {
            e.preventDefault();
            
            const trazadoId = $('#selectTrazado').val();
            const piloto = $('#selectPiloto').val();

            if (!trazadoId || !piloto) {
                Swal.fire('Error', 'Por favor seleccione trazado y piloto', 'error');
                return;
            }

            $.ajax({
                url: 'controllers/preparadorController.php',
                type: 'POST',
                data: {
                    action: 'setContexto',
                    trazado_id: trazadoId,
                    piloto: piloto
                },
                success: function(response) {
                    try {
                        const data = JSON.parse(response);
                        if (data.error) {
                            Swal.fire('Error', data.error, 'error');
                            return;
                        }
                        
                        if (data.success) {
                            $('#modalSeleccionContexto').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Contexto actualizado',
                                text: 'La selección se ha guardado correctamente',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        }
                    } catch (e) {
                        console.error('Error al procesar la respuesta:', e);
                        Swal.fire('Error', 'Error al procesar los datos', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error AJAX:', error);
                    Swal.fire('Error', 'Error al guardar la selección', 'error');
                }
            });
        });
    </script>
    <?php include 'view/preparador/salidas.php'; ?>
</body>
</html>