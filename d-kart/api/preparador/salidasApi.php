<?php
// salidasApi.php - API Endpoint for Salidas

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database configuration
require_once '../../config/database.php';

// Include the controller
require_once '../../controllers/preparador/pre_salidasController.php';

try {
    // Initialize the controller
    $controller = new PreSalidasController();

    // Handle the request based on the action
    if (!isset($_POST['action'])) {
        throw new Exception("Action parameter is required");
    }

    switch ($_POST['action']) {
        case 'getSalidas':
            $result = $controller->getSalidas();
            break;
        case 'getSalidaDetalle':
            if (!isset($_POST['id_sesion'])) {
                throw new Exception("id_sesion is required");
            }
            $result = $controller->getSalidaDetalle($_POST['id_sesion']);
            break;
        case 'agregarSalida':
            if (!isset($_POST['formData'])) {
                throw new Exception("formData is required");
            }
            $result = $controller->agregarSalida($_POST['formData']);
            break;
        default:
            throw new Exception("Invalid action");
    }

    // Return the result as JSON
    if ($result) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($result);
    } else {
        throw new Exception("No results found");
    }
} catch (Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    $error = [
        "success" => false,
        "error" => $e->getMessage()
    ];
    echo json_encode($error);
}
?>