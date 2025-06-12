<?php
require_once '../../backend/conexion_bd.php';

header('Content-Type: application/json');

// Get userId from cookie
$userId = $_COOKIE['userId'] ?? null;

if (!$userId) {
    http_response_code(401);
    echo json_encode(['success' => false, 'mensaje' => 'No autenticado.']);
    exit();
}

$data = json_decode(file_get_contents("php://input"));
$reservaId = $data->id ?? 0;

if ($reservaId) {
    // Verify that the reservation belongs to the authenticated user
    $stmt = $con->prepare("SELECT id FROM reservas WHERE id = ? AND id_usuario = ?");
    $stmt->bind_param("ii", $reservaId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(403);
        echo json_encode(['success' => false, 'mensaje' => 'No tienes permiso para cancelar esta reserva.']);
        exit();
    }

    $stmt = $con->prepare("UPDATE reservas SET estado = 'cancelada' WHERE id = ?");
    $stmt->bind_param("i", $reservaId);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'mensaje' => 'Reserva cancelada correctamente.']);
    } else {
        echo json_encode(['success' => false, 'mensaje' => 'Error al cancelar reserva.']);
    }
} else {
    echo json_encode(['success' => false, 'mensaje' => 'ID de reserva no proporcionado.']);
}

$con->close();
?>
