<?php
require_once '../../backend/conexion_bd.php';

$idHabitacion = $_POST['id'] ?? null;

if (!$idHabitacion) {
    echo json_encode(['success' => false, 'message' => 'ID de habitación no recibido']);
    exit;
}


$stmtReservas = $con->prepare("DELETE FROM reservas WHERE habitacion_id = ?");
$stmtReservas->bind_param("i", $idHabitacion);
$stmtReservas->execute();

$stmtFotos = $con->prepare("DELETE FROM fotografias_habitacion WHERE habitacion_id = ?");
$stmtFotos->bind_param("i", $idHabitacion);
$stmtFotos->execute();


$stmt = $con->prepare("DELETE FROM habitaciones WHERE id = ?");
$stmt->bind_param("i", $idHabitacion);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Habitación eliminada correctamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al eliminar la habitación']);
}
?>
