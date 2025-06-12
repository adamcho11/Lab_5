<?php
require_once '../../backend/conexion_bd.php';

$id = $_POST['id'];
$numero = $_POST['nombreH'];
$piso = $_POST['piso'];
$tipo_id = $_POST['TipoH'];

$stmt = $con->prepare("UPDATE habitaciones SET numero = ?, piso = ?, tipo_id = ? WHERE id = ?");
$stmt->bind_param("siii", $numero, $piso, $tipo_id, $id);

if ($stmt->execute()) {
    echo json_encode(['message' => 'Habitación actualizada']);
} else {
    echo json_encode(['message' => 'Error al actualizar']);
}
?>