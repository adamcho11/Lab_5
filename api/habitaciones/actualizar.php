<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    echo json_encode(['success' => false, 'mensaje' => 'Solo admin']); exit;
}
require_once '../../backend/conexion_bd.php';
$data = json_decode(file_get_contents("php://input"));
$id = $data->id ?? 0;
$numero = $data->numero ?? '';
$piso = $data->piso ?? 0;
$tipo_id = $data->tipo_id ?? 0;
if ($id && $numero && $piso && $tipo_id) {
    $stmt = $con->prepare("UPDATE habitaciones SET numero=?, piso=?, tipo_id=? WHERE id=?");
    $stmt->bind_param("siii", $numero, $piso, $tipo_id, $id);
    if ($stmt->execute()) echo json_encode(['success' => true]);
    else echo json_encode(['success' => false]);
} else echo json_encode(['success' => false, 'mensaje' => 'Datos incompletos']);
?>
