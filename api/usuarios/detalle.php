<?php
session_start();
require_once '../../backend/conexion_bd.php';
$id = intval($_GET['id'] ?? 0);
$es_propio = $_SESSION['usuario']['id'] == $id;
if (!$es_admin && !$es_propio) {
    echo json_encode(['success' => false, 'mensaje' => 'No permitido']); exit;
}
$es_admin = $_SESSION['usuario']['rol'] === 'administrador';
$es_propio = $_SESSION['usuario']['id'] == $id;
if (!$es_admin && !$es_propio) {
    echo json_encode(['success' => false, 'mensaje' => 'No permitido']); exit;
}
$res = $con->query("SELECT id, nombre, email, rol, estado FROM usuarios WHERE id = $id");
echo json_encode($res->fetch_assoc());
?>
