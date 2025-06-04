<?php
require_once '../../backend/conexion_bd.php';
$habitacion_id = intval($_GET['habitacion_id'] ?? 0);
$fotos = [];
if ($habitacion_id > 0) {
    $res = $con->query("SELECT id, fotografia, orden FROM fotografias_habitacion WHERE habitacion_id = $habitacion_id ORDER BY orden ASC");
    while ($f = $res->fetch_assoc()) $fotos[] = $f;
}
echo json_encode($fotos);
?>
