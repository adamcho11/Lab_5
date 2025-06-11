<?php
require_once '../../backend/conexion_bd.php';

$id = $_POST['id'];
$nombre = $_POST['nombretipo'];
$superficie = $_POST['superficie'];
$numero_camas = $_POST['ncamas'];

$stmt = $con->prepare("UPDATE tipo_habitacion SET nombre_tipo = ?, superficie = ?, numero_camas = ? WHERE id = ?");
$stmt->bind_param("ssii", $nombre, $superficie, $numero_camas, $id);

if ($stmt->execute()) {
    echo json_encode(['message' => 'Tipo de habitación actualizado correctamente']);
} else {
    echo json_encode(['message' => 'Error al actualizar tipo de habitación']);
}
?>

