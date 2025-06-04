<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    echo json_encode(['success' => false, 'mensaje' => 'Solo admin']); exit;
}
require_once '../../backend/conexion_bd.php';
$data = json_decode(file_get_contents("php://input"));
$id = $data->id ?? 0;
if ($id) {
    $stmt = $con->prepare("DELETE FROM usuarios WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo json_encode(['success' => true]);
} else echo json_encode(['success' => false]);
?>
