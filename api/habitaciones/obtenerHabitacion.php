<?php
require_once '../../backend/conexion_bd.php';

$id = $_GET['id'];

$sql = "SELECT * FROM habitaciones WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode($row);
} else {
    echo json_encode(['error' => 'No encontrado']);
}
?>
