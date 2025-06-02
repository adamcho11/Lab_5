<?php
require_once '../db.php';
$data = json_decode(file_get_contents("php://input"));
$id = $data->id ?? 0;
$usuario_id = $data->usuario_id ?? 0;
$habitacion_id = $data->habitacion_id ?? 0;
$fecha_ingreso = $data->fecha_ingreso ?? '';
$fecha_salida = $data->fecha_salida ?? '';
$estado = $data->estado ?? '';
if ($id and $usuario_id and $habitacion_id and $fecha_ingreso and $fecha_salida and $estado) {
    $stmt = $con->prepare("UPDATE reservas SET usuario_id=?, habitacion_id=?, fecha_ingreso=?, fecha_salida=?, estado=? WHERE id=?");
    $stmt->bind_param("iisssi", $usuario_id, $habitacion_id, $fecha_ingreso, $fecha_salida, $estado, $id);
    if ($stmt->execute()) {
        echo json_encode(['success' => True]);
    } else {
        echo json_encode(['success' => False, 'mensaje' => 'Error al actualizar reserva']);
    }
} else {
    echo json_encode(['success' => False, 'mensaje' => 'Datos incompletos']);
}
?>
