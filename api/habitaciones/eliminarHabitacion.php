<?php
require_once '../../backend/conexion_bd.php';

$idHabitacion = $_POST['id'] ?? null;

if (!$idHabitacion) {
    echo json_encode(['success' => false, 'message' => 'ID de habitación no recibido']);
    exit;
}

// Primero, eliminar las fotos asociadas
$stmtFotos = $con->prepare("DELETE FROM fotografias_habitacion WHERE habitacion_id = ?");
$stmtFotos->bind_param("i", $idHabitacion);
$stmtFotos->execute();

// Luego, eliminar la habitación
$stmt = $con->prepare("DELETE FROM habitaciones WHERE id = ?");
$stmt->bind_param("i", $idHabitacion);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Habitación eliminada correctamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al eliminar la habitación']);
}
?>
