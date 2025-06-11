<?php
require_once '../../backend/conexion_bd.php';
$id = $_GET['id'];
$sql = "SELECT * FROM tipo_habitacion WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
echo json_encode($res->fetch_assoc());
?>
