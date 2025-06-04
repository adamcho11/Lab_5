<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['success' => false, 'mensaje' => 'Debes iniciar sesión']); exit;
}
require_once '../../backend/conexion_bd.php';
$data = json_decode(file_get_contents("php://input"));
$id = $data->id ?? 0;
$habitacion_id = $data->habitacion_id ?? 0;
$fecha_ingreso = $data->fecha_ingreso ?? '';
$fecha_salida = $data->fecha_salida ?? '';
$estado = $data->estado ?? '';
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
if ($id && $habitacion_id && $fecha_ingreso && $fecha_salida && $estado) {
    $stmt = $con->prepare("UPDATE reservas SET habitacion_id=?, fecha_ingreso=?, fecha_salida=?, estado=? WHERE id=?");
    $stmt->bind_param("isssi", $habitacion_id, $fecha_ingreso, $fecha_salida, $estado, $id);
    if ($stmt->execute()) echo json_encode(['success' => true]);
    else echo json_encode(['success' => false]);
} else echo json_encode(['success' => false, 'mensaje' => 'Datos incompletos']);
?>
