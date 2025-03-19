<?php
//  /dashboard.php
session_start();
if (!isset($_SESSION["id_usuario"]) || $_SESSION["rol"] !== "admin") {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Panel de Administración</title>
    
    <!-- Bootstrap & AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    
    <!-- SweetAlert2 & jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <style>
        :root {
            --transition-speed: 0.3s;
        }

        .admin-button {
            transition: transform var(--transition-speed), box-shadow var(--transition-speed);
            margin-bottom: 1rem;
            padding: 1rem;
            border-radius: 10px;
            font-weight: 500;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .admin-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
        }

        .admin-panel {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .welcome-text {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 2rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            
            .admin-panel {
                padding: 1rem;
            }

            h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="admin-panel">
            <h2 class="mb-4">⚙️ Panel de Administración</h2>
            <p class="welcome-text">Bienvenido, <strong><?= htmlspecialchars($_SESSION["user_nombre"]) ?></strong>. Administra el sistema aquí.</p>

            <div class="row g-3">
                <div class="col-12 col-sm-6 col-lg-3">
                    <button class="btn btn-primary admin-button w-100" onclick="user_abrirModalUsuarios()">
                        <i class="fas fa-users me-2"></i>👥 Gestionar Usuarios
                    </button>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <button class="btn btn-warning admin-button w-100" onclick="cir_abrirModalCircuitos()">
                        <i class="fas fa-road me-2"></i>🛣 Gestionar Circuitos
                    </button>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <button class="btn btn-success admin-button w-100" onclick="tra_abrirModalTrazados()">
                        <i class="fas fa-flag-checkered me-2"></i>🏎 Gestionar Trazados
                    </button>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <button class="btn btn-danger admin-button w-100" onclick="ejesql_abrirModal()">
                        <i class="fas fa-database me-2"></i>📥 Ejecutar SQL
                    </button>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <button class="btn btn-info admin-button w-100" onclick="pro_abrirModalPrompts()">
                        <i class="fas fa-robot me-2"></i>🤖 Asistente IA
                    </button>
                </div>
            </div>
        </div>
    </div>

<!-- Modales -->
<?php 
include "view/dashboard/usuarios.php";
include "view/dashboard/circuitos.php";
include "view/dashboard/trazados.php";
include "view/dashboard/sql.php";
include "view/dashboard/promts.php"; // Añadir esta línea
?>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Prevenir doble clic en botones
        document.querySelectorAll('.admin-button').forEach(button => {
            button.addEventListener('click', function(e) {
                this.disabled = true;
                setTimeout(() => this.disabled = false, 1000);
            });
        });

        // Detectar si hay conexión a internet
        window.addEventListener('online', function() {
            Swal.fire({
                icon: 'success',
                title: 'Conexión restaurada',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        });

        window.addEventListener('offline', function() {
            Swal.fire({
                icon: 'warning',
                title: 'Sin conexión',
                text: 'Verifica tu conexión a internet',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        });
    </script>
</body>
</html>