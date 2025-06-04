<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['success' => false, 'mensaje' => 'Debes iniciar sesión']); exit;
}
require_once '../../backend/conexion_bd.php';
$data = json_decode(file_get_contents("php://input"));
$usuario_id = $_SESSION['usuario']['id'];
$habitacion_id = $data->habitacion_id ?? 0;
$fecha_ingreso = $data->fecha_ingreso ?? '';
$fecha_salida = $data->fecha_salida ?? '';
if ($habitacion_id && $fecha_ingreso && $fecha_salida) {
    $stmt = $con->prepare("INSERT INTO reservas (usuario_id, habitacion_id, fecha_ingreso, fecha_salida, estado) VALUES (?, ?, ?, ?, 'pendiente')");
    $stmt->bind_param("iiss", $usuario_id, $habitacion_id, $fecha_ingreso, $fecha_salida);
    if ($stmt->execute()) echo json_encode(['success' => true]);
    else echo json_encode(['success' => false]);
} else echo json_encode(['success' => false, 'mensaje' => 'Datos incompletos']);
?>
