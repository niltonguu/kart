<?php
session_start();
require_once "../config/database.php";

class PreparadorController {
    private $conn;

    public function __construct() {
        try {
            if (!isset($_SESSION["id_usuario"]) || $_SESSION["rol"] !== "preparador") {
                throw new Exception("No autorizado");
            }
            $this->conn = getConnection();
        } catch (Exception $e) {
            $this->responderError($e->getMessage());
        }
    }

    /**
     * Obtiene las opciones de trazados y pilotos disponibles
     */
    public function getOpciones() {
        try {
            // Obtener trazados con sesiones
            $queryTrazados = "
                SELECT DISTINCT 
                    t.id as id_trazado,
                    t.nombre,
                    t.longitud,
                    t.tipo,
                    t.sentido
                FROM trazados t
                INNER JOIN sesiones_karting sk ON t.id = sk.id_trazado
                ORDER BY t.nombre
            ";
            
            $stmtTrazados = $this->conn->query($queryTrazados);
            if (!$stmtTrazados) {
                throw new Exception("Error al obtener trazados");
            }
            $trazados = $stmtTrazados->fetchAll(PDO::FETCH_ASSOC);

            // Obtener pilotos únicos
            $queryPilotos = "
                SELECT DISTINCT piloto
                FROM sesiones_karting
                WHERE piloto != ''
                ORDER BY piloto
            ";
            
            $stmtPilotos = $this->conn->query($queryPilotos);
            if (!$stmtPilotos) {
                throw new Exception("Error al obtener pilotos");
            }
            $pilotos = $stmtPilotos->fetchAll(PDO::FETCH_ASSOC);

            $this->responderExito([
                "trazados" => $trazados,
                "pilotos" => $pilotos
            ]);

        } catch (Exception $e) {
            $this->responderError("Error al cargar opciones: " . $e->getMessage());
        }
    }

    /**
     * Establece el contexto de trabajo en la sesión
     */
    public function setContexto() {
        try {
            // Validar datos recibidos
            if (!isset($_POST['trazado_id']) || !isset($_POST['piloto'])) {
                throw new Exception("Datos incompletos");
            }

            $trazadoId = filter_var($_POST['trazado_id'], FILTER_VALIDATE_INT);
            $piloto = filter_var($_POST['piloto'], FILTER_SANITIZE_STRING);

            if (!$trazadoId || empty($piloto)) {
                throw new Exception("Datos inválidos");
            }

            // Verificar trazado
            $stmtTrazado = $this->conn->prepare("
                SELECT id as id_trazado, nombre 
                FROM trazados 
                WHERE id = ?
            ");
            $stmtTrazado->execute([$trazadoId]);
            $trazado = $stmtTrazado->fetch(PDO::FETCH_ASSOC);

            if (!$trazado) {
                throw new Exception("Trazado no encontrado");
            }

            // Verificar existencia de sesiones
            $stmtSesiones = $this->conn->prepare("
                SELECT COUNT(*) 
                FROM sesiones_karting 
                WHERE id_trazado = ? AND piloto = ?
            ");
            $stmtSesiones->execute([$trazadoId, $piloto]);
            
            if ($stmtSesiones->fetchColumn() == 0) {
                throw new Exception("No hay sesiones para esta combinación");
            }

            // Guardar en sesión
            $_SESSION['trazado_id'] = $trazadoId;
            $_SESSION['trazado_nombre'] = $trazado['nombre'];
            $_SESSION['piloto_nombre'] = $piloto;

            $this->responderExito([
                "mensaje" => "Contexto actualizado correctamente"
            ]);

        } catch (Exception $e) {
            $this->responderError($e->getMessage());
        }
    }

    /**
     * Limpia el contexto actual
     */
    public function limpiarContexto() {
        try {
            unset($_SESSION['trazado_id']);
            unset($_SESSION['trazado_nombre']);
            unset($_SESSION['piloto_nombre']);

            $this->responderExito([
                "mensaje" => "Contexto limpiado correctamente"
            ]);

        } catch (Exception $e) {
            $this->responderError("Error al limpiar contexto: " . $e->getMessage());
        }
    }

    /**
     * Verifica si existe un contexto válido
     */
    public function verificarContexto() {
        try {
            $tieneContexto = isset($_SESSION['trazado_id']) && 
                            isset($_SESSION['piloto_nombre']) &&
                            $this->contextoEsValido(
                                $_SESSION['trazado_id'], 
                                $_SESSION['piloto_nombre']
                            );

            $this->responderExito([
                "tieneContexto" => $tieneContexto
            ]);

        } catch (Exception $e) {
            $this->responderError("Error al verificar contexto: " . $e->getMessage());
        }
    }

    /**
     * Verifica si el contexto actual es válido
     */
    private function contextoEsValido($trazadoId, $piloto) {
        try {
            $stmt = $this->conn->prepare("
                SELECT COUNT(*) 
                FROM sesiones_karting sk
                INNER JOIN trazados t ON t.id = sk.id_trazado
                WHERE sk.id_trazado = ? 
                AND sk.piloto = ?
            ");
            $stmt->execute([$trazadoId, $piloto]);
            return $stmt->fetchColumn() > 0;

        } catch (Exception $e) {
            error_log("Error al validar contexto: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Responde con éxito
     */
    private function responderExito($datos = []) {
        $respuesta = array_merge(["success" => true], $datos);
        echo json_encode($respuesta);
    }

    /**
     * Responde con error
     */
    private function responderError($mensaje) {
        echo json_encode([
            "success" => false,
            "error" => $mensaje
        ]);
    }
}

// Manejo de peticiones
try {
    if (!isset($_POST['action'])) {
        throw new Exception("Acción no especificada");
    }

    $controller = new PreparadorController();
    
    switch ($_POST['action']) {
        case 'getOpciones':
            $controller->getOpciones();
            break;
        case 'setContexto':
            $controller->setContexto();
            break;
        case 'limpiarContexto':
            $controller->limpiarContexto();
            break;
        case 'verificarContexto':
            $controller->verificarContexto();
            break;
        default:
            throw new Exception("Acción no válida");
    }
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
}
?>