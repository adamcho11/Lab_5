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

$stmt = $con->prepare("SELECT id, nombre, email, rol FROM usuarios WHERE id=?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if ($usuario) {
    echo json_encode($usuario);
} else {
    http_response_code(404);
    echo json_encode(['success' => false, 'mensaje' => 'Usuario no encontrado.']);
}

$stmt->close();
$con->close();
?>

