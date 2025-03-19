<?php
/**
 * Controlador para el módulo de Salidas
 * 
 * Este controlador maneja la lógica de negocio para las operaciones
 * relacionadas con las sesiones de karting.
 */

// Incluir la conexión a la base de datos
require_once __DIR__ . '/../config/database.php';

class SalidasController {
    private $conn;
    
    /**
     * Constructor
     */
    public function __construct($connection) {
        $this->conn = $connection;
    }
    
    /**
     * Obtiene todas las sesiones de karting
     * 
     * @return array Lista de sesiones
     */
    public function getAllSesiones() {
        $result = [];
        
        try {
            $sql = "SELECT * FROM sesiones_karting ORDER BY session_datetime DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error al obtener sesiones: " . $e->getMessage());
        }
        
        return $result;
    }
    
    /**
     * Obtiene una sesión por su ID
     * 
     * @param int $id ID de la sesión
     * @return array|null Datos de la sesión o null si no existe
     */
    public function getSesionById($id) {
        try {
            $sql = "SELECT * FROM sesiones_karting WHERE id_sesion = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_assoc();
            }
        } catch (Exception $e) {
            error_log("Error al obtener sesión por ID: " . $e->getMessage());
        }
        
        return null;
    }
    
    /**
     * Crea una nueva sesión
     * 
     * @param array $data Datos de la sesión
     * @return bool|int ID de la sesión creada o false en caso de error
     */
    public function createSesion($data) {
        try {
            // Preparar la consulta SQL
            $sql = "INSERT INTO sesiones_karting (
                session_datetime, piloto, circuito, trazado, condiciones,
                rpm_max, vel_max, mejor_tiempo, vuelta_mejor_tiempo, tiempo_promedio,
                total_vueltas, presion_neumaticos, convergencia, altura, otros_ajustes, notas
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            // Preparar la sentencia
            $stmt = $this->conn->prepare($sql);
            
            // Vincular parámetros
            $stmt->bind_param(
                "sssssiisisisssss",
                $data['session_datetime'],
                $data['piloto'],
                $data['circuito'],
                $data['trazado'],
                $data['condiciones'],
                $data['rpm_max'],
                $data['vel_max'],
                $data['mejor_tiempo'],
                $data['vuelta_mejor_tiempo'],
                $data['tiempo_promedio'],
                $data['total_vueltas'],
                $data['presion_neumaticos'],
                $data['convergencia'],
                $data['altura'],
                $data['otros_ajustes'],
                $data['notas']
            );
            
            // Ejecutar la consulta
            if ($stmt->execute()) {
                return $this->conn->insert_id;
            }
        } catch (Exception $e) {
            error_log("Error al crear sesión: " . $e->getMessage());
        }
        
        return false;
    }
    
    /**
     * Actualiza una sesión existente
     * 
     * @param int $id ID de la sesión
     * @param array $data Datos actualizados
     * @return bool Resultado de la operación
     */
    public function updateSesion($id, $data) {
        try {
            // Preparar la consulta SQL
            $sql = "UPDATE sesiones_karting SET
                session_datetime = ?,
                piloto = ?,
                circuito = ?,
                trazado = ?,
                condiciones = ?,
                rpm_max = ?,
                vel_max = ?,
                mejor_tiempo = ?,
                vuelta_mejor_tiempo = ?,
                tiempo_promedio = ?,
                total_vueltas = ?,
                presion_neumaticos = ?,
                convergencia = ?,
                altura = ?,
                otros_ajustes = ?,
                notas = ?
            WHERE id_sesion = ?";
            
            // Preparar la sentencia
            $stmt = $this->conn->prepare($sql);
            
            // Vincular parámetros
            $stmt->bind_param(
                "sssssiisisissssi",
                $data['session_datetime'],
                $data['piloto'],
                $data['circuito'],
                $data['trazado'],
                $data['condiciones'],
                $data['rpm_max'],
                $data['vel_max'],
                $data['mejor_tiempo'],
                $data['vuelta_mejor_tiempo'],
                $data['tiempo_promedio'],
                $data['total_vueltas'],
                $data['presion_neumaticos'],
                $data['convergencia'],
                $data['altura'],
                $data['otros_ajustes'],
                $data['notas'],
                $id
            );
            
            // Ejecutar la consulta
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al actualizar sesión: " . $e->getMessage());
        }
        
        return false;
    }
    
    /**
     * Elimina una sesión
     * 
     * @param int $id ID de la sesión
     * @return bool Resultado de la operación
     */
    public function deleteSesion($id) {
        try {
            $sql = "DELETE FROM sesiones_karting WHERE id_sesion = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al eliminar sesión: " . $e->getMessage());
        }
        
        return false;
    }
    
    /**
     * Obtiene las últimas 5 sesiones
     * 
     * @return array Lista de las últimas 5 sesiones
     */
    public function getUltimasCincoSesiones() {
        $result = [];
        
        try {
            $sql = "SELECT * FROM sesiones_karting ORDER BY session_datetime DESC LIMIT 5";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error al obtener últimas sesiones: " . $e->getMessage());
        }
        
        return $result;
    }
    
    /**
     * Obtiene las 5 sesiones con mejores tiempos
     * 
     * @return array Lista de las 5 sesiones con mejores tiempos
     */
    public function getCincoMejoresTiempos() {
        $result = [];
        
        try {
            // Asumiendo que mejor_tiempo está en formato mm:ss.ms
            $sql = "SELECT * FROM sesiones_karting WHERE mejor_tiempo IS NOT NULL AND mejor_tiempo != '' ORDER BY mejor_tiempo ASC LIMIT 5";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error al obtener mejores tiempos: " . $e->getMessage());
        }
        
        return $result;
    }
    
    /**
     * Valida los datos de una sesión
     * 
     * @param array $data Datos a validar
     * @return array Errores encontrados
     */
    public function validateSesionData($data) {
        $errors = [];
        
        // Validar campos obligatorios
        if (empty($data['session_datetime'])) {
            $errors[] = "La fecha y hora son obligatorias";
        }
        
        if (empty($data['piloto'])) {
            $errors[] = "El nombre del piloto es obligatorio";
        }
        
        // Validar formato de tiempo (mm:ss.ms)
        if (!empty($data['mejor_tiempo']) && !preg_match('/^\d+:\d{2}\.\d{1,3}$/', $data['mejor_tiempo'])) {
            $errors[] = "El formato del mejor tiempo debe ser mm:ss.ms (ejemplo: 1:23.456)";
        }
        
        if (!empty($data['tiempo_promedio']) && !preg_match('/^\d+:\d{2}\.\d{1,3}$/', $data['tiempo_promedio'])) {
            $errors[] = "El formato del tiempo promedio debe ser mm:ss.ms (ejemplo: 1:25.789)";
        }
        
        // Validar valores numéricos
        if (!empty($data['rpm_max']) && !is_numeric($data['rpm_max'])) {
            $errors[] = "Las RPM máximas deben ser un valor numérico";
        }
        
        if (!empty($data['vel_max']) && !is_numeric($data['vel_max'])) {
            $errors[] = "La velocidad máxima debe ser un valor numérico";
        }
        
        if (!empty($data['vuelta_mejor_tiempo']) && !is_numeric($data['vuelta_mejor_tiempo'])) {
            $errors[] = "La vuelta del mejor tiempo debe ser un valor numérico";
        }
        
        if (!empty($data['total_vueltas']) && !is_numeric($data['total_vueltas'])) {
            $errors[] = "El total de vueltas debe ser un valor numérico";
        }
        
        return $errors;
    }
    
    /**
     * Sanitiza los datos de entrada
     * 
     * @param array $data Datos a sanitizar
     * @return array Datos sanitizados
     */
    public function sanitizeInput($data) {
        $sanitized = [];
        
        foreach ($data as $key => $value) {
            // Sanitizar strings
            if (is_string($value)) {
                $sanitized[$key] = htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
            } else {
                $sanitized[$key] = $value;
            }
            
            // Convertir valores vacíos a NULL para la base de datos
            if ($sanitized[$key] === '') {
                $sanitized[$key] = null;
            }
        }
        
        return $sanitized;
    }
}
?>