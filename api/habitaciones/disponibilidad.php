<?php
require_once '../../backend/conexion_bd.php';
$fecha_ingreso = $_GET['fecha_ingreso'] ?? '';
$fecha_salida = $_GET['fecha_salida'] ?? '';
if (!$fecha_ingreso || !$fecha_salida) {
    echo json_encode(['success' => false, 'mensaje' => 'Fechas requeridas']); exit;
}
$sql = "SELECT h.*, t.nombre_tipo, t.superficie, t.numero_camas, (SELECT fotografia FROM fotografias_habitacion f WHERE f.habitacion_id = h.id ORDER BY orden ASC LIMIT 1) AS fotografia FROM habitaciones h JOIN tipo_habitacion t ON h.tipo_id = t.id WHERE h.id NOT IN (
    SELECT habitacion_id FROM reservas
    WHERE ((fecha_ingreso < ? AND fecha_salida > ?)
    OR (fecha_ingreso < ? AND fecha_salida > ?)
    OR (fecha_ingreso >= ? AND fecha_salida <= ?))
    AND estado IN ('pendiente','confirmada')
)";
$stmt = $con->prepare($sql);
$stmt->bind_param('ssssss', $fecha_salida, $fecha_ingreso, $fecha_salida, $fecha_ingreso, $fecha_ingreso, $fecha_salida);
$stmt->execute();
$res = $stmt->get_result();
$disponibles = [];
while ($h = $res->fetch_assoc()) $disponibles[] = $h;
echo json_encode($disponibles);
?>
