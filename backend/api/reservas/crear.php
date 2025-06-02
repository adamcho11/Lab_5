<?php
require_once '../db.php';
$data = json_decode(file_get_contents("php://input"));
$usuario_id = $data->usuario_id ?? 0;
$habitacion_id = $data->habitacion_id ?? 0;
$fecha_ingreso = $data->fecha_ingreso ?? '';
$fecha_salida = $data->fecha_salida ?? '';
if ($usuario_id && $habitacion_id && $fecha_ingreso && $fecha_salida) {
    $stmt = $con->prepare("INSERT INTO reservas (usuario_id, habitacion_id, fecha_ingreso, fecha_salida, estado) VALUES (?, ?, ?, ?, 'pendiente')");
    $stmt->bind_param("iiss", $usuario_id, $habitacion_id, $fecha_ingreso, $fecha_salida);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'mensaje' => 'Error al crear reserva']);
    }
} else {
    echo json_encode(['success' => false, 'mensaje' => 'Datos incompletos']);
}
?>
