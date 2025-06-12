<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../backend/conexion_bd.php';

header('Content-Type: application/json');

// Get userId from cookie
$userId = $_COOKIE['userId'] ?? null;

if (!$userId) {
    http_response_code(401);
    echo json_encode(['success' => false, 'mensaje' => 'No autenticado.']);
    exit();
}

$stmt = $con->prepare("SELECT r.id, h.numero AS habitacion, r.fecha_ingreso, r.fecha_salida, r.estado FROM reservas r JOIN habitaciones h ON r.habitacion_id = h.id WHERE r.usuario_id = ? ORDER BY r.fecha_ingreso DESC");

if ($stmt === false) {
    http_response_code(500);
    echo json_encode(['success' => false, 'mensaje' => 'Error en la preparación de la consulta: ' . $con->error]);
    exit();
}

$stmt->bind_param("i", $userId);

if ($stmt->execute() === false) {
    http_response_code(500);
    echo json_encode(['success' => false, 'mensaje' => 'Error al ejecutar la consulta: ' . $stmt->error]);
    exit();
}

$result = $stmt->get_result();

$reservas = [];
while ($row = $result->fetch_assoc()) {
    $reservas[] = $row;
}

echo json_encode($reservas);

$stmt->close();
$con->close();
?>
