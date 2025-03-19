<?php
header('Content-Type: application/json');
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'obtenerDatosSesion':
            $promptId = $_POST['promptId'] ?? 0;
            obtenerDatosSesion($promptId);
            break;
        default:
            echo json_encode([
                'status' => 'error',
                'mensaje' => 'Acción no válida'
            ]);
    }
    exit;
}

function obtenerDatosSesion($promptId) {
    try {
        $conn = getConnection();
        
        // Verificar la conexión
        if (!$conn) {
            throw new Exception("Error de conexión a la base de datos");
        }
        
        $query = "
            SELECT 
                DATE_FORMAT(sk.session_datetime, '%d/%m/%Y %H:%i') as fecha,
                t.nombre as trazado,
                sk.categoria,
                sk.num_kart,
                sk.piloto,
                CONCAT(sk.temp_ambiente, '°C') as temp_ambiente,
                CONCAT(sk.temp_pista, '°C') as temp_pista,
                CONCAT(sk.vel_max, ' km/h') as vel_max,
                CONCAT(sk.temp_motor, '°C') as temp_motor,
                CONCAT(sk.rpm_max, ' rpm') as rpm_max,
                CONCAT(sk.rpm_min, ' rpm') as rpm_min,
                sk.aire,
                sk.aguja,
                sk.chicler,
                sk.mejor_tiempo as mejor_tiempo, 
                sk.eje,
                sk.caster,
                sk.ackermann,
                sk.camber,
                sk.toe_out,
                sk.vueltas_neumaticos
            FROM sesiones_karting sk
            JOIN trazados t ON sk.id_trazado = t.id
            ORDER BY sk.session_datetime DESC
            LIMIT 1
        ";

        $stmt = $conn->query($query);
        
        if (!$stmt) {
            throw new Exception("Error al ejecutar la consulta");
        }
        
        $datos = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($datos) {
            // Formatear los datos
            $datosFormateados = "Datos de la sesión:\n\n";
            $datosFormateados .= "📅 Fecha: " . $datos['fecha'] . "\n";
            $datosFormateados .= "🏁 Trazado: " . $datos['trazado'] . "\n";
            $datosFormateados .= "🏎️ Categoría: " . $datos['categoria'] . "\n";
            $datosFormateados .= "🔢 Número de Kart: " . $datos['num_kart'] . "\n";
            $datosFormateados .= "👤 Piloto: " . $datos['piloto'] . "\n\n";

            $datosFormateados .= "Condiciones:\n";
            $datosFormateados .= "🌡️ Temperatura Ambiente: " . $datos['temp_ambiente'] . "\n";
            $datosFormateados .= "🛣️ Temperatura Pista: " . $datos['temp_pista'] . "\n";
            $datosFormateados .= "⚡ Velocidad Máxima: " . $datos['vel_max'] . "\n";
            $datosFormateados .= "🔥 Temperatura Motor: " . $datos['temp_motor'] . "\n";
            $datosFormateados .= "⚙️ RPM Máximas: " . $datos['rpm_max'] . "\n";
            $datosFormateados .= "⚙️ RPM Mínimas: " . $datos['rpm_min'] . "\n\n";

            $datosFormateados .= "Configuración del Carburador:\n";
            $datosFormateados .= "💨 Aire: " . $datos['aire'] . "\n";
            $datosFormateados .= "📍 Aguja: " . $datos['aguja'] . "\n";
            $datosFormateados .= "🔧 Chicler: " . $datos['chicler'] . "\n\n";

            $datosFormateados .= "Rendimiento:\n";
            $datosFormateados .= "⏱️ Mejor Tiempo: " . $datos['mejor_tiempo'] . "\n\n";

            $datosFormateados .= "Configuración Técnica:\n";
            $datosFormateados .= "🔧 Eje: " . $datos['eje'] . "\n";
            $datosFormateados .= "📐 Caster: " . $datos['caster'] . "\n";
            $datosFormateados .= "🔄 Ackermann: " . $datos['ackermann'] . "\n";
            $datosFormateados .= "📏 Camber: " . $datos['camber'] . "\n";
            $datosFormateados .= "↔️ Toe Out: " . $datos['toe_out'] . "\n";
            $datosFormateados .= "🔄 Vueltas Neumáticos: " . $datos['vueltas_neumaticos'];

            echo json_encode([
                'status' => 'ok',
                'datos' => $datosFormateados
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'mensaje' => 'No se encontraron datos de sesión'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'mensaje' => 'Error al obtener los datos: ' . $e->getMessage()
        ]);
    }
}
?>