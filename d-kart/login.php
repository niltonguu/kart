<?php
session_start();
if (isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inicio de Sesión | NilKart</title>
    
    <!-- AdminLTE & Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        body {
            background: #343a40; /* Fondo oscuro */
            color: white;
        }
        .login-box {
            width: 400px;
            margin: 100px auto;
            background: #222;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
        }
        .login-box h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-control {
            background: #444;
            color: white;
            border: none;
        }
        .form-control:focus {
            background: #555;
            color: white;
        }
        .btn-login {
            background: #28a745;
            color: white;
            border: none;
        }
        .btn-login:hover {
            background: #218838;
        }
    </style>
</head>
<body>

    <div class="login-box">
        <h2>🔑 Iniciar Sesión</h2>
        <form id="loginForm">
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" placeholder="Ingresa tu email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" placeholder="Ingresa tu contraseña" required>
            </div>
            <button type="submit" class="btn btn-login w-100">Ingresar</button>
        </form>
        <p class="text-center mt-3">
            <a href="#" class="text-light">¿Olvidaste tu contraseña?</a>
        </p>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
    $(document).ready(function() {
        $("#loginForm").submit(function(event) {
            event.preventDefault();

            let email = $("#email").val();
            let password = $("#password").val();

            $.post("controllers/authController.php", { email, password }, function(response) {
                let data = JSON.parse(response);

                if (data.status === "ok") {
                    Swal.fire("✅ Éxito", "Inicio de sesión exitoso.", "success").then(() => {
                        window.location.href = data.redirect; // Redirección según el rol
                    });
                } else {
                    Swal.fire("❌ Error", data.message, "error");
                }
            });
        });
    });

    </script>

</body>
</html>
