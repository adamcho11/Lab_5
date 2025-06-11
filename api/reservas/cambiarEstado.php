<?php
require_once '../../backend/conexion_bd.php';

$idReserva = $_POST['id'];
$estadoNuevo = $_POST['estado'];

$sql = "UPDATE reservas SET estado = ? WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("si", $estadoNuevo, $idReserva);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Estado actualizado correctamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al actualizar estado']);
}
?>