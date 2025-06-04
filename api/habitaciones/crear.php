<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    echo json_encode(['success' => false, 'mensaje' => 'Solo admin']); exit;
}
require_once '../../backend/conexion_bd.php';
$data = json_decode(file_get_contents("php://input"));
$numero = $data->numero ?? '';
$piso = $data->piso ?? 0;
$tipo_id = $data->tipo_id ?? 0;
if ($numero && $piso && $tipo_id) {
    $stmt = $con->prepare("INSERT INTO habitaciones (numero, piso, tipo_id) VALUES (?, ?, ?)");
    $stmt->bind_param("sii", $numero, $piso, $tipo_id);
    if ($stmt->execute()) echo json_encode(['success' => true]);
    else echo json_encode(['success' => false]);
} else echo json_encode(['success' => false, 'mensaje' => 'Datos incompletos']);
?>
