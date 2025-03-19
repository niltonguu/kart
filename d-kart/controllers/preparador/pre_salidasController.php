<?php
// /controllers/preparador/pre_salidasController.php

require_once '../../config/database.php';

class PreSalidasController {
    private $conexion;

    public function __construct() {
        $this->conexion = getConnection();
    }

    public function getSalidas() {
        try {
            $query = "
                SELECT 
                    s.id_sesion,
                    s.session_datetime,
                    t.nombre AS trazado,
                    s.vel_max,
                    s.rpm_max,
                    s.temp_motor,
                    s.mejor_tiempo
                FROM sesiones_karting s
                INNER JOIN trazados t ON s.trazado_id = t.id_trazado
                WHERE s.id_usuario = " . $_SESSION["id_usuario"] . "
                ORDER BY s.session_datetime DESC
            ";
            $resultado = $this->conexion->query($query)->fetchAll(PDO::FETCH_ASSOC);
            return ['success' => true, 'data' => $resultado];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Error al consultar las salidas: " . $e->getMessage()];
        }
    }

    public function getSalidaDetalle($idSesion) {
        try {
            $query = "
                SELECT 
                    s.id_sesion,
                    s.session_datetime,
                    t.nombre AS trazado,
                    s.vel_max,
                    s.rpm_max,
                    s.temp_motor,
                    s.mejor_tiempo
                FROM sesiones_karting s
                INNER JOIN trazados t ON s.trazado_id = t.id_trazado
                WHERE s.id_sesion = '$idSesion' AND s.id_usuario = " . $_SESSION["id_usuario"] . "
            ";
            $stmt = $this->conexion->query($query);
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($resultado) {
                return ['success' => true, 'detalle' => $resultado[0]];
            } else {
                return ['success' => false, 'error' => 'No se encontró la sesión solicitada.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Error al cargar los detalles: " . $e->getMessage()];
        }
    }

    public function agregarSalida($formData) {
        try {
            $fecha = $formData['fecha'];
            $trazado_id = $formData['trazado'];
            $vel_max = $formData['vel_max'];
            $rpm_max = $formData['rpm_max'];
            $temp_motor = $formData['temp_motor'];
            $mejor_tiempo = $formData['mejor_tiempo'];

            $query = "
                INSERT INTO sesiones_karting (
                    session_datetime,
                    trazado_id,
                    vel_max,
                    rpm_max,
                    temp_motor,
                    mejor_tiempo,
                    id_usuario
                ) VALUES (
                    '$fecha',
                    '$trazado_id',
                    '$vel_max',
                    '$rpm_max',
                    '$temp_motor',
                    '$mejor_tiempo',
                    " . $_SESSION["id_usuario"] . "
                )
            ";
            $this->conexion->query($query);
            return ['success' => true, 'message' => 'Salida agregada correctamente.'];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => "Error al guardar la salida: " . $e->getMessage()];
        }
    }
}