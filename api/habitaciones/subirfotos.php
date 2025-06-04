<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    echo json_encode(['success' => false, 'mensaje' => 'Solo el administrador puede subir fotos']);
    exit;
}
require_once '../../backend/conexion_bd.php';

$data = json_decode(file_get_contents("php://input"));
$fotografia = $data->fotografia ?? '';
$habitacion_id = intval($data->habitacion_id ?? 0);
$orden = intval($data->orden ?? 0);

if ($fotografia && $habitacion_id && $orden >= 0) {
    $stmt = $con->prepare("INSERT INTO fotografias_habitacion (habitacion_id, fotografia, orden) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $habitacion_id, $fotografia, $orden);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'mensaje' => 'No se pudo registrar la foto']);
    }
} else {
    echo json_encode(['success' => false, 'mensaje' => 'Datos incompletos']);
}
?>
