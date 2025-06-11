<?php
session_start();
require_once '../../backend/conexion_bd.php';

$id = $_SESSION['id'];

// Usar sentencia preparada para evitar inyección SQL
$stmt = $con->prepare("SELECT id, nombre, email, rol, estado_cuenta AS estado FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado && $fila = $resultado->fetch_assoc()) {
    echo json_encode($fila);
} else {
    echo json_encode(['success' => false, 'mensaje' => 'Usuario no encontrado']);
}
?>

