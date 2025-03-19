<?php
//  view/dashboard/usuarios.php
?>
<!-- Modal Usuarios -->
<div id="user_modalUsuarios" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title d-flex align-items-center">
                    <span class="me-2">👥</span>
                    <span>Gestión de Usuarios</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid p-0">
                    <button class="btn btn-success mb-3 d-flex align-items-center" onclick="user_nuevoUsuario()">
                        <span class="me-2">➕</span>
                        <span>Agregar Usuario</span>
                    </button>
                  
                    <!-- Tabla responsiva -->
                    <div class="table-responsive" id="user_tablaUsuarios"></div>

                    <!-- Formulario mejorado -->
                    <div id="user_formUsuario" class="d-none">
                        <form id="user_usuarioForm" class="needs-validation" novalidate>
                            <input type="hidden" id="user_id_usuario">
                          
                            <div class="mb-3">
                                <label for="user_nombre" class="form-label">Nombre</label>
                                <input type="text" id="user_nombre" class="form-control" required>
                                <div class="invalid-feedback">Por favor, ingrese un nombre.</div>
                            </div>

                            <div class="mb-3">
                                <label for="user_nickname" class="form-label">Nickname</label>
                                <input type="text" id="user_nickname" class="form-control" required>
                                <div class="invalid-feedback">Por favor, ingrese un nickname.</div>
                            </div>

                            <div class="mb-3">
                                <label for="user_email" class="form-label">Email</label>
                                <input type="email" id="user_email" class="form-control" required>
                                <div class="invalid-feedback">Por favor, ingrese un email válido.</div>
                            </div>

                            <div class="mb-3">
                                <label for="user_password" class="form-label">Contraseña</label>
                                <div class="input-group">
                                    <input type="password" id="user_password" class="form-control" 
                                           minlength="6">
                                    <button class="btn btn-outline-secondary" type="button" 
                                            onclick="user_togglePassword()">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text" id="user_passwordHelpBlock">
                                    Obligatoria al agregar. Mínimo 6 caracteres.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="user_rol" class="form-label">Rol</label>
                                <select id="user_rol" class="form-select" required>
                                    <option value="">Seleccione un rol...</option>
                                    <option value="admin">Admin</option>
                                    <option value="preparador">Preparador</option>
                                    <option value="piloto">Piloto</option>
                                    <option value="mecanico">Mecánico</option>
                                </select>
                                <div class="invalid-feedback">Por favor, seleccione un rol.</div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Guardar
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="user_cancelarEdicion()">
                                    <i class="fas fa-times me-2"></i>Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('user_usuarioForm');
  
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        event.stopPropagation();
      
        if (form.checkValidity()) {
            user_guardarUsuario();
        }
      
        form.classList.add('was-validated');
    });
});

function user_togglePassword() {
    const passwordInput = document.getElementById('user_password');
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
}

function user_abrirModalUsuarios() {
    const modal = new bootstrap.Modal(document.getElementById('user_modalUsuarios'));
    modal.show();
    user_cargarListaUsuarios();
}

function user_cargarListaUsuarios() {
    user_mostrarCargando();
    $.post("controllers/usuariosController.php", { accion: "listar" })
        .done(function(data) {
            $("#user_tablaUsuarios").html(data);
        })
        .fail(function() {
            user_mostrarError("Error al cargar usuarios");
        })
        .always(function() {
            user_ocultarCargando();
        });
}

function user_mostrarCargando() {
    Swal.fire({
        title: 'Cargando...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}

function user_ocultarCargando() {
    Swal.close();
}

function user_mostrarError(mensaje) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: mensaje,
        timer: 3000
    });
}

function user_nuevoUsuario() {
    document.getElementById('user_usuarioForm').reset();
    document.getElementById('user_id_usuario').value = '';
    $("#user_formUsuario").removeClass('d-none').addClass('d-block');
    $("#user_tablaUsuarios").hide();
}

function user_editarUsuario(id, nombre, nickname, email, rol) {
    document.getElementById('user_id_usuario').value = id;
    document.getElementById('user_nombre').value = nombre;
    document.getElementById('user_nickname').value = nickname;
    document.getElementById('user_email').value = email;
    document.getElementById('user_password').value = '';
    document.getElementById('user_rol').value = rol;
  
    $("#user_formUsuario").removeClass('d-none').addClass('d-block');
    $("#user_tablaUsuarios").hide();
}

function user_cancelarEdicion() {
    document.getElementById('user_usuarioForm').reset();
    document.getElementById('user_usuarioForm').classList.remove('was-validated');
    $("#user_formUsuario").removeClass('d-block').addClass('d-none');
    $("#user_tablaUsuarios").show();
}

function user_guardarUsuario() {
    const formData = {
        accion: $("#user_id_usuario").val() ? "editar" : "agregar",
        id_usuario: $("#user_id_usuario").val(),
        nombre: $("#user_nombre").val().trim(),
        nickname: $("#user_nickname").val().trim(),
        email: $("#user_email").val().trim(),
        password: $("#user_password").val(),
        rol: $("#user_rol").val()
    };

    if (!formData.id_usuario && !formData.password) {
        Swal.fire({
            icon: 'warning',
            title: 'Contraseña requerida',
            text: 'La contraseña es obligatoria al agregar un usuario.'
        });
        return;
    }

    user_mostrarCargando();
  
    $.post("controllers/usuariosController.php", formData)
        .done(function(response) {
            const data = JSON.parse(response);
            Swal.fire({
                icon: data.status === "ok" ? "success" : "error",
                title: data.status === "ok" ? "¡Éxito!" : "Error",
                text: data.message,
                timer: data.status === "ok" ? 2000 : undefined
            }).then(() => {
                if (data.status === "ok") {
                    user_cancelarEdicion();
                    user_cargarListaUsuarios();
                }
            });
        })
        .fail(function() {
            user_mostrarError("Error al guardar el usuario");
        });
}

function user_eliminarUsuario(id) {
    Swal.fire({
        title: '¿Eliminar Usuario?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            user_mostrarCargando();
            $.post("controllers/usuariosController.php", {
                accion: "eliminar",
                id_usuario: id
            })
            .done(function(response) {
                const data = JSON.parse(response);
                Swal.fire({
                    icon: data.status === "ok" ? "success" : "error",
                    title: data.status === "ok" ? "Usuario eliminado" : "Error",
                    text: data.message,
                    timer: data.status === "ok" ? 2000 : undefined
                }).then(() => {
                    if (data.status === "ok") {
                        user_cargarListaUsuarios();
                    }
                });
            })
            .fail(function() {
                user_mostrarError("Error al eliminar el usuario");
            });
        }
    });
}
</script>

<style>
/* Los estilos se mantienen igual ya que no contienen IDs específicos que necesiten el prefijo */
.modal-header {
    border-bottom: 2px solid #dee2e6;
}

.modal-body {
    padding: 1.5rem;
}

.form-control:focus, .form-select:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

@media (max-width: 576px) {
    .modal-dialog {
        margin: 0.5rem;
    }
  
    .modal-body {
        padding: 1rem;
    }
}

.table-responsive {
    margin-bottom: 1rem;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    border-radius: 0.25rem;
}
</style>