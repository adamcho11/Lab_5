<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['success' => false, 'mensaje' => 'Debes iniciar sesión']); exit;
}
require_once '../../backend/conexion_bd.php';
$data = json_decode(file_get_contents("php://input"));
$id = $data->id ?? 0;
$stmt = $con->prepare("SELECT usuario_id FROM reservas WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$es_admin = $_SESSION['usuario']['rol'] === 'administrador';
$es_propietario = $_SESSION['usuario']['id'] == ($row['usuario_id'] ?? 0);
if (!$es_admin && !$es_propietario) {
    echo json_encode(['success' => false, 'mensaje' => 'No permitido']); exit;
}
if ($id) {
    $stmt = $con->prepare("UPDATE reservas SET estado='cancelada' WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo json_encode(['success' => true]);
} else echo json_encode(['success' => false]);
?>
